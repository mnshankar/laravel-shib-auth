<?php namespace mnshankar\Shib;

use Illuminate\Support\ServiceProvider;

class ShibAuthServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/../config/shib.php' => config_path('shib.php'),
        ], 'config');
    }

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->mergeConfigFrom(__DIR__.'/../config/shib.php', 'shib');
	}

}
