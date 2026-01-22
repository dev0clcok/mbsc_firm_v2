<?php

return [
    /**
     * If set, this user becomes Super Admin and bypasses all role/permission checks.
     * If empty/null, the first user (id=1) is treated as Super Admin.
     */
    'super_admin_email' => env('APP_SUPER_ADMIN_EMAIL', 'admin@admin.com'),
    'default_password' => env('APP_DEFAULT_USER_PASSWORD', 'password'),
];

