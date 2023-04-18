<?php

return [
	'admin' => [
		'name' => [
			'max' => 64,
            'min' => 3,
            'characters'
		],
		'password' => [
			'max' => 20
		],
		'email' => [
			'max' => 64
		],
        'phone' => [
            'max' => 20,
            'min' => 7,
            'number'
        ],
	],
    'city' => [
        'name' => [
            'max' => 128
        ],
    ],
    'mail' => [
        'email' => [
            'max' => 100
        ],
        'subject' => [
            'min' => 1,
            'max' => 1000
        ],
        'description' => [
            'min' => 2,
            'max' => 1000
        ],
    ],
    'accommodations' => [
        'name' => [
			'max' => 64,
            'min' => 3,
            'characters'
		],
		'street' => [
			'max' => 200
		],
		'email' => [
            'max' => 64
		],
        'phone' => [
            'max' => 20,
            'min' => 7,
            'number'
        ],

    ],
];
