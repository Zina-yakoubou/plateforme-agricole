<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    protected $fillable = ['groupe', 'cle', 'valeur'];

    public static function toutes(): array
    {
        try {
            return Cache::rememberForever('parametres.toutes', function () {
                return static::query()->get()
                    ->mapWithKeys(fn ($p) => [$p->groupe . '.' . $p->cle => $p->valeur])
                    ->all();
            });
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Exemple : Parametre::valeur('recensement.delai_retard_jours') */
    public static function valeur(string $cle, mixed $defaut = null): mixed
    {
        [$groupe, $nom] = explode('.', $cle, 2);
        $champ = config("parametres.groupes.{$groupe}.champs.{$nom}", []);

        $valeur = static::toutes()[$cle] ?? null;

        if ($valeur === null || $valeur === '') {
            $valeur = $champ['defaut'] ?? $defaut;
        }

        return ($champ['type'] ?? null) === 'integer' ? (int) $valeur : $valeur;
    }

    public static function vider(): void
    {
        Cache::forget('parametres.toutes');
    }
}