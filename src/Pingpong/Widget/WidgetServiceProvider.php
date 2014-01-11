<?php

namespace Pingpong\Widget;

use Illuminate\Support\ServiceProvider;
use Pingpong\Widget\WidgetException as Exception;

class WidgetServiceProvider extends ServiceProvider {

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
		$this->package('pingpong/widget', 'widget');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['widget'] = $this->app->share(function($app)
		{
			return new Widget;
		});
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Widget', 'Pingpong\Widget\Facades\Widget');

			$widgetFile = app_path('widget.php');
			if(file_exists($widgetFile))
			{
				include $widgetFile;
			}else
			{
				throw new Exception("Widget file not found!");
			}
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('widget');
	}

}
