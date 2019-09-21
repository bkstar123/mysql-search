<?php
/**
 * MySqlSearchServiceProvider
 *
 * @author: tuanha
 * @last-mod: 21-Sept-2019
 */
namespace Bkstar123\MySqlSearch;

use Illuminate\Support\ServiceProvider;
use Bkstar123\MySqlSearch\Console\Commands\MySqlSearchInit;
use Bkstar123\MySqlSearch\Console\Commands\MySqlSearchReset;

class MySqlSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/bkstar123_mysqlseach.php' => config_path('bkstar123_mysqlseach.php'),
        ]);
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                MySqlSearchInit::class,
                MySqlSearchReset::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/bkstar123_mysqlseach.php', 'bkstar123_mysqlseach');
    }
}
