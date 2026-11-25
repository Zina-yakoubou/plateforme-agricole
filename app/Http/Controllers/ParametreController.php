<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParametreController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $groupes = config('parametres.groupes');
        $valeurs = [];

        foreach ($groupes as $g => $groupe) {
            foreach (array_keys($groupe['champs']) as $c) {
                $valeurs["$g.$c"] = Parametre::valeur("$g.$c");
            }
        }

        return view('admin.parametres.index', compact('groupes', 'valeurs'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $groupes = config('parametres.groupes');
        $regles  = [];

        foreach ($groupes as $g => $groupe) {
            foreach ($groupe['champs'] as $c => $champ) {
                $r = ['required'];

                if ($champ['type'] === 'integer') {
                    array_push($r, 'integer', 'min:' . ($champ['min'] ?? 0), 'max:' . ($champ['max'] ?? 100000));
                } else {
                    array_push($r, 'string', 'max:' . ($champ['max'] ?? 150));
                }

                $regles["parametres.$g.$c"] = $r;
            }
        }

        $data = $request->validate($regles);

        foreach ($groupes as $g => $groupe) {
            foreach (array_keys($groupe['champs']) as $c) {
                Parametre::updateOrCreate(
                    ['groupe' => $g, 'cle' => $c],
                    ['valeur' => (string) $data['parametres'][$g][$c]]
                );
            }
        }

        Parametre::vider();

        return redirect()->route('admin.parametres.index')
            ->with('success', 'Paramètres enregistrés.');
    }
}