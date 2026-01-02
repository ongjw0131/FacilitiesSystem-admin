<?php

namespace App\Providers;

use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Policies\FacilityBookingPolicy;
use App\Policies\FacilityPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Facility::class, FacilityPolicy::class);
        Gate::policy(FacilityBooking::class, FacilityBookingPolicy::class);
    }
}
