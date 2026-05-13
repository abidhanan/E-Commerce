<?php

return [
    'roles' => [
        'superadmin',
        'admin',
        'editor',
        'finance',
        'staff',
        'user',
    ],

    'groups' => [
        'general' => [
            'label' => 'General',
            'permissions' => [
                'view dashboard' => 'Melihat dashboard admin',
                'view shop' => 'Melihat storefront',
            ],
        ],
        'users' => [
            'label' => 'Users & Akses',
            'permissions' => [
                'manage users' => 'Kelola user dan role user',
                'manage role access' => 'Kelola hak akses seluruh role',
            ],
        ],
        'storefront' => [
            'label' => 'Landing Page',
            'permissions' => [
                'manage displays' => 'Kelola main display, bestsellers, custom collections, social link, FAQ, dan About Us',
            ],
        ],
        'catalog' => [
            'label' => 'Katalog Produk',
            'permissions' => [
                'manage products' => 'Kelola produk',
                'manage categories' => 'Kelola kategori produk',
                'manage collections' => 'Kelola collections',
                'manage product attributes' => 'Kelola temperature, intensities, insulations, breathability, dan materials',
                'manage size guides' => 'Kelola size guide',
            ],
        ],
        'content' => [
            'label' => 'Konten',
            'permissions' => [
                'manage blogs' => 'Kelola blog, kategori blog, dan tags',
            ],
        ],
        'sales' => [
            'label' => 'Sales',
            'permissions' => [
                'manage orders' => 'Kelola order dan konfirmasi pembayaran manual',
                'manage payments' => 'Akses area pembayaran/finance',
            ],
        ],
    ],

    'defaults' => [
        'superadmin' => ['*'],
        'admin' => [
            'view dashboard',
            'manage displays',
            'manage users',
            'manage products',
            'manage categories',
            'manage collections',
            'manage product attributes',
            'manage size guides',
            'manage blogs',
            'manage orders',
            'manage payments',
            'view shop',
        ],
        'editor' => [
            'view dashboard',
            'manage products',
            'manage categories',
            'manage collections',
            'manage blogs',
            'view shop',
        ],
        'finance' => [
            'view dashboard',
            'manage orders',
            'manage payments',
        ],
        'staff' => [
            'view dashboard',
            'manage orders',
            'view shop',
        ],
        'user' => [
            'view shop',
        ],
    ],
];
