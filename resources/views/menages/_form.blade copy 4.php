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

    {{-- Carte d'identité : un clic ouvre la caméra et lance le scan automatique (Nom & Prénom) --}}
    <div class="rounded-lg border border-emerald-200 bg-emerald-50/50 p-4 space-y-3">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-gray-800">
                    Scan carte d'identité du chef de ménage (éléments extraits : Nom et Prénom)
                </p>
                <p id="statut_scan_carte_chef" class="text-xs text-gray-500">
                    Cliquez sur "Scanner la carte" : la caméra s'ouvre et la lecture est automatique dès que la carte est dans le cadre. Vous pouvez aussi choisir une image. La saisie manuelle reste possible à tout moment.
                </p>
                <p id="apercu_texte_ocr_chef" class="mt-1 hidden rounded bg-white/80 p-2 font-mono text-[11px] text-gray-600 border border-gray-200"></p>
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
        const INTERVALLE_SCAN_AUTO_MS = 600;
        const LECTURES_STABLES_REQUISES = 2;

        // Seuils de qualité de l'image avant de lancer l'OCR
        const LUMINOSITE_MIN = 35;   // moyenne des gris (0-255) en dessous = trop sombre / caméra noire
        const PLAGE_CONTRASTE_MIN = 25; // écart entre pixels sombres et clairs en dessous = image uniforme

        const COULEUR_ATTENTE = 'rgba(52, 211, 153, 0.9)';    // vert clair : au repos
        const COULEUR_RECHERCHE = 'rgba(251, 191, 36, 0.95)'; // orange : recherche de la carte
        const COULEUR_DETECTE = 'rgba(16, 185, 129, 1)';      // vert : carte lisible

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
        const nomInput = document.getElementById('nomChef');
        const prenomInput = document.getElementById('prenomChef');
        const sexeInputs = document.querySelectorAll('input[name="sexeChef"]');

        let streamActif = null;
        let enCours = false;
        let derniereImageScannee = null;

        // Qualité de la dernière image préparée
        let qualiteImage = { luminosite: 0, plage: 0, exploitable: false, raison: '' };

        // Scan automatique
        let worker = null;
        let workerPromise = null;
        let timerAuto = null;
        let scanAutoActif = false;
        let derniereSignature = '';
        let lecturesStables = 0;

        const MOTS_STOP = new Set([
            'NOM', 'NON', 'PRENOM', 'PRENOMS', 'PRENOME', 'PRENOMES',
            'PERE', 'MERE', 'SEXE', 'DATE', 'JATE', 'NAISSANCE',
            'LIEU', 'PROFESSION', 'ADRESSE', 'NATIONALITE',
            'DELIVRANCE', 'EXPIRATION', 'VALIDITE', 'NUMERO',
            'PIECE', 'NPI', 'ETUDIANT', 'ETUDIANTE'
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
                    imageDataUrl: imageAEnregistrer
                };

                try {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
                } catch (quotaErr) {
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
                        nom: payload.nom,
                        prenom: payload.prenom,
                        sexe: payload.sexe,
                        imageDataUrl: ''
                    }));
                }
            } catch (e) {
                console.warn('Sauvegarde sessionStorage indisponible:', e);
            }
        }

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

        function estValeurValide(val) {
            if (!val || val.length < 3) return false;
            if (!/^[A-Z\-']+$/.test(val)) return false;
            if (MOTS_STOP.has(val)) return false;
            return true;
        }

        function extraireDepuisLibelle(textePlat, regexLibelle) {
            const match = textePlat.match(regexLibelle);
            if (!match || !match[1]) return '';

            const tokens = match[1].split(/\s+/);
            const gardes = [];

            for (const t of tokens) {
                const propre = nettoyerToken(t);
                if (MOTS_STOP.has(propre)) break;
                if (estValeurValide(propre)) {
                    gardes.push(propre);
                }
                if (gardes.length >= 2) break;
            }

            return gardes.join(' ');
        }

        // Vérifie si la lecture semble suffisamment propre avant de récupérer les infos
        function lectureEstFiable(nom, prenom, texteBrut) {
            if (!nom || !prenom) return false;
            if (nom.length < 3 || prenom.length < 3) return false;

            const propreTexte = (texteBrut || '').replace(/\s+/g, ' ').trim();
            if (propreTexte.length < 8) return false;

            // Trop de caractères spéciaux bizarres indique un scan bruité
            const symbolesBizarres = (propreTexte.match(/[=><{}\[\]|\\~^`]/g) || []).length;
            if (symbolesBizarres > 3) return false;

            return true;
        }

        function extraireNomPrenom(texteBrut) {
            const textePlat = normaliserSansAccents((texteBrut || '').replace(/\r?\n/g, ' '));

            let nom = extraireDepuisLibelle(
                textePlat,
                /\b(?:NOM|NON)\b[\s.:;\-]*([A-ZÀ-Ÿ\s\-']+?)(?=\s+\b(?:PRENOME?S?|PERE|MERE|SEXE|DATE|JATE|LIEU|PROFESSION|ADRESSE)\b|$)/i
            );

            let prenom = extraireDepuisLibelle(
                textePlat,
                /\bPRENOME?S?\b[\s.:;\-]*([A-ZÀ-Ÿ\s\-']+?)(?=\s+\b(?:PERE|MERE|SEXE|DATE|JATE|LIEU|PROFESSION|ADRESSE)\b|$)/i
            );

            if (!nom || !prenom) {
                const pointDepart = textePlat.search(/\b(?:NOM|NON|PRENOME?S?)\b/i);
                const zoneRecherche = pointDepart >= 0 ? textePlat.slice(pointDepart) : textePlat;

                const candidats = zoneRecherche
                    .split(/[\s\/|:;,]+/)
                    .map(nettoyerToken)
                    .filter(m => estValeurValide(m));

                if (!nom) nom = candidats[0] ?? '';
                if (!prenom) prenom = candidats.find(c => c !== nom) ?? '';
            }

            return { nom, prenom };
        }

        /*
         * Prépare l'image pour l'OCR :
         *  - redimensionne entre LARGEUR_CIBLE_MIN et LARGEUR_CIBLE_MAX
         *  - passe en niveaux de gris
         *  - normalise le contraste selon l'histogramme réel de l'image
         *    (étirement entre le 2e et le 98e percentile), ce qui évite
         *    les images toutes noires sur une photo un peu sombre
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

            // 3) Étirement du contraste (adaptatif)
            const etendue = Math.max(plage, 1);
            for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                let v = (gris[p] - bas) / etendue;
                v = v < 0 ? 0 : (v > 1 ? 1 : v);
                const ajuste = Math.round(v * 255);
                data[i] = ajuste;
                data[i + 1] = ajuste;
                data[i + 2] = ajuste;
                data[i + 3] = 255;
            }

            ctx.putImageData(imgData, 0, 0);
            return canvasEl.toDataURL('image/jpeg', 0.9);
        }

        // Moteur OCR créé une seule fois puis réutilisé
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
            derniereSignature = '';
            lecturesStables = 0;
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

        function remplirChamps(nom, prenom) {
            if (nomInput) {
                nomInput.value = nom;
                nomInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            if (prenomInput) {
                prenomInput.value = prenom;
                prenomInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        // Boucle de scan automatique : la caméra lit en continu jusqu'à obtenir une carte lisible
        async function boucleScanAuto() {
            if (!scanAutoActif) return;

            if (!enCours && videoEl && videoEl.readyState >= 2 && videoEl.videoWidth) {
                enCours = true;
                try {
                    const image = capturerCadreVideo();
                    if (image) {
                        if (!qualiteImage.exploitable) {
                            // Image noire ou uniforme : inutile de lancer l'OCR
                            derniereSignature = '';
                            lecturesStables = 0;
                            setCadre(COULEUR_RECHERCHE, true);
                            setStatut(qualiteImage.raison);
                        } else {
                            const w = await obtenirWorker();
                            const { data } = await w.recognize(canvasEl);
                            const texte = (data?.text || '').trim();
                            const { nom, prenom } = extraireNomPrenom(texte);

                            if (scanAutoActif) {
                                if (lectureEstFiable(nom, prenom, texte)) {
                                    const signature = nom + '|' + prenom;

                                    if (signature === derniereSignature) {
                                        lecturesStables++;
                                    } else {
                                        derniereSignature = signature;
                                        lecturesStables = 1;
                                    }

                                    setCadre(COULEUR_DETECTE, false);

                                    // Carte stable et lisible : capture automatique
                                    if (lecturesStables >= LECTURES_STABLES_REQUISES) {
                                        derniereImageScannee = image;

                                        if (apercuImgEl) {
                                            apercuImgEl.src = image;
                                            apercuImgEl.classList.remove('hidden');
                                        }
                                        if (apercuTexteEl && texte) {
                                            apercuTexteEl.textContent = 'Texte lu : ' + texte.replace(/\s+/g, ' ');
                                            apercuTexteEl.classList.remove('hidden');
                                        }

                                        arreterCamera();
                                        remplirChamps(nom, prenom);
                                        sauvegarderContexteChef(image);
                                        setStatut('Carte capturée automatiquement : vérifiez le nom et le prénom.');
                                        enCours = false;
                                        return;
                                    }

                                    setStatut('Carte détectée, ne bougez pas...');
                                } else {
                                    derniereSignature = '';
                                    lecturesStables = 0;
                                    setCadre(COULEUR_RECHERCHE, true);
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
            derniereSignature = '';
            lecturesStables = 0;
            scanAutoActif = true;
            afficherLigneScan(true);
            setCadre(COULEUR_RECHERCHE, true);
            setStatut('Caméra lancée : placez la carte dans le cadre, la capture est automatique.');
            boucleScanAuto();
        }

        // Scan unique (capture manuelle ou image sélectionnée)
        async function analyserSource(source) {
            if (!source || enCours) return;
            enCours = true;
            derniereImageScannee = source;

            if (apercuImgEl) {
                apercuImgEl.src = source;
                apercuImgEl.classList.remove('hidden');
            }

            if (apercuTexteEl) {
                apercuTexteEl.classList.add('hidden');
                apercuTexteEl.textContent = '';
            }

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

                const texteBrut = (resultat?.data?.text || '').trim();

                if (apercuTexteEl && texteBrut) {
                    apercuTexteEl.textContent = 'Texte lu : ' + texteBrut.replace(/\s+/g, ' ');
                    apercuTexteEl.classList.remove('hidden');
                }

                const { nom, prenom } = extraireNomPrenom(texteBrut);
                const fiable = lectureEstFiable(nom, prenom, texteBrut);

                sauvegarderContexteChef(source);

                if (fiable) {
                    remplirChamps(nom, prenom);
                    setStatut('Carte bien scannée : informations récupérées. Vous pouvez vérifier ou modifier manuellement.');
                } else {
                    setStatut('Scan incomplet ou peu lisible : aucune donnée douteuse récupérée. Réessayez ou saisissez manuellement.');
                }
            } catch (error) {
                console.error('Erreur scan OCR:', error);
                sauvegarderContexteChef(source);
                setStatut('Échec du scan. Vous pouvez saisir manuellement.');
            } finally {
                enCours = false;
            }
        }

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