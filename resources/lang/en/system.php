<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the system ":name"?',
    ],
    'deleted' => 'The system has been deleted.',
    'index'   => [
        'title'       => 'Systems',
        'description' => 'List of Systems',
        'table'       => [
            'header' => [
                'name'      => 'Name',
                'building-component' => 'Building Component',
                'actions'   => 'Actions',
            ],
            'edit'   => 'Edit',
            'delete' => 'Delete',
            'show' => 'Show',
        ],
        'create' => 'Add new system',
    ],
    'create'  => [
        'title' => 'Create new system',
        'save'  => 'Save',
        'saved' => 'The system has been created.',
    ],
    'edit'  => [
        'title'         => 'Modify a system',
        'description'   => 'Modify data of a system.',
        'saved'         => 'The system has been modified.',
        'select-button' => 'Edit system',
    ],
    'edit-attribute'  => [
        'title'         => 'Modify a system\'s attribute',
        'description'   => 'Modify attributes of a system.',
        'saved'         => 'The system attributes have been modified.',
        'select-button' => 'Edit system\'s attributes',
    ],
    'edit-layer'  => [
        'title'         => 'Modify a system\'s layers',
        'description'   => 'Modify layers of a system.',
        'saved'         => 'The system layers have been modified.',
        'select-button' => 'Edit system\'s layers',
    ],
    'layer' => [
        'edit' => [
            'description' => 'Add or edit a system layer',
            'title'       => 'Add or edit a system layer',
            'save'        => 'Save',
            'saved'       => 'Layer successfully saved.',
        ],
        'deleteConfirm' => 'Are you sure you want to delete the layer ":name"?',
    ],
    'layers' => [
        'title'         => 'Layers',
        'no-layers'     => 'No layer in this group.',
        'add'           => 'Add a new layer.',
        'saved'         => 'Layer created successfully.',
        'deleted'       => 'Layer successfully deleted.',
    ],
    'layer-groups' => [
        'delete-confirm' => 'Are you sure you want to delete this layer group and all its layers ?',
        'edit'        => 'Edit the layer',
        'name'        => 'Name',
        'no-layers'   => 'This system has no layer groups.',
        'deleted'     => 'Layer group successfully deleted.',
        'save'      => 'Save the group',
        'saved'     => 'Layer group saved.',
    ],
    'type' => [
        'parent'   => 'Parent product',
        'substrat' => 'Substrate',
    ],
    'attribute' => [
        'add' => 'Add an attribute to product',
    ],
    'show' => [
        'title' => 'System',
        'back' => 'Back',
    ],
];
