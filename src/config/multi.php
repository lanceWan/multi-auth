<?php
return [
    /**
     * auth view directroy
     */
	'directory' =>[
		'layouts' 	=> 'resources/views/layouts',
		'auth' 		=> [
			'passwords' => 'resources/views/multi-auth/passwords',
			'emails' 	=> 'resources/views/multi-auth/emails'
		]
	],

    /**
     * auth view file name 
     */
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
    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
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
                /**
                 * According to this configuration will create the corresponding model and migration
                 */
                'model' => 'App\Models\Admin',
            ],
        ],
        /**
         * Plan development...
         */
        'passwords' => [
            'admins' => [
                'provider' => 'admins',
                'email' => 'mulit-auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ],
        /**
         * The route of the jump after the success of the certification
         */
	    'redirectTo' => '/admin',
        /**
         * custom AuthController login view
         */
	    'loginView' => 'multi-auth.login',
        /**
         * custom AuthController register view
         */
	    'registerView' => 'multi-auth.register',
    ]
];