<?php

return [
    'default' => env('AI_DEFAULT', 'openai'),

    'providers'=> [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'base' => env('OPENAI_BASE', 'https://api.openai.com'),
        ],
        'anthropic' => [
            'key' => env('ANTHROPIC_KEY'),
            'base' => env('ANTHROPIC_BASE', 'https://api.anthropic.com'),
        ],
        'deepseek' => [
            'key' => env('DEEPSEEK_KEY'),
            'base' => env('DEEPSEEK_BASE', 'https://api.deepseek.ai'),
        ],
    ],
];
