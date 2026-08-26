<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ReferenceGeneratorService
{
    /**
     * Génère le prochain numéro d'une séquence.
     *
     * Exemple maison :
     * KPK-M-00001
     * KPK-M-00002
     *
     * Exemple ménage :
     * KPK-M-00001-MEN-01
     */
    public function generate(
        string $type,
        string $parentType,
        int|string $parentId,
        string $codeParent,
        string $prefix,
        int $padding = 5
    ): string {
        return DB::transaction(function () use (
            $type,
            $parentType,
            $parentId,
            $codeParent,
            $prefix,
            $padding
        ) {
            $sequence = DB::table('reference_sequences')
                ->where('type', $type)
                ->where('parent_type', $parentType)
                ->where('parent_id', $parentId)
                ->lockForUpdate()
                ->first();

            if ($sequence) {
                $nextNumber = $sequence->last_number + 1;

                DB::table('reference_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_number' => $nextNumber,
                        'updated_at' => now(),
                    ]);
            } else {
                $nextNumber = 1;

                DB::table('reference_sequences')->insert([
                    'type' => $type,
                    'parent_type' => $parentType,
                    'parent_id' => $parentId,
                    'last_number' => $nextNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return sprintf(
                '%s-%s-%0' . $padding . 'd',
                strtoupper($codeParent),
                strtoupper($prefix),
                $nextNumber
            );
        });
    }
}

