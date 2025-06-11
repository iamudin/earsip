<?php
return [
    'name'=>'E-Arsip',
    'url'=> env('APP_URL_EARSIP',null),
    'path'=>'earsip',
    'logo'=>env('APP_LOGO_EARSIP','/noimage.webp'),
    'path_url'=>null,
    'icon' => 'fa-archive',
    'user' => null,
    'route' => null,
    'title' => env('APP_TITLE_EARSIP','e-Arsip'),
    'description' => env('APP_DESCRIPTION_EARSIP','Arsip Surat Masuk Elektronik'),
    'api'=> [
        'wa_sender'=>[
            'url'=> env('WA_SENDER_URL',null),
            'session'=> env('WA_SENDER_SESSION',null),
        ]
        ],
    'module' =>
    array(
        [
            'name' => 'Dashboard',
            'route' => 'earsip.dashboard',
            'icon' => 'fa-dashboard',
            'path' => 'dashboard',
            'only_admin' => false,
        ],

        [
            'name' => 'Surat Masuk',
            'route' => 'surat-masuk.index',
            'icon' => 'fa-envelope',
            'path' => 'surat-masuk',
            'only_admin' => false,
        ],
        [
            'name' => 'Pejabat',
            'route' => 'pejabat.index',
            'icon' => 'fa-user',
            'path' => 'pejabat',
            'only_admin' => true,
        ],

    )
];
