<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Maison;
use App\Models\Recensement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

use App\Models\CampagneRecensement;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

        $recensement->loadMissing(['affectation']);

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
    // public function index(Request $request): View
    // {
    //     $user = Auth::user();
    //     $search = trim($request->input('search', ''));

    //     $recensements = Recensement::query()
    //         ->with([
    //             'campagne',
    //             'maison.village.canton.commune',
    //             'affectation.equipe',
    //         ])
    //         ->where('agent_id', $user->id)
    //         ->when($search !== '', function ($query) use ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->whereHas('maison', function ($maison) use ($search) {
    //                     $maison->where('numeroMaison', 'like', "%{$search}%")
    //                         ->orWhere('uid', 'like', "%{$search}%")
    //                         ->orWhere('adresse', 'like', "%{$search}%");
    //                 });

    //                 $q->orWhereHas('campagne', function ($campagne) use ($search) {
    //                     $campagne->where('libelle', 'like', "%{$search}%")
    //                         ->orWhere('codeCampagne', 'like', "%{$search}%");
    //                 });

    //                 $q->orWhere('statut', 'like', "%{$search}%");
    //             });
    //         })
    //         ->latest('idRecensement')
    //         ->paginate(15)
    //         ->withQueryString();

    //     return view('agent.recensements.index', compact('recensements', 'search'));
    // }


        public function index(Request $request): View
    {
        $user = Auth::user();
        $search = trim($request->input('search', ''));

        $recensements = Recensement::query()
            ->with([
                'campagne',
                'maison.village.canton.commune',
                'affectation.equipe',
            ])
            ->where('agent_id', $user->id)

            // Filtre village (bouton « Reprendre le village »)
            ->when($request->filled('village'), function ($query) use ($request) {
                $query->whereHas('maison', function ($maison) use ($request) {
                    $maison->where('village_id', $request->input('village'));
                });
            })

            // Filtre « à rappeler / incomplets » : tout sauf terminé et validé
            ->when($request->input('statut') === 'en_attente', function ($query) {
                $query->whereNotIn('statut', ['termine', 'valide']);
            })

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('maison', function ($maison) use ($search) {
                        $maison->where('numeroMaison', 'like', "%{$search}%")
                            ->orWhere('uid', 'like', "%{$search}%")
                            ->orWhere('adresse', 'like', "%{$search}%");
                    });

                    $q->orWhereHas('campagne', function ($campagne) use ($search) {
                        $campagne->where('libelle', 'like', "%{$search}%")
                            ->orWhere('codeCampagne', 'like', "%{$search}%");
                    });

                    $q->orWhere('statut', 'like', "%{$search}%");
                });
            })
            ->latest('idRecensement')
            ->paginate(15)
            ->withQueryString();

        return view('agent.recensements.index', compact('recensements', 'search'));
    }

    /**
     * Affiche les maisons de la zone de collecte
     * correspondant à une affectation.
     */
    public function zoneCollecte(Request $request, Affectation $affectation): View
    {
        $this->verifierAccesAffectation($affectation);

        $affectation->load([
            'campagne',
            'village.canton.commune.prefecture',
            'equipe.superviseur',
        ]);

        $search = trim($request->input('search', ''));
        $filtre = $request->input('statut', 'tous');

        $campagneId = $affectation->campagne_id;
        $villageId = $affectation->village_id;

        $maisonsQuery = Maison::query()
            ->where('village_id', $villageId)
            ->with([
                'recensements' => function ($query) use ($campagneId) {
                    $query->where('campagne_id', $campagneId)
                        ->with(['agent', 'campagne'])
                        ->latest('idRecensement');
                },
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numeroMaison', 'like', "%{$search}%")
                        ->orWhere('uid', 'like', "%{$search}%")
                        ->orWhere('adresse', 'like', "%{$search}%");
                });
            });

        // Filtre appliqué directement en SQL, avant pagination.
        if ($filtre === 'a_recense') {
            $maisonsQuery->whereDoesntHave('recensements', function ($query) use ($campagneId) {
                $query->where('campagne_id', $campagneId);
            });
        } elseif ($filtre === 'en_cours') {
            $maisonsQuery->whereHas('recensements', function ($query) use ($campagneId) {
                $query->where('campagne_id', $campagneId)
                    ->whereIn('statut', ['brouillon', 'en_cours']);
            });
        } elseif ($filtre === 'termine') {
            $maisonsQuery->whereHas('recensements', function ($query) use ($campagneId) {
                $query->where('campagne_id', $campagneId)
                    ->whereIn('statut', ['termine', 'valide']);
            });
        }

        $maisons = $maisonsQuery
            ->orderBy('numeroMaison')
            ->paginate(20)
            ->withQueryString();

        $totalMaisons = Maison::query()
            ->where('village_id', $villageId)
            ->count();

        $recensementsCampagneBase = Recensement::query()
            ->where('campagne_id', $campagneId)
            ->whereHas('maison', function ($query) use ($villageId) {
                $query->where('village_id', $villageId);
            });

        $nombreRecensees = (clone $recensementsCampagneBase)->count();

        $nombreEnCours = (clone $recensementsCampagneBase)
            ->whereIn('statut', ['brouillon', 'en_cours'])
            ->count();

        $nombreTerminees = (clone $recensementsCampagneBase)
            ->whereIn('statut', ['termine', 'valide'])
            ->count();

        $nombreARecenser = max(0, $totalMaisons - $nombreRecensees);

        $pourcentage = $totalMaisons > 0
            ? round(($nombreRecensees / $totalMaisons) * 100)
            : 0;

        return view('agent.recensements.zone', compact(
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
        ));
    }

    /**
     * Crée ou reprend le recensement d'une maison existante
     * pour la campagne de l'affectation.
     */
    public function commencer(Affectation $affectation, Maison $maison)
    {
        $this->verifierAccesAffectation($affectation);

        abort_unless(
            (int) $maison->village_id === (int) $affectation->village_id,
            403,
            'Cette maison ne se trouve pas dans votre zone de collecte.'
        );

        $recensement = Recensement::query()
            ->where('campagne_id', $affectation->campagne_id)
            ->where('maison_id', $maison->idMaison)
            ->first();

        if ($recensement) {
            abort_unless(
                (int) $recensement->affectation_id === (int) $affectation->idAffectation,
                403,
                'Cette maison possède déjà un recensement pour cette campagne dans une autre affectation.'
            );

            if ($recensement->statut === 'valide') {
                return redirect()
                    ->route('agent.recensements.show', $recensement)
                    ->with('info', 'Ce recensement est déjà validé.');
            }

            return redirect()
                ->route('agent.recensements.show', $recensement)
                ->with('info', 'Le recensement existant a été repris.');
        }

        // La Maison existe déjà : on ne crée jamais de Maison ici.
        $recensement = Recensement::create([
            'uid' => (string) Str::uuid(),
            'campagne_id' => $affectation->campagne_id,
            'affectation_id' => $affectation->idAffectation,
            'agent_id' => Auth::id(),
            'maison_id' => $maison->idMaison,
            'statut' => 'brouillon',
            'dateDebut' => null,
            'dateDerniereModification' => now(),
        ]);

        return redirect()
            ->route('agent.recensements.show', $recensement)
            ->with('success', 'Le recensement a été créé. Vous pouvez maintenant commencer la collecte.');
    }

    /**
     * Tableau de bord du recensement.
     */
    // public function show(Recensement $recensement): View
    // {
    //     $recensement->load([
    //         'campagne',
    //         'maison.village.canton.commune.prefecture',
    //         'affectation.equipe',
    //         'affectation.village',
    //         'agent',
    //         'menages',
    //     ]);

    //     $this->verifierAccesRecensement($recensement);

    //     return view('agent.recensements.show', compact('recensement'));
    // }


    //     public function show(Recensement $recensement): View
    // {
    //     $recensement->load([
    //         'campagne',
    //         'maison.village.canton.commune.prefecture',
    //         'affectation.equipe',
    //         'affectation.village',
    //         'agent',
    //         'menages.exploitants.exploitations',
    //     ]);

    //     $this->verifierAccesRecensement($recensement);

    //     return view('agent.recensements.show', compact('recensement'));
    // }



    //     public function show(Recensement $recensement): View
    // {
    //     $recensement->load([
    //         'campagne',
    //         'maison.village.canton.commune.prefecture',
    //         'affectation.equipe',
    //         'affectation.village',
    //         'agent',
    //         'menages.exploitants.exploitation',
    //     ]);
        

    //     $this->verifierAccesRecensement($recensement);

    //     return view('agent.recensements.show', compact('recensement'));
    // }

