<?php


namespace App\Http\Controllers\Dpa;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBaremeRequest;
use App\Http\Requests\UpdateBaremeRequest;
use App\Models\BaremeAgronomique;
use App\Models\Culture;
use App\Models\Intrant;
use Illuminate\Database\QueryException;

class BaremeController extends Controller
{
    public function index()
    {
        $prefectureId = Auth::user()->prefecture_id;

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
            $prefectureId = Auth::user()->prefecture_id;

            BaremeAgronomique::updateOrCreate(
                [
                    'prefecture_id' => $prefectureId,
                    'culture_id'    => $validated['culture_id'],
                    'intrant_id'    => $validated['intrant_id'],
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

        } catch (QueryException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur BDD : Un barème existe déjà pour ce couple Culture/Intrant.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur inattendue est survenue lors de l\'enregistrement.');
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