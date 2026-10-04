<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Maison;
use App\Models\Recensement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RecensementController extends Controller
{
    /**
     * Vérifie que l'agent connecté appartient à l'équipe
     * affectée et que l'affectation est active.
     */
    private function verifierAccesAffectation(Affectation $affectation): void
    {
        $user = Auth::user();

        abort_unless(
            $affectation->statut === 'active',
            403,
            'Cette affectation n’est plus active.'
        );

        abort_unless(
            $affectation->equipe()
                ->whereHas('membres', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->exists(),
            403,
            'Vous n’êtes pas membre de l’équipe affectée à cette zone.'
        );
    }

    /**
     * Vérifie l'accès de l'agent à un recensement donné,
     * via son affectation.
     */
    private function verifierAccesRecensement(Recensement $recensement): void
    {
        $user = Auth::user();

        $recensement->loadMissing([
            'affectation'
        ]);

        abort_unless(
            $recensement->affectation,
            403,
            'Aucune affectation associée à ce recensement.'
        );

        abort_unless(
            $recensement->affectation->statut === 'active',
            403,
            'Cette affectation n’est plus active.'
        );

        abort_unless(
            $recensement->affectation->equipe()
                ->whereHas('membres', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->exists(),
            403,
            'Vous n’avez pas accès à ce recensement.'
        );
    }

    /**
     * Liste des recensements de l'agent connecté.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $search = trim(
            $request->input('search', '')
        );

        $recensements = Recensement::query()
            ->with([
                'campagne',
                'maison.village.canton.commune',
                'affectation.equipe',
            ])
            ->where(
                'agent_id',
                $user->id
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        function ($q) use ($search) {

                            $q->whereHas(
                                'maison',
                                function ($maison) use ($search) {
                                    $maison
                                        ->where(
                                            'numeroMaison',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'uid',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'adresse',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );

                            $q->orWhereHas(
                                'campagne',
                                function ($campagne) use ($search) {
                                    $campagne
                                        ->where(
                                            'libelle',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'code',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );

                            $q->orWhere(
                                'statut',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )
            ->latest('idRecensement')
            ->paginate(15)
            ->withQueryString();

        return view(
            'agent.recensements.index',
            compact(
                'recensements',
                'search'
            )
        );
    }

    /**
     * Affiche les maisons de la zone de collecte
     * correspondant à une affectation.
     */
    public function zoneCollecte(
        Request $request,
        Affectation $affectation
    ): View {
        $this->verifierAccesAffectation(
            $affectation
        );

        $affectation->load([
            'campagne',
            'village.canton.commune.prefecture',
            'equipe.superviseur',
        ]);

        $search = trim(
            $request->input('search', '')
        );

        $filtre = $request->input(
            'statut',
            'tous'
        );

        $campagneId = $affectation->campagne_id;
        $villageId = $affectation->village_id;

        $maisonsQuery = Maison::query()
            ->where(
                'village_id',
                $villageId
            )
            ->with([
                'recensements' => function ($query) use ($campagneId) {
                    $query
                        ->where(
                            'campagne_id',
                            $campagneId
                        )
                        ->with([
                            'agent',
                            'campagne'
                        ])
                        ->latest(
                            'idRecensement'
                        );
                },
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        function ($q) use ($search) {
                            $q
                                ->where(
                                    'numeroMaison',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'uid',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'adresse',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            );

        /*
         * Filtre appliqué directement en SQL,
         * avant pagination.
         */
        if ($filtre === 'a_recense') {

            $maisonsQuery->whereDoesntHave(
                'recensements',
                function ($query) use ($campagneId) {
                    $query->where(
                        'campagne_id',
                        $campagneId
                    );
                }
            );

        } elseif ($filtre === 'en_cours') {

            $maisonsQuery->whereHas(
                'recensements',
                function ($query) use ($campagneId) {
                    $query
                        ->where(
                            'campagne_id',
                            $campagneId
                        )
                        ->whereIn(
                            'statut',
                            [
                                'brouillon',
                                'en_cours'
                            ]
                        );
                }
            );

        } elseif ($filtre === 'termine') {

            $maisonsQuery->whereHas(
                'recensements',
                function ($query) use ($campagneId) {
                    $query
                        ->where(
                            'campagne_id',
                            $campagneId
                        )
                        ->whereIn(
                            'statut',
                            [
                                'termine',
                                'valide'
                            ]
                        );
                }
            );
        }

        $maisons = $maisonsQuery
            ->orderBy('numeroMaison')
            ->paginate(20)
            ->withQueryString();

        $totalMaisons = Maison::query()
            ->where(
                'village_id',
                $villageId
            )
            ->count();

        $recensementsCampagneBase = Recensement::query()
            ->where(
                'campagne_id',
                $campagneId
            )
            ->whereHas(
                'maison',
                function ($query) use ($villageId) {
                    $query->where(
                        'village_id',
                        $villageId
                    );
                }
            );

        $nombreRecensees = (
            clone $recensementsCampagneBase
        )->count();

        $nombreEnCours = (
            clone $recensementsCampagneBase
        )
            ->whereIn(
                'statut',
                [
                    'brouillon',
                    'en_cours'
                ]
            )
            ->count();

        $nombreTerminees = (
            clone $recensementsCampagneBase
        )
            ->whereIn(
                'statut',
                [
                    'termine',
                    'valide'
                ]
            )
            ->count();

        $nombreARecenser = max(
            0,
            $totalMaisons - $nombreRecensees
        );

        $pourcentage = $totalMaisons > 0
            ? round(
                (
                    $nombreRecensees /
                    $totalMaisons
                ) * 100
            )
            : 0;

        return view(
            'agent.recensements.zone',
            compact(
                'affectation',
                'maisons',
                'search',
                'filtre',
                'totalMaisons',
                'nombreRecensees',
                'nombreEnCours',
                'nombreTerminees',
                'nombreARecenser',
                'pourcentage'
            )
        );
    }

    /**
     * Crée ou reprend le recensement d'une maison existante
     * pour la campagne de l'affectation.
     */
    public function commencer(
        Affectation $affectation,
        Maison $maison
    ) {
        $this->verifierAccesAffectation(
            $affectation
        );

        abort_unless(
            (int) $maison->village_id ===
            (int) $affectation->village_id,
            403,
            'Cette maison ne se trouve pas dans votre zone de collecte.'
        );

        $recensement = Recensement::query()
            ->where(
                'campagne_id',
                $affectation->campagne_id
            )
            ->where(
                'maison_id',
                $maison->idMaison
            )
            ->first();

        /*
         * Si un recensement existe déjà pour cette maison
         * et cette campagne, on le reprend.
         */
        if ($recensement) {

            abort_unless(
                (int) $recensement->affectation_id ===
                (int) $affectation->idAffectation,
                403,
                'Cette maison possède déjà un recensement pour cette campagne dans une autre affectation.'
            );

            /*
             * Le recensement est déjà validé.
             */
            if (
                $recensement->statut === 'valide'
            ) {
                return redirect()
                    ->route(
                        'recensements.show',
                        $recensement
                    )
                    ->with(
                        'info',
                        'Ce recensement est déjà validé.'
                    );
            }

            /*
             * Le recensement existe mais n'est pas encore validé.
             */
            return redirect()
                ->route(
                    'recensements.show',
                    $recensement
                )
                ->with(
                    'info',
                    'Le recensement existant a été repris.'
                );
        }

        /*
         * La Maison existe déjà :
         * on ne crée jamais de Maison ici.
         */
        $recensement = Recensement::create([
            'uid' => (string) Str::uuid(),

            'campagne_id' =>
                $affectation->campagne_id,

            'affectation_id' =>
                $affectation->idAffectation,

            'agent_id' =>
                Auth::id(),

            'maison_id' =>
                $maison->idMaison,

            'statut' =>
                'brouillon',

            'dateDebut' =>
                null,

            'dateDerniereModification' =>
                now(),
        ]);

        /*
         * IMPORTANT :
         * après le POST, on redirige vers la route GET
         * recensements.show.
         *
         * On ne redirige surtout pas vers
         * recensements.commencer, car cette route accepte
         * uniquement POST.
         */
        return redirect()
            ->route(
                'recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le recensement a été créé. Vous pouvez maintenant commencer la collecte.'
            );
    }

    /**
     * Tableau de bord du recensement.
     */
    public function show(
        Recensement $recensement
    ): View {
        $recensement->load([
            'campagne',
            'maison.village.canton.commune.prefecture',
            'affectation.equipe',
            'affectation.village',
            'agent',
            'menages.exploitants.exploitation',
        ]);

        $this->verifierAccesRecensement(
            $recensement
        );

        return view(
            'agent.recensements.show',
            compact('recensement')
        );
    }

    /**
     * Passe le recensement de "brouillon"
     * à "en_cours".
     */
    public function mettreEnCours(
        Recensement $recensement
    ) {
        $this->verifierAccesRecensement(
            $recensement
        );

        abort_if(
            $recensement->statut === 'valide',
            403,
            'Ce recensement est déjà validé.'
        );

        abort_if(
            $recensement->statut === 'termine',
            422,
            'Ce recensement est déjà terminé.'
        );

        if (
            $recensement->statut === 'brouillon'
        ) {
            $recensement->statut = 'en_cours';

            $recensement->dateDebut =
                $recensement->dateDebut ??
                now();
        }

        $recensement->dateDerniereModification =
            now();

        $recensement->save();

        return redirect()
            ->route(
                'recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le recensement est maintenant en cours.'
            );
    }

    /**
     * Termine le recensement.
     */
    public function terminer(
        Recensement $recensement
    ) {
        $this->verifierAccesRecensement(
            $recensement
        );

        abort_if(
            $recensement->statut === 'valide',
            403,
            'Ce recensement est déjà validé.'
        );

        abort_if(
            $recensement->statut === 'termine',
            422,
            'Ce recensement est déjà terminé.'
        );

        $recensement->update([
            'statut' =>
                'termine',

            'dateTerminaison' =>
                now(),

            'dateDerniereModification' =>
                now(),
        ]);

        return redirect()
            ->route(
                'recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le recensement a été terminé avec succès.'
            );
    }

    /**
     * Affiche la page de démarrage
     * du recensement pour une affectation.
     */
    public function demarrer(
        Affectation $affectation
    ): View {
        $this->verifierAccesAffectation(
            $affectation
        );

        $affectation->load([
            'campagne',
            'equipe.superviseur',
            'village.canton.commune.prefecture',
        ]);

        $maisons = Maison::query()
            ->where(
                'village_id',
                $affectation->village_id
            )
            ->with([
                'recensements' => function ($query) use ($affectation) {
                    $query
                        ->where(
                            'campagne_id',
                            $affectation->campagne_id
                        )
                        ->withCount(
                            'menages'
                        );
                },
            ])
            ->orderBy(
                'numeroMaison'
            )
            ->paginate(15);

        return view(
            'agent.recensements.demarrer',
            compact(
                'affectation',
                'maisons'
            )
        );
    }
}