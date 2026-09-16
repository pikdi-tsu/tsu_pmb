<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions.
         */
        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles.
         */
        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        'roles' => config('app.table.roles', 'pmb_roles'),

        'permissions' => config('app.table.permissions', 'pmb_permissions'),

        'model_has_permissions' => config('app.table.model_has_permissions', 'pmb_model_has_permissions'),

        'model_has_roles' => config('app.table.model_has_roles', 'pmb_model_has_roles'),

        'role_has_permissions' => config('app.table.role_has_permissions', 'pmb_role_has_permissions'),
    ],

    'column_names' => [
        'role_pivot_key' => null, // default 'role_id'
        'permission_pivot_key' => null, // default 'permission_id'

        /*
         * In PMB, users.id is bigint unsigned, so default model_id is used.
         */
        'model_morph_key' => 'model_id',

        'team_foreign_key' => 'team_id',
    ],

    'register_permission_check_method' => true,

    'register_octane_reset_listener' => false,

    'events_enabled' => false,

    'teams' => false,

    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,

    'use_passport_client_credentials' => false,

    'display_permission_in_exception' => false,

    'display_role_in_exception' => false,

    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
