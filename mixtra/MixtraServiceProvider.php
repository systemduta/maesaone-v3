<?php
    
namespace Mixtra;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Mixtra\Commands\MITInstallCommand;
use Mixtra\Commands\MITUpdateCommand;
use App;

class MixtraServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'mixtra');

        $this->publishes([__DIR__.'/config/mixtra.php' => config_path('mixtra.php')], 'mit_config');
        $this->publishes([__DIR__.'/userfiles/assets' => public_path('assets')], 'mit_assets');
        $this->publishes([__DIR__.'/userfiles/config/mixtra.php' => config_path('mixtra.php')], 'mit_config');
        $this->publishes([__DIR__.'/userfiles/lang' => resource_path('lang')], 'mit_localization');
        $this->publishes([__DIR__.'/userfiles/views/vendor' => resource_path('views/vendor')], 'mit_vendor');
        $this->publishes([__DIR__.'/database' => base_path('database')], 'mit_migration');

        if (!file_exists(app_path('Http/Controllers/UserController.php'))) {
            $this->publishes([__DIR__.'/userfiles/controllers/UserController.php' => app_path('Http/Controllers/UserController.php')], 'mit_controllers');
        }
        if (!file_exists(app_path('Http/Controllers/AdminController.php'))) {
            $this->publishes([__DIR__.'/userfiles/controllers/AdminController.php' => app_path('Http/Controllers/AdminController.php')], 'mit_controllers');
        }
        if (!file_exists(resource_path('views/mixtra/sidemenu.blade.php'))) {
            $this->publishes([__DIR__.'/userfiles/views/mixtra/sidemenu.blade.php' => resource_path('views/mixtra/sidemenu.blade.php')], 'mit_sidemenu');
        }

        require __DIR__.'/routes.php';
    }

    public function register()
    {
        $this->registerMITCommand();
        $this->commands('mitinstall');
        $this->commands('mitupdate');
        $this->commands(['Mixtra\Commands\MITVersionCommand']);

        $loader = AliasLoader::getInstance();
        $loader->alias('MITBooster', 'Mixtra\Helpers\MITBooster');
    }

    private function registerMITCommand()
    {
        $this->app->singleton('mitinstall', function () {
            return new MITInstallCommand;
        });
        
        $this->app->singleton('mitupdate', function () {
            return new MITUpdateCommand;
        });
    }
}
