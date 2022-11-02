<?php

return [
    'confirm' => [
        'body' => 'Are you sure you want to delete the user ":name"?',
    ],
    'index'   => [
        'title'       => 'Users',
        'description' => 'List of Users',
        'table'       => [
            'header' => [
                'name'      => 'Name',
                'email'     => 'E-mail',
                'active'    => 'Active',
                'actions'   => 'Actions',
                'groups'    => 'Groups',
                'companies' => 'Companies'
            ],
            'edit'   => 'Edit',
            'delete' => 'Delete',
        ],
        'create' => 'Add new user',
        'you'    => 'You',
    ],
    'create'  => [
        'title' => 'Create new user',
        'saved' => 'The user has been created.',
    ],
    'edit'  => [
        'title' => 'Modify a user',
        'saved' => 'The user has been modified.',
    ],
    'my_profile'  => [
        'title'                 => 'Modify your profile',
        'first_name'            => 'First name',
        'last_name'             => 'Last name',
        'email'                 => 'E-mail',
        'current_password'      => 'Current password',
        'password'              => 'New password',
        'password_confirmation' => 'Confirm new password',
        'retype_password'       => 'Retype password',
        'save'                  => 'Save',
        'cancel'                => 'Cancel',
        'saved'                 => 'Your profile has been modified.',
        'users'                 => 'Users',
        'edit_profile'          => 'Edit my profile',
    ],
    'form'    => [
        'first_name'            => 'First name',
        'last_name'             => 'Last name',
        'email'                 => 'E-mail',
        'password'              => 'Password',
        'password_confirmation' => 'Confirm password',
        'groups'                => 'Groups',
        'active'                => 'Active',
        'save'                  => 'Save',
        'cancel'                => 'Cancel',
        'companies'             => 'Companies'
    ],
];
