<?php

namespace Tobidsn\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/assets/' => public_path('/') ,
        ]);

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/stubs/'),
        ]);

        $this->publishes([
            __DIR__ . '/stubsapi/' => base_path('resources/stubsapi/'),
        ]);

        $this->publishes([
            __DIR__ . '/stubs_web_api/' => base_path('resources/stubs_web_api/'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Tobidsn\CrudGenerator\Commands\CrudGenerator',
            'Tobidsn\CrudGenerator\Commands\CrudViewCommand',
            'Tobidsn\CrudGenerator\Commands\ApiGenerator',
            'Tobidsn\CrudGenerator\Commands\ApiWebGenerator'
        );
    }
}
