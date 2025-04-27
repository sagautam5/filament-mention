<?php

return [
    'mentionable' => [
        'model' => \App\Models\User::class,
        'column' => [
            'id' => 'id',
            'label' => 'name',
            'value' => 'username',
            'avatar' => 'profile',
        ],
        'url' => 'admin/users/{id}', // this will be used to generate the url for the mention item
    ],
    'default' => [
        'trigger_with' => [
            '@',
            '#',
            '%',
        ],
        'trigger_configs' => [
            '#' => [
                'lookupKey' => 'value',
                'prefix' => '',
                'suffix' => '',
                'labelKey' => 'id',
                'hintKey' => null,
            ],
            '%' => [
                'lookupKey' => 'value',
                'prefix' => '%',
                'suffix' => '%',
                'labelKey' => 'id',
                'hintKey' => null,
            ],
        ],
        'lookup_key' => 'value',
        'menu_show_min_length' => 2,
        'menu_item_limit' => 10,
        'prefix' => '',
        'suffix' => '',
        'label_key' => 'label',
        'hint_key' => null,
    ],
];
