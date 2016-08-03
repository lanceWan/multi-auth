<?php
return [
	'directory' =>[
		'layouts' => base_path('resources/views/layouts'),
		'auth' => [
			'passwords' => base_path('resources/views/auth/passwords'),
			'emails' => base_path('resources/views/auth/emails')
		]
	],
	'views' => [
        'login' => 'multi-auth/login.blade.php',
        'register' => 'multi-auth/register.blade.php',
        'email' => 'multi-auth/passwords/email.blade.php',
        'reset' => 'multi-auth/passwords/reset.blade.php',
        'password' => 'multi-auth/emails/password.blade.php',
        'app' => 'layouts/app.blade.php',
        'home' => 'home.blade.php',
        'welcome' => 'welcome.blade.php',
    ],
    'auth' => [
    	'guards' => [
	        'admin' => [
	            'driver' => 'session',
	            'provider' => 'admins',
	        ],
	    ],
	    'providers' => [
	        'admins' => [
	            'driver' => 'eloquent',
	            'model' => 'App\Admin',
	        ],
	    ],
	    'redirectTo' => '/admin',
	    'loginView' => 'mulit-auth.login',
	    'registerView' => 'multi-auth.register',
    ]
];