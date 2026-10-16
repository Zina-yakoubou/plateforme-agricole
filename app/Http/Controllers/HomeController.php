<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Canton;
use App\Models\Menage;
use App\Models\Parcelle;

class HomeController extends Controller
{
    public function index()
    {
        // Récupération des données réelles depuis la base de données
        $nombreCantons = Canton::count();
        $nombreMenages = Menage::count();
        $nombreParcelles = Parcelle::count();
        $campagneActive = DB::table('campagne_recensements')->where('est_active', true)->first();

        // On envoie ces variables à la vue 'welcome'
        return view('welcome', compact(
            'nombreCantons',
            'nombreMenages',
            'nombreParcelles',
            'campagneActive'
        ));
    }
}