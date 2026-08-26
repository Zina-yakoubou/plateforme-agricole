@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">

            <a
                href="{{ route('campagnes.show', $campagne) }}"
                class="hover:text-blue-600"
            >
                Campagne
            </a>

            <span>/</span>

            <span>Planification</span>

        </div>

        <h1 class="text-2xl font-bold text-slate-800">
            Planifier la campagne
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Définissez le calendrier commun des activités de la campagne.
        </p>
    </div>


    {{-- =========================================================
         INFORMATIONS CAMPAGNE
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-semibold text-slate-800">
            {{ $campagne->libelle }}
        </h2>

        <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-600">

            <span>
                Code :
                <strong>
                    {{ $campagne->codeCampagne }}
                </strong>
            </span>

            <span>
                Portée :
                <strong>
                    {{ ucfirst($campagne->portee) }}
                </strong>
            </span>

        </div>

    </div>


    {{-- =========================================================
         FORMULAIRE
    ========================================================== --}}
    <form
        method="POST"
        action="{{ route('campagnes.planification.store', $campagne) }}"
        x-data="planificationForm()"
        @submit="validerFormulaire($event)"
        class="space-y-6"
    >

        @csrf


        {{-- =====================================================
             ERREURS LARAVEL
        ====================================================== --}}
        @if ($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 p-5">

                <div class="font-semibold text-red-800">
                    Impossible d'enregistrer la planification.
                </div>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             OBSERVATIONS
        ====================================================== --}}
        {{-- <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <label
                for="observations"
                class="block text-sm font-medium text-slate-700"
            >
                Observations
            </label>

            <textarea
                id="observations"
                name="observations"
                rows="3"
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="Observations générales sur le calendrier..."
            >{{ old('observations') }}</textarea>

        </div> --}}


        {{-- =====================================================
             ACTIVITÉS
        ====================================================== --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-200 p-6">

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Calendrier des activités
                    </h2>

                    

                </div>


                <button
                    type="button"
                    @click="ajouterActivite()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    + Ajouter une activité
                </button>

            </div>


            <div class="space-y-4 p-6">

                <template
                    x-for="(activite, index) in activites"
                    :key="activite.id"
                >

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

                        {{-- =================================================
                             EN-TÊTE ACTIVITÉ
                        ================================================== --}}
                        <div class="mb-4 flex items-center justify-between">

                            <div class="font-semibold text-slate-700">

                                Activité
                                <span x-text="index + 1"></span>

                            </div>


                            <button
                                type="button"
                                @click="supprimerActivite(index)"
                                x-show="activites.length > 1"
                                class="text-sm font-medium text-red-600 hover:text-red-800"
                            >
                                Supprimer
                            </button>

                        </div>


                        <div class="grid gap-4 md:grid-cols-2">

                            {{-- =================================================
                                 LIBELLÉ
                            ================================================== --}}
                            <div class="md:col-span-2">

                                <label class="block text-sm font-medium text-slate-700">

                                    Activité
                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="text"
                                    :name="`activites[${index}][libelle]`"
                                    x-model="activite.libelle"
                                    required
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Ex. Identification des producteurs"
                                >

                            </div>


                            {{-- =================================================
                                 DESCRIPTION
                            ================================================== --}}
                            {{-- <div class="md:col-span-2">

                                <label class="block text-sm font-medium text-slate-700">
                                    Description
                                </label>

                                <textarea
                                    :name="`activites[${index}][description]`"
                                    x-model="activite.description"
                                    rows="2"
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Description de l'activité..."
                                ></textarea>

                            </div> --}}


                            {{-- =================================================
                                 DATE DÉBUT
                            ================================================== --}}
                            <div>

                                <label class="block text-sm font-medium text-slate-700">

                                    Date de début
                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="datetime-local"
                                    :name="`activites[${index}][dateDebut]`"
                                    x-model="activite.dateDebut"
                                    :min="dateDebutMinimum(index)"
                                    @change="controlerDates(index)"
                                    required
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                >

                                {{-- Message d'erreur --}}
                                <p
                                    x-show="activite.erreurDebut"
                                    x-text="activite.erreurDebut"
                                    class="mt-1 text-sm text-red-600"
                                ></p>

                            </div>


                            {{-- =================================================
                                 DATE FIN
                            ================================================== --}}
                            <div>

                                <label class="block text-sm font-medium text-slate-700">

                                    Date de fin
                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="datetime-local"
                                    :name="`activites[${index}][dateFin]`"
                                    x-model="activite.dateFin"
                                    :min="dateFinMinimum(index)"
                                    @change="controlerDates(index)"
                                    required
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                >

                                {{-- Message d'erreur --}}
                                <p
                                    x-show="activite.erreurFin"
                                    x-text="activite.erreurFin"
                                    class="mt-1 text-sm text-red-600"
                                ></p>

                            </div>

                        </div>

                    </div>

                </template>

            </div>

        </div>


        {{-- =========================================================
             ACTIONS
        ========================================================== --}}
        <div class="flex items-center justify-between">

            <a
                href="{{ route('campagnes.show', $campagne) }}"
                class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Annuler
            </a>


            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700"
            >
                Enregistrer la planification
            </button>

        </div>

    </form>

</div>


{{-- =============================================================
     ALPINE JS
============================================================= --}}
<script>

function planificationForm() {

    return {

        /*
        |--------------------------------------------------------------------------
        | ACTIVITÉS
        |--------------------------------------------------------------------------
        */

        activites: [
            {
                id: Date.now(),
                libelle: '',
                description: '',
                dateDebut: '',
                dateFin: '',
                erreurDebut: '',
                erreurFin: ''
            }
        ],


        /*
        |--------------------------------------------------------------------------
        | DATE / HEURE ACTUELLE
        |--------------------------------------------------------------------------
        */

        maintenant() {

            const maintenant = new Date();

            const annee = maintenant.getFullYear();

            const mois = String(
                maintenant.getMonth() + 1
            ).padStart(2, '0');

            const jour = String(
                maintenant.getDate()
            ).padStart(2, '0');

            const heures = String(
                maintenant.getHours()
            ).padStart(2, '0');

            const minutes = String(
                maintenant.getMinutes()
            ).padStart(2, '0');

            return `${annee}-${mois}-${jour}T${heures}:${minutes}`;
        },


        /*
        |--------------------------------------------------------------------------
        | DATE DU JOUR À MINUIT
        |--------------------------------------------------------------------------
        */

        aujourdHui() {

            const maintenant = new Date();

            const annee = maintenant.getFullYear();

            const mois = String(
                maintenant.getMonth() + 1
            ).padStart(2, '0');

            const jour = String(
                maintenant.getDate()
            ).padStart(2, '0');

            return `${annee}-${mois}-${jour}T00:00`;
        },


        /*
        |--------------------------------------------------------------------------
        | DATE MINIMUM POUR LE DÉBUT
        |--------------------------------------------------------------------------
        |
        | Activité 1 :
        |   début >= aujourd'hui
        |
        | Activité suivante :
        |   début >= lendemain de la fin précédente
        |
        */

        dateDebutMinimum(index) {

            if (index === 0) {

                return this.maintenant();

            }

            const precedente = this.activites[index - 1];

            if (!precedente.dateFin) {

                return this.maintenant();

            }

            const date = new Date(
                precedente.dateFin
            );

            /*
            | On passe au jour suivant.
            */

            date.setDate(
                date.getDate() + 1
            );

            const annee = date.getFullYear();

            const mois = String(
                date.getMonth() + 1
            ).padStart(2, '0');

            const jour = String(
                date.getDate()
            ).padStart(2, '0');

            return `${annee}-${mois}-${jour}T00:00`;
        },


        /*
        |--------------------------------------------------------------------------
        | DATE MINIMUM POUR LA FIN
        |--------------------------------------------------------------------------
        |
        | La fin doit être au minimum :
        |
        | - le début de la même activité
        | - ou maintenant si le début n'est pas encore défini
        |
        */

        dateFinMinimum(index) {

            const activite = this.activites[index];

            if (activite.dateDebut) {

                return activite.dateDebut;

            }

            return this.maintenant();
        },


        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE DES DATES D'UNE ACTIVITÉ
        |--------------------------------------------------------------------------
        */

        controlerDates(index) {

            const activite = this.activites[index];

            activite.erreurDebut = '';
            activite.erreurFin = '';


            /*
            |--------------------------------------------------------------------------
            | DATE DE DÉBUT
            |--------------------------------------------------------------------------
            */

            if (!activite.dateDebut) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTIVITÉ 1
            |--------------------------------------------------------------------------
            |
            | Elle ne doit pas commencer avant maintenant.
            |
            */

            if (index === 0) {

                const maintenant = new Date();

                const debut = new Date(
                    activite.dateDebut
                );

                if (debut < maintenant) {

                    activite.erreurDebut =
                        "La première activité ne peut pas commencer dans le passé.";

                    return;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | ACTIVITÉS SUIVANTES
            |--------------------------------------------------------------------------
            |
            | Exemple :
            |
            | Activité 1 finit le 24
            | Activité 2 doit commencer le 25 minimum.
            |
            */

            if (index > 0) {

                const precedente =
                    this.activites[index - 1];

                if (precedente.dateFin) {

                    const debut =
                        new Date(activite.dateDebut);

                    const finPrecedente =
                        new Date(precedente.dateFin);

                    /*
                    | On remet la fin précédente à la fin
                    | de sa journée.
                    */

                    finPrecedente.setHours(
                        23,
                        59,
                        59,
                        999
                    );

                    if (debut <= finPrecedente) {

                        activite.erreurDebut =
                            "Cette activité doit commencer le jour suivant la fin de l'activité précédente.";

                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DATE DE FIN
            |--------------------------------------------------------------------------
            */

            if (activite.dateFin) {

                const debut =
                    new Date(activite.dateDebut);

                const fin =
                    new Date(activite.dateFin);

                if (fin < debut) {

                    activite.erreurFin =
                        "La date de fin doit être postérieure ou égale à la date de début.";

                }
            }

        },


        /*
        |--------------------------------------------------------------------------
        | CONTRÔLE GLOBAL AVANT SOUMISSION
        |--------------------------------------------------------------------------
        */

        validerFormulaire(event) {

            let valide = true;


            this.activites.forEach(
                (activite, index) => {

                    this.controlerDates(index);

                    if (
                        activite.erreurDebut ||
                        activite.erreurFin
                    ) {

                        valide = false;

                    }

                }
            );


            if (!valide) {

                event.preventDefault();

                /*
                | On remonte vers la première erreur.
                */

                this.$nextTick(() => {

                    const erreur =
                        document.querySelector(
                            '.text-red-600'
                        );

                    if (erreur) {

                        erreur.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                    }

                });

                return false;
            }

            return true;
        },


        /*
        |--------------------------------------------------------------------------
        | AJOUTER UNE ACTIVITÉ
        |--------------------------------------------------------------------------
        */

        ajouterActivite() {

            this.activites.push({

                id: Date.now() + Math.random(),

                libelle: '',

                description: '',

                dateDebut: '',

                dateFin: '',

                erreurDebut: '',

                erreurFin: ''

            });

        },


        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER UNE ACTIVITÉ
        |--------------------------------------------------------------------------
        */

        supprimerActivite(index) {

            if (this.activites.length > 1) {

                this.activites.splice(
                    index,
                    1
                );

            }

        }

    }

}

</script>

@endsection