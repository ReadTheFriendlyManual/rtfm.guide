<?php

return [
    // Use local cache mode (no API token required)
    'token' => env('TORCHLIGHT_TOKEN', ''),

    // Theme to use for syntax highlighting
    'theme' => 'github-dark',

    // Cache path for compiled snippets
    'cache_path' => env('TORCHLIGHT_CACHE_PATH'),

    // Additional options
    'options' => [
        'lineNumbers' => false,
        'diffIndicators' => true,
        'diffIndicatorsInPlaceOfLineNumbers' => false,
        'summaryCollapsedIndicator' => '...',
    ],
];
