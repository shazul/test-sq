<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the product ":name"?',
    ],
    'deleted' => 'The child product has been deleted.',
    'index'   => [
        'title'       => 'Child Products',
        'description' => 'List of Child Products',
        'table'       => [
            'header' => [
                'name'         => 'Name',
                'nature'       => 'Nature',
                'parent'       => 'Parent',
                'product_code' => 'Product code',
                'actions'      => 'Actions',
            ],
            'edit'   => 'Edit',
            'delete' => 'Delete',
            'show'   => 'Show',
        ],
        'create'             => 'Add new child Product',
        'review-approbation' => 'Review for approbation',
    ],
    'create'  => [
        'title' => 'Create new child product',
        'saved' => 'The child product has been created.',
        'save'  => 'Create',
    ],
    'show' => [
        'title' => 'Child product',
        'back'  => 'Back',
    ],
    'edit'  => [
        'description'   => 'Modify data of a child product.',
        'draft'         => 'Save and move to drafts',
        'publish'       => 'Publish',
        'saved'         => 'The child product has been modified.',
        'select-button' => 'Edit child product',
        'title'         => 'Modify a child product',
        'copy-children' => 'Copy attributes from an other child product',
        'copy-no-children' => 'Not child available to copy attributes from.',
        'select-child' => 'Select a child product',
        'copy-attribute' => 'Child products',
        'replace-attributes' => 'Replace attributes',
        'attributes-copied' => 'The attributes have been copied successfully.',
        'child-product-missing' => 'child product',
    ],
    'edit-attribute'  => [
        'description'   => 'Modify attributes of a child product.',
        'saved'         => 'The child product attributes have been modified.',
        'select-button' => 'Edit child product\'s attributes',
        'title'         => 'Modify a child product\'s attribute',
    ],
    'edit-parent'  => [
        'remove-parent' => 'Remove parent',
        'title'         => 'Edit parent of a child product',
        'saved'         => 'The child product has been modified',
        'select-button' => 'Edit parent',
        'warning-title' => 'Warning!',
        'warning-message' => 'Deleting this parent product could edit the product\'s nature and this could
            delete all attributes that are not in the new nature. <br />
            Removing the parent will delete all non required attributes and move the product in Parentless.',
    ],
    'status' => [
        'incomplet' => 'The parent product is now incomplete.',
    ],
    'approve' => [
        'approve'     => 'Approve',
        'approved'    => 'Child product approved successfully',
        'delete'      => 'Delete',
        'title'       => 'Approve a product',
        'description' => 'Approve this child product.',
    ],
];
