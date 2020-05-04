<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //registrando las rutas protegidas con laravel passport
        Passport::routes();
        //poniendole tiempo limite al token
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        //un refresh token tiempo de expiracion a los refresh
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
