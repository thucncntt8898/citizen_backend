<?php

namespace App\Providers;

use App\Repositories\Citizen\CitizenRepositoryInterface;
use App\Repositories\District\DistrictRepository;
use App\Repositories\District\DistrictRepositoryInterface;
use App\Repositories\Occupation\OccupationRepository;
use App\Repositories\Occupation\OccupationRepositoryInterface;
use App\Repositories\Hamlet\HamletRepository;
use App\Repositories\Hamlet\HamletRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Ward\WardRepository;
use App\Repositories\Ward\WardRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CitizenVServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\Province\ProvinceRepositoryInterface::class,
            \App\Repositories\Province\ProvinceRepository::class
        );

        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Citizen\CitizenRepositoryInterface::class,
            \App\Repositories\Citizen\CitizenRepository::class
        );

        $this->app->singleton(
            DistrictRepositoryInterface::class,
            DistrictRepository::class
        );

        $this->app->singleton(
            OccupationRepositoryInterface::class,
            OccupationRepository::class
        );

        $this->app->singleton(
            WardRepositoryInterface::class,
            WardRepository::class
        );

        $this->app->singleton(
            HamletRepositoryInterface::class,
            HamletRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ProvinceRepositoryInterface::class,
            UserRepositoryInterface::class,
            CitizenRepositoryInterface::class,
            DistrictRepositoryInterface::class,
            OccupationRepositoryInterface::class,
            HamletRepositoryInterface::class,
            WardRepositoryInterface::class
        ];
    }
}
