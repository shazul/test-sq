<?php

return [
    'confirm' => [
        'body' => 'Êtes-vous certain de vouloir supprimer le devis ":name"?',
    ],
    'index'   => [
        'title'       => 'Devis',
        'description' => 'Liste des Devis',
        'table'       => [
            'header' => [
                'name'      => 'Nom',
                'actions'   => 'Actions',
                'building-component' => 'Composante',
            ],
            'edit'   => 'Modifier',
            'delete' => 'Supprimer',
            'show' => 'Voir',
        ],
        'create' => 'Ajouter un nouveau devis',
    ],
    'create'  => [
        'title' => 'Créer un nouveau devis',
        'saved' => 'Le devis a été créé avec succès',
    ],
    'show'           => [
        'title' => 'Devis',
        'back'  => 'Retour',
    ],
    'edit'  => [
        'title'         => 'Modifier un devis',
        'description'   => 'Modifier les informations d\'un devis',
        'saved'         => 'Le devis a été modifiée',
        'select-button' => 'Modifier le devis',
    ],
    'edit-attribute'  => [
        'title'         => 'Modifier les attributs d\'un devis',
        'description'   => 'Modifier les attributs d\'un devis',
        'saved'         => 'Les attributs du devis ont été modifiés',
        'select-button' => 'Modifier les attributs du devis',
    ],
];
