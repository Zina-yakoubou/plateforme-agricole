<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaremeRequest;
use App\Http\Requests\UpdateBaremeRequest;
use App\Models\BaremeAgronomique;
use App\Models\Culture;
use App\Models\Intrant;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class BaremeController extends Controller
{
    public function index()
    {
        $prefectureId = Auth::user()->prefecture_id ?? 1;

        $baremes = BaremeAgronomique::where('prefecture_id', $prefectureId)
            ->with(['culture', 'intrant'])
            ->get();

        $cultures = Culture::all();
        $intrants = Intrant::all();

        return view('dpa.baremes.index', compact('baremes', 'cultures', 'intrants'));
    }

    public function store(StoreBaremeRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = Auth::user();
            $prefectureId = $user->prefecture_id ?? 1;

            // 1. Gestion de la Culture
            $cultureId = $validated['culture_id'] ?? null;
            if (!empty($validated['nouvelle_culture'])) {
                // CORRECTION : On transmet la 'categorie' pour éviter l'erreur NOT NULL de SQLite
                $culture = Culture::firstOrCreate(
                    ['nomCulture' => trim($validated['nouvelle_culture'])],
                    [
                        'categorie' => $validated['categorie'] ?? 'vivriere', 
                        'active'    => true
                    ]
                );
                $cultureId = $culture->idCulture ?? $culture->id;
            }

            // 2. Gestion de l'Intrant
            $intrantId = $validated['intrant_id'] ?? null;
            if (!empty($validated['nouvel_intrant'])) {
                $intrant = Intrant::firstOrCreate(
                    ['nom' => trim($validated['nouvel_intrant'])],
                    [
                        'type'  => 'autre',
                        'unite' => $validated['unite'] ?? 'kg',
                        'actif' => true
                    ]
                );
                $intrantId = $intrant->idIntrant ?? $intrant->id;
            }

            // 3. Enregistrement du barème
            BaremeAgronomique::updateOrCreate(
                [
                    'prefecture_id' => $prefectureId,
                    'culture_id'    => $cultureId,
                    'intrant_id'    => $intrantId,
                ],
                [
                    'dose_par_hectare'       => $validated['dose_par_hectare'],
                    'unite'                  => $validated['unite'],
                    'plafond_max_producteur' => $validated['plafond_max_producteur'] ?? null,
                    'est_actif'              => true,
                ]
            );

            return redirect()->route('dpa.baremes.index')
                ->with('success', 'Le barème agronomique a été enregistré avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    public function update(UpdateBaremeRequest $request, BaremeAgronomique $bareme)
    {
        try {
            $bareme->update($request->validated());

            return redirect()->route('dpa.baremes.index')
                ->with('success', 'Barème agronomique mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible de mettre à jour le barème.');
        }
    }

    public function toggleStatus(BaremeAgronomique $bareme)
    {
        if ($bareme->prefecture_id !== Auth::user()->prefecture_id) {
            abort(403, 'Action non autorisée.');
        }

        $bareme->update(['est_actif' => !$bareme->est_actif]);

        return redirect()->back()->with('success', 'Statut du barème modifié.');
    }

    public function destroy(BaremeAgronomique $bareme)
    {
        if ($bareme->prefecture_id !== Auth::user()->prefecture_id) {
            abort(403, 'Action non autorisée.');
        }

        try {
            $bareme->delete();
            return redirect()->route('dpa.baremes.index')
                ->with('success', 'Barème supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce barème.');
        }
    }
}