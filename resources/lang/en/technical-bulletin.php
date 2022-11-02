<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the technical bulletin ":name"?',
    ],
    'deleted' => 'The technical bulletin has been deleted.',
    'index'   => [
        'title'       => 'Technical bulletins',
        'description' => 'List of Technical bulletins',
        'table'       => [
            'header' => [
                'name'               => 'Name',
                'building-component' => 'Building component',
                'actions'            => 'Actions',
            ],
            'edit'   => 'Edit',
            'delete' => 'Delete',
            'show' => 'Show',
        ],
        'create' => 'Add new technical bulletin',
    ],
    'create'  => [
        'title' => 'Create new technical bulletin',
        'saved' => 'The technical bulletin has been created.',
    ],
    'edit'  => [
        'title'         => 'Modify a technical bulletin',
        'description'   => 'Modify data of a technical bulletin.',
        'saved'         => 'The technical bulletin has been modified.',
        'select-button' => 'Edit technical bulletin',
    ],
    'show' => [
        'title' => 'Technical bulletin',
        'description' => '',
        'back' => 'Back',
    ],
    'edit-attribute'  => [
        'title'         => 'Modify a technical bulletin\'s attribute',
        'description'   => 'Modify attributes of a technical bulletin.',
        'saved'         => 'The technical bulletin attributes have been modified.',
        'select-button' => 'Edit technical bulletin\'s attributes',
    ],
];
