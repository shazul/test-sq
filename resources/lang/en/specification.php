<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the specification ":name"?',
    ],
    'index'   => [
        'title'       => 'Specifications',
        'description' => 'List of Specifications',
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
        'create' => 'Add new specification',
    ],
    'create'  => [
        'title' => 'Create new specification',
        'saved' => 'The specification has been created.',
        'save'  => 'Create',
    ],
    'show' => [
        'title' => 'Specification',
        'back' => 'Back',
    ],
    'edit'  => [
        'title'         => 'Modify a specification',
        'description'   => 'Modify data of a specification.',
        'saved'         => 'The specification has been modified.',
        'select-button' => 'Edit specification',
        'save'          => 'Save',
    ],
    'edit-attribute'  => [
        'title'         => 'Modify an specification\'s attribute',
        'description'   => 'Modify attributes of a specification.',
        'saved'         => 'The specification attributes have been modified.',
        'select-button' => 'Edit specification\'s attributes',
    ],
];
