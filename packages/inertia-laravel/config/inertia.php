<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Server-Side Rendering
    |--------------------------------------------------------------------------
    |
    | These options config how Inertia handles server-side rendering. You can
    | toggle SSR support and specify the gateway endpoint the SSR server
    | listens to when rendering pages outside the browser.
    */
    'ssr' => [
        'enabled' => true,
        'url' => env('INERTIA_SSR_URL', 'http://127.0.0.1:13714'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | These options allow you to customize the page paths and extensions that
    | will be asserted by Inertia's testing utilities. You may disable page
    | existence checks when you rely on dynamic page resolution.
    */
    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('js/Pages'),
        ],
        'page_extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Resolution
    |--------------------------------------------------------------------------
    |
    | These settings control the directories and extensions Inertia scans when
    | resolving pages. They should mirror the testing configuration when
    | possible to keep behavior consistent across environments.
    */
    'page_paths' => [
        resource_path('js/Pages'),
    ],

    'page_extensions' => [
        'js',
        'jsx',
        'svelte',
        'ts',
        'tsx',
        'vue',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shared Props
    |--------------------------------------------------------------------------
    |
    | Define the props that should be shared by default across all Inertia
    | responses in your application. This is an array of key-value pairs or
    | callables that return additional props on each request.
    */
    'shared_props' => [],

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | When set, this value is used by Inertia to determine if the assets are
    | up-to-date. This can be a string or a callable that returns a string.
    */
    'version' => null,
];
