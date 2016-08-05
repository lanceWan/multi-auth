<?php
return [
	'directory' =>[
		'layouts' 	=> 'resources/views/layouts',
		'auth' 		=> [
			'passwords' => 'resources/views/multiAuth/passwords',
			'emails' 	=> 'resources/views/multiAuth/emails'
		]
	],
	'views' => [
        'login' 	=> 'multiAuth/login.blade.php',
        'register' 	=> 'multiAuth/register.blade.php',
        'email' 	=> 'multiAuth/passwords/email.blade.php',
        'reset' 	=> 'multiAuth/passwords/reset.blade.php',
        'password' 	=> 'multiAuth/emails/password.blade.php',
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
	    'loginView' => 'multiAuth.login',
	    'registerView' => 'multiAuth.register',
    ]
];