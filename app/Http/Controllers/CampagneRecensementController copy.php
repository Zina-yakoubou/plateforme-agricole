<?php

namespace App\Http\Controllers;


use App\Models\CampagneRecensement;
use App\Models\User;

use App\Http\Requests\StoreCampagneRecensementRequest;

use Illuminate\Http\Request;



class CampagneRecensementController extends Controller
{


    /**
     * Liste des campagnes
     */
    public function index(Request $request)
    {

        $search = $request->search;


        $campagnes = CampagneRecensement::with('responsable')

            ->when($search, function($query) use ($search){

                $query->where(function($q) use ($search){

                    $q->where('libelle','like',"%{$search}%")
                      ->orWhere('codeRNA','like',"%{$search}%");

                });

            })

            ->orderByDesc('created_at')

            ->paginate(10);



        return view(
            'campagnes.index',
            compact(
                'campagnes',
                'search'
            )
        );

    }





    /**
     * Formulaire création
     */
    public function create()
    {


        $responsables = User::whereHas(
            'role',
            function($query){

                $query->whereIn(
                    'nom',
                    [
                        'Administrateur',
                        'Coordinateur'
                    ]
                );

            }
        )

        ->orderBy('name')

        ->get();



        return view(
            'campagnes.create',
            compact(
                'responsables'
            )
        );

    }





    /**
     * Génération automatique code RNA
     */
    private function genererCodeRNA()
    {

        $annee = now()->format('Y');


        $numero = CampagneRecensement::count()+1;


        return "RNA-{$annee}-{$numero}";

    }






    /**
     * Enregistrement
     */
    public function store(
        StoreCampagneRecensementRequest $request
    )
    {


        $data = $request->validated();



        // Génération automatique

        $data['codeRNA'] = $this->genererCodeRNA();



        /**
         * Une seule campagne active
         */

        if($data['statut'] === 'Active')
        {

            CampagneRecensement::where(
                'statut',
                'Active'
            )

            ->update([
                'statut'=>'Clôturée'
            ]);

        }




        CampagneRecensement::create($data);



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne créée avec succès.'
            );

    }







    /**
     * Affichage détail
     */
    public function show(
        CampagneRecensement $campagne
    )
    {


        $campagne->load(
            'responsable',
            'affectations.user'
        );



        return view(
            'campagnes.show',
            compact(
                'campagne'
            )
        );

    }








    /**
     * Formulaire modification
     */
    public function edit(
        CampagneRecensement $campagne
    )
    {


        $responsables = User::whereHas(
            'role',
            function($query){

                $query->whereIn(
                    'nom',
                    [
                        'Administrateur',
                        'Coordinateur'
                    ]
                );

            }
        )

        ->orderBy('name')

        ->get();



        return view(
            'campagnes.edit',
            compact(
                'campagne',
                'responsables'
            )
        );

    }










    /**
     * Mise à jour
     */
    public function update(
        Request $request,
        CampagneRecensement $campagne
    )
    {


        $data=$request->validated();



        if(
            isset($data['statut'])
            &&
            $data['statut']=='Active'
        )
        {

            CampagneRecensement::where(
                'idCampagne',
                '!=',
                $campagne->idCampagne
            )

            ->where(
                'statut',
                'Active'
            )

            ->update([
                'statut'=>'Clôturée'
            ]);

        }



        $campagne->update($data);



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne modifiée avec succès.'
            );

    }









    /**
     * Activer une campagne
     */
    public function activate(
        CampagneRecensement $campagne
    )
    {


        // fermer l'actuelle campagne active

        CampagneRecensement::where(
            'statut',
            'Active'
        )

        ->where(
            'idCampagne',
            '!=',
            $campagne->idCampagne
        )

        ->update([
            'statut'=>'Clôturée'
        ]);




        // activer celle choisie

        $campagne->update([

            'statut'=>'Active'

        ]);



        return back()

            ->with(
                'success',
                'Campagne activée avec succès.'
            );

    }

        /**
         * Clôturer une campagne
         */
        public function close(
            CampagneRecensement $campagne
        )
        {

            $campagne->update([

                'statut'=>'Clôturée'

            ]);



            return back()
                ->with(
                    'success',
                    'Campagne clôturée avec succès.'
                );

        }




    


    /**
     * Suppression
     */
    public function destroy(
        CampagneRecensement $campagne
    )
    {


        $campagne->delete();



        return redirect()

            ->route('campagnes.index')

            ->with(
                'success',
                'Campagne supprimée avec succès.'
            );

    }


    


}