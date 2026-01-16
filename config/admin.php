<?php

return [
    /**
     * If set, this user becomes Super Admin and bypasses all role/permission checks.
     * If empty/null, the first user (id=1) is treated as Super Admin.
     */
    'super_admin_email' => env('SUPER_ADMIN_EMAIL', 'admin@admin.com'),
];

