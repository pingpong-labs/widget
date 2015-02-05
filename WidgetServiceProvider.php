<?php namespace Pingpong\Widget;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['widget'] = $this->app->share(function ($app)
        {
            $blade = $app['view']->getEngineResolver()->resolve('blade')->getCompiler();

            return new Widget($blade, $app);
        });

        $this->app->booting(function ()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('Widget', 'Pingpong\Widget\Facades\Widget');

            $file = app_path('widgets.php');

            if (file_exists($file)) include $file;
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
