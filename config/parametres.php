<?php

return [
    'groupes' => [

        'general' => [
            'label'       => 'Général',
            'description' => "Identité de l'application.",
            'champs' => [
                'nom_application' => [
                    'label' => "Nom de l'application", 'type' => 'text',
                    'defaut' => 'SIRA-Mô', 'max' => 60,
                ],
                'organisme' => [
                    'label' => "Nom de l'organisme", 'type' => 'text',
                    'defaut' => 'Préfecture de Mô', 'max' => 100,
                ],
            ],
        ],

        'recensement' => [
            'label'       => 'Règles du recensement',
            'description' => 'Valeurs utilisées par les agents.',
            'champs' => [
                'delai_retard_jours' => [
                    'label' => 'Délai avant « en retard »', 'type' => 'integer',
                    'defaut' => 7, 'min' => 1, 'max' => 90, 'unite' => 'jours',
                    'aide' => 'Un village sans activité depuis ce délai est signalé en retard.',
                ],
            ],
        ],

        'securite' => [
            'label'       => 'Sécurité',
            'description' => 'Sessions de connexion.',
            'champs' => [
                'duree_session_minutes' => [
                    'label' => 'Durée de session', 'type' => 'integer',
                    'defaut' => 120, 'min' => 15, 'max' => 1440, 'unite' => 'minutes',
                    'aide' => "Au-delà de ce délai d'inactivité, l'utilisateur est déconnecté.",
                ],
            ],
        ],
    ],
];