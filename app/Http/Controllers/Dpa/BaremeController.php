<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaremeRequest;
use App\Http\Requests\UpdateBaremeRequest;
use App\Models\BaremeAgronomique;
use App\Models\Culture;
use App\Models\CultureParcelle;
use App\Models\Exploitation;
use App\Models\Intrant;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaremeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // DPA : sa préfecture. Admin : filtre facultatif (null = toutes).
        $prefectureId = $user->isAdmin()
            ? ($request->filled('prefecture_id') ? (int) $request->input('prefecture_id') : null)
            : $this->prefectureDuDpa();

        $search = trim((string) $request->input('search'));
        $statut = $request->input('statut');

        $baremes = BaremeAgronomique::query()
            ->with(['culture', 'intrant', 'prefecture'])
            ->when($prefectureId, fn ($q) => $q->where('prefecture_id', $prefectureId))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->whereHas('culture', fn ($c) => $c->where('nomCulture', 'like', "%{$search}%"))
                      ->orWhereHas('intrant', fn ($i) => $i->where('nom', 'like', "%{$search}%"));
                });
            })
            ->when($statut === 'actif', fn ($q) => $q->where('est_actif', true))
            ->when($statut === 'inactif', fn ($q) => $q->where('est_actif', false))
            ->orderByDesc('idBareme')
            ->paginate(15)
            ->withQueryString();

        // Cultures : uniquement celles recensées dans la zone du DPA.
        // Admin sans préfecture choisie : toutes les cultures.
        $cultures = $prefectureId
            ? $this->culturesDeLaZone($prefectureId)->get()
            : Culture::orderBy('nomCulture')->get();

        $intrants = Intrant::orderBy('nom')->get();
        $prefectures = $user->isAdmin() ? Prefecture::orderBy('nom')->get() : collect();

        // Référentiel : [ idCulture => [idIntrant, idIntrant, ...], ... ]
        $referentiel = DB::table('referentiel_culture_intrants')
            ->get()
            ->groupBy('culture_id')
            ->map(fn ($lignes) => $lignes->pluck('intrant_id')->map(fn ($id) => (int) $id)->values());

        return view('dpa.baremes.index', compact(
            'baremes', 'cultures', 'intrants', 'prefectures', 'prefectureId', 'search', 'referentiel'
        ));
    }

    public function store(StoreBaremeRequest $request)
    {
        $user = Auth::user();

        $prefectureId = $user->isAdmin()
            ? (int) $request->validated('prefecture_id')
            : $this->prefectureDuDpa();

        try {
            $validated = $request->validated();

            $cultureId = $validated['culture_id'] ?? null;

            if (! empty($validated['nouvelle_culture'])) {
                // Seul un admin peut créer une nouvelle culture
                if (! $user->isAdmin()) {
                    return redirect()->back()->withInput()->withErrors([
                        'culture_id' => "Vous ne pouvez définir un barème que pour une culture recensée dans votre préfecture.",
                    ]);
                }

                $culture = Culture::firstOrCreate(
                    ['nomCulture' => trim($validated['nouvelle_culture'])],
                    ['categorie' => $validated['categorie'] ?? 'vivriere', 'active' => true]
                );
                $cultureId = $culture->getKey();
            }

            // Un DPA ne peut choisir qu'une culture recensée dans sa préfecture
            if (! $user->isAdmin()
                && (! $cultureId
                    || ! $this->culturesDeLaZone($prefectureId)->where('idCulture', $cultureId)->exists())) {
                return redirect()->back()->withInput()->withErrors([
                    'culture_id' => "Cette culture n'a pas été recensée dans votre préfecture.",
                ]);
            }

            $intrantId = $validated['intrant_id'] ?? null;
            if (! empty($validated['nouvel_intrant'])) {
                $intrant = Intrant::firstOrCreate(
                    ['nom' => trim($validated['nouvel_intrant'])],
                    ['type' => 'autre', 'unite' => $validated['unite'] ?? 'kg', 'actif' => true]
                );
                $intrantId = $intrant->getKey();
            }

            $existant = BaremeAgronomique::where('prefecture_id', $prefectureId)
                ->where('culture_id', $cultureId)
                ->where('intrant_id', $intrantId)
                ->first();

            if ($existant && $existant->est_actif) {
                $dose = rtrim(rtrim(number_format($existant->dose_par_hectare, 2, ',', ' '), '0'), ',');

                return redirect()->back()->withInput()->withErrors([
                    'intrant_id' => "Un barème actif existe déjà pour cette culture et cet intrant ({$dose} {$existant->unite}). "
                        . "Modifiez-le depuis la liste, ou désactivez-le avant d'en définir un nouveau.",
                ]);
            }

            $donnees = [
                'dose_par_hectare'       => $validated['dose_par_hectare'],
                'unite'                  => $validated['unite'],
                'plafond_max_producteur' => $validated['plafond_max_producteur'] ?? null,
                'est_actif'              => true,
            ];

            if ($existant) {
                $existant->update($donnees);
                $message = 'Le barème inactif a été réactivé avec la nouvelle dose.';
            } else {
                BaremeAgronomique::create($donnees + [
                    'prefecture_id' => $prefectureId,
                    'culture_id'    => $cultureId,
                    'intrant_id'    => $intrantId,
                ]);
                $message = 'Le barème agronomique a été enregistré avec succès.';
            }

            return redirect()
                ->route('dpa.baremes.index', $user->isAdmin() ? ['prefecture_id' => $prefectureId] : [])
                ->with('success', $message);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->withInput()
                ->with('error', "Erreur lors de l'enregistrement du barème.");
        }
    }

    public function update(UpdateBaremeRequest $request)
    {
        $bareme = BaremeAgronomique::findOrFail($request->input('bareme_id'));
        $this->verifierPropriete($bareme);

        try {
            $bareme->update($request->safe()->only([
                'dose_par_hectare', 'unite', 'plafond_max_producteur',
            ]));

            return redirect()->back()->with('success', 'Barème agronomique mis à jour avec succès.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->withInput()
                ->with('error', 'Impossible de mettre à jour le barème.');
        }
    }

    public function toggleStatus(Request $request)
    {
        $bareme = BaremeAgronomique::findOrFail($request->input('bareme_id'));
        $this->verifierPropriete($bareme);

        $bareme->update(['est_actif' => ! $bareme->est_actif]);

        return redirect()->back()->with('success', 'Statut du barème modifié.');
    }

    public function destroy(Request $request)
    {
        $bareme = BaremeAgronomique::findOrFail($request->input('bareme_id'));
        $this->verifierPropriete($bareme);

        try {
            $bareme->delete();

            return redirect()->back()->with('success', 'Barème supprimé avec succès.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with('error', 'Impossible de supprimer ce barème.');
        }
    }

    private function prefectureDuDpa(): int
    {
        $prefectureId = Auth::user()->prefectureId();

        abort_if(! $prefectureId, 403, 'Aucune préfecture rattachée à votre compte.');

        return $prefectureId;
    }

    private function verifierPropriete(BaremeAgronomique $bareme): void
    {
        if (Auth::user()->isAdmin()) {
            return;
        }

        abort_if(
            (int) $bareme->prefecture_id !== $this->prefectureDuDpa(),
            403,
            'Action non autorisée.'
        );
    }

    /**
     * Cultures effectivement recensées dans une préfecture.
     * Chaîne : culture_parcelle → parcelle → exploitation → exploitant
     *          → (scope dansPrefecture) ménage → recensement → maison
     *          → village → canton → commune.prefecture_id
     *
     * Retourne un Builder (on peut chaîner ->get(), ->where(...)->exists()).
     */
    private function culturesDeLaZone(int $prefectureId)
    {
        $cultureIds = CultureParcelle::query()
            ->whereIn('parcelle_id', function ($q) use ($prefectureId) {
                $q->select('idParcelle')
                  ->from('parcelles')
                  ->whereIn(
                      'exploitation_id',
                      Exploitation::query()
                          ->whereHas('exploitant', fn ($e) => $e->dansPrefecture($prefectureId))
                          ->select('idExploitation')
                  );
            })
            ->whereNotNull('culture_id')
            ->distinct()
            ->pluck('culture_id');

        return Culture::query()
            ->whereIn('idCulture', $cultureIds)
            ->orderBy('nomCulture');
    }
}