<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaisonRequest;
use App\Http\Requests\UpdateMaisonRequest;
use App\Models\Affectation;
use App\Models\Maison;
use App\Models\Recensement;
use App\Models\Village;
use App\Services\ReferenceGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaisonController extends Controller
{
    public function __construct(
        protected ReferenceGeneratorService $referenceGenerator
    ) {
    }

    /**
     * Vérifie que l'utilisateur connecté appartient bien
     * à l'affectation et que celle-ci concerne le village.
     */
    private function verifierAffectation(
        Affectation $affectation,
        Village $village
    ): void {
        abort_unless(
            $affectation->statut === 'active',
            403,
            'Cette affectation n’est plus active.'
        );

        abort_unless(
            $affectation->village_id === $village->idVillage,
            403,
            'Ce village ne correspond pas à votre affectation.'
        );

        $estMembre = $affectation->equipe()
            ->whereHas('membres', function ($query) {
                $query->where('users.id', Auth::id());
            })
            ->exists();

        abort_unless(
            $estMembre,
            403,
            'Vous n’êtes pas autorisé à accéder à cette affectation.'
        );
    }

    /**
     * Vérifie que la campagne de l'affectation est ouverte
     * pour permettre la collecte.
     */
    private function verifierCampagneActive(Affectation $affectation): void
    {
        $campagne = $affectation->campagne;

        abort_unless(
            $campagne,
            403,
            'Aucune campagne n’est associée à cette affectation.'
        );

        // Synchronisation du statut automatique de la campagne.
        if (method_exists($campagne, 'synchroniserStatut')) {
            $campagne->synchroniserStatut();
            $campagne->refresh();
        }

        abort_unless(
            $campagne->statut === 'active',
            403,
            'La collecte n’est pas ouverte pour cette campagne.'
        );
    }

    /**
     * Liste des maisons d'un village.
     *
     * Pour l'agent, l'affectation est transmise avec :
     * ?affectation_id=...
     */
    // public function index(Request $request, Village $village): View
    // {
    //     $affectation = null;

    //     if ($request->filled('affectation_id')) {
    //         $affectation = Affectation::with([
    //             'campagne',
    //             'equipe',
    //             'village.canton.commune',
    //         ])->findOrFail(
    //             $request->integer('affectation_id')
    //         );

    //         $this->verifierAffectation(
    //             $affectation,
    //             $village
    //         );
    //     }

    //     $maisons = $village->maisons()
    //         ->with([
    //             'village.canton.commune',
    //             'recensements' => function ($query) {
    //                 $query
    //                     ->with('campagne:idCampagne,libelle,statut')
    //                     ->withCount('menages')
    //                     ->orderByDesc('campagne_id');
    //             },
    //         ])
    //         ->when(
    //             $request->filled('q'),
    //             function ($query) use ($request) {

    //                 $terme = $request->string('q');

    //                 $query->where(function ($q) use ($terme) {
    //                     $q->where(
    //                         'numeroMaison',
    //                         'like',
    //                         "%{$terme}%"
    //                     )
    //                     ->orWhere(
    //                         'adresse',  
    //                         'like',
    //                         "%{$terme}%"
    //                     )
    //                       ->orWhere(
    //                         'nomChef',  
    //                         'like',
    //                         "%{$terme}%"
    //                     )
    //                     ->orWhere(
    //                         'repere',
    //                         'like',
    //                         "%{$terme}%"
    //                     );
    //                 });
    //             }
    //         )
    //         ->orderBy('idMaison')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view(
    //         'maisons.index',
    //         compact(
    //             'village',
    //             'maisons',
    //             'affectation'
    //         )
    //     );
    // }


    /**
     * Liste des maisons d'un village avec vérification du statut de la campagne.
     */
    public function index(Request $request, Village $village): View
    {
        $village->load(['canton.commune']);
        $affectation = null;
        $campagneActive = false;

        // 1. Récupération et vérification de l'affectation transmise
        if ($request->filled('affectation_id')) {
            $affectation = Affectation::with([
                'campagne',
                'equipe',
                'village.canton.commune',
            ])->find($request->integer('affectation_id'));

            if ($affectation) {
                $this->verifierAffectation($affectation, $village);
            }
        }

        // 2. Si aucune affectation transmise, on recherche l'affectation active de l'agent connecté
        if (!$affectation) {
            $user = Auth::user();
            $affectation = Affectation::with(['campagne', 'equipe'])
                ->where('village_id', $village->idVillage)
                ->where('statut', 'active')
                ->whereHas('equipe.membres', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->latest('idAffectation')
                ->first();
        }

        // 3. Vérification du statut de la campagne en BD
        if ($affectation && $affectation->campagne) {
            $campagne = $affectation->campagne;

            if (method_exists($campagne, 'synchroniserStatut')) {
                $campagne->synchroniserStatut();
                $campagne->refresh();
            }

            $campagneActive = ($campagne->statut === 'active');
        }

        // 4. Chargement des maisons du village
        $maisons = $village->maisons()
            ->with([
                'village.canton.commune',
                'recensements' => function ($query) {
                    $query
                        ->with('campagne:idCampagne,libelle,statut')
                        ->withCount('menages')
                        ->orderByDesc('campagne_id');
                },
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $terme = $request->string('q');
                $query->where(function ($q) use ($terme) {
                    $q->where('numeroMaison', 'like', "%{$terme}%")
                    ->orWhere('adresse', 'like', "%{$terme}%")
                    ->orWhere('nomChef', 'like', "%{$terme}%")
                    ->orWhere('repere', 'like', "%{$terme}%");
                });
            })
            ->orderBy('idMaison')
            ->paginate(10)
            ->withQueryString();

        return view(
            'maisons.index',
            compact(
                'village',
                'maisons',
                'affectation',
                'campagneActive'
            )
        );
    }

    /**
     * Formulaire de création d'une nouvelle maison.
     *
     * L'affectation est transmise par :
     * ?affectation_id=...
     */
    public function create(
        Request $request,
        Village $village
    ): View {
        $village->load([
            'canton.commune',
        ]);

        $affectation = null;

        if ($request->filled('affectation_id')) {

            $affectation = Affectation::with([
                'campagne',
                'equipe',
                'village.canton.commune',
            ])->findOrFail(
                $request->integer('affectation_id')
            );

            $this->verifierAffectation(
                $affectation,
                $village
            );

            // Une nouvelle maison n'est possible
            // que pendant une campagne active.
            $this->verifierCampagneActive($affectation);
        }

        return view(
            'maisons.create',
            compact(
                'village',
                'affectation'
            )
        );
    }

    /**
     * Création d'une nouvelle maison.
     *
     * Si la création est faite depuis une affectation agent :
     * - création de la Maison permanente
     * - création automatique du Recensement
     *   pour la campagne active.
     */
    public function store(
        StoreMaisonRequest $request,
        Village $village
    ): RedirectResponse {

        $affectation = null;

        if ($request->filled('affectation_id')) {

            $affectation = Affectation::with([
                'campagne',
                'equipe',
            ])->findOrFail(
                $request->integer('affectation_id')
            );

            $this->verifierAffectation(
                $affectation,
                $village
            );

            $this->verifierCampagneActive(
                $affectation
            );
        }

        $validated = $request->validated();

        $maison = DB::transaction(function () use (
            $village,
            $validated,
            $affectation
        ) {

            /*
             * ---------------------------------------------------------
             * 1. Création de la maison permanente
             * ---------------------------------------------------------
             */

            $numeroMaison = $this->referenceGenerator->generate(
                type: 'maison',
                parentType: 'village',
                parentId: $village->idVillage,
                codeParent: $village->code,
                prefix: 'M',
                padding: 5
            );

            $maison = $village->maisons()->create([
                'uid' => (string) Str::uuid(),
                'numeroMaison' => $numeroMaison,
                'repere' => $validated['repere'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'nomChef' => $validated['nomChef'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'precisionGPS' => $validated['precisionGPS'] ?? null,
            ]);

            /*
             * ---------------------------------------------------------
             * 2. Création automatique du recensement
             * ---------------------------------------------------------
             */

            if ($affectation) {

                $campagne = $affectation->campagne;

                // Protection contre un éventuel doublon.
                $recensementExiste = Recensement::query()
                    ->where('maison_id', $maison->idMaison)
                    ->where(
                        'campagne_id',
                        $campagne->idCampagne
                    )
                    ->exists();

                if (!$recensementExiste) {

                    Recensement::create([
                        'uid' => (string) Str::uuid(),

                        'campagne_id' =>
                            $campagne->idCampagne,

                        'affectation_id' =>
                            $affectation->idAffectation,

                        'agent_id' =>
                            Auth::id(),

                        'maison_id' =>
                            $maison->idMaison,

                        'statut' =>
                            'brouillon',

                        'dateDebut' =>
                            now(),

                        'dateDerniereModification' =>
                            now(),
                    ]);
                }
            }

            return $maison;
        });

        /*
         * -------------------------------------------------------------
         * Retour vers la liste des maisons
         * -------------------------------------------------------------
         */

        $routeParameters = [
            'village' => $village,
        ];

        if ($affectation) {
            $routeParameters['affectation_id'] =
                $affectation->idAffectation;
        }

        return redirect()
            ->route(
                'villages.maisons.index',
                $routeParameters
            )
            ->with(
                'success',
                "La maison {$maison->numeroMaison} a été enregistrée avec succès."
            );
    }

    /**
     * Affichage d'une maison.
     */
    // public function show(Maison $maison): View
    // {
    //     $maison->load([
    //         'village.canton.commune',
    //         'recensements.campagne',
    //         'recensements.agent',
    //         'recensements.affectation.equipe',
    //     ]);

    //     $user = Auth::user();

      
    //     $affectation = Affectation::query()
    //         ->where(
    //             'village_id',
    //             $maison->village_id
    //         )
    //         ->where(
    //             'statut',
    //             'active'
    //         )
    //         ->whereHas(
    //             'equipe.membres',
    //             function ($query) use ($user) {
    //                 $query->where(
    //                     'users.id',
    //                     $user->id
    //                 );
    //             }
    //         )
    //         ->latest('idAffectation')
    //         ->first();

        
    //     $recensement = $affectation
    //         ? $maison->recensements
    //             ->firstWhere(
    //                 'campagne_id',
    //                 $affectation->campagne_id
    //             )
    //         : null;

    //     return view(
    //         'maisons.show',
    //         compact(
    //             'maison',
    //             'affectation',
    //             'recensement'
    //         )
    //     );
    // }

   
    /**
     * Affichage d'une maison avec gestion stricte des statuts de campagne (active, planifiée, clôturée).
     */
      // Détail de la maison : sobre, avec un seul bouton vers la liste
    public function show(Maison $maison)
    {
        $maison->load('village.canton')->loadCount('recensements');

        return view('maisons.show', compact('maison'));
    }

    // Liste des recensements de la maison + création si campagne active
    public function recensements(Maison $maison)
    {
        $maison->load('village');

        $recensements = $maison->recensements()
            ->with(['campagne', 'agent'])
            ->withCount('menages')
            ->latest()
            ->get();

        // Campagne ACTIVE du village, pour l'équipe de l'agent connecté
        $affectation = Affectation::with('campagne')
            ->where('village_id', $maison->village_id)
            ->whereHas('campagne', fn ($q) => $q->where('statut', 'active'))
            ->whereHas('equipe.membres', fn ($q) => $q->where('users.id', Auth::id()))
            ->latest('idAffectation')
            ->first();

        // Un recensement existe-t-il déjà pour cette maison dans la campagne active ?
        $recensementActuel = $affectation
            ? $recensements->firstWhere('campagne_id', $affectation->campagne_id)
            : null;

        return view('maisons.recensements', compact('maison', 'recensements', 'affectation', 'recensementActuel'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Maison $maison): View
    {
        $maison->load([
            'village.canton.commune',
        ]);

        return view(
            'maisons.edit',
            compact('maison')
        );
    }

    /**
     * Mise à jour d'une maison.
     */
    public function update(
        UpdateMaisonRequest $request,
        Maison $maison
    ): RedirectResponse {

        $validated = $request->validated();

        $maison->update([
            'numeroMaison' =>
                $validated['numeroMaison'],

            'repere' =>
                $validated['repere'] ?? null,

            'adresse' =>
                $validated['adresse'] ?? null,

            'nomChef' =>
                $validated['nomChef'] ?? null,

            'latitude' =>
                $validated['latitude'] ?? null,

            'longitude' =>
                $validated['longitude'] ?? null,

            'precisionGPS' =>
                $validated['precisionGPS'] ?? null,
        ]);

        return redirect()
            ->route(
                'maisons.show',
                $maison
            )
            ->with(
                'success',
                'La maison a été mise à jour avec succès.'
            );
    }

    /**
     * Suppression d'une maison.
     *
     * À conserver pour l'instant selon ton fonctionnement actuel.
     */
    public function destroy(
        Maison $maison
    ): RedirectResponse {

        $village = $maison->village;

        $maison->delete();

        return redirect()
            ->route(
                'villages.maisons.index',
                $village
            )
            ->with(
                'success',
                'La maison a été supprimée avec succès.'
            );
    }
}