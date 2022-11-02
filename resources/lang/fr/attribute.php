<?php

return [
    'confirm'       => [
        'body' => 'Êtes-vous certain de vouloir supprimer l\'attribut":name"?',
    ],
    'deleted'       => 'L\'attribut ":name" a été supprimé.',
    'index'         => [
        'required'    => 'Obligatoire',
        'title'       => 'Liste des attributs',
        'description' => [
            'system' => 'Système',
        ],
        'table'       => [
            'header' => [
                'name'    => 'Nom',
                'type'    => 'Type',
                'natures' => 'Natures',
                'actions' => 'Actions',
            ],
            'search' => 'Rechercher',
            'show'   => 'Voir',
            'edit'   => 'Modifier',
            'delete' => 'Supprimer',
        ],
        'create'      => 'Créer un nouvel attribut',
    ],
    'edit'          => [
        'default_choice' => 'Choisir un attribut à ajouter',
        'description' => 'Modifier l\'attribut',
        'form'        => [
            'delete-image' => 'Supprimer l\'image',
            'label'       => 'Étiquette',
            'name'        => 'Nom',
            'type'        => 'Type',
            'select-type' => 'Sélectionner un type',
            'company'     => 'Compagnie',
            'natures'     => 'Natures',
            'types'       => [
                'choice' => 'Choix :number',
            ],
        ],
        'confirmation' => [
            'body' => 'La modification de cet attribut va SUPPRIMER l\'attribut de :nombre produit. Êtes-vous certain?
                |La modification de cet attribut va SUPPRIMER l\'attribut de :nombre produits. Êtes-vous certain?',
            'title' => 'Confirmation de modification d\'attribut',
        ],
        'save'        => 'Sauvegarder',
        'cancel'      => 'Annuler',
        'delete'      => 'Supprimer',
        'saved'       => 'L\'attribut a été enregistré avec succès.',
    ],
    'create'        => [
        'title'       => 'Créer un nouvel attribut',
        'description' => [
            'detail'             => 'Détail',
            'product'            => 'Produit',
            'specification'      => 'Devis',
            'system'             => 'Système',
            'technical_bulletin' => 'Bulletin technique',
        ],
        'form'        => [
            'label'   => 'Étiquette',
            'type'    => 'Type',
            'natures' => 'Natures',
            'select-type' => 'Sélectionner un type',
            'types'   => [
                'choice' => 'Choix {{ $index + 1 }}',
                'add'    => 'Ajoute un choix',
                'remove' => 'Supprimer',
            ],
        ],
        'save'        => 'Sauvegarder',
        'cancel'      => 'Annuler',
        'saved'       => 'L\'attribut a été enregistré avec succès.',
    ],
    'show'          => [
        'title' => 'Attribut',
        'description' => 'Information sur l\'attribut',
        'form'        => [
            'label'                        => 'Étiquette',
            'name'                         => 'Nom',
            'type'                         => 'Type',
            'company'                      => 'Companie',
            'natures'                      => 'Natures',
            'model-type'                   => 'Type de modèle',
            'system-type'                  => 'Type de système',
            'has-value'                    => 'Valeur',
            'has-value-checkbox'           => 'A t-il une valeur?',
            'is-parent-attribute'          => 'Attribut parent',
            'is-parent-attribute-checkbox' => 'Est-ce un attribut parent?',
            'building_components'          => 'Composantes du bâtiment'
        ],
        'back'        => 'Retour',
        'edit'        => 'Modifier',
        'delete'      => 'Supprimer',
    ],
    'systems'       => [
        'default'           => 'Global',
        'roof_system'       => 'Toiture',
        'plaza_deck_system' => 'Terrasse',
        'wall_system'       => 'Murs',
        'waterproofing'     => 'Étanchéité',
        'foundation_system' => 'Fondation',
        'parking_system'    => 'Stationnement',
        'bridge_system'     => 'Pont',
        'balcony_system'    => 'Balcon',
        'fountains_system'  => 'Fontaines et bassins',
    ],
    'models'        => [
        'child_product'      => 'Produit enfants',
        'detail'             => 'Détail',
        'parent_product'     => 'Produit parent',
        'specification'      => 'Devis',
        'system'             => 'Système',
        'technical_bulletin' => 'Bulletin technique',
    ],
    'natures'       => [
        'A' => 'Autre',
        'F' => 'Feuille',
        'L' => 'Liquide',
        'P' => 'Panneau',
        'C' => 'Complementaire',
        'O' => 'Outils',
    ],
    'natures-short' => [
        'A' => 'A',
        'F' => 'F',
        'L' => 'L',
        'P' => 'P',
        'C' => 'C',
        'O' => 'O',
    ],
    'add' => 'Ajouter',
    'default_choice' => 'Choisir une valeur',
];
