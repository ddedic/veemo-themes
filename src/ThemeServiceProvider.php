<?php namespace Veemo\Themes;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
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
        // Publish config.
        $this->publishes([
            __DIR__ . '/Config/themes.php' => config_path('veemo/themes.php'),
        ]);


    }

    /**
     * Register service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/themes.php', 'veemo.themes'
        );

        // Register Asset
        $this->registerAsset();

        // Register theme manager
        $this->registerThemeManager();

        // Register theme
        $this->registerTheme();

        // Register Helpers
        $this->registerHelpers();



        // Register commands
        /*
        $this->registerThemeGenerator();
        $this->registerThemeDestroy();

        // Assign commands.
        $this->commands(
            'veemo.theme.create',
            'veemo.theme.destroy'
        );
        */
    }


    /**
     * Register asset provider.
     *
     * @return void
     */
    public function registerAsset()
    {
        $this->app['veemo.asset'] = $this->app->share(function($app)
        {
            return new Asset();
        });
    }



    public function registerThemeManager()
    {
        $this->app['veemo.theme.manager'] = $this->app->share(function ($app) {
            $adapter = new Adapters\DummyManagerAdapter($app['files'], $app['config']);

            return new ThemeManager($adapter, $app['config']);
        });
    }


    /**
     * Register theme provider.
     *
     * @return void
     */
    public function registerTheme()
    {

        $this->app->bindShared('veemo.theme', function ($app) {
            return new Theme($app['veemo.theme.manager'],$app['files'], $app['config'], $app['events'], $app['view'], $app['veemo.asset']);
        });

        $this->app->booting(function ($app) {
            //$app['veemo.themes']->register();
        });

    }



    protected function registerHelpers()
    {
        foreach (glob(__DIR__ . '/Helpers/*Helper.php') as $filename){
            require_once($filename);
        }
    }




    /**
     * Register generator of theme.
     *
     * @return void
     */
    public function registerThemeGenerator()
    {
        $this->app['veemo.theme.create'] = $this->app->share(function ($app) {
            return new Commands\ThemeGeneratorCommand($app['config'], $app['files']);
        });
    }


    /**
     * Register theme destroy.
     *
     * @return void
     */
    public function registerThemeDestroy()
    {
        $this->app['veemo.theme.destroy'] = $this->app->share(function ($app) {
            //return new Commands\ThemeDestroyCommand($app['config'], $app['files']);
            return;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('veemo.theme', 'veemo.assets');
    }

}