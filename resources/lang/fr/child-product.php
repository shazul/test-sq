<?php

return [
    'confirm' => [
        'body' => 'Êtes-vous certain de vouloir supprimer le produit ":name"?',
    ],
    'deleted'        => 'Le produit a été supprimé.',
    'index'          => [
        'title'       => 'Produits enfants',
        'description' => 'Liste des produits enfants',
        'table'       => [
            'header' => [
                'name'         => 'Nom',
                'nature'       => 'Nature',
                'parent'       => 'Parent',
                'product_code' => 'Code de produit',
                'actions'      => 'Actions',
            ],
            'edit'   => 'Modifier',
            'delete' => 'Supprimer',
            'show'   => 'Voir',
        ],
        'create'             => 'Ajouter un nouveau produit enfant',
        'review-approbation' => 'Réviser pour approbation',
    ],
    'create'         => [
        'title' => 'Créer un nouveau produit enfant',
        'saved' => 'Le produit enfant a été créé avec succès',
        'save'  => 'Créer',
    ],
    'show'           => [
        'title' => 'Produit enfant',
        'back'  => 'Retour',
    ],
    'edit'  => [
        'description'   => 'Modifier les informations d\'un produit enfant',
        'draft'         => 'Enregistrer et déplacer dans brouillons',
        'publish'       => 'Publier',
        'saved'         => 'Le produit enfant a été modifié',
        'select-button' => 'Modifier le produit enfant',
        'title'         => 'Modifier un produit enfant',
        'copy-children' => 'Copier les attributs d\'un autre produit enfant',
        'copy-no-children' => 'Aucun produit enfant disponible pour copier les attributs.',
        'select-child' => 'Sélectionner un produit enfant',
        'copy-attribute' => 'Produits enfant',
        'replace-attributes' => 'Remplacer les attributs',
        'attributes-copied' => 'Les attributs ont été copiées avec succès.',
        'child-product-missing' => 'produit enfant',
    ],
    'edit-attribute'  => [
        'saved'         => 'Les attributs du produits enfant ont été modifiés',
        'select-button' => 'Modifier les attributs du produit enfant',
        'title'         => 'Modifier les attributs d\'un produits enfants',
    ],
    'edit-parent'  => [
        'remove-parent' => 'Retirer le parent',
        'title'         => 'Modifier le parent d\'un produit enfant',
        'saved'         => 'Le produit enfant a été modifié',
        'select-button' => 'Modifier le parent',
        'warning-title' => 'Attention!',
        'warning-message' => 'Modifier le produit parent pourrais modifier la nature du produit et cela
            entraînerait la suppression de tous les attributs de ce produit qui ne sont pas dans cette nouvelle nature. <br />
            Retirer le parent va supprimer tous les attributs non obligatoires et déplacer le produit dans Sans parent.',
    ],
    'status' => [
        'incomplet' => 'Le status du produit parent est maintenant incomplet.',
    ],
    'approve' => [
        'approve'     => 'Approuver',
        'approved'    => 'Produit enfant approuvé avec succès.',
        'delete'      => 'Supprimer',
        'description' => 'Approuver un produit enfant.',
        'title'       => 'Approuver un produit',
    ],
];
