<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        Gate::define('if_admin', function (User $user){
            $user->hasRole('admin');
        });

        Gate::define('if_moderator', function (User $user){
            $user->hasRole('moderator');
        });

//        Gate::define('if_moderator', fn(User $user) => $user->hasRole('moderator'));

        Gate::before(function ($user, $ability)
        {
            if($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
