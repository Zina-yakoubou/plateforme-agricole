<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Models\Equipe;
use App\Models\EquipeMembre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EquipeController extends Controller
{
    /**
     * Liste des équipes de la préfecture du DPA.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Préfecture actuelle du DPA
        |--------------------------------------------------------------------------
        */

        $prefecture = $user->rattachementPrefectureActif?->prefecture;

        if (!$prefecture) {
            abort(403, 'Aucune préfecture de rattachement active.');
        }

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        $search = trim($request->input('search', ''));

        /*
        |--------------------------------------------------------------------------
        | Équipes
        |--------------------------------------------------------------------------
        |
        | Pour le moment, on récupère les équipes dont le superviseur
        | appartient à cette préfecture.
        |
        */

        $query = Equipe::query()
            ->with([
                'superviseur',
                'membres.user',
            ])
            ->whereHas('superviseur.rattachementPrefectureActif', function ($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->idPrefecture)
                  ->where('statut', 'actif')
                  ->whereNull('dateFin');
            });

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('nom', 'like', "%{$search}%")
                    ->orWhereHas('superviseur', function ($q) use ($search) {

                        $q->where('name', 'like', "%{$search}%");

                    });

            });
        }

        $equipes = $query
            ->latest('idEquipe')
            ->paginate(10)
            ->withQueryString();

        return view('dpa.equipes.index', compact(
            'equipes',
            'prefecture',
            'search'
        ));
    }


    /**
     * Formulaire de création d'une équipe.
     */
    public function create()
    {
        $user = Auth::user();

        $prefecture = $user->rattachementPrefectureActif?->prefecture;

        if (!$prefecture) {
            abort(403, 'Aucune préfecture de rattachement active.');
        }

        /*
        |--------------------------------------------------------------------------
        | Superviseurs disponibles
        |--------------------------------------------------------------------------
        */

        $superviseurs = User::query()
            ->whereHas('role', function ($q) {
                $q->where('codeRole', 'R03');
            })
            ->whereHas('rattachementPrefectureActif', function ($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->idPrefecture)
                    ->where('statut', 'actif')
                    ->whereNull('dateFin');
            })
            ->where('statut', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Enquêteurs disponibles
        |--------------------------------------------------------------------------
        */

        $enqueteurs = User::query()
            ->whereHas('role', function ($q) {
                $q->whereIn('codeRole', [
                    'R04',
                ]);
            })
            ->whereHas('rattachementPrefectureActif', function ($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->idPrefecture)
                    ->where('statut', 'actif')
                    ->whereNull('dateFin');
            })
            ->where('statut', true)
            ->orderBy('name')
            ->get();

        return view('dpa.equipes.create', compact(
            'prefecture',
            'superviseurs',
            'enqueteurs'
        ));
    }


    /**
     * Enregistre une nouvelle équipe.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $prefecture = $user->rattachementPrefectureActif?->prefecture;

        if (!$prefecture) {
            abort(403, 'Aucune préfecture de rattachement active.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:150',
            ],

            'superviseur_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'membres' => [
                'required',
                'array',
                'min:1',
                'max:3',
            ],

            'membres.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],

        ], [

            'nom.required' =>
                'Le nom de l’équipe est obligatoire.',

            'superviseur_id.required' =>
                'Veuillez sélectionner un superviseur.',

            'membres.required' =>
                'Veuillez sélectionner au moins un enquêteur.',

            'membres.min' =>
                'Une équipe doit avoir au moins un enquêteur.',

            'membres.max' =>
                'Une équipe ne peut pas dépasser 3 enquêteurs.',

            'membres.*.distinct' =>
                'Un enquêteur ne peut apparaître qu’une seule fois.',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérification superviseur
        |--------------------------------------------------------------------------
        */

        $superviseur = User::query()
            ->where('id', $validated['superviseur_id'])
            ->where('statut', true)
            ->whereHas('role', function ($q) {
                $q->where('codeRole', 'R03');
            })
            ->whereHas('rattachementPrefectureActif', function ($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->idPrefecture)
                    ->where('statut', 'actif')
                    ->whereNull('dateFin');
            })
            ->first();

        if (!$superviseur) {
            return back()
                ->withInput()
                ->withErrors([
                    'superviseur_id' =>
                        'Le superviseur sélectionné n’appartient pas à votre préfecture.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification enquêteurs
        |--------------------------------------------------------------------------
        */

        $enqueteursValides = User::query()
            ->whereIn('id', $validated['membres'])
            ->where('statut', true)
            ->whereHas('role', function ($q) {
                $q->whereIn('codeRole', ['R04']);
            })
            ->whereHas('rattachementPrefectureActif', function ($q) use ($prefecture) {
                $q->where('prefecture_id', $prefecture->idPrefecture)
                    ->where('statut', 'actif')
                    ->whereNull('dateFin');
            })
            ->count();

        if ($enqueteursValides !== count($validated['membres'])) {

            return back()
                ->withInput()
                ->withErrors([
                    'membres' =>
                        'Un ou plusieurs enquêteurs sélectionnés ne sont pas valides pour votre préfecture.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $reference = $this->genererReference();

            $equipe = Equipe::create([
                'reference' => $reference,
                'nom' => $validated['nom'],
                'superviseur_id' => $validated['superviseur_id'],
                'statut' => 'ACTIVE',
            ]);

            foreach ($validated['membres'] as $userId) {

                EquipeMembre::create([
                    'equipe_id' => $equipe->idEquipe,
                    'user_id' => $userId,
                ]);

            }
        });

        return redirect()
            ->route('dpa.equipes.index')
            ->with('success', 'L’équipe a été créée avec succès.');
    }


    /**
     * Génération de la référence de l'équipe.
     */
    private function genererReference(): string
    {
        do {

            $reference = 'EQ-' . now()->format('Y') . '-' .
                str_pad(
                    (Equipe::max('idEquipe') ?? 0) + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

        } while (
            Equipe::where('reference', $reference)->exists()
        );

        return $reference;
    }
}