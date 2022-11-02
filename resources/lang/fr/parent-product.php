<?php

return [
    'confirm'        => [
        'body' => 'Êtes-vous certain de vouloir supprimer le produit ":name"?',
    ],
    'deleted'        => 'Le produit a été supprimé.',
    'index'          => [
        'title'       => 'Produit parents',
        'description' => 'Liste des produits parents',
        'table'       => [
            'header' => [
                'name'      => 'Nom',
                'nature'    => 'Nature',
                'building-component' => 'Composante',
                'actions'   => 'Actions',
            ],
            'edit'   => 'Modifier',
            'delete' => 'Supprimer',
            'show'   => 'Voir',
        ],
        'create'     => 'Ajouter un nouveau produit parent',
    ],
    'create'         => [
        'title' => 'Créer un nouveau produit parent',
        'saved' => 'Le produit parent a été créé avec succès',
        'save'  => 'Créer',
    ],
    'show'           => [
        'title' => 'Produit parent',
        'back'  => 'Retour',
    ],
    'edit'           => [
        'title'         => 'Modifier un produit parent',
        'description'   => 'Modifier les informations d\'un produit parent',
        'saved'         => 'Le produit parent a été modifié',
        'select-button' => 'Mofidier le produit parent',
        'save'          => 'Sauvegarder',
    ],
    'edit-attribute' => [
        'title'         => 'Modifier les attributs d\'un produit parent',
        'description'   => 'Modifier les attributs d\'un produit parent',
        'saved'         => 'Les attributs du produit parent ont été modifiés',
        'select-button' => 'Modifier les attributs du produit parent',
    ],
    'nature' => [
        'title'         => 'Modifier la nature d\'un produit parent',
        'description'   => 'Modifier la nature d\'un produit parent',
        'saved'         => 'La nature du produit parent a été modifiée',
        'select-button' => 'Modifier la nature du produit parent',
        'warning-title' => 'Attention!',
        'warning-message' => 'Modifier la nature d\'un produit va supprimer tous les attributs de ce produit
            et de ses enfants qui ne sont pas dans cette nouvelle nature.',
    ],
    'form'           => [
        'input_label' => 'Produit parent',
        'new_product' => 'Nouveau produit',
        'star_product' => 'Produit vedette',
    ],
];
