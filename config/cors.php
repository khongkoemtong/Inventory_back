<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // អនុញ្ញាតឱ្យគ្រប់គ្នាចូលបាន

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // បើប្រើ Sanctum/Auth ត្រូវទុក true បើអត់ទេដាក់ false ក៏បាន
];