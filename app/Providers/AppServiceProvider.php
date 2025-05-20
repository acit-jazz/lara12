<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

		foreach (glob(app_path().'/Helpers/*.php') as $filename) {
			require_once ($filename);
		}

        
        
        Route::middleware(['web']) // or 'api'
            ->prefix('backend') // optional
            ->group(base_path('routes/backend.php'));
      //  View::share('locale', app()->getLocale());
        //View::share('meta', ['en'=> checkMeta(null), 'id' => checkMeta(null), 'sc' => checkMeta(null)]);
    }
}
