<?php

return [
    //Super Admin
    'Super Admin' => [
        'dashboard' => 1,
        'hallmark_management' =>1,
        'hallmark_management_access' => [
            'create' => 1,
            'edit' => 1,
            'view' => 1,            
            'change_status' => 1,
        ], 
        'chain_management' =>1,
        'chain_management_access' => [
            'create' => 1,
            'edit' => 1,
            'view' => 1,            
            'change_status' => 1,
        ], 
        'user_management' => 1,
        'user_management_access' => [
            'create' => 1,
            'edit' => 1,
            'view' => 1,
            'change_password' => 1,
            'delete' => 1,
            'change_status' => 1,
        ],
        'customer_management' => 1,
        'customer_management_access' => [
            'create' => 1,
            'edit' => 1,
            'view' => 1,
            'delete' => 1,
            'change_status' => 1,
        ],
        'stock_management' => 1,
        'stock_management_access' => [
            'create' => 1,
            'edit' => 1,
            'view' => 1,
            'delete' => 1,
            'change_status' => 1,
        ],
        'change_profile_password' => 1,
    ],
];
