<?php

namespace App\Http\Controllers;

use App\Models\SaisonDistribution;
use App\Models\CampagneRecensement;
use App\Http\Requests\StoreSaisonDistributionRequest;
use App\Http\Requests\UpdateSaisonDistributionRequest;
use Illuminate\Http\Request;

class SaisonDistributionController extends Controller
{
        public function index(Request $request)
    {
        $search = $request->input('search');

        $saisons = SaisonDistribution::with('campagneBase')
            ->when($search, function ($query, $search) {
                $query->where('libelle', 'like', '%' . $search . '%')
                    ->orWhere('statut', 'like', '%' . $search . '%');
            })
            ->latest('dateDebut')
            ->paginate(10)
            ->withQueryString(); // Conserve le paramètre de recherche dans la pagination

        return view('saison_distributions.index', compact('saisons', 'search'));
    }

    public function create()
    {
        $campagnesRecensement = CampagneRecensement::all();

        return view('saison_distributions.create', compact('campagnesRecensement'));
    }

    public function store(StoreSaisonDistributionRequest $request)
    {
        $validated = $request->validated();
        $validated['baseSurDerniereAnnee'] = $request->has('baseSurDerniereAnnee');

        SaisonDistribution::create($validated);

        return redirect()->route('saison-distributions.index')
            ->with('success', 'Saison de distribution créée avec succès.');
    }

    public function show(SaisonDistribution $saisonDistribution)
    {
        $saisonDistribution->load('campagneBase');
        
        return view('saison_distributions.show', compact('saisonDistribution'));
    }

    public function edit(SaisonDistribution $saisonDistribution)
    {
        if (in_array($saisonDistribution->statut, ['cloturee', 'archivee'])) {
            return redirect()->route('saison-distributions.index')
                ->with('error', 'Impossible de modifier une saison clôturée ou archivée.');
        }

        $campagnesRecensement = CampagneRecensement::all();

        return view('saison_distributions.edit', compact('saisonDistribution', 'campagnesRecensement'));
    }

    public function update(UpdateSaisonDistributionRequest $request, SaisonDistribution $saisonDistribution)
    {
        if (in_array($saisonDistribution->statut, ['cloturee', 'archivee'])) {
            return redirect()->route('saison-distributions.index')
                ->with('error', 'Impossible de modifier une saison clôturée ou archivée.');
        }

        $validated = $request->validated();
        $validated['baseSurDerniereAnnee'] = $request->has('baseSurDerniereAnnee');

        $saisonDistribution->update($validated);

        return redirect()->route('saison-distributions.index')
            ->with('success', 'Saison de distribution mise à jour avec succès.');
    }

    public function destroy(SaisonDistribution $saisonDistribution)
    {
        if ($saisonDistribution->statut === 'ouverte') {
            return redirect()->route('saison-distributions.index')
                ->with('error', 'Impossible de supprimer une saison en cours d\'ouverture.');
        }

        $saisonDistribution->delete();

        return redirect()->route('saison-distributions.index')
            ->with('success', 'Saison de distribution supprimée avec succès.');
    }
}