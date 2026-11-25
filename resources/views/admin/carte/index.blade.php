@extends('layouts.app')

@section('title', 'Carte des exploitations')
@section('page-title', 'Carte')
@section('page-subtitle', 'Recherche et carte des exploitations du Togo')

@section('content')
@php
    $couleurs = [
        'brouillon'  => '#94a3b8',
        'en_attente' => '#f59e0b',
        'en_cours'   => '#3b82f6',
        'termine'    => '#22c55e',
        'valide'     => '#047857',
    ];
    $selectClasse = 'rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-text-primary
                     focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700';
    $localises = $diag['maison'] + $diag['village'];
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      onerror="this.onerror=null;this.href='{{ asset('vendor/leaflet/leaflet.css') }}'">

<div class="w-full">

    <div class="mb-5">
        <h1 class="text-[28px] font-semibold leading-tight text-text-primary">Carte des exploitations</h1>
        <p class="mt-1 text-sm text-text-secondary">
            Recherchez un village, un chef de ménage, un agent ou une culture. Les résultats s'affichent
            dans la liste, et sur la carte quand une position est connue.
        </p>
    </div>

    <div id="erreur-carte" class="mb-4 hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"></div>

    {{-- Recherche et filtres --}}
    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-4">

        <div class="flex flex-wrap gap-3">
            <div class="relative min-w-[260px] flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input id="recherche" type="text" autocomplete="off"
                       placeholder="Rechercher un village, un chef de ménage, un agent, une commune, une culture…"
                       class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-10 text-sm
                              focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700">
                <button id="effacer-recherche" type="button"
                        class="absolute right-3 top-1/2 hidden -translate-y-1/2 text-slate-400 hover:text-slate-600"
                        title="Effacer">✕</button>

                <div id="suggestions"
                     class="absolute left-0 right-0 top-full z-[1000] mt-1 hidden max-h-72 overflow-y-auto
                            rounded-lg border border-slate-200 bg-white shadow-lg"></div>
            </div>

            <button id="btn-rechercher" type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-800 px-5 py-2.5 text-sm font-semibold
                           text-white hover:bg-green-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                Rechercher
            </button>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-3">

            <select id="f-culture" class="{{ $selectClasse }}">
                <option value="">Toutes cultures</option>
                @foreach($listeCultures as $id => $nom)
                    <option value="{{ $id }}">{{ $nom }}</option>
                @endforeach
            </select>

            <select id="f-prefecture" class="{{ $selectClasse }}">
                <option value="">Toutes préfectures</option>
                @foreach($prefectures as $p)
                    <option value="{{ $p->idPrefecture }}">{{ $p->nom }}</option>
                @endforeach
            </select>

            <select id="f-campagne" class="{{ $selectClasse }}">
                <option value="">Toutes campagnes</option>
                @foreach($campagnes as $c)
                    <option value="{{ $c->idCampagne }}">{{ $c->libelle }}</option>
                @endforeach
            </select>

            <select id="f-statut" class="{{ $selectClasse }}">
                <option value="">Tous statuts</option>
                @foreach($statuts as $k => $l)
                    <option value="{{ $k }}">{{ $l }}</option>
                @endforeach
            </select>

            <button id="reinitialiser" type="button"
                    class="hidden text-sm font-semibold text-green-700 hover:underline">
                Réinitialiser
            </button>

            <p class="ml-auto text-sm text-text-secondary">
                <span id="compteur" class="font-semibold text-green-800">0</span> résultat(s)
                · <span id="compteur-carte" class="font-semibold text-green-800">0</span> sur la carte
            </p>
        </div>

        {{-- Résumé de la culture sélectionnée --}}
        <div id="resume-culture"
             class="mt-3 hidden rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900"></div>
    </div>

    {{-- Information sur les coordonnées --}}
    @if($diag['total'] === 0)
        <p class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Aucun recensement n'est enregistré pour le moment.
        </p>
    @elseif($localises === 0)
        <p class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Aucun des {{ $diag['total'] }} recensements n'a de coordonnées GPS (ni sur la maison, ni sur le village).
            La recherche fonctionne dans la liste ci-dessous, mais la carte restera vide tant que les coordonnées
            ne sont pas renseignées.
        </p>
    @else
        <p class="mb-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            {{ $diag['total'] }} recensement(s) :
            <strong>{{ $diag['maison'] }}</strong> avec le GPS de la maison,
            <strong>{{ $diag['village'] }}</strong> placés au centre de leur village (position approximative),
            <strong>{{ $diag['aucune'] }}</strong> sans coordonnées.
        </p>
    @endif

    @unless($culturesOk)
        <p class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Le filtre par culture ne peut pas encore agir. Raison : {{ $culturesDiag ?: 'relations introuvables.' }}
        </p>
    @endunless

    {{-- Liste des résultats --}}
    <div class="mb-5 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-text-primary">Résultats de la recherche</h2>
                <p id="sous-titre-liste" class="mt-0.5 text-xs text-text-secondary"></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-y border-slate-200 bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-700">
                        <th class="w-16 px-6 py-3">N°</th>
                        <th class="px-6 py-3">Village</th>
                        <th class="px-6 py-3">Chef de ménage</th>
                        <th class="px-6 py-3">Agent</th>
                        <th class="px-6 py-3">Cultures</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3">Position</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="liste-resultats" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>

        <div id="plus-wrap" class="hidden border-t border-slate-100 px-6 py-4 text-center">
            <button id="btn-plus" type="button" class="text-sm font-semibold text-green-700 hover:underline">
                Afficher plus de résultats
            </button>
        </div>
    </div>

    {{-- Carte --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-slate-100 px-5 py-3 text-xs text-text-secondary">
            @foreach($statuts as $k => $l)
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full" style="background: {{ $couleurs[$k] ?? '#94a3b8' }}"></span>
                    {{ $l }} (<span data-statut="{{ $k }}">0</span>)
                </span>
            @endforeach

            <span class="ml-auto inline-flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full border-2 border-dashed border-slate-500"></span>
                    Position approximative (village)
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full border border-green-700 bg-green-400/30"></span>
                    Zone de culture
                </span>
            </span>
        </div>

        <div class="relative">
            <div id="carte-exploitations" style="height: 560px; z-index: 0;"></div>

            <div id="msg-carte"
                 class="pointer-events-none absolute inset-x-0 top-4 z-[500] mx-auto hidden w-fit max-w-[90%] rounded-lg
                        border border-amber-200 bg-amber-50 px-4 py-2 text-center text-sm text-amber-800 shadow"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    window.L || document.write('<script src="{{ asset('vendor/leaflet/leaflet.js') }}"><\/script>');
</script>
<script>
window.addEventListener('error', function (e) {
    var b = document.getElementById('erreur-carte');
    if (!b) return;
    b.textContent = 'Erreur : ' + e.message;
    b.classList.remove('hidden');
});

document.addEventListener('DOMContentLoaded', function () {

    var points   = @json($points);
    var couleurs = @json($couleurs);

    /* ---------- Utilitaires ---------- */
    function esc(t) { var d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML; }
    function norm(t) { return (t == null ? '' : String(t)).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase(); }
    function nb(n) { return Number(n).toLocaleString('fr-FR', { maximumFractionDigits: 2 }); }

    var carteDispo = (typeof L !== 'undefined');
    var carte = null, groupe = null, zones = null;

    /* ---------- Carte centrée sur le Togo ---------- */
    if (carteDispo) {
        var limitesTogo = L.latLngBounds([[6.0, -0.2], [11.2, 1.9]]);
        carte = L.map('carte-exploitations', {
            minZoom: 7, maxBounds: limitesTogo.pad(0.15), maxBoundsViscosity: 1.0
        });

        var plan = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            { maxZoom: 19, attribution: '&copy; OpenStreetMap' });
        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            { maxZoom: 18, attribution: 'Imagerie &copy; Esri' });

        plan.addTo(carte);
        L.control.layers({ 'Plan': plan, 'Satellite': satellite }, null, { position: 'topright' }).addTo(carte);
        carte.fitBounds(limitesTogo);

        fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries/TGO.geo.json')
            .then(function (r) { return r.json(); })
            .then(function (geo) {
                var geom = (geo.features ? geo.features[0] : geo).geometry;
                var polys = geom.type === 'Polygon' ? [geom.coordinates] : geom.coordinates;
                var anneaux = polys.map(function (p) { return p[0].map(function (c) { return [c[1], c[0]]; }); });
                var monde = [[-60, -180], [-60, 180], [80, 180], [80, -180]];

                L.polygon([monde].concat(anneaux), { stroke: false, fillColor: '#0f172a', fillOpacity: 0.35, interactive: false }).addTo(carte);
                anneaux.forEach(function (a) {
                    L.polygon(a, { color: '#047857', weight: 2, fill: false, interactive: false }).addTo(carte);
                });
            })
            .catch(function () {});

        groupe = L.layerGroup().addTo(carte);
        zones  = L.layerGroup().addTo(carte);
    } else {
        var b = document.getElementById('erreur-carte');
        b.textContent = "La carte n'a pas pu être chargée (connexion internet ?). La recherche fonctionne quand même dans la liste.";
        b.classList.remove('hidden');
    }

    /* ---------- Éléments (un par recensement) ---------- */
    var elements = points.map(function (p) {
        var localise = (p.lat !== null && p.lng !== null);
        var approx   = p.source === 'village';
        var marqueur = null;

        if (carteDispo && localise) {
            marqueur = L.circleMarker([p.lat, p.lng], {
                radius: 7,
                color: approx ? '#475569' : '#ffffff',
                weight: approx ? 2 : 1.5,
                dashArray: approx ? '3 3' : null,
                fillColor: couleurs[p.statut] || '#94a3b8',
                fillOpacity: approx ? 0.6 : 0.95
            });

            var cultures = (p.cultures || []).map(function (c) {
                return esc(c.nom) + ' : ' + nb(c.ha) + ' ha';
            }).join('<br>');

            marqueur.bindPopup(
                '<strong>Village de ' + esc(p.village) + '</strong><br>' +
                (p.commune ? esc(p.commune) + '<br>' : '') +
                (p.chef ? 'Chef de ménage : ' + esc(p.chef) + '<br>' : '') +
                'Agent : ' + esc(p.agent || '—') + '<br>' +
                'Campagne : ' + esc(p.campagne || '—') + '<br>' +
                'Statut : <strong>' + esc(p.statutLb) + '</strong><br>' +
                (approx ? '<em>Position approximative (centre du village)</em><br>' : '') +
                (cultures ? '<hr style="margin:6px 0"><strong>Cultures</strong><br>' + cultures + '<br>' : '') +
                '<a href="' + p.url + '">Consulter le recensement</a>'
            );
        }

        var cultureNoms = (p.cultures || []).map(function (c) { return c.nom; }).join(' ');

        return {
            p: p, localise: localise, marqueur: marqueur,
            texte: norm([p.village, p.commune, p.chef, p.agent, p.campagne, p.numero, cultureNoms].join(' '))
        };
    });

    /* ---------- Références ---------- */
    var champRecherche = document.getElementById('recherche');
    var fCult   = document.getElementById('f-culture');
    var fPref   = document.getElementById('f-prefecture');
    var fCamp   = document.getElementById('f-campagne');
    var fStatut = document.getElementById('f-statut');
    var btnReinit  = document.getElementById('reinitialiser');
    var btnEffacer = document.getElementById('effacer-recherche');
    var btnRech    = document.getElementById('btn-rechercher');
    var resume     = document.getElementById('resume-culture');
    var tbody      = document.getElementById('liste-resultats');
    var plusWrap   = document.getElementById('plus-wrap');
    var btnPlus    = document.getElementById('btn-plus');
    var msgCarte   = document.getElementById('msg-carte');

    var PAS = 50;
    var limite = PAS;
    var resultats = [];

    /* ---------- Liste ---------- */
    function badgeStatut(p) {
        var c = couleurs[p.statut] || '#94a3b8';
        return '<span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">' +
               '<span style="display:inline-block;width:6px;height:6px;border-radius:9999px;background:' + c + '"></span>' +
               esc(p.statutLb) + '</span>';
    }

    function badgePosition(e) {
        if (!e.localise) return '<span class="text-xs text-slate-400">Non localisée</span>';
        if (e.p.source === 'village') return '<span class="text-xs text-slate-600">Village (approx.)</span>';
        return '<span class="text-xs font-medium text-green-700">GPS maison</span>';
    }

    function dessinerListe() {
        var affiches = resultats.slice(0, limite);

        if (!affiches.length) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-14 text-center text-text-secondary">' +
                              'Aucun recensement ne correspond à votre recherche.</td></tr>';
        } else {
            tbody.innerHTML = affiches.map(function (e, i) {
                var p = e.p;
                var noms = (p.cultures || []).map(function (c) { return c.nom; });
                var cultures = noms.length ? esc(noms.join(', ')) : '<span class="text-slate-400">—</span>';

                var btnCarte = e.localise && carteDispo
                    ? '<button type="button" data-voir="' + i + '" title="Voir sur la carte" ' +
                      'class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">' +
                      '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></button>'
                    : '';

                return '<tr class="hover:bg-slate-50/60">' +
                    '<td class="px-6 py-4 text-text-secondary">' + (i + 1) + '</td>' +
                    '<td class="px-6 py-4"><p class="font-semibold text-text-primary">' + esc(p.village || '—') + '</p>' +
                        '<p class="text-xs text-text-secondary">' + esc(p.commune || '') + '</p></td>' +
                    '<td class="px-6 py-4 text-text-primary">' + esc(p.chef || '—') + '</td>' +
                    '<td class="px-6 py-4 text-text-primary">' + esc(p.agent || '—') + '</td>' +
                    '<td class="px-6 py-4 text-text-primary">' + cultures + '</td>' +
                    '<td class="px-6 py-4">' + badgeStatut(p) + '</td>' +
                    '<td class="px-6 py-4">' + badgePosition(e) + '</td>' +
                    '<td class="px-6 py-4"><div class="flex justify-end gap-2">' + btnCarte +
                        '<a href="' + p.url + '" title="Consulter" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">' +
                        '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>' +
                    '</div></td></tr>';
            }).join('');
        }

        document.getElementById('sous-titre-liste').textContent =
            resultats.length ? 'Affichage de ' + affiches.length + ' sur ' + resultats.length + ' résultat(s)' : '';

        plusWrap.classList.toggle('hidden', resultats.length <= limite);
    }

    tbody.addEventListener('click', function (ev) {
        var b = ev.target.closest('[data-voir]');
        if (!b || !carteDispo) return;

        var e = resultats[parseInt(b.getAttribute('data-voir'), 10)];
        if (!e || !e.marqueur) return;

        carte.setView([e.p.lat, e.p.lng], 15);
        e.marqueur.openPopup();
        document.getElementById('carte-exploitations').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    btnPlus.addEventListener('click', function () { limite += PAS; dessinerListe(); });

    /* ---------- Filtrage ---------- */
    function appliquer(cadrer) {
        var q = norm(champRecherche.value.trim());
        var cultureId = fCult.value;
        var visibles = [];
        var parStatut = {};
        var maisonsVues = {};
        var villages = {};
        var totalHa = 0, totalParcelles = 0, totalMaisons = 0;

        resultats = [];
        limite = PAS;

        if (groupe) { groupe.clearLayers(); zones.clearLayers(); }

        elements.forEach(function (e) {
            var p = e.p;

            if (!fCamp.value && maisonsVues[p.maison]) return;

            var ok =
                (!q || e.texte.indexOf(q) !== -1) &&
                (!cultureId   || p.cultureIds.indexOf(cultureId) !== -1) &&
                (!fPref.value || p.prefecture === fPref.value) &&
                (!fCamp.value || p.campagneId === fCamp.value) &&
                (!fStatut.value || p.statut === fStatut.value);

            if (!ok) return;

            maisonsVues[p.maison] = true;
            resultats.push(e);
            parStatut[p.statut] = (parStatut[p.statut] || 0) + 1;

            if (e.localise && groupe) {
                groupe.addLayer(e.marqueur);
                visibles.push([p.lat, p.lng]);
            }

            if (cultureId) {
                var ha = 0, parc = 0;
                p.cultures.forEach(function (c) {
                    if (c.id === cultureId) { ha += c.ha; parc += c.parcelles; }
                });

                totalHa += ha; totalParcelles += parc; totalMaisons++;

                if (e.localise) {
                    var cle = p.village || 'Village inconnu';
                    if (!villages[cle]) villages[cle] = { lat: 0, lng: 0, n: 0, ha: 0 };
                    villages[cle].lat += p.lat; villages[cle].lng += p.lng;
                    villages[cle].n++; villages[cle].ha += ha;
                }
            }
        });

        // Zones de culture : un cercle par village localisé
        var nomsVillages = Object.keys(villages);
        if (zones) {
            nomsVillages.forEach(function (nom) {
                var v = villages[nom];
                var cercle = L.circle([v.lat / v.n, v.lng / v.n], {
                    radius: 1500 + Math.min(v.n, 20) * 80,
                    color: '#047857', weight: 1.5, fillColor: '#22c55e', fillOpacity: 0.18
                });
                cercle.bindTooltip(esc(nom) + ' — ' + v.n + ' maison(s), ' + nb(v.ha) + ' ha', { sticky: true });
                zones.addLayer(cercle);
            });
        }

        // Résumé de la culture
        if (cultureId) {
            var libelle = fCult.options[fCult.selectedIndex].text;
            resume.innerHTML = totalMaisons
                ? '<strong>' + esc(libelle) + '</strong> : ' + totalMaisons + ' maison(s) · ' +
                  totalParcelles + ' parcelle(s) · ' + nb(totalHa) + ' ha'
                : '<strong>' + esc(libelle) + '</strong> : aucune exploitation enregistrée pour cette culture.';
            resume.classList.remove('hidden');
        } else {
            resume.classList.add('hidden');
        }

        // Compteurs
        document.getElementById('compteur').textContent = resultats.length;
        document.getElementById('compteur-carte').textContent = visibles.length;
        document.querySelectorAll('[data-statut]').forEach(function (s) {
            s.textContent = parStatut[s.getAttribute('data-statut')] || 0;
        });

        // Message sur la carte
        if (resultats.length && !visibles.length) {
            msgCarte.textContent = 'Ces résultats n\'ont pas de position GPS : ils apparaissent dans la liste, pas sur la carte.';
            msgCarte.classList.remove('hidden');
        } else if (!resultats.length) {
            msgCarte.textContent = 'Aucun résultat.';
            msgCarte.classList.remove('hidden');
        } else {
            msgCarte.classList.add('hidden');
        }

        var actif = q || cultureId || fPref.value || fCamp.value || fStatut.value;
        btnReinit.classList.toggle('hidden', !actif);
        btnEffacer.classList.toggle('hidden', !q);

        if (cadrer && carte && visibles.length) {
            carte.fitBounds(visibles, { padding: [60, 60], maxZoom: visibles.length === 1 ? 17 : 14 });
        }

        dessinerListe();
    }

    [fCult, fPref, fCamp, fStatut].forEach(function (s) {
        s.addEventListener('change', function () { appliquer(true); });
    });

    btnRech.addEventListener('click', function () { cacherSuggestions(); appliquer(true); });

    btnReinit.addEventListener('click', function () {
        champRecherche.value = '';
        fCult.value = ''; fPref.value = ''; fCamp.value = ''; fStatut.value = '';
        cacherSuggestions();
        appliquer(true);
    });

    btnEffacer.addEventListener('click', function () {
        champRecherche.value = ''; cacherSuggestions(); appliquer(true);
    });

    /* ---------- Suggestions pendant la frappe ---------- */
    var boite = document.getElementById('suggestions');

    function cacherSuggestions() { boite.classList.add('hidden'); boite.innerHTML = ''; }

    function suggestions(q) {
        var vus = {}, liste = [];

        function ajouter(libelle, type) {
            if (!libelle) return;
            var cle = type + '|' + norm(libelle);
            if (vus[cle] || norm(libelle).indexOf(q) === -1) return;
            vus[cle] = true;
            liste.push({ libelle: libelle, type: type });
        }

        elements.forEach(function (e) { ajouter(e.p.village, 'Village'); });
        elements.forEach(function (e) { ajouter(e.p.commune, 'Commune'); });
        elements.forEach(function (e) { (e.p.cultures || []).forEach(function (c) { ajouter(c.nom, 'Culture'); }); });
        elements.forEach(function (e) { ajouter(e.p.chef, 'Chef de ménage'); });
        elements.forEach(function (e) { ajouter(e.p.agent, 'Agent'); });

        return liste.slice(0, 8);
    }

    champRecherche.addEventListener('input', function () {
        var q = norm(champRecherche.value.trim());
        appliquer(false);

        if (q.length < 2) { cacherSuggestions(); return; }

        var liste = suggestions(q);
        if (!liste.length) { cacherSuggestions(); return; }

        boite.innerHTML = liste.map(function (s) {
            return '<button type="button" data-valeur="' + esc(s.libelle).replace(/"/g, '&quot;') + '" ' +
                   'class="flex w-full items-center justify-between px-4 py-2.5 text-left text-sm hover:bg-slate-50">' +
                   '<span>' + esc(s.libelle) + '</span>' +
                   '<span class="text-xs text-slate-400">' + s.type + '</span></button>';
        }).join('');
        boite.classList.remove('hidden');
    });

    boite.addEventListener('click', function (ev) {
        var b = ev.target.closest('button[data-valeur]');
        if (!b) return;
        champRecherche.value = b.getAttribute('data-valeur');
        cacherSuggestions();
        appliquer(true);
    });

    champRecherche.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter') { ev.preventDefault(); cacherSuggestions(); appliquer(true); }
        if (ev.key === 'Escape') { cacherSuggestions(); }
    });

    document.addEventListener('click', function (ev) {
        if (!boite.contains(ev.target) && ev.target !== champRecherche) cacherSuggestions();
    });

    /* ---------- Affichage initial ---------- */
    appliquer(true);
});
</script>
@endsection