<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the product ":name"?',
    ],
    'deleted' => 'The product has been deleted.',
    'index'   => [
        'title'       => 'Parent Products',
        'description' => 'List of Parent Products',
        'table'       => [
            'header' => [
                'name'      => 'Name',
                'nature'    => 'Nature',
                'building-component' => 'Building component',
                'actions'   => 'Actions',
            ],
            'edit'   => 'Edit',
            'delete' => 'Delete',
            'show' => 'Show',
        ],
        'create' => 'Add new parent Product',
    ],
    'create'  => [
        'title' => 'Create new parent product',
        'saved' => 'The parent product has been created.',
        'save'  => 'Create',
    ],
    'show' => [
        'title' => 'Parent product',
        'back' => 'Back',
    ],
    'edit'  => [
        'title'         => 'Modify a parent product',
        'description'   => 'Modify data of a parent product.',
        'saved'         => 'The parent product has been modified.',
        'select-button' => 'Edit product',
        'save'          => 'Save',
    ],
    'edit-attribute'  => [
        'title'         => 'Modify a parent product\'s attribute',
        'description'   => 'Modify attributes of a parent product.',
        'saved'         => 'The parent product attributes have been modified.',
        'select-button' => 'Edit product\'s attributes',
    ],
    'nature' => [
        'title'         => 'Modify a parent product\'s nature',
        'description'   => 'Modify nature of a parent product.',
        'saved'         => 'The parent product nature has been modified.',
        'select-button' => 'Edit product\'s nature',
        'warning-title' => 'Warning!',
        'warning-message' => 'Editing this product\'s nature will delete all attributes of this product
            and its child(s) that are not in the new nature.',
    ],
    'form'            => [
        'input_label'  => 'Parent product',
        'new_product'  => 'New Product',
        'star_product'  => 'Produit vedette',
    ],
];
