<?php

/*
|--------------------------------------------------------------------------
| Permissions
|--------------------------------------------------------------------------
|
| Permissions which pertain to roles, you can define access based on permissions
| with the permission middleware
| ex. ['middleware' => ['permission:roles.create|users']]
*/


return [

    'roles' => [
        'create' => 'Create Roles',
        'update' => 'Update Roles',
        'delete' => 'Delete Roles',
    ],

    'users' => [
        'invite' => 'Invite Users',
        'activity' => 'View User Activity',
        'update' => 'Update Users',
        'delete' => 'Delete Users',
    ],

];
