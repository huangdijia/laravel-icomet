<?php

namespace Huangdijia\IComet;

use Illuminate\Support\ServiceProvider;

class ICometServiceProvider extends ServiceProvider
{
    protected $defer = true;
    protected $commands  = [
        Console\BroadcastCommand::class,
        Console\CheckCommand::class,
        Console\CloseCommand::class,
        Console\InfoCommand::class,
        Console\PushCommand::class,
    ];

    public function boot() {
        $path = __DIR__ . '/../config/icomet.php';

        $this->mergeConfigFrom($path, 'icomet');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/icomet.php' => config_path('icomet.php')]);
        }
    }

    public function register () {
        $this->app->singleton(IComet::class, function () {
            return new IComet(config('icomet'));
        });

        $this->app->alias(IComet::class, 'icomet');

        $this->commands($this->commands);
    }

    public function provides()
    {
        return [
            IComet::class,
            'icomet',
        ];
    }
}