<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Culture;
use App\Models\Exploitation;
use App\Models\Exploitant;
use App\Models\Intrant;
use App\Models\Parcelle;
use App\Models\PointGPS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;
use App\Models\CultureParcelle;
use App\Models\CultureIntrant;

class ParcelleController extends Controller
{
    public function index(Exploitant $exploitant): View
    {
        $menage = $exploitant->menage;
        $recensement = $menage ? $menage->recensement : null;

        $exploitation = Exploitation::where('exploitant_id', $exploitant->idExploitant)
            ->with(['parcelles.pointsGPS'])
            ->withCount('parcelles')
            ->first();

        $exploitations = collect();
        if ($exploitation) {
            $exploitations->push($exploitation);
        }

        $parcelles = $exploitation
            ? $exploitation->parcelles->sortBy('numeroParcelle')->values()
            : collect();

        return view('parcelles.index', [
            'recensement'   => $recensement,
            'menage'        => $menage,
            'exploitant'    => $exploitant,
            'exploitation'  => $exploitation,
            'exploitations' => $exploitations,
            'parcelles'     => $parcelles,
        ]);
    }

    public function create(Exploitant $exploitant): View|RedirectResponse
    {
        $menage = $exploitant->menage;
        $recensement = $menage ? $menage->recensement : null;

        $exploitation = Exploitation::query()
            ->where('exploitant_id', $exploitant->idExploitant)
            ->withCount('parcelles')
            ->first();

        if (!$exploitation) {
            return redirect()
                ->route('agent.exploitations.create', $exploitant)
                ->with('error', 'Aucune exploitation n’est encore créée pour cet exploitant.');
        }

        $numeroParcelle = $this->genererProchainNumeroParcelle($exploitation);

        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        $intrantsReferentiel = Intrant::query()
            ->orderBy('nom')
            ->get();

        return view('parcelles.create', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'numeroParcelle' => $numeroParcelle,
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }

    public function store(
        StoreParcelleRequest $request,
        Exploitant $exploitant
    ): RedirectResponse {
        $exploitation = Exploitation::query()
            ->where('exploitant_id', $exploitant->idExploitant)
            ->first();

        if (!$exploitation) {
            return back()
                ->withInput()
                ->with('error', 'Aucune exploitation n’est associée à cet exploitant.');
        }

        $data = $request->validated();
        $points = $this->decoderPointsGPS($data['points_gps'] ?? null);

        if (count($points) < 3) {
            throw ValidationException::withMessages([
                'points_gps' => 'Veuillez enregistrer au moins 3 points GPS pour calculer la superficie de la parcelle.',
            ]);
        }

        $superficie = $this->calculerSuperficieHa($points);

        if ($superficie <= 0) {
            throw ValidationException::withMessages([
                'points_gps' => 'Impossible de calculer une superficie valide à partir des points GPS.',
            ]);
        }

        $numeroParcelle = $this->genererProchainNumeroParcelle($exploitation);

        try {
            DB::transaction(function () use (
                $data,
                $points,
                $superficie,
                $numeroParcelle,
                $exploitation
            ) {
                $parcelle = new Parcelle();
                $parcelle->uid = (string) Str::uuid();
                $parcelle->exploitation_id = $exploitation->idExploitation;
                $parcelle->numeroParcelle = $numeroParcelle;
                $parcelle->superficie = $superficie;
                $parcelle->statutParcelle = $data['statutParcelle'] ?? null;
                $parcelle->typeSol = $data['typeSol'] ?? null;
                $parcelle->modeFaireValoir = $data['modeFaireValoir'] ?? null;
                $parcelle->modeIrrigation = $data['modeIrrigation'] ?? null;
                $parcelle->presenceArbres = isset($data['presenceArbres']) ? (bool) $data['presenceArbres'] : false;
                $parcelle->observations = $data['observations'] ?? null;
                $parcelle->save();

                $this->enregistrerPointsGPS($parcelle, $points);
                $this->enregistrerCulturesEtIntrants($parcelle, $data['cultures'] ?? []);
            });

            return redirect()
                ->route('agent.parcelles.index', $exploitant)
                ->with('success', 'La parcelle ' . $numeroParcelle . ' a été enregistrée avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la création d’une parcelle.', [
                'message' => $e->getMessage(),
                'exploitant_id' => $exploitant->idExploitant,
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Impossible d’enregistrer la parcelle : ' . $e->getMessage());
        }
    }

    public function edit(Parcelle $parcelle): View
    {
        $exploitation = $parcelle->exploitation;
        $exploitant = $exploitation->exploitant;
        $menage = $exploitant->menage;
        $recensement = $menage ? $menage->recensement : null;

        $parcelle->load([
            'pointsGPS' => function ($query) {
                $query->orderBy('ordre');
            },
        ]);

        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        $intrantsReferentiel = Intrant::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('agent.parcelles.edit', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'parcelle' => $parcelle,
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }

    public function update(
        UpdateParcelleRequest $request,
        Parcelle $parcelle
    ): RedirectResponse {
        $data = $request->validated();
        $points = $this->decoderPointsGPS($data['points_gps'] ?? null);

        if (count($points) < 3) {
            throw ValidationException::withMessages([
                'points_gps' => 'Veuillez enregistrer au moins 3 points GPS pour calculer la superficie.',
            ]);
        }

        $superficie = $this->calculerSuperficieHa($points);

        if ($superficie <= 0) {
            throw ValidationException::withMessages([
                'points_gps' => 'Impossible de calculer une superficie valide à partir des points GPS.',
            ]);
        }

        try {
            DB::transaction(function () use ($parcelle, $data, $points, $superficie) {
                $parcelle->superficie = $superficie;
                $parcelle->statutParcelle = $data['statutParcelle'] ?? null;
                $parcelle->typeSol = $data['typeSol'] ?? null;
                $parcelle->modeFaireValoir = $data['modeFaireValoir'] ?? null;
                $parcelle->modeIrrigation = $data['modeIrrigation'] ?? null;
                $parcelle->presenceArbres = isset($data['presenceArbres']) ? (bool) $data['presenceArbres'] : false;
                $parcelle->observations = $data['observations'] ?? null;
                $parcelle->save();

                $parcelle->pointsGPS()->delete();
                $this->enregistrerPointsGPS($parcelle, $points);

                if (method_exists($this, 'mettreAJourCulturesEtIntrants')) {
                    $this->mettreAJourCulturesEtIntrants($parcelle, $data['cultures'] ?? []);
                }
            });

            return redirect()
                ->route('agent.parcelles.index', $parcelle->exploitation->exploitant_id)
                ->with('success', 'La parcelle a été mise à jour avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la modification d’une parcelle.', [
                'message' => $e->getMessage(),
                'parcelle_id' => $parcelle->idParcelle ?? $parcelle->id,
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Impossible de modifier la parcelle : ' . $e->getMessage());
        }
    }

    public function destroy(Parcelle $parcelle): RedirectResponse
    {
        $exploitantId = $parcelle->exploitation->exploitant_id;

        try {
            DB::transaction(function () use ($parcelle) {
                PointGPS::query()->where('parcelle_id', $parcelle->idParcelle)->delete();
                $parcelle->delete();
            });

            return redirect()
                ->route('agent.parcelles.index', $exploitantId)
                ->with('success', 'La parcelle a été supprimée avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la suppression d’une parcelle.', [
                'message' => $e->getMessage(),
                'parcelle_id' => $parcelle->idParcelle,
                'exception' => $e,
            ]);

            return back()->with('error', 'Impossible de supprimer la parcelle : ' . $e->getMessage());
        }
    }

    private function genererProchainNumeroParcelle(Exploitation $exploitation): string
    {
        $dernierNumero = Parcelle::query()
            ->where('exploitation_id', $exploitation->idExploitation)
            ->orderByDesc('idParcelle')
            ->value('numeroParcelle');

        if (!$dernierNumero) {
            return 'P-001';
        }

        if (preg_match('/P-(\d+)/', $dernierNumero, $matches)) {
            $numero = ((int) $matches[1]) + 1;
        } else {
            $numero = Parcelle::query()->where('exploitation_id', $exploitation->idExploitation)->count() + 1;
        }

        return 'P-' . str_pad((string) $numero, 3, '0', STR_PAD_LEFT);
    }

    private function decoderPointsGPS(mixed $pointsGps): array
    {
        if (is_string($pointsGps)) {
            $pointsGps = json_decode($pointsGps, true);
        }

        if (!is_array($pointsGps)) {
            return [];
        }

        $points = [];
        foreach ($pointsGps as $point) {
            if (!is_array($point) || !isset($point['latitude'], $point['longitude'])) {
                continue;
            }

            $latitude = (float) $point['latitude'];
            $longitude = (float) $point['longitude'];

            if (!is_finite($latitude) || !is_finite($longitude) || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                continue;
            }

            $points[] = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'precisionGPS' => isset($point['precisionGPS']) && is_numeric($point['precisionGPS']) ? (float) $point['precisionGPS'] : null,
                'altitude' => isset($point['altitude']) && is_numeric($point['altitude']) ? (float) $point['altitude'] : null,
            ];
        }

        foreach ($points as $index => &$point) {
            $point['ordre'] = $index + 1;
        }
        unset($point);

        return $points;
    }

    private function calculerSuperficieHa(array $points): float
    {
        if (count($points) < 3) {
            return 0.0;
        }

        $latitude0 = deg2rad((float) $points[0]['latitude']);
        $longitude0 = deg2rad((float) $points[0]['longitude']);
        $rayonTerre = 6371000.0;
        $xy = [];

        foreach ($points as $point) {
            $latitude = deg2rad((float) $point['latitude']);
            $longitude = deg2rad((float) $point['longitude']);
            $x = ($longitude - $longitude0) * cos($latitude0) * $rayonTerre;
            $y = ($latitude - $latitude0) * $rayonTerre;
            $xy[] = ['x' => $x, 'y' => $y];
        }

        $surfaceM2 = 0.0;
        $nombrePoints = count($xy);

        for ($i = 0; $i < $nombrePoints; $i++) {
            $j = ($i + 1) % $nombrePoints;
            $surfaceM2 += ($xy[$i]['x'] * $xy[$j]['y']) - ($xy[$j]['x'] * $xy[$i]['y']);
        }

        return round(abs($surfaceM2) / 2 / 10000, 4);
    }

    private function enregistrerPointsGPS(Parcelle $parcelle, array $points): void
    {
        foreach ($points as $index => $point) {
            $pointGPS = new PointGPS();
            $pointGPS->parcelle_id = $parcelle->idParcelle;
            $pointGPS->latitude = $point['latitude'];
            $pointGPS->longitude = $point['longitude'];
            $pointGPS->precisionGPS = $point['precisionGPS'] ?? null;
            $pointGPS->altitude = $point['altitude'] ?? null;
            $pointGPS->ordre = $index + 1;
            $pointGPS->save();
        }
    }

    private function enregistrerCulturesEtIntrants(Parcelle $parcelle, array $cultures): void
    {
        foreach ($cultures as $cultureData) {
            if (empty($cultureData['culture_id'])) {
                continue;
            }

            $cultureParcelle = CultureParcelle::create([
                'uid' => (string) Str::uuid(),
                'parcelle_id' => $parcelle->idParcelle,
                'culture_id' => $cultureData['culture_id'],
                'campagneAgricole' => $cultureData['campagneAgricole'] ?? null,
                'modeCulture' => $cultureData['modeCulture'] ?? 'principale',
                'superficieCultivee' => $cultureData['superficieCultivee'] ?: null,
                'dateSemis' => $cultureData['dateSemis'] ?: null,
                'dateRecoltePrevue' => $cultureData['dateRecoltePrevue'] ?: null,
                'dateRecolteEffective' => $cultureData['dateRecolteEffective'] ?: null,
                'irriguee' => !empty($cultureData['irriguee']),
                'etatCulture' => $cultureData['etatCulture'] ?? 'semis',
                'observations' => $cultureData['observations'] ?? null,
            ]);

            foreach ($cultureData['intrants'] ?? [] as $intrantData) {
                if (empty($intrantData['intrant_id']) || empty($intrantData['quantite']) || empty($intrantData['nombreApplications'])) {
                    continue;
                }

                CultureIntrant::create([
                    'culture_parcelle_id' => $cultureParcelle->idCultureParcelle,
                    'intrant_id' => $intrantData['intrant_id'],
                    'quantite' => $intrantData['quantite'],
                    'nombreApplications' => $intrantData['nombreApplications'],
                    'dateApplication' => $intrantData['dateApplication'] ?: null,
                    'observations' => $intrantData['observations'] ?? null,
                ]);
            }
        }
    }
}