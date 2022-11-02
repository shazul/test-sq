<?php

return [
    'confirm' => [
        'body' => 'Êtes-vous certain de vouloir supprimer le système ":name"?',
    ],
    'deleted'         => 'Le système a été supprimé.',
    'index'   => [
        'title'       => 'Systèmes',
        'description' => 'Liste des Systèmes',
        'table'       => [
            'header' => [
                'name'      => 'Nom',
                'building-component' => 'Composante du bâtiment',
                'actions'   => 'Actions',
            ],
            'edit'   => 'Modifier',
            'delete' => 'Supprimer',
            'show' => 'Voir',
        ],
        'create' => 'Ajouter un nouveau système',
    ],
    'create'  => [
        'save'  => 'Enregistrer',
        'title' => 'Créer un nouveau système',
        'saved' => 'Le système a été créé avec succès',
    ],
    'show'           => [
        'title' => 'Système',
        'back'  => 'Retour',
    ],
    'edit'  => [
        'title'         => 'Modifier un système',
        'description'   => 'Modifier les informations d\'un système',
        'saved'         => 'Le système a été modifié',
        'select-button' => 'Modifier le système',
    ],
    'edit-attribute'  => [
        'title'         => 'Modifier les attributs d\'un système',
        'description'   => 'Modifier les attributs d\'un système',
        'saved'         => 'Les attributs du système ont été modifiés',
        'select-button' => 'Modifier les attributs du système',
    ],
    'edit-layer'  => [
        'title'         => 'Modifier les couches d\'un système',
        'description'   => 'Modifier les couches d\'un système',
        'saved'         => 'Les couches du système ont été modifiés',
        'select-button' => 'Modifier les couches du système',
    ],
    'layer' => [
        'edit' => [
            'title'       => 'Modifier les couches d\'un système',
            'description' => 'Ajout ou modification de couche système',
            'save'        => 'Enregistrer',
            'saved'       => 'Couche modifiée avec succès.'
        ],
        'deleteConfirm' => 'Êtes-vous certain de vouloir supprimer la couche ":name"?',
    ],
    'layers' => [
        'title'         => 'Couches',
        'no-layers'     => 'Aucune couche dans ce groupe.',
        'add'           => 'Ajoute une nouvelle couche',
        'saved'         => 'Couche enregistrée avec succès.',
        'deleted'       => 'Couche supprimée avec succès.',
    ],
    'layer-groups' => [
        'delete-confirm' => 'Êtes-vous certain de vouloir supprimer ce groupe de couche et toute ses couches?',
        'edit'        => 'Modifier la couche',
        'name'        => 'Nom',
        'no-layers'   => 'Ce système n\'a pas de groupe de couches.',
        'deleted'     => 'Groupe de couche supprimé avec succès.',
        'save'        => 'Enregistrer le groupe',
        'saved'       => 'Groupe de couche enregistré.'
    ],
    'type' => [
        'parent'   => 'Produit parent',
        'substrat' => 'Substrat',
    ],
    'attribute' => [
        'add' => 'Ajouter un attribut au produit',
    ],
];
