<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BaremeAgronomique;
use App\Models\Culture;
use App\Models\Intrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReferentielController extends Controller
{
    private const LIENS = 'referentiel_culture_intrants';

    private const CATEGORIES = [
        'vivriere'   => 'Vivrière',
        'rente'      => 'Rente',
        'maraichere' => 'Maraîchère',
        'fruitiere'  => 'Fruitière',
        'fourragere' => 'Fourragère',
        'autre'      => 'Autre',
    ];

    /* ------------------------------------------------------------------ */
    /*  PAGE                                                               */
    /* ------------------------------------------------------------------ */

    public function index(Request $request)
    {
        $onglet = $request->input('onglet') === 'intrants' ? 'intrants' : 'cultures';

        $cultures = Culture::orderBy('nomCulture')->get();
        $intrants = Intrant::orderBy('nom')->get();

        // [ idCulture => [idIntrant, ...] ]
        $liens = DB::table(self::LIENS)->get()
            ->groupBy('culture_id')
            ->map(fn ($l) => $l->pluck('intrant_id')->map(fn ($id) => (int) $id)->values());

        $nbCulturesParIntrant = DB::table(self::LIENS)
            ->select('intrant_id', DB::raw('count(*) as n'))
            ->groupBy('intrant_id')->pluck('n', 'intrant_id');

        $nbBaremesParCulture = BaremeAgronomique::select('culture_id', DB::raw('count(*) as n'))
            ->groupBy('culture_id')->pluck('n', 'culture_id');

        $nbBaremesParIntrant = BaremeAgronomique::select('intrant_id', DB::raw('count(*) as n'))
            ->groupBy('intrant_id')->pluck('n', 'intrant_id');

        return view('admin.referentiel.index', [
            'onglet'               => $onglet,
            'cultures'             => $cultures,
            'intrants'             => $intrants,
            'liens'                => $liens,
            'categories'           => self::CATEGORIES,
            'types'                => $this->types(),
            'unites'               => $this->unites(),
            'nbCulturesParIntrant' => $nbCulturesParIntrant,
            'nbBaremesParCulture'  => $nbBaremesParCulture,
            'nbBaremesParIntrant'  => $nbBaremesParIntrant,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  CULTURES                                                           */
    /* ------------------------------------------------------------------ */

    public function storeCulture(Request $request)
    {
        $data = $this->validerCulture($request, false);

        $culture = new Culture();
        $culture->nomCulture = $data['nomCulture'];
        $culture->categorie  = $data['categorie'];
        $culture->active     = true;
        $culture->save();

        return $this->retour('cultures', 'La culture a été ajoutée au référentiel.');
    }

    public function updateCulture(Request $request)
    {
        $data = $this->validerCulture($request, true);

        $culture = Culture::where('idCulture', (int) $data['culture_id'])->firstOrFail();
        $culture->nomCulture = $data['nomCulture'];
        $culture->categorie  = $data['categorie'];
        $culture->save();

        return $this->retour('cultures', 'La culture a été mise à jour.');
    }

    public function toggleCulture(Request $request)
    {
        $culture = Culture::where('idCulture', (int) $request->input('culture_id'))->firstOrFail();
        $culture->active = ! $culture->active;
        $culture->save();

        return $this->retour('cultures', $culture->active ? 'Culture activée.' : 'Culture désactivée.');
    }

    /* ------------------------------------------------------------------ */
    /*  INTRANTS                                                           */
    /* ------------------------------------------------------------------ */

    public function storeIntrant(Request $request)
    {
        $data = $this->validerIntrant($request, false);

        $intrant = new Intrant();
        $intrant->nom   = $data['nomIntrant'];
        $intrant->type  = $data['type'];
        $intrant->unite = $data['unite'];
        $intrant->actif = true;
        $intrant->save();

        return $this->retour('intrants', "L'intrant a été ajouté au référentiel.");
    }

    public function updateIntrant(Request $request)
    {
        $data = $this->validerIntrant($request, true);

        $intrant = Intrant::where('idIntrant', (int) $data['intrant_id'])->firstOrFail();
        $intrant->nom   = $data['nomIntrant'];
        $intrant->type  = $data['type'];
        $intrant->unite = $data['unite'];
        $intrant->save();

        return $this->retour('intrants', "L'intrant a été mis à jour.");
    }

    public function toggleIntrant(Request $request)
    {
        $intrant = Intrant::where('idIntrant', (int) $request->input('intrant_id'))->firstOrFail();
        $intrant->actif = ! $intrant->actif;
        $intrant->save();

        return $this->retour('intrants', $intrant->actif ? 'Intrant activé.' : 'Intrant désactivé.');
    }

    /* ------------------------------------------------------------------ */
    /*  INTRANTS POSSIBLES D'UNE CULTURE                                   */
    /* ------------------------------------------------------------------ */

    public function syncIntrants(Request $request)
    {
        $data = $request->validateWithBag('liens', [
            'culture_id'  => ['required', 'integer', 'exists:cultures,idCulture'],
            'intrants'    => ['nullable', 'array'],
            'intrants.*'  => ['integer', 'exists:intrants,idIntrant'],
        ]);

        $cultureId = (int) $data['culture_id'];
        $voulus    = collect($data['intrants'] ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();

        DB::transaction(function () use ($cultureId, $voulus) {
            $existants = DB::table(self::LIENS)->where('culture_id', $cultureId)
                ->pluck('intrant_id')->map(fn ($id) => (int) $id)->all();

            $aRetirer = array_diff($existants, $voulus);
            $aAjouter = array_diff($voulus, $existants);

            if ($aRetirer) {
                DB::table(self::LIENS)->where('culture_id', $cultureId)
                    ->whereIn('intrant_id', $aRetirer)->delete();
            }

            foreach ($aAjouter as $intrantId) {
                DB::table(self::LIENS)->insert([
                    'culture_id' => $cultureId,
                    'intrant_id' => $intrantId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return $this->retour('cultures', 'Les intrants de la culture ont été enregistrés.');
    }

    /* ------------------------------------------------------------------ */
    /*  OUTILS                                                             */
    /* ------------------------------------------------------------------ */

    private function validerCulture(Request $request, bool $modif): array
    {
        $regles = [
            'nomCulture' => ['required', 'string', 'max:100'],
            'categorie'  => ['required', Rule::in(array_keys(self::CATEGORIES))],
        ];
        if ($modif) {
            $regles['culture_id'] = ['required', 'integer'];
        }

        $data = $request->validateWithBag('culture', $regles);
        $data['nomCulture'] = $this->nettoyer($data['nomCulture']);

        $this->refuserDoublon(
            Culture::all(), 'nomCulture', 'idCulture',
            $data['nomCulture'], $modif ? (int) $data['culture_id'] : null,
            'culture', 'nomCulture', 'La culture'
        );

        return $data;
    }

    private function validerIntrant(Request $request, bool $modif): array
    {
        $regles = [
            'nomIntrant' => ['required', 'string', 'max:150'],
            'type'       => ['required', Rule::in($this->types())],
            'unite'      => ['required', Rule::in($this->unites())],
        ];
        if ($modif) {
            $regles['intrant_id'] = ['required', 'integer'];
        }

        $data = $request->validateWithBag('intrant', $regles);
        $data['nomIntrant'] = $this->nettoyer($data['nomIntrant']);

        $this->refuserDoublon(
            Intrant::all(), 'nom', 'idIntrant',
            $data['nomIntrant'], $modif ? (int) $data['intrant_id'] : null,
            'intrant', 'nomIntrant', "L'intrant"
        );

        return $data;
    }

    /** Refuse un nom déjà existant, sans tenir compte des accents, majuscules ni ponctuation. */
    private function refuserDoublon($existants, string $colNom, string $colId, string $nom,
                                    ?int $ignorerId, string $bag, string $champ, string $libelle): void
    {
        $cle = $this->cle($nom);

        $trouve = $existants->first(function ($e) use ($colNom, $colId, $cle, $ignorerId) {
            return (int) $e->{$colId} !== (int) $ignorerId
                && $this->cle($e->{$colNom}) === $cle;
        });

        if ($trouve) {
            throw ValidationException::withMessages([
                $champ => "{$libelle} « {$trouve->{$colNom}} » existe déjà dans le référentiel.",
            ])->errorBag($bag);
        }
    }

    private function cle(?string $texte): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', ' ', Str::lower(Str::ascii((string) $texte))));
    }

    private function nettoyer(string $texte): string
    {
        return Str::ucfirst(trim(preg_replace('/\s+/u', ' ', $texte)));
    }

    private function types(): array
    {
        return Intrant::whereNotNull('type')->distinct()->pluck('type')
            ->push('autre')->unique()->sort()->values()->all();
    }

    private function unites(): array
    {
        return Intrant::whereNotNull('unite')->distinct()->pluck('unite')
            ->merge(['kg', 'L'])->unique()->sort()->values()->all();
    }

    private function retour(string $onglet, string $message)
    {
        return redirect()
            ->route('admin.referentiel.index', ['onglet' => $onglet])
            ->with('success', $message);
    }
}