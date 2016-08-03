<?php
namespace Iwanli\MultiAuth;
use Illuminate\Support\ServiceProvider;
use Iwanli\MultiAuth\Console\MultiAuthCommands;
use Iwanli\MultiAuth\Generators\AuthGenerator;
class MultiAuthServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->singleton('iwanli.generator', function ($app) {
            return new AuthGenerator($app['files'],$app['config']);
        });

		$this->app->singleton('multi_auth', function ($app) {
            return new MultiAuthCommands($app['iwanli.generator']);
        });
		$this->commands([
			'multi_auth'
		]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
