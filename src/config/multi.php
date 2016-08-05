<?php
return [
	'directory' =>[
		'layouts' 	=> 'resources/views/layouts',
		'auth' 		=> [
			'passwords' => 'resources/views/multi-auth/passwords',
			'emails' 	=> 'resources/views/multi-auth/emails'
		]
	],
	'views' => [
        'login' 	=> 'multi-auth/login.blade.php',
        'register' 	=> 'multi-auth/register.blade.php',
        'email' 	=> 'multi-auth/passwords/email.blade.php',
        'reset' 	=> 'multi-auth/passwords/reset.blade.php',
        'password' 	=> 'multi-auth/emails/password.blade.php',
        'app' 		=> 'layouts/app.blade.php',
        'home' 		=> 'home.blade.php',
        'welcome' 	=> 'welcome.blade.php',
    ],
    'auth' => [
    	'guards' => [
            'admin' => [
                'driver'    => 'session',
                'provider'  => 'admins',
            ]
        ],
        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model' => 'App\Models\Admin',
            ],
        ],
        'passwords' => [
            'admins' => [
                'provider' => 'admins',
                'email' => 'mulit-auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ],
	    'redirectTo' => '/admin',
	    'loginView' => 'multi-auth.login',
	    'registerView' => 'multi-auth.register',
    ]
];