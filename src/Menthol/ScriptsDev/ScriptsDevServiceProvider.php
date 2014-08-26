<?php namespace Menthol\ScriptsDev;

use Illuminate\Support\ServiceProvider;

class ScriptsDevServiceProvider extends ServiceProvider {

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
		$this->package('menthol/scripts-dev');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['command.scripts-dev.execute'] = $this->app->share(
			function ($app) {
				return new ScriptsDevCommand($app['config']);
			}
		);

		$this->commands('command.scripts-dev.execute');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('command.scripts-dev.execute');
	}

}
