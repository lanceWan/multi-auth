# multi-auth
> Custom your Auth Drivers
# Installation
```
composer require iwanli/multi-auth
```

**Or**

First, pull in the package through Composer.

```
"iwanli/multi-auth": "~0.1.*"
```

Now you'll want to update or install via composer.

```
composer update
```

# Providers
open your `config/app.php` and add this line in providers section .

```
Iwanli\MultiAuth\MultiAuthServiceProvider::class,
```

# Configuration
And the last, publish the package's configuration by running:

```
php artisan vendor:publish
```

That will publish the `multi.php` config file to your `config/` folder .

```php
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
```

# Usage

```
php artisan make:multi-auth Admin/LoginController
```
Or mandatory coverage of existing documents

```
php artisan make:multi-auth Admin/LoginController --force
```

The Artisan command generates the routes, views controller and update `Authenticate` middleware required for user authentication .

Ok,that's all, enjoy it!