//  public function show(Recensement $recensement)
// {
//     $recensement->load([
//         'affectation.campagne', 
//         'affectation.village.canton.commune.prefecture', 
//         'affectation.equipe.membres',
//         'maison.village',
//         'agent'
//     ]);

//     $affectation = $recensement->affectation;
//     $village = $affectation?->village;

    
//     $stats = [
//         'total'        => $village ? $village->maisons()->count() : 0,
//         'termine'      => 0, 
//         'en_cours'     => 0,
//         'non_commence' => 0,
//     ];

//     if ($village) {
//         $maisonsIds = $village->maisons()->pluck('idMaison');
//         $stats['termine'] = Recensement::whereIn('maison_id', $maisonsIds)->where('statut', 'termine')->count();
//         $stats['en_cours'] = Recensement::whereIn('maison_id', $maisonsIds)->where('statut', 'en_cours')->count();
//         $stats['non_commence'] = max(0, $stats['total'] - ($stats['termine'] + $stats['en_cours']));
//     }

//     return view('agent.recensements.show', compact('recensement', 'affectation', 'stats'));
// }


    public function show(Recensement $recensement): View
    {
        $recensement->load([
            'campagne',
            'maison.village',
            'agent',
            'menages' // <--- Indispensable pour lister les ménages de la maison
        ]);

        $this->verifierAccesRecensement($recensement);

        return view('agent.recensements.show', compact('recensement'));
    }

   
    public function mettreEnCours(Recensement $recensement)
    {
        $this->verifierAccesRecensement($recensement);

        abort_if($recensement->statut === 'valide', 403, 'Ce recensement est déjà validé.');
        abort_if($recensement->statut === 'termine', 422, 'Ce recensement est déjà terminé.');

        if ($recensement->statut === 'brouillon') {
            $recensement->statut = 'en_cours';
            $recensement->dateDebut = $recensement->dateDebut ?? now();
        }

        $recensement->dateDerniereModification = now();
        $recensement->save();

        return redirect()
            ->route('agent.recensements.show', $recensement)
            ->with('success', 'Le recensement est maintenant en cours.');
    }

    /**
     * Termine le recensement.
     */
    public function terminer(Recensement $recensement)
    {
        $this->verifierAccesRecensement($recensement);

        abort_if($recensement->statut === 'valide', 403, 'Ce recensement est déjà validé.');
        abort_if($recensement->statut === 'termine', 422, 'Ce recensement est déjà terminé.');

        $recensement->update([
            'statut' => 'termine',
            'dateTerminaison' => now(),
            'dateDerniereModification' => now(),
        ]);

        return redirect()
            ->route('agent.recensements.show', $recensement)
            ->with('success', 'Le recensement a été terminé avec succès.');
    }


    public function demarrer(
        Affectation $affectation
    ): View {
        $this->verifierAccesAffectation($affectation);

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
                        ->withCount('menages');
                },
            ])
            ->orderBy('numeroMaison')
            ->paginate(15);

        return view(
            'agent.recensements.demarrer',
            compact(
                'affectation',
                'maisons'
            )
        );
    }


        /**
         * Clôturer globalement le recensement de la maison.
         */
        public function cloturerMaison(Request $request, Recensement $recensement)
        {
            // 1. Vérifier s'il y a au moins un ménage enregistré pour cette maison
            if ($recensement->menages()->count() === 0) {
                return back()->with('error', 'Impossible de clôturer : aucun ménage n\'est enregistré pour cette maison.');
            }

            // 2. Vérifier que chaque ménage de ce recensement possède au moins un exploitant
            foreach ($recensement->menages as $menage) {
                if ($menage->exploitants()->count() === 0) {
                    return back()->with('error', 'Impossible de clôturer : au moins un ménage ne contient aucun exploitant agricole.');
                }
            }

            // 3. Valider et clôturer le recensement global
            $recensement->update([
                'statut' => 'termine',
                'dateTerminaison' => now(),
            ]);

            return redirect()->route('agent.recensements.index')
                ->with('success', 'La collecte pour cette maison a été clôturée et transmise avec succès.');
        }
    


        // app/Http/Controllers/RecensementController.php

    public function cloturer(Recensement $recensement)
    {
        // Sécurité : Vérifier qu'il est modifiable avant de clôturer
        if (! $recensement->estModifiable()) {
            return redirect()->back()->with('error', 'Ce recensement est déjà clôturé.');
        }

        // Basculement du statut vers "Terminé"
        $recensement->update([
            'statut' => 'termine',
        ]);

        return redirect()
            ->route('agent.recensements.show', $recensement)
            ->with('success', 'Le recensement de cette maison a été clôturé avec succès et verrouillé en mode consultation.');
    }


    //     public function incomplets(Request $request): View
    // {
    //     $user   = Auth::user();
    //     $search = trim($request->input('search', ''));

    //     $recensements = Recensement::query()
    //         ->with(['campagne', 'maison.village'])
    //         ->where('agent_id', $user->id)
    //         ->when($request->filled('campagne'), fn ($q) => $q->where('campagne_id', $request->campagne))
    //         ->get()
    //         ->filter(fn ($r) => $r->maison && $r->maison->village);

    //     $totaux = Maison::query()
    //         ->whereIn('village_id', $recensements->pluck('maison.village_id')->unique())
    //         ->selectRaw('village_id, COUNT(*) as total')
    //         ->groupBy('village_id')
    //         ->pluck('total', 'village_id');

    //     $villages = $recensements
    //         ->groupBy(fn ($r) => $r->maison->village_id . '-' . $r->campagne_id)
    //         ->map(function ($items) use ($totaux) {
    //             $premier  = $items->first();
    //             $termines = $items->whereIn('statut', ['termine', 'valide'])->count();
    //             $enCours  = $items->count() - $termines;
    //             $total    = max((int) ($totaux[$premier->maison->village_id] ?? 0), $items->count());

    //             return (object) [
    //                 'village'    => $premier->maison->village,
    //                 'campagne'   => $premier->campagne,
    //                 'termines'   => $termines,
    //                 'en_cours'   => $enCours,
    //                 'restantes'  => $total - $termines - $enCours,
    //                 'total'      => $total,
    //                 'pct_term'   => round($termines / $total * 100),
    //                 'pct_cours'  => round($enCours / $total * 100),
    //                 'activite'   => $items->max('dateDerniereModification'),
    //             ];
    //         })
    //         ->filter(fn ($v) => $v->termines < $v->total)
    //         ->when($search !== '', fn ($c) => $c->filter(
    //             fn ($v) => str_contains(mb_strtolower($v->village->nom), mb_strtolower($search))
    //         ))
    //         ->sortBy('activite')   
    //         ->values();

    //     return view('agent.recensements.incomplets', compact('villages', 'search'));
    // }

        public function incomplets(Request $request): View
    {
        $user   = Auth::user();
        $search = trim($request->input('search', ''));

        $villages = Recensement::query()
            ->with(['campagne', 'maison.village', 'affectation'])
            ->where('agent_id', $user->id)
            ->whereHas('affectation', fn ($q) => $q->where('statut', 'active'))
            ->get()
            ->filter(fn ($r) => $r->maison && $r->maison->village && $r->affectation)
            ->groupBy(fn ($r) => $r->affectation->getKey())
            ->map(function ($items) {
                $premier  = $items->first();
                $termines = $items->whereIn('statut', ['termine', 'valide'])->count();
                $total    = $items->count();

                return (object) [
                    'affectation' => $premier->affectation,
                    'village'     => $premier->maison->village,
                    'campagne'    => $premier->campagne,
                    'recenses'    => $total,
                    'termines'    => $termines,
                    'en_cours'    => $total - $termines,
                    'pct_term'    => round($termines / $total * 100),
                    'activite'    => $items->max('dateDerniereModification'),
                ];
            })
            ->when($search !== '', fn ($c) => $c->filter(
                fn ($v) => str_contains(mb_strtolower($v->village->nom), mb_strtolower($search))
            ))
            ->sortBy([['en_cours', 'desc'], ['activite', 'asc']])
            ->values();

        return view('agent.recensements.incomplets', compact('villages', 'search'));
    }


        /* ================= CONSULTATION ADMIN / DPA ================= */

       /* ================= CONSULTATION ADMIN / DPA ================= */

        private const CONSULT_TERMINES = ['termine', 'valide'];

    public function consultationIndex(Request $request): View
    {
        $user    = Auth::user();
        $prefixe = $this->consultPrefixe($request);
        $this->consultAutoriser($user, $prefixe);

        $perimetre = $this->consultPerimetre($user, $prefixe);
        $filtre    = $this->consultFiltres(clone $perimetre, $request, $prefixe);

        $parStatut = (clone $filtre)->reorder()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $total    = (int) $parStatut->sum();
        $termines = (int) collect(self::CONSULT_TERMINES)->sum(fn ($s) => $parStatut[$s] ?? 0);

        $stats = [
            'total'      => $total,
            'incomplets' => $total - $termines,
            'termines'   => $termines,
            'valides'    => (int) ($parStatut['valide'] ?? 0),
        ];

        $recensements = (clone $filtre)
            ->when($request->filled('statut'), function ($q) use ($request) {
                $request->statut === 'incomplets'
                    ? $q->whereNotIn('statut', self::CONSULT_TERMINES)
                    : $q->where('statut', $request->statut);
            })
            ->latest('idRecensement')
            ->paginate(20)
            ->withQueryString();

        $campagnes   = CampagneRecensement::orderByDesc('idCampagne')->get();
        $agents      = User::whereIn('id', (clone $perimetre)->reorder()->distinct()->pluck('agent_id'))
                            ->orderBy('name')->get(['id', 'name']);
        $prefectures = $prefixe === 'admin' ? Prefecture::orderBy('nom')->get() : collect();

        // Nom de la préfecture du DPA (carte « Ma préfecture »)
        $prefectureNom = $prefixe === 'dpa'
            ? Prefecture::where('idPrefecture', $this->consultPrefectureId($user))->value('nom')
            : null;

        return view('consultation.recensements.index', compact(
            'recensements', 'stats', 'campagnes', 'agents', 'prefectures', 'prefixe', 'prefectureNom'
        ));
    }

    public function consultationShow(Request $request, Recensement $recensement): View
    {
        $user    = Auth::user();
        $prefixe = $this->consultPrefixe($request);
        $this->consultAutoriser($user, $prefixe);

        $recensement->load(['campagne', 'maison.village.canton.commune', 'affectation', 'agent']);

        if ($prefixe === 'dpa') {
            $prefectureId = $recensement->maison?->village?->canton?->commune?->prefecture_id;
            abort_unless(
                $prefectureId !== null && (int) $prefectureId === $this->consultPrefectureId($user),
                403
            );
        }

        return view('consultation.recensements.show', compact('recensement', 'prefixe'));
    }

    private function consultPrefixe(Request $request): string
    {
        return $request->routeIs('dpa.*') ? 'dpa' : 'admin';
    }

    private function consultAutoriser($user, string $prefixe): void
    {
        if ($prefixe === 'admin') {
            abort_unless($user?->isAdmin(), 403);
            return;
        }

        abort_unless($user && method_exists($user, 'isDpa') && $user->isDpa(), 403);
        abort_if(
            $this->consultPrefectureId($user) === null,
            403,
            "Aucune préfecture n'est associée à votre compte."
        );
    }

    private function consultPerimetre($user, string $prefixe): Builder
    {
        $q = Recensement::query()->with([
            'campagne', 'maison.village.canton.commune', 'affectation', 'agent',
        ]);

        if ($prefixe === 'dpa') {
            $prefectureId = $this->consultPrefectureId($user);

            $q->whereHas('maison.village.canton.commune',
                fn ($c) => $c->where('prefecture_id', $prefectureId));
        }

        return $q;
    }

    private function consultFiltres(Builder $q, Request $request, string $prefixe): Builder
    {
        $search = trim($request->input('search', ''));

        return $q
            ->when($prefixe === 'admin' && $request->filled('prefecture'), fn ($q) =>
                $q->whereHas('maison.village.canton.commune',
                    fn ($c) => $c->where('prefecture_id', $request->prefecture)))
            ->when($request->filled('campagne'), fn ($q) => $q->where('campagne_id', $request->campagne))
            ->when($request->filled('agent'), fn ($q) => $q->where('agent_id', $request->agent))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->whereHas('maison', fn ($m) => $m
                            ->where('numeroMaison', 'like', "%{$search}%")
                            ->orWhere('uid', 'like', "%{$search}%")
                            ->orWhere('adresse', 'like', "%{$search}%"))
                      ->orWhereHas('campagne', fn ($c) => $c
                            ->where('libelle', 'like', "%{$search}%")
                            ->orWhere('codeCampagne', 'like', "%{$search}%"))
                      ->orWhereHas('agent', fn ($a) => $a->where('name', 'like', "%{$search}%"));
                });
            });
    }

    /**
     * Préfecture active du DPA, lue dans la table rattachements_prefecture.
     */
    private function consultPrefectureId($user): ?int
    {
        $aujourdhui = now()->toDateString();

        $id = \Illuminate\Support\Facades\DB::table('rattachements_prefecture')
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->where(function ($q) use ($aujourdhui) {
                $q->whereNull('dateDebut')->orWhere('dateDebut', '<=', $aujourdhui);
            })
            ->where(function ($q) use ($aujourdhui) {
                $q->whereNull('dateFin')->orWhere('dateFin', '>=', $aujourdhui);
            })
            ->orderByDesc('id')
            ->value('prefecture_id');

        return $id ? (int) $id : null;
    }
}

    
