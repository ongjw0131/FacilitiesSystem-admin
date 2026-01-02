<?php

use App\Http\Controllers\Api\FacilityAvailabilityController;
use App\Http\Controllers\Api\FacilityVenueController;
use App\Http\Controllers\Api\EventProxyController;
use App\Http\Controllers\Api\SocietyProxyController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\TicketPurchaseProxyController;
use App\Http\Controllers\Api\EventTicketProxyController;
use App\Http\Controllers\Api\EventPresidentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'web'])->group(function () {

    Route::post('/facilities/availability', [FacilityAvailabilityController::class, 'checkAvailability']);
    Route::post('/facility-bookings/pending', [FacilityAvailabilityController::class, 'createPendingBooking']);
    Route::post('/facility-bookings/{eventId}/approve', [FacilityAvailabilityController::class, 'approveBooking']);
    Route::post('/facility-bookings/{eventId}/reject', [FacilityAvailabilityController::class, 'rejectBooking']);
    Route::get('/facilities/{facility}/venues', [FacilityVenueController::class, 'index']);

    // Event API endpoints
    Route::prefix('events')->group(function () {
        Route::get('/', [EventProxyController::class, 'index']);
        Route::post('/', [EventProxyController::class, 'store']);
        Route::get('/{event}', [EventProxyController::class, 'show']);
        Route::put('/{event}', [EventProxyController::class, 'update']);
        Route::post('/{event}/join', [EventProxyController::class, 'join']);
        Route::get('/{event}/tickets', [EventProxyController::class, 'getTickets']);
        Route::post('/{event}/purchase', [EventProxyController::class, 'purchaseTickets']);
        Route::delete('/{event}', [EventProxyController::class, 'destroy']);
        Route::post('/{event}/restore', [EventProxyController::class, 'restore']);
        Route::delete('/{event}/permanent', [EventProxyController::class, 'permanentDelete']);
        Route::get('/{event}/attendees', [EventProxyController::class, 'getAttendees']);
    });
// Society API endpoints
Route::get('/societies', [SocietyProxyController::class, 'index']);
Route::get('/societies/search', [SocietyProxyController::class, 'search']);
Route::get('/societies/all', [SocietyProxyController::class, 'allSociety']);
Route::get('/societies/banned', [SocietyProxyController::class, 'banned']);
Route::get('/societies/presidents/all', [SocietyProxyController::class, 'allPresidents']);
Route::get('/societies/{societyID}', [SocietyProxyController::class, 'show']);
Route::put('/societies/{societyID}', [SocietyProxyController::class, 'update']);
Route::post('/societies/{societyID}/ban', [SocietyProxyController::class, 'ban'])->middleware('auth');
Route::put('/societies/{societyID}/president', [SocietyProxyController::class, 'changePresident']);
Route::get('/societies/{societyID}/members', [SocietyProxyController::class, 'members']);
Route::get('/societies/{societyID}/society-users', [SocietyProxyController::class, 'societyUsers']);
Route::get('/societies/{societyID}/members-count', [SocietyProxyController::class, 'memberCount']);
Route::get('/societies/{societyID}/president', [SocietyProxyController::class, 'president']);
Route::get('/societies/{societyID}/committee', [SocietyProxyController::class, 'committee']);
Route::get('/societies/{societyID}/user/{userID}/position', [SocietyProxyController::class, 'userPosition']);
Route::get('/societies/{societyID}/user/{userID}/is-member', [SocietyProxyController::class, 'isMember']);
Route::get('/societies/{societyID}/user/{userID}/can-post', [SocietyProxyController::class, 'canPost']);

    // Notifications API
    Route::middleware('auth')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'apiIndex']);
    });

    // User API endpoints
    Route::get('/search-users', [SearchController::class, 'users'])->name('search-users');
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Ticket orders - get total revenue
    Route::get('/ticket-orders/revenue/total', [TicketPurchaseProxyController::class, 'getTotalRevenue']);
});
Route::get('/events/{event}/is-president', [EventPresidentController::class, 'isPresident']);

Route::prefix('events')->group(function () {
    Route::get('/{event}/tickets/active', [EventTicketProxyController::class, 'active']);
    Route::get('/{event}/tickets/all', [EventTicketProxyController::class, 'index']);
});

Route::get('/event-tickets/{ticket}', [EventTicketProxyController::class, 'show']);

// purchase ticket API endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events/{event}/tickets/available', [TicketPurchaseProxyController::class, 'availableTickets']);
    Route::post('/ticket-orders', [TicketPurchaseProxyController::class, 'createOrder']);
    Route::post('/ticket-orders/{order}/checkout', [TicketPurchaseProxyController::class, 'checkout']);
    Route::post('/ticket-orders/{order}/success', [TicketPurchaseProxyController::class, 'paymentSuccess']);
    Route::middleware('auth:sanctum')->get('/profile/ticket-orders',[TicketPurchaseProxyController::class, 'myTicketOrders']);
});
