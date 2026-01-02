<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\InputSanitizationService;
use App\Facades\EventFacade;
use App\Services\EventImageService;
use App\Services\EventValidationService;
use App\Services\FacilityBookingService;
use App\Services\TicketService;
use App\Services\PaymentService;

/**
 * Event Service Provider
 * 
 * Registers all event-related services and the EventFacade
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Register individual services as singletons
        $this->app->singleton(EventImageService::class);
        $this->app->singleton(EventValidationService::class);
        $this->app->singleton(FacilityBookingService::class);
        $this->app->singleton(TicketService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(InputSanitizationService::class);

        // Register the EventFacade
        $this->app->singleton(EventFacade::class, function ($app) {
            return new EventFacade(
                $app->make(EventImageService::class),
                $app->make(EventValidationService::class),
                $app->make(FacilityBookingService::class),
                $app->make(TicketService::class),
                $app->make(PaymentService::class)
            );
        });
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        //
    }
}