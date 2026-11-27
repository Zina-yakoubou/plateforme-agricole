{{-- resources/views/menages/_form.blade.php --}}

<style>
    @keyframes ligneScanChef {
        0%   { top: 8%; }
        50%  { top: 88%; }
        100% { top: 8%; }
    }
    #ligne_scan_chef {
        animation: ligneScanChef 1.8s ease-in-out infinite;
    }
</style>

<div class="space-y-6">

    {{-- Carte d'identité : un clic ouvre la caméra et lance le scan automatique (Nom, Prénom, naissance) --}}
    <div class="rounded-lg border border-emerald-200 bg-emerald-50/50 p-4 space-y-3">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-gray-800">
                    Scan carte d'identité du chef de ménage (éléments extraits : Nom, Prénom, date et lieu de naissance)
                </p>
                <p id="statut_scan_carte_chef" class="text-xs text-gray-500">
                    Cliquez sur "Scanner la carte" : la caméra s'ouvre et la lecture est automatique dès que la carte est dans le cadre. Vous pouvez aussi choisir une image. La saisie manuelle reste possible à tout moment.
                </p>
                <p id="apercu_texte_ocr_chef" class="mt-1 hidden rounded bg-white/80 p-2 font-mono text-[11px] text-gray-600 border border-gray-200"></p>

                {{-- Informations conservées pour la saisie de l'exploitant --}}
                <p id="infos_extra_ocr_chef" class="mt-1 hidden rounded border border-emerald-200 bg-white/80 p-2 text-[11px] text-gray-700"></p>

                {{-- Mots lus sur la carte : un clic pour les attribuer au nom, au prénom ou au lieu de naissance --}}
                <div id="suggestions_ocr_chef" class="mt-2 hidden space-y-1">
                    <p class="text-[11px] text-gray-500">
                        Mots lus sur la carte : touchez "Nom", "Prénom" ou "Lieu naiss." pour corriger si besoin.
                    </p>
                    <div id="liste_suggestions_ocr_chef" class="flex flex-wrap gap-1.5"></div>
                </div>

                <img
                    id="apercu_image_ocr_chef"
                    alt="Aperçu préparé pour l'OCR"
                    class="mt-1 hidden max-h-32 rounded border border-gray-200 bg-white object-contain"
                >
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <button
                    type="button"
                    id="btn_ouvrir_camera_chef"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#156c52]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Scanner la carte
                </button>

                <input
                    type="file"
                    id="input_fichier_carte_chef"
                    accept="image/*"
                    class="hidden"
                >
                <button
                    type="button"
                    id="btn_choisir_image_chef"
                    class="inline-flex items-center gap-2 rounded-lg border border-[#006a4f] bg-white px-3.5 py-2 text-xs font-semibold text-[#006a4f] transition hover:bg-emerald-50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Sélectionner image
                </button>
            </div>
        </div>

        {{-- Zone caméra avec cadre de visée --}}
        <div id="zone_camera_chef" class="hidden rounded-lg border border-gray-300 bg-black p-3 space-y-3">
            <div class="relative flex items-center justify-center overflow-hidden rounded bg-black">
                <video id="video_carte_chef" autoplay playsinline muted class="w-full max-h-72 object-contain"></video>

                {{-- Cadre de visée : change de couleur selon l'état du scan --}}
                <div
                    id="cadre_visee_chef"
                    class="pointer-events-none absolute inset-x-8 inset-y-10 rounded border-2 border-dashed"
                    style="border-color: rgba(251, 191, 36, 0.95);"
                >
                    {{-- Ligne de balayage --}}
                    <div
                        id="ligne_scan_chef"
                        class="hidden absolute inset-x-0 h-0.5 bg-emerald-400/90 shadow-[0_0_8px_rgba(52,211,153,0.9)]"
                    ></div>
                </div>
            </div>

            <canvas id="canvas_carte_chef" class="hidden"></canvas>

            <div class="flex flex-wrap items-center justify-end gap-2">
                <button
                    type="button"
                    id="btn_capturer_scanner_chef"
                    class="inline-flex items-center gap-2 rounded-lg border border-emerald-400 bg-white px-3.5 py-2 text-xs font-semibold text-[#006a4f] hover:bg-emerald-50"
                >
                    Capturer maintenant
                </button>
                <button
                    type="button"
                    id="btn_fermer_camera_chef"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-700 px-3.5 py-2 text-xs font-semibold text-white hover:bg-gray-600"
                >
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Nom du chef --}}
        <div>
            <label for="nomChef" class="block text-sm font-medium text-gray-700 mb-1">
                Nom du chef
            </label>

            <input
                type="text"
                id="nomChef"
                name="nomChef"
                value="{{ old('nomChef', $menage->nomChef ?? '') }}"
                class="w-full border-gray-300 rounded-lg text-sm @error('nomChef') border-red-500 @enderror"
            >

            @error('nomChef')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- Prénom du chef --}}
        <div>
            <label for="prenomChef" class="block text-sm font-medium text-gray-700 mb-1">
                Prénom du chef
            </label>

            <input
                type="text"
                id="prenomChef"
                name="prenomChef"
                value="{{ old('prenomChef', $menage->prenomChef ?? '') }}"
                class="w-full border-gray-300 rounded-lg text-sm @error('prenomChef') border-red-500 @enderror"
            >

            @error('prenomChef')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>


    {{-- Sexe du chef (Boutons radio classiques) --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Sexe du chef
        </label>

        <div class="flex items-center space-x-6">
            {{-- Masculin --}}
            <label class="flex items-center cursor-pointer">
                <input
                    type="radio"
                    name="sexeChef"
                    value="M"
                    @checked(old('sexeChef', $menage->sexeChef ?? '') === 'M')
                    class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500"
                >
                <span class="ml-2 text-sm text-gray-700">Masculin</span>
            </label>

            {{-- Féminin --}}
            <label class="flex items-center cursor-pointer">
                <input
                    type="radio"
                    name="sexeChef"
                    value="F"
                    @checked(old('sexeChef', $menage->sexeChef ?? '') === 'F')
                    class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500"
                >
                <span class="ml-2 text-sm text-gray-700">Féminin</span>
            </label>
        </div>

        @error('sexeChef')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>


    {{-- Composition du ménage --}}
    <div>

        <p class="block text-sm font-medium text-gray-700 mb-2">
            Composition du ménage
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

            @foreach([
                'nombreHommes' => 'Hommes',
                'nombreFemmes' => 'Femmes',
                'nombreGarcons' => 'Garçons',
                'nombreFilles' => 'Filles'
            ] as $field => $label)

                <div>

                    <label
                        for="{{ $field }}"
                        class="block text-xs text-gray-500 mb-1"
                    >
                        {{ $label }}
                    </label>

                    <input
                        type="number"
                        id="{{ $field }}"
                        name="{{ $field }}"
                        min="0"
                        value="{{ old($field, $menage->$field ?? 0) }}"
                        class="w-full border-gray-300 rounded-lg text-sm @error($field) border-red-500 @enderror"
                    >

                    @error($field)
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            @endforeach

        </div>

    </div>


    {{-- Possession d'une exploitation --}}
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

        <div class="flex items-center gap-3">

            <input
                type="hidden"
                name="possedeExploitation"
                value="0"
            >

            <input
                type="checkbox"
                id="possedeExploitation"
                name="possedeExploitation"
                value="1"
                @checked(old(
                    'possedeExploitation',
                    $menage->possedeExploitation ?? false
                ))
                class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
            >

            <label
                for="possedeExploitation"
                class="text-sm font-medium text-gray-700 cursor-pointer"
            >
                Ce ménage possède une exploitation agricole
            </label>

        </div>

        @error('possedeExploitation')
            <p class="text-red-600 text-xs mt-2">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- Observations --}}
    <div>

        <label
            for="observations"
            class="block text-sm font-medium text-gray-700 mb-1"
        >
            Observations
        </label>

        <textarea
            id="observations"
            name="observations"
            rows="3"
            class="w-full border-gray-300 rounded-lg text-sm @error('observations') border-red-500 @enderror"
        >{{ old('observations', $menage->observations ?? '') }}</textarea>

        @error('observations')
            <p class="text-red-600 text-xs mt-1">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
    (function () {
        const STORAGE_KEY = 'sira_mo_chef_identite';
        const LARGEUR_CIBLE_MIN = 1000;
        const LARGEUR_CIBLE_MAX = 1600;
        const INTERVALLE_SCAN_AUTO_MS = 500;

        // Lecture : un mot est retenu seulement s'il est assez fiable
        const CONFIANCE_MIN = 50;
        // Nombre de lectures concordantes requis pour valider automatiquement (nom ET prénom)
        const VOTES_REQUIS = 2;
        // Nombre maximum de mots proposés en suggestion
        const SUGGESTIONS_MAX = 8;

        // Seuillage local (adapté aux ombres, reflets et fonds holographiques)
        const BINARISATION_LOCALE = true;
        const FACTEUR_SEUIL_LOCAL = 0.88;

        // Seuils de qualité de l'image avant de lancer l'OCR
        const LUMINOSITE_MIN = 35;
        const PLAGE_CONTRASTE_MIN = 25;

        const COULEUR_ATTENTE = 'rgba(52, 211, 153, 0.9)';
        const COULEUR_RECHERCHE = 'rgba(251, 191, 36, 0.95)';
        const COULEUR_DETECTE = 'rgba(16, 185, 129, 1)';

        const btnOuvrirCamera = document.getElementById('btn_ouvrir_camera_chef');
        const btnChoisirImage = document.getElementById('btn_choisir_image_chef');
        const btnCapturerScanner = document.getElementById('btn_capturer_scanner_chef');
        const btnFermerCamera = document.getElementById('btn_fermer_camera_chef');
        const zoneCamera = document.getElementById('zone_camera_chef');
        const videoEl = document.getElementById('video_carte_chef');
        const canvasEl = document.getElementById('canvas_carte_chef');
        const cadreVisee = document.getElementById('cadre_visee_chef');
        const ligneScan = document.getElementById('ligne_scan_chef');
        const fichierInput = document.getElementById('input_fichier_carte_chef');
        const scanStatus = document.getElementById('statut_scan_carte_chef');
        const apercuTexteEl = document.getElementById('apercu_texte_ocr_chef');
        const apercuImgEl = document.getElementById('apercu_image_ocr_chef');
        const infosExtraEl = document.getElementById('infos_extra_ocr_chef');
        const suggestionsBloc = document.getElementById('suggestions_ocr_chef');
        const suggestionsListe = document.getElementById('liste_suggestions_ocr_chef');
        const nomInput = document.getElementById('nomChef');
        const prenomInput = document.getElementById('prenomChef');
        const sexeInputs = document.querySelectorAll('input[name="sexeChef"]');

        let streamActif = null;
        let enCours = false;
        let derniereImageScannee = null;

        let qualiteImage = { luminosite: 0, plage: 0, exploitable: false, raison: '' };

        // Scan automatique
        let worker = null;
        let workerPromise = null;
        let timerAuto = null;
        let scanAutoActif = false;

        // Votes sur plusieurs lectures
        let votesNom = [];
        let votesPrenom = [];
        let motsVus = new Map();
        let derniereImageFrame = null;

        // Informations complémentaires lues sur la carte (transmises au formulaire exploitant)
        let contexteExtra = { dateNaissance: '', lieuNaissance: '', numeroPiece: '' };
        // Après un nouveau scan, on n'utilise plus les anciennes valeurs enregistrées
        let extraAutoritaire = false;

        const MOTS_STOP = new Set([
            'NOM', 'NON', 'NOMS', 'PRENOM', 'PRENOMS', 'PRENOME', 'PRENOMES',
            'PERE', 'MERE', 'SEXE', 'DATE', 'JATE', 'NAISSANCE',
            'LIEU', 'PROFESSION', 'ADRESSE', 'NATIONALITE',
            'DELIVRANCE', 'EXPIRATION', 'VALIDITE', 'NUMERO',
            'PIECE', 'NPI', 'ETUDIANT', 'ETUDIANTE',
            'REPUBLIQUE', 'TOGOLAISE', 'TOGO', 'CARTE', 'NATIONALE',
            'IDENTITE', 'EMISSION', 'TAILLE', 'SIGNATURE', 'TITULAIRE'
        ]);

        function setStatut(msg) {
            if (scanStatus) scanStatus.textContent = msg;
        }

        function setCadre(couleur, pointille = true) {
            if (!cadreVisee) return;
            cadreVisee.style.borderColor = couleur;
            cadreVisee.style.borderStyle = pointille ? 'dashed' : 'solid';
        }

        function afficherLigneScan(visible) {
            if (!ligneScan) return;
            ligneScan.classList.toggle('hidden', !visible);
        }

        function getSexeSelectionne() {
            const checked = document.querySelector('input[name="sexeChef"]:checked');
            return checked ? checked.value : '';
        }

        // Valeur d'une info complémentaire : la lecture en cours, sinon l'ancienne (tant qu'aucun nouveau scan)
        function valeurExtra(cle, existant) {
            if (contexteExtra[cle]) return contexteExtra[cle];
            return extraAutoritaire ? '' : (existant[cle] || '');
        }

        function formaterDateAffichage(iso) {
            const m = (iso || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
            return m ? `${m[3]}/${m[2]}/${m[1]}` : '';
        }

        // Récapitulatif visible des informations gardées pour l'exploitant
        function afficherInfosExtra() {
            if (!infosExtraEl) return;

            const morceaux = [];
            const date = formaterDateAffichage(contexteExtra.dateNaissance);
            if (date) morceaux.push('Date de naissance : ' + date);
            if (contexteExtra.lieuNaissance) morceaux.push('Lieu de naissance : ' + contexteExtra.lieuNaissance);
            if (contexteExtra.numeroPiece) morceaux.push('N° de la carte : ' + contexteExtra.numeroPiece);

            if (!morceaux.length) {
                infosExtraEl.classList.add('hidden');
                infosExtraEl.textContent = '';
                return;
            }

            infosExtraEl.textContent = 'Gardé pour la saisie de l\'exploitant → ' + morceaux.join(' · ');
            infosExtraEl.classList.remove('hidden');
        }

        function sauvegarderContexteChef(imageUrl = null) {
            try {
                let existant = {};
                const raw = sessionStorage.getItem(STORAGE_KEY);
                if (raw) existant = JSON.parse(raw) || {};

                const imageAEnregistrer = imageUrl !== null
                    ? imageUrl
                    : (derniereImageScannee || existant.imageDataUrl || '');

                const payload = {
                    nom: (nomInput?.value || '').trim(),
                    prenom: (prenomInput?.value || '').trim(),
                    sexe: getSexeSelectionne() || existant.sexe || '',
                    imageDataUrl: imageAEnregistrer,
                    dateNaissance: valeurExtra('dateNaissance', existant),
                    lieuNaissance: valeurExtra('lieuNaissance', existant),
                    numeroPiece: valeurExtra('numeroPiece', existant)
                };

                try {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
                } catch (quotaErr) {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
                        nom: payload.nom,
                        prenom: payload.prenom,
                        sexe: payload.sexe,
                        imageDataUrl: '',
                        dateNaissance: payload.dateNaissance,
                        lieuNaissance: payload.lieuNaissance,
                        numeroPiece: payload.numeroPiece
                    }));
                }
            } catch (e) {
                console.warn('Sauvegarde sessionStorage indisponible:', e);
            }
        }

        /* ------------------------------------------------------------------
         * Utilitaires de texte
         * ------------------------------------------------------------------ */

        function normaliserSansAccents(str) {
            return (str || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toUpperCase()
                .trim();
        }

        function nettoyerToken(token) {
            return normaliserSansAccents(token)
                .replace(/[^A-Z\-']/g, '')
                .replace(/^[\-']+|[\-']+$/g, '');
        }

        // Un nom plausible : lettres seulement, voyelle présente, pas de suite de 4 consonnes
        function estNomPlausible(val) {
            if (!val || val.length < 3 || val.length > 20) return false;
            if (!/^[A-Z\-']+$/.test(val)) return false;
            if (MOTS_STOP.has(val)) return false;
            if (!/[AEIOUY]/.test(val)) return false;
            if (/[BCDFGHJKLMNPQRSTVWXZ]{4,}/.test(val)) return false;
            return true;
        }

        function distanceEdition(a, b) {
            const m = a.length;
            const n = b.length;
            const d = Array.from({ length: m + 1 }, (_, i) => {
                const ligne = new Array(n + 1).fill(0);
                ligne[0] = i;
                return ligne;
            });
            for (let j = 0; j <= n; j++) d[0][j] = j;

            for (let i = 1; i <= m; i++) {
                for (let j = 1; j <= n; j++) {
                    const cout = a[i - 1] === b[j - 1] ? 0 : 1;
                    d[i][j] = Math.min(
                        d[i - 1][j] + 1,
                        d[i][j - 1] + 1,
                        d[i - 1][j - 1] + cout
                    );
                }
            }
            return d[m][n];
        }

        // Deux mots sont "voisins" s'ils ne diffèrent que d'une lettre (ex. ZINA / ZIMA)
        function sontVoisins(a, b) {
            if (!a || !b) return false;
            if (a === b) return true;
            if (Math.min(a.length, b.length) < 4) return false;
            if (Math.abs(a.length - b.length) > 1) return false;
            return distanceEdition(a, b) <= 1;
        }

        /* ------------------------------------------------------------------
         * Informations complémentaires : date et lieu de naissance, n° de carte
         * ------------------------------------------------------------------ */

        // Date de naissance = la plus ancienne date valide trouvée
        // (les autres dates de la carte sont l'émission et l'expiration)
        function extraireDateNaissance(texteBrut) {
            const anneeMax = new Date().getFullYear() - 15;
            const trouvees = [];

            for (const m of (texteBrut || '').matchAll(/\b(\d{2})\s*[\/.\-]\s*(\d{2})\s*[\/.\-]\s*(\d{4})\b/g)) {
                const j = +m[1], mo = +m[2], a = +m[3];
                if (j >= 1 && j <= 31 && mo >= 1 && mo <= 12 && a >= 1920 && a <= anneeMax) {
                    trouvees.push({ a, iso: `${m[3]}-${m[2]}-${m[1]}` });
                }
            }

            trouvees.sort((x, y) => x.a - y.a);
            return trouvees.length ? trouvees[0].iso : '';
        }

        // Lieu de naissance : mots qui suivent le libellé "LIEU DE NAISSANCE"
        function extraireLieuNaissance(texteBrut) {
            const plat = normaliserSansAccents((texteBrut || '').replace(/\r?\n/g, ' '));
            const m = plat.match(/LIEU\s+DE\s+NAISSANCE\s*[:.\-]?\s*([A-Z\s\-']+?)(?=\s+(?:SEXE|PROFESSION|TAILLE|NATIONALITE|NPI|DATE|PERE|MERE)\b|$)/);
            if (!m) return '';

            const mots = m[1].split(/\s+/).map(nettoyerToken).filter(estNomPlausible);
            return mots.slice(0, 2).join(' ');
        }

        function extraireNumeroPiece(texteBrut) {
            const m = (texteBrut || '').match(/\b[A-Z]\d?[\-\s]?\d{2}[\-\s]\d{3}[\-\s]\d{2}[\-\s]\d{2}[\-\s]\d{2}[\-\s]\d{2}[\-\s]\d{4,6}\b/);
            return m ? m[0].replace(/\s+/g, '') : '';
        }

        // Complète les infos au fil des lectures, sans écraser une valeur déjà trouvée
        function mettreAJourInfosComplementaires(texte) {
            const infos = {
                dateNaissance: extraireDateNaissance(texte),
                lieuNaissance: extraireLieuNaissance(texte),
                numeroPiece: extraireNumeroPiece(texte),
            };

            for (const [cle, valeur] of Object.entries(infos)) {
                if (valeur && !contexteExtra[cle]) contexteExtra[cle] = valeur;
            }

            afficherInfosExtra();
        }

        /* ------------------------------------------------------------------
         * Extraction nom / prénom à partir de la sortie OCR
         * ------------------------------------------------------------------ */

        // Récupère les mots (avec confiance) dans l'ordre de lecture
        function lireMots(data) {
            const mots = [];

            const lignes = Array.isArray(data?.lines) ? data.lines : [];
            if (lignes.length) {
                for (const ligne of lignes) {
                    for (const m of (ligne.words || [])) {
                        mots.push({
                            texte: m.text || '',
                            conf: typeof m.confidence === 'number' ? m.confidence : 100
                        });
                    }
                }
                return mots;
            }

            if (Array.isArray(data?.words) && data.words.length) {
                for (const m of data.words) {
                    mots.push({
                        texte: m.text || '',
                        conf: typeof m.confidence === 'number' ? m.confidence : 100
                    });
                }
                return mots;
            }

            for (const t of (data?.text || '').split(/\s+/)) {
                mots.push({ texte: t, conf: 100 });
            }
            return mots;
        }

        // Ne garde que les mots écrits en majuscules, fiables et plausibles comme noms
        function extraireCandidats(data) {
            const candidats = [];

            for (const mot of lireMots(data)) {
                if (mot.conf < CONFIANCE_MIN) continue;

                const brut = (mot.texte || '').trim();
                if (!brut) continue;

                const sansAccent = brut.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                if (sansAccent !== sansAccent.toUpperCase()) continue;

                const propre = nettoyerToken(brut);
                if (!estNomPlausible(propre)) continue;

                candidats.push(propre);
            }

            return candidats;
        }

        // Nom = premier mot retenu ; prénom = premier mot suivant différent du nom
        function lectureDepuisCandidats(candidats) {
            const nom = candidats[0] || '';
            const prenom = candidats.find(c => !sontVoisins(c, nom)) || '';
            return { nom, prenom };
        }

        /* ------------------------------------------------------------------
         * Votes sur plusieurs lectures (regroupe les variantes proches)
         * ------------------------------------------------------------------ */

        function reinitialiserLecture() {
            votesNom = [];
            votesPrenom = [];
            motsVus = new Map();
            derniereImageFrame = null;
            contexteExtra = { dateNaissance: '', lieuNaissance: '', numeroPiece: '' };
            extraAutoritaire = true;
            afficherInfosExtra();
        }

        function ajouterVote(liste, valeur) {
            if (!valeur) return;

            let entree = liste.find(e =>
                e.variantes.has(valeur) ||
                [...e.variantes.keys()].some(v => sontVoisins(v, valeur))
            );

            if (!entree) {
                entree = { votes: 0, variantes: new Map() };
                liste.push(entree);
            }

            entree.votes++;
            entree.variantes.set(valeur, (entree.variantes.get(valeur) || 0) + 1);
        }

        function meilleurChoix(liste) {
            if (!liste.length) return null;

            const tri = [...liste].sort((a, b) => b.votes - a.votes);
            const gagnant = tri[0];
            const variante = [...gagnant.variantes.entries()].sort((a, b) => b[1] - a[1])[0][0];

            return { valeur: variante, votes: gagnant.votes };
        }

        function enregistrerLecture(candidats) {
            // Mots vus (pour les suggestions cliquables), comptés une fois par lecture
            for (const mot of new Set(candidats)) {
                motsVus.set(mot, (motsVus.get(mot) || 0) + 1);
            }

            const { nom, prenom } = lectureDepuisCandidats(candidats);
            if (nom && prenom) {
                ajouterVote(votesNom, nom);
                ajouterVote(votesPrenom, prenom);
                return true;
            }
            return false;
        }

        /* ------------------------------------------------------------------
         * Suggestions cliquables
         * ------------------------------------------------------------------ */

        function remplirChamp(input, valeur) {
            if (!input) return;
            input.value = valeur;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function remplirChamps(nom, prenom) {
            remplirChamp(nomInput, nom);
            remplirChamp(prenomInput, prenom);
        }

        function creerBoutonSuggestion(libelle, onClick) {
            const bouton = document.createElement('button');
            bouton.type = 'button';
            bouton.textContent = libelle;
            bouton.className = 'rounded bg-emerald-600 px-1.5 py-0.5 text-[10px] font-semibold text-white hover:bg-emerald-700';
            bouton.addEventListener('click', onClick);
            return bouton;
        }

        function afficherSuggestions() {
            if (!suggestionsBloc || !suggestionsListe) return;

            const mots = [...motsVus.entries()]
                .sort((a, b) => b[1] - a[1])
                .slice(0, SUGGESTIONS_MAX)
                .map(([mot]) => mot);

            suggestionsListe.innerHTML = '';

            if (!mots.length) {
                suggestionsBloc.classList.add('hidden');
                return;
            }

            for (const mot of mots) {
                const puce = document.createElement('span');
                puce.className = 'inline-flex items-center gap-1 rounded-full border border-emerald-300 bg-white px-2 py-0.5 text-[11px]';

                const texte = document.createElement('span');
                texte.className = 'font-semibold text-gray-700';
                texte.textContent = mot;

                puce.appendChild(texte);

                puce.appendChild(creerBoutonSuggestion('Nom', () => {
                    arreterCamera();
                    remplirChamp(nomInput, mot);
                    sauvegarderContexteChef();
                    setStatut('Nom corrigé : ' + mot);
                }));

                puce.appendChild(creerBoutonSuggestion('Prénom', () => {
                    arreterCamera();
                    remplirChamp(prenomInput, mot);
                    sauvegarderContexteChef();
                    setStatut('Prénom corrigé : ' + mot);
                }));

                puce.appendChild(creerBoutonSuggestion('Lieu naiss.', () => {
                    arreterCamera();
                    contexteExtra.lieuNaissance = mot;
                    afficherInfosExtra();
                    sauvegarderContexteChef();
                    setStatut('Lieu de naissance : ' + mot);
                }));

                suggestionsListe.appendChild(puce);
            }

            suggestionsBloc.classList.remove('hidden');
        }

        function masquerSuggestions() {
            if (suggestionsListe) suggestionsListe.innerHTML = '';
            suggestionsBloc?.classList.add('hidden');
        }

        /* ------------------------------------------------------------------
         * Préparation de l'image
         * ------------------------------------------------------------------ */

        // Seuillage local : chaque pixel est comparé à la moyenne de son voisinage
        function binarisationLocale(gris, largeur, hauteur) {
            const l1 = largeur + 1;
            const integrale = new Float64Array(l1 * (hauteur + 1));

            for (let y = 0; y < hauteur; y++) {
                let cumulLigne = 0;
                for (let x = 0; x < largeur; x++) {
                    cumulLigne += gris[y * largeur + x];
                    integrale[(y + 1) * l1 + (x + 1)] = integrale[y * l1 + (x + 1)] + cumulLigne;
                }
            }

            const rayon = Math.max(12, Math.round(largeur / 30));
            const sortie = new Uint8ClampedArray(largeur * hauteur);

            for (let y = 0; y < hauteur; y++) {
                const y1 = Math.max(0, y - rayon);
                const y2 = Math.min(hauteur - 1, y + rayon);

                for (let x = 0; x < largeur; x++) {
                    const x1 = Math.max(0, x - rayon);
                    const x2 = Math.min(largeur - 1, x + rayon);

                    const aire = (x2 - x1 + 1) * (y2 - y1 + 1);
                    const somme =
                        integrale[(y2 + 1) * l1 + (x2 + 1)] -
                        integrale[y1 * l1 + (x2 + 1)] -
                        integrale[(y2 + 1) * l1 + x1] +
                        integrale[y1 * l1 + x1];

                    const moyenne = somme / aire;
                    sortie[y * largeur + x] = gris[y * largeur + x] < moyenne * FACTEUR_SEUIL_LOCAL ? 0 : 255;
                }
            }

            return sortie;
        }

        /*
         * Prépare l'image pour l'OCR :
         *  - redimensionne entre LARGEUR_CIBLE_MIN et LARGEUR_CIBLE_MAX
         *  - passe en niveaux de gris et normalise le contraste (histogramme réel)
         *  - applique un seuillage local (texte noir sur fond blanc)
         *  - mesure la qualité (luminosité, plage de contraste) dans qualiteImage
         */
        function ameliorerClarteSurCanvas(sourceImg, sx, sy, sw, sh) {
            if (!canvasEl || sw <= 0 || sh <= 0) return null;

            let cibleW = sw;
            let cibleH = sh;

            if (cibleW < LARGEUR_CIBLE_MIN) {
                const zoom = LARGEUR_CIBLE_MIN / cibleW;
                cibleW = Math.round(cibleW * zoom);
                cibleH = Math.max(1, Math.round(sh * zoom));
            } else if (cibleW > LARGEUR_CIBLE_MAX) {
                const ratio = LARGEUR_CIBLE_MAX / cibleW;
                cibleW = LARGEUR_CIBLE_MAX;
                cibleH = Math.max(1, Math.round(sh * ratio));
            }

            canvasEl.width = cibleW;
            canvasEl.height = cibleH;
            const ctx = canvasEl.getContext('2d', { willReadFrequently: true });
            if (!ctx) return null;

            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(sourceImg, sx, sy, sw, sh, 0, 0, cibleW, cibleH);

            const imgData = ctx.getImageData(0, 0, cibleW, cibleH);
            const data = imgData.data;
            const totalPixels = data.length / 4;

            // 1) Niveaux de gris + histogramme
            const gris = new Uint8ClampedArray(totalPixels);
            const histogramme = new Uint32Array(256);
            let somme = 0;

            for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                const g = Math.round(0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
                gris[p] = g;
                histogramme[g]++;
                somme += g;
            }

            const luminosite = somme / totalPixels;

            const percentile = (fraction) => {
                const cible = totalPixels * fraction;
                let cumul = 0;
                for (let v = 0; v < 256; v++) {
                    cumul += histogramme[v];
                    if (cumul >= cible) return v;
                }
                return 255;
            };

            const bas = percentile(0.02);
            const haut = percentile(0.98);
            const plage = haut - bas;

            // 2) Qualité de l'image
            let raison = '';
            if (luminosite < LUMINOSITE_MIN) {
                raison = 'Image trop sombre ou caméra noire : éclairez la carte et vérifiez que l\'objectif n\'est pas couvert.';
            } else if (plage < PLAGE_CONTRASTE_MIN) {
                raison = 'Image trop uniforme ou floue : rapprochez la carte et assurez-vous qu\'elle est bien éclairée.';
            }

            qualiteImage = {
                luminosite: Math.round(luminosite),
                plage,
                exploitable: raison === '',
                raison
            };

            // 3) Étirement du contraste
            const etendue = Math.max(plage, 1);
            const etire = new Uint8ClampedArray(totalPixels);
            for (let p = 0; p < totalPixels; p++) {
                let v = (gris[p] - bas) / etendue;
                v = v < 0 ? 0 : (v > 1 ? 1 : v);
                etire[p] = Math.round(v * 255);
            }

            // 4) Seuillage local (optionnel) puis écriture dans le canvas
            const finale = (BINARISATION_LOCALE && qualiteImage.exploitable)
                ? binarisationLocale(etire, cibleW, cibleH)
                : etire;

            for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                const v = finale[p];
                data[i] = v;
                data[i + 1] = v;
                data[i + 2] = v;
                data[i + 3] = 255;
            }

            ctx.putImageData(imgData, 0, 0);
            return canvasEl.toDataURL('image/jpeg', 0.9);
        }

        /* ------------------------------------------------------------------
         * Moteur OCR
         * ------------------------------------------------------------------ */

        function obtenirWorker() {
            if (worker) return Promise.resolve(worker);
            if (workerPromise) return workerPromise;

            if (typeof Tesseract === 'undefined' || !Tesseract.createWorker) {
                return Promise.reject(new Error('Bibliothèque Tesseract non chargée'));
            }

            workerPromise = (async () => {
                const w = await Tesseract.createWorker('fra');
                await w.setParameters({
                    tessedit_pageseg_mode: Tesseract.PSM?.SINGLE_BLOCK ?? '6',
                    preserve_interword_spaces: '1',
                });
                worker = w;
                return w;
            })().catch((err) => {
                workerPromise = null;
                throw err;
            });

            return workerPromise;
        }

        function arreterScanAuto() {
            scanAutoActif = false;
            if (timerAuto) {
                clearTimeout(timerAuto);
                timerAuto = null;
            }
            afficherLigneScan(false);
        }

        function arreterCamera() {
            arreterScanAuto();
            if (streamActif) {
                streamActif.getTracks().forEach(track => track.stop());
                streamActif = null;
            }
            if (videoEl) {
                videoEl.srcObject = null;
            }
            setCadre(COULEUR_ATTENTE, true);
            zoneCamera?.classList.add('hidden');
        }

        // Capture la zone cadrée de la vidéo avec prétraitement
        function capturerCadreVideo() {
            if (!videoEl || videoEl.readyState < 2 || !videoEl.videoWidth || !videoEl.videoHeight) return null;

            const vw = videoEl.videoWidth;
            const vh = videoEl.videoHeight;
            const sw = Math.floor(vw * 0.72);
            const sh = Math.floor(vh * 0.45);
            const sx = Math.floor((vw - sw) / 2);
            const sy = Math.floor((vh - sh) * 0.55);

            return ameliorerClarteSurCanvas(videoEl, sx, sy, sw, sh);
        }

        function afficherApercu(image, texte) {
            if (apercuImgEl && image) {
                apercuImgEl.src = image;
                apercuImgEl.classList.remove('hidden');
            }
            if (apercuTexteEl) {
                if (texte) {
                    apercuTexteEl.textContent = 'Texte lu : ' + texte.replace(/\s+/g, ' ');
                    apercuTexteEl.classList.remove('hidden');
                } else {
                    apercuTexteEl.classList.add('hidden');
                    apercuTexteEl.textContent = '';
                }
            }
        }

        /* ------------------------------------------------------------------
         * Scan automatique (la caméra lit en continu et vote)
         * ------------------------------------------------------------------ */

        async function boucleScanAuto() {
            if (!scanAutoActif) return;

            if (!enCours && videoEl && videoEl.readyState >= 2 && videoEl.videoWidth) {
                enCours = true;
                try {
                    const image = capturerCadreVideo();
                    if (image) {
                        if (!qualiteImage.exploitable) {
                            setCadre(COULEUR_RECHERCHE, true);
                            setStatut(qualiteImage.raison);
                        } else {
                            derniereImageFrame = image;

                            const w = await obtenirWorker();
                            const { data } = await w.recognize(canvasEl);
                            const texte = (data?.text || '').trim();

                            if (scanAutoActif) {
                                // Date et lieu de naissance, n° de carte
                                mettreAJourInfosComplementaires(texte);

                                const candidats = extraireCandidats(data);
                                const lectureValide = enregistrerLecture(candidats);
                                afficherSuggestions();

                                const meilleurNom = meilleurChoix(votesNom);
                                const meilleurPrenom = meilleurChoix(votesPrenom);

                                if (lectureValide) {
                                    setCadre(COULEUR_DETECTE, false);
                                } else {
                                    setCadre(COULEUR_RECHERCHE, true);
                                }

                                const pret = meilleurNom && meilleurPrenom &&
                                    meilleurNom.votes >= VOTES_REQUIS &&
                                    meilleurPrenom.votes >= VOTES_REQUIS;

                                if (pret) {
                                    derniereImageScannee = derniereImageFrame;
                                    afficherApercu(derniereImageFrame, texte);

                                    arreterCamera();
                                    remplirChamps(meilleurNom.valeur, meilleurPrenom.valeur);
                                    sauvegarderContexteChef(derniereImageFrame);
                                    setStatut('Carte lue automatiquement : vérifiez les informations (touchez un mot ci-dessous pour corriger).');
                                    enCours = false;
                                    return;
                                }

                                if (lectureValide) {
                                    setStatut('Carte détectée, ne bougez pas... (lecture en cours de confirmation)');
                                } else {
                                    setStatut('Recherche de la carte... centrez le nom et le prénom dans le cadre.');
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Erreur scan automatique:', error);
                    arreterScanAuto();
                    setCadre(COULEUR_ATTENTE, true);
                    setStatut('Erreur de lecture. Utilisez "Capturer maintenant" ou saisissez manuellement.');
                }
                enCours = false;
            }

            if (scanAutoActif) {
                timerAuto = setTimeout(boucleScanAuto, INTERVALLE_SCAN_AUTO_MS);
            }
        }

        function demarrerScanAuto() {
            reinitialiserLecture();
            masquerSuggestions();
            scanAutoActif = true;
            afficherLigneScan(true);
            setCadre(COULEUR_RECHERCHE, true);
            setStatut('Caméra lancée : placez la carte dans le cadre, la capture est automatique.');
            boucleScanAuto();
        }

        /* ------------------------------------------------------------------
         * Scan unique (capture manuelle ou image sélectionnée)
         * ------------------------------------------------------------------ */

        async function analyserSource(source) {
            if (!source || enCours) return;
            enCours = true;
            derniereImageScannee = source;

            reinitialiserLecture();
            masquerSuggestions();
            afficherApercu(source, '');

            try {
                // Image noire ou uniforme : on l'indique au lieu de lancer un OCR inutile
                if (!qualiteImage.exploitable) {
                    sauvegarderContexteChef(source);
                    setStatut(qualiteImage.raison + ' Réessayez ou saisissez manuellement.');
                    return;
                }

                setStatut('Chargement du moteur de lecture...');
                const w = await obtenirWorker();
                setStatut('Scannage en cours...');
                const resultat = await w.recognize(source);
                const data = resultat?.data;
                const texteBrut = (data?.text || '').trim();

                afficherApercu(source, texteBrut);

                // Date et lieu de naissance, n° de carte
                mettreAJourInfosComplementaires(texteBrut);

                const candidats = extraireCandidats(data);
                const { nom, prenom } = lectureDepuisCandidats(candidats);

                for (const mot of new Set(candidats)) {
                    motsVus.set(mot, (motsVus.get(mot) || 0) + 1);
                }
                afficherSuggestions();

                sauvegarderContexteChef(source);

                if (nom && prenom) {
                    remplirChamps(nom, prenom);
                    setStatut('Carte scannée : vérifiez les informations (touchez un mot ci-dessous pour corriger).');
                } else if (candidats.length) {
                    setStatut('Lecture incomplète : touchez un mot ci-dessous pour l\'attribuer au nom, au prénom ou au lieu de naissance.');
                } else {
                    setStatut('Scan peu lisible : aucune donnée récupérée. Réessayez avec la caméra ou saisissez manuellement.');
                }
            } catch (error) {
                console.error('Erreur scan OCR:', error);
                sauvegarderContexteChef(source);
                setStatut('Échec du scan. Vous pouvez saisir manuellement.');
            } finally {
                enCours = false;
            }
        }

        /* ------------------------------------------------------------------
         * Événements
         * ------------------------------------------------------------------ */

        nomInput?.addEventListener('input', () => sauvegarderContexteChef());
        prenomInput?.addEventListener('input', () => sauvegarderContexteChef());
        sexeInputs.forEach(radio => radio.addEventListener('change', () => sauvegarderContexteChef()));
        sauvegarderContexteChef();

        btnChoisirImage?.addEventListener('click', () => fichierInput?.click());

        // Un seul clic : la caméra s'ouvre et le scan automatique démarre tout seul
        btnOuvrirCamera?.addEventListener('click', async () => {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                fichierInput?.click();
                return;
            }

            try {
                arreterCamera();
                setStatut('Ouverture de la caméra...');
                streamActif = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    },
                    audio: false
                });
                videoEl.srcObject = streamActif;
                zoneCamera?.classList.remove('hidden');
                try {
                    await videoEl.play();
                } catch (_) {}

                // Préchauffe le moteur OCR pendant que la caméra démarre
                obtenirWorker().catch(() => {});

                demarrerScanAuto();
            } catch (err) {
                console.error('Erreur caméra:', err);
                setStatut('Caméra inaccessible. Utilisez "Sélectionner image" ou saisissez manuellement.');
            }
        });

        // Capture immédiate (secours)
        btnCapturerScanner?.addEventListener('click', async () => {
            if (!videoEl || !canvasEl || videoEl.readyState < 2 || !videoEl.videoWidth || !videoEl.videoHeight) {
                setStatut('Flux vidéo non prêt. Patientez une seconde ou utilisez "Sélectionner image".');
                return;
            }

            arreterScanAuto();

            // Attend la fin d'une éventuelle lecture en cours
            let tentatives = 0;
            while (enCours && tentatives < 50) {
                await new Promise(r => setTimeout(r, 100));
                tentatives++;
            }

            const imageClaire = capturerCadreVideo();
            arreterCamera();
            if (imageClaire) {
                await analyserSource(imageClaire);
            } else {
                setStatut('Capture impossible. Saisissez manuellement.');
            }
        });

        btnFermerCamera?.addEventListener('click', () => {
            arreterCamera();
            setStatut('Caméra fermée. Vous pouvez scanner à nouveau, choisir une image ou saisir manuellement.');
        });

        fichierInput?.addEventListener('change', (event) => {
            const fichier = event.target.files?.[0];
            if (!fichier) return;

            setStatut('Chargement de l\'image...');
            const url = URL.createObjectURL(fichier);
            const img = new Image();

            img.onload = async () => {
                const imageClaire = ameliorerClarteSurCanvas(img, 0, 0, img.width, img.height);
                URL.revokeObjectURL(url);
                if (imageClaire) {
                    await analyserSource(imageClaire);
                } else {
                    setStatut('Image non exploitable. Saisissez manuellement.');
                }
            };

            img.onerror = () => {
                URL.revokeObjectURL(url);
                setStatut('Format d’image non reconnu. Saisissez manuellement.');
            };

            img.src = url;
            event.target.value = '';
        });

        window.addEventListener('beforeunload', () => {
            arreterCamera();
            if (worker) {
                worker.terminate();
                worker = null;
            }
        });
    })();
</script>