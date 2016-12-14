<?php

namespace Fahmiardi\Mongodb\UserProviders;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Fahmiardi\Mongodb\UserProviders\Contracts\Provider as ProviderContract;
use Fahmiardi\Mongodb\UserProviders\Contracts\UserProvider as UserProviderContract;
use Fahmiardi\Mongodb\UserProviders\Models\Provider;
use Fahmiardi\Mongodb\UserProviders\Models\UserProvider;

class UserProviderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerModelBindings();
        $this->registerBladeExtensions();
    }

    /**
     * Bind the Provider model into the IoC.
     */
    protected function registerModelBindings()
    {
        $this->app->bind(ProviderContract::class, Provider::class);
        $this->app->bind(UserProviderContract::class, UserProvider::class);
    }

    /**
     * Register the blade extensions.
     */
    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('provider', function ($provider) {
                return "<?php if(auth()->check() && auth()->user()->hasProvider({$provider})): ?>";
            });
            $bladeCompiler->directive('endprovider', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasprovider', function ($provider) {
                return "<?php if(auth()->check() && auth()->user()->hasProvider({$provider})): ?>";
            });
            $bladeCompiler->directive('endhasprovider', function () {
                return '<?php endif; ?>';
            });
        });
    }
}
