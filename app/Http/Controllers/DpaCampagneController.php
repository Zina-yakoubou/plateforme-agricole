<?php

namespace App\Http\Controllers;

use App\Models\CampagneDeploiement;
use App\Models\RattachementPrefecture;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DpaCampagneController extends Controller
{
    /**
     * ==========================================================
     * LISTE DES CAMPAGNES DÉPLOYÉES
     * ==========================================================
     *
     * Affiche uniquement les campagnes déployées
     * vers la préfecture du DPA connecté.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT PRÉFECTORAL ACTIF
        |--------------------------------------------------------------------------
        */

        $rattachement = RattachementPrefecture::query()
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->whereNull('dateFin')
            ->with('prefecture')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | AUCUN RATTACHEMENT
        |--------------------------------------------------------------------------
        |
        | La vue utilise $deploiements->total().
        |
        | Il faut donc toujours envoyer un paginator,
        | même lorsqu'il n'y a aucun rattachement.
        |
        */

        if (!$rattachement) {

            $deploiements = new LengthAwarePaginator(
                collect(),
                0,
                10,
                $request->integer('page', 1),
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('dpa.campagnes.index', [
                'deploiements' => $deploiements,
                'prefecture' => null,
                'search' => $request->search,
            ])->with(
                'error',
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRÉFECTURE DU DPA
        |--------------------------------------------------------------------------
        */

        $prefecture = $rattachement->prefecture;

        if (!$prefecture) {

            $deploiements = new LengthAwarePaginator(
                collect(),
                0,
                10,
                $request->integer('page', 1),
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('dpa.campagnes.index', [
                'deploiements' => $deploiements,
                'prefecture' => null,
                'search' => $request->search,
            ])->with(
                'error',
                'La préfecture associée à votre rattachement est introuvable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        $search = trim($request->input('search', ''));

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES DE LA PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $deploiements = CampagneDeploiement::query()

            ->with([
                // 'campagne.structure',
                'campagne.createur',
                'prefecture',
                'recuPar',
            ])

            /*
            |--------------------------------------------------------------------------
            | SÉCURITÉ
            |--------------------------------------------------------------------------
            |
            | Le DPA ne voit que les déploiements destinés
            | à sa propre préfecture.
            |
            */

            ->where(
                'prefecture_id',
                $prefecture->idPrefecture
            )

            /*
            |--------------------------------------------------------------------------
            | RECHERCHE
            |--------------------------------------------------------------------------
            */

            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->whereHas(
                        'campagne',
                        function ($q) use ($search) {

                            $q->where(function ($subQuery) use ($search) {

                                $subQuery
                                    ->where(
                                        'libelle',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'codeCampagne',
                                        'like',
                                        "%{$search}%"
                                    );

                            });

                        }
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | ORDRE
            |--------------------------------------------------------------------------
            */

            ->latest('created_at')

            /*
            |--------------------------------------------------------------------------
            | PAGINATION
            |--------------------------------------------------------------------------
            */

            ->paginate(10)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SYNCHRONISATION DES STATUTS
        |--------------------------------------------------------------------------
        |
        | On synchronise le statut de chaque campagne avant affichage.
        |
        */

        foreach ($deploiements as $deploiement) {

            $deploiement->campagne?->synchroniserStatut();

        }

        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'dpa.campagnes.index',
            compact(
                'deploiements',
                'prefecture',
                'search'
            )
        );
    }


    /**
     * ==========================================================
     * AFFICHER UNE CAMPAGNE
     * ==========================================================
     *
     * Le DPA ne peut consulter qu'une campagne
     * destinée à sa propre préfecture.
     */
    // public function show(CampagneDeploiement $deploiement)
    // {
    //     $user = auth()->user();

       

    //     $rattachement = $user->rattachementsPrefecture()
    //         ->where('statut', 'actif')
    //         ->whereNull('dateFin')
    //         ->first();

    //     if (!$rattachement) {

    //         abort(
    //             403,
    //             'Vous n’êtes rattaché à aucune préfecture active.'
    //         );
    //     }

       

    //     if (
    //         (int) $deploiement->prefecture_id
    //         !==
    //         (int) $rattachement->prefecture_id
    //     ) {

    //         abort(
    //             403,
    //             'Cette campagne ne concerne pas votre préfecture.'
    //         );
    //     }

       

    //    $deploiement->load([
    //         'campagne.createur',
    //         'campagne.zones.region',
    //         'campagne.zones.prefecture',

    //         'prefecture.communes.cantons.villages',

    //         'campagne.affectations.user',
    //         'recuPar',
    //     ]);
    //     $prefecture = $deploiement->prefecture;
    //     $prefecture->loadMissing('communes.cantons.villages');

        

    //     $campagne = $deploiement->campagne;

        

    //     $campagne?->synchroniserStatut();

        

    //     $prefecture = $deploiement->prefecture;

        

    //     return view(
    //         'dpa.campagnes.show',
    //         compact(
    //             'deploiement',
    //             'campagne',
    //             'prefecture'
    //         )
    //     );
    // }

        public function show(CampagneDeploiement $deploiement)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT PRÉFECTORAL ACTIF
        |--------------------------------------------------------------------------
        */

        $rattachement = RattachementPrefecture::query()
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->whereNull('dateFin')
            ->with('prefecture')
            ->first();

        if (!$rattachement) {
            abort(403, 'Vous n’êtes rattaché à aucune préfecture active.');
        }

        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DE SÉCURITÉ
        |--------------------------------------------------------------------------
        */

        if ((int) $deploiement->prefecture_id !== (int) $rattachement->prefecture_id) {
            abort(403, 'Cette campagne ne concerne pas votre préfecture.');
        }

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT DES DONNÉES
        |--------------------------------------------------------------------------
        */

        $deploiement->load([
            'campagne.createur',
            'campagne.zones.region',
            'campagne.zones.prefecture',
            'campagne.affectations.user',
            'prefecture.communes.cantons.villages',
            'recuPar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DONNÉES POUR LA VUE
        |--------------------------------------------------------------------------
        */

        $campagne = $deploiement->campagne;
        $campagne?->synchroniserStatut();

        $prefecture = $deploiement->prefecture;

        // Sécurité : s'assurer que toute la hiérarchie est chargée
        $prefecture->loadMissing([
            'communes.cantons.villages',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view('dpa.campagnes.show', compact(
            'deploiement',
            'campagne',
            'prefecture'
        ));
    }


    /**
     * ==========================================================
     * ACCUSER RÉCEPTION
     * ==========================================================
     *
     * Le DPA confirme que la campagne a bien été reçue
     * par sa préfecture.
     */
    public function receive(CampagneDeploiement $deploiement)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT ACTIF
        |--------------------------------------------------------------------------
        */

        $rattachement = RattachementPrefecture::query()
            ->where('user_id', $user->id)
            ->where('statut', 'actif')
            ->whereNull('dateFin')
            ->with('prefecture')
            ->first();

        if (!$rattachement) {

            abort(
                403,
                'Vous n’êtes rattaché à aucune préfecture active.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DE SÉCURITÉ
        |--------------------------------------------------------------------------
        */

        if (
            (int) $deploiement->prefecture_id
            !==
            (int) $rattachement->prefecture_id
        ) {

            abort(
                403,
                'Cette campagne ne concerne pas votre préfecture.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE DÉJÀ REÇUE
        |--------------------------------------------------------------------------
        */

        if ($deploiement->statut === 'recue') {

            return redirect()
                ->route('dpa.campagnes.index')
                ->with(
                    'info',
                    'Cette campagne a déjà été réceptionnée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DU STATUT
        |--------------------------------------------------------------------------
        |
        | Une campagne doit être notifiée avant
        | de pouvoir être réceptionnée.
        |
        */

        if ($deploiement->statut !== 'notifiee') {

            return redirect()
                ->route('dpa.campagnes.index')
                ->with(
                    'error',
                    'Cette campagne ne peut pas encore être réceptionnée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RÉCEPTION
        |--------------------------------------------------------------------------
        */

        $deploiement->update([
            'statut' => 'recue',
            'dateReception' => now(),
            'recu_par' => $user->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOM DE LA CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $libelle = $deploiement->campagne?->libelle
            ?? 'la campagne';

        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dpa.campagnes.index')
            ->with(
                'success',
                'La campagne « '
                . $libelle
                . ' » a été réceptionnée avec succès.'
            );
    }
}