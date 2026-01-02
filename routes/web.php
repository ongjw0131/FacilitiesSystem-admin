<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\SocietyFollowerController;
use App\Http\Controllers\SocietyAdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\Admin\FacilityBookingController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\TicketOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketPurchaseController;
use App\Http\Controllers\ProfileTicketController;
use App\Http\Controllers\PresidentEventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\SoapController;
use App\Models\User;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // If admin is logged in, redirect to admin dashboard
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('user.admin')->with('info', 'Welcome to Admin Dashboard');
    }
    // For public and students, show home
    return view('home');
});

// Public routes - redirects admin to dashboard
Route::get('login', function () {
    // If already logged in, redirect to appropriate dashboard
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('user.admin');
        }
        return redirect('/');
    }
    return view('user/login');
})->name('login');

Route::post('login', [UserController::class, 'login']);

Route::get('user/login', function () {
    // If already logged in, redirect to appropriate dashboard
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('user.admin');
        }
        return redirect('/');
    }
    return view('user/login');
})->name('user.login');

Route::get('user/signup', function () {
    return view('user/signup');
})->name('user.signup');

Route::get('user/forgotPassword', function () {
    return view('user/forgotPassword');
})->name('user.forgotPassword');

// Password reset routes
Route::post('/password/request', [UserController::class, 'requestPasswordReset'])->name('password.request');
Route::get('/password/reset/{token}', [UserController::class, 'showPasswordResetForm'])->name('password.reset.form');
Route::post('/password/reset', [UserController::class, 'resetPassword'])->name('password.reset');

Route::get('/signup', function () {
    return view('user/signup');
})->name('signup');

Route::middleware('auth')->group(function () {
    Route::get('user/admin', function () {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        $users = User::all();
        return view('user/admin', ['users' => $users]);
    })->name('user.admin');

    Route::get('user/admin_user', [UserController::class, 'adminUserList'])->middleware('admin.only')->name('user.admin_user');
    Route::post('user/create-admin', [UserController::class, 'createAdmin'])->name('user.createAdmin');
    Route::delete('user/{id}/delete', [UserController::class, 'deleteUser'])->name('user.deleteUser');

    // Admin pages - Society Management
    Route::get('user/admin/society', function () {
        return view('user.admin_society');
    })->middleware('admin.only')->name('user.admin_society');

    // Admin pages - Event Oversight
    Route::get('user/admin/event', function () {
        $events = \App\Models\Event::all();
        $filter = request('filter');
        
        if ($filter === 'active') {
            $events = $events->where('is_deleted', 0);
        } elseif ($filter === 'deleted') {
            $events = $events->where('is_deleted', 1);
        }
        
        return view('user.admin_event', ['events' => $events]);
    })->middleware('admin.only')->name('user.admin_event');

    // Admin pages - Reports & Analytics
    Route::get('user/admin/report', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('user.admin_report');
    })->name('user.admin_report');

    // Admin pages - System Settings
    Route::get('user/admin/settings', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('admin.settings');
    })->name('user.admin_settings');
});

Route::post('/signup', [UserController::class, 'store'])->name('user.store');

Route::get('/email/verify', function () {
    return view('auth.verify_email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Logged out successfully!');
})->middleware('auth')->name('logout');
Route::get('society/{societyID}/edit', [SocietyAdminController::class, 'edit'])->middleware(['auth', 'admin.only'])->name('society.edit');
// Student-only routes (admins cannot access these pages)
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/president/events', [PresidentEventController::class, 'index'])->name('president.events.index');
    
    // Notification routes (student-only)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{notificationID}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});
 
Route::get('society/create', [SocietyController::class, 'create'])->name('society.create');
Route::post('society', [SocietyController::class, 'store'])->name('society.store');
Route::get('society/joined', [SocietyController::class, 'joined'])->name('society.joined');

// Society routes - student-only (admins cannot access)
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get('society', [SocietyController::class, 'index'])->name('society.index');
    Route::get('society/{societyID}', [SocietyController::class, 'show'])->name('society.show');
});

// Society routes - student-only (admins cannot access)
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get('society/joined', [SocietyController::class, 'joined'])->name('society.joined');
    Route::post('society/{societyID}/join', [SocietyController::class, 'join'])->name('society.join');
    Route::post('society/{societyID}/direct-join', [SocietyController::class, 'directJoin'])->name('society.directJoin');
    Route::post('society/{societyID}/request-join', [SocietyController::class, 'requestJoin'])->name('society.requestJoin');
    Route::post('society/{societyID}/member/leave', [SocietyController::class, 'leaveSociety'])->name('society.leaveSociety');
    Route::post('society/{societyID}/leave', [SocietyController::class, 'leaveSociety'])->name('society.leave');
    Route::post('society/{societyID}/follow', [SocietyFollowerController::class, 'follow'])->name('society.follow');
    Route::post('society/{societyID}/unfollow', [SocietyFollowerController::class, 'unfollow'])->name('society.unfollow');
    Route::get('society/{societyID}/is-following', [SocietyFollowerController::class, 'isFollowing'])->name('society.isFollowing');
});

// Society routes - student-only (admins cannot access)
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get('society/{societyID}/settings', [SocietyController::class, 'settings'])->name('society.settings');
    Route::post('society/{societyID}/settings', [SocietyController::class, 'updateSettings'])->name('society.updateSettings');
    Route::get('society/{societyID}/people', [SocietyController::class, 'people'])->name('society.people');
    Route::post('society/{societyID}/member/{userID}/promote', [SocietyController::class, 'promoteToCommittee'])->name('society.promoteCommittee');
    Route::post('society/{societyID}/member/{userID}/pass-president', [SocietyController::class, 'passPresidentRole'])->name('society.passPresident');
    Route::post('society/{societyID}/member/{userID}/downgrade', [SocietyController::class, 'downgradeCommittee'])->name('society.downgradeCommittee');
    Route::post('society/{societyID}/member/{userID}/kick', [SocietyController::class, 'kickMember'])->name('society.kickMember');
    Route::post('society/{societyID}/accept-request/{userID}', [SocietyController::class, 'acceptJoinRequest'])->name('society.acceptRequest');
    Route::post('society/{societyID}/decline-request/{userID}', [SocietyController::class, 'declineJoinRequest'])->name('society.declineRequest');
    Route::post('society/{societyID}/remove-declined/{userID}', [SocietyController::class, 'removeDeclinedRequest'])->name('society.removeDeclined');
});

Route::post('society/{societyID}/post', [PostController::class, 'store'])->name('society.post.store')->middleware('auth');
Route::get('society/{societyID}/post/{postID}', [PostController::class, 'show'])->name('society.post.show');
Route::delete('society/{societyID}/post/{postID}', [PostController::class, 'destroy'])->name('society.post.destroy')->middleware('auth');

// Comment routes
Route::post('society/{societyID}/post/{postID}/comment', [CommentController::class, 'store'])->name('comment.store')->middleware('auth');
Route::get('society/{societyID}/post/{postID}/comments', [CommentController::class, 'getComments'])->name('comment.list');
Route::put('society/{societyID}/post/{postID}/comment/{commentID}', [CommentController::class, 'update'])->name('comment.update')->middleware('auth');
Route::delete('society/{societyID}/post/{postID}/comment/{commentID}', [CommentController::class, 'destroy'])->name('comment.destroy')->middleware('auth');

// File serving routes (public access)
Route::get('file/{fileID}/download', [FileDownloadController::class, 'download'])->name('file.download');
Route::get('file/{fileID}/preview', [FileDownloadController::class, 'preview'])->name('file.preview');
Route::get('file/{fileID}/view', [FileDownloadController::class, 'view'])->name('file.view');

// Facilities routes
Route::prefix('admin')->middleware('auth')->group(function () {
    // Facility Management routes
    Route::resource('facilities', FacilityController::class)->names([
        'index' => 'admin.facilities.index',
        'create' => 'admin.facilities.create',
        'store' => 'admin.facilities.store',
        'show' => 'admin.facilities.show',
        'edit' => 'admin.facilities.edit',
        'update' => 'admin.facilities.update',
        'destroy' => 'admin.facilities.destroy',
    ]);
    
    // Facility Bookings routes
    Route::resource('bookings', FacilityBookingController::class)->names([
        'index' => 'admin.bookings.index',
        'create' => 'admin.bookings.create',
        'store' => 'admin.bookings.store',
        'show' => 'admin.bookings.show',
        'edit' => 'admin.bookings.edit',
        'update' => 'admin.bookings.update',
        'destroy' => 'admin.bookings.destroy',
    ]);
});

// Event Ticket routes (student-only - admins manage from admin panel)
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/tickets',[EventTicketController::class, 'index'])->name('event-tickets.index');
    Route::get('/events/{event}/tickets/create',[EventTicketController::class, 'create'])->name('event-tickets.create');
    Route::post('/event-tickets',[EventTicketController::class, 'store'])->name('event-tickets.store');
    Route::patch('/event-tickets/{ticket}/status',[EventTicketController::class, 'updateStatus'])->name('event-tickets.update-status');
    Route::get('/event-tickets/{ticket}/edit',[EventTicketController::class, 'edit'])->name('event-tickets.edit');
    Route::put('/event-tickets/{ticket}',[EventTicketController::class, 'update'])->name('event-tickets.update');
    Route::delete('/event-tickets/{ticket}',[EventTicketController::class, 'destroy'])->name('event-tickets.destroy');
});

// Ticket Order routes (student-only)
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/orders',[TicketOrderController::class, 'index'])->name('ticket-orders.index');
    Route::get('/ticket-orders/{order}/edit',[TicketOrderController::class, 'edit'])->name('ticket-orders.edit');
    Route::put('/ticket-orders/{order}',[TicketOrderController::class, 'update'])->name('ticket-orders.update');
    Route::delete('/ticket-orders/{order}',[TicketOrderController::class, 'destroy'])->name('ticket-orders.destroy');
});

// Ticket Purchase routes (authenticated but student-only - admins manage from admin panel)
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get('/events/{event}/buy-tickets',[TicketPurchaseController::class, 'showTickets'])->name('tickets.buy.list');
    Route::get('/tickets/{ticket}/quantity',[TicketPurchaseController::class, 'selectQuantity'])->name('tickets.buy.quantity');
    Route::post('/ticket-orders',[TicketPurchaseController::class, 'store'])->name('ticket-orders.store');
    Route::post('/ticket-orders/checkout', [TicketPurchaseController::class, 'checkout'])->name('ticket-orders.checkout');
    Route::get('/ticket-orders/{order}/success', [TicketPurchaseController::class, 'paymentSuccess'])->name('ticket-orders.success');
    Route::get('/my-tickets', [TicketPurchaseController::class, 'myTickets'])->name('user.myTickets');
});

/*
|--------------------------------------------------------------------------
| EVENT ROUTES - CAREFULLY ORDERED TO AVOID CONFLICTS
|--------------------------------------------------------------------------
*/

// PUBLIC Event Routes (No Auth Required) - MUST BE FIRST
Route::get('event', [EventController::class, 'index'])->name('event.index');
Route::get('events', [EventController::class, 'index'])->name('events.index');

// AUTHENTICATED Event Routes - Specific paths before wildcards (student-only for user creation)
Route::middleware(['auth'])->group(function () {
    // User event creation (MUST be before {event} routes)
    Route::get('event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('event', [EventController::class, 'storeUser'])->name('event.storeUser');
    Route::post('events', [EventController::class, 'store'])->name('events.store');
});

// ADMIN Event Routes (MUST come before general {event} routes)
Route::middleware('auth')->prefix('events')->group(function () {
    // Admin list and create - specific paths
    Route::get('user/admin_event', [EventController::class, 'adminIndex'])->name('events.admin.index');
    Route::get('admin/create', [EventController::class, 'adminCreate'])->name('events.admin.create');
    Route::post('admin', [EventController::class, 'adminStore'])->name('events.admin.store');
    
    // Event-specific admin routes
    Route::get('{event}/admin', [EventController::class, 'adminShow'])->name('events.admin.show');
    Route::get('{event}/admin/edit', [EventController::class, 'adminEdit'])->name('events.admin.edit');
    Route::patch('{event}/admin', [EventController::class, 'adminUpdate'])->name('events.admin.update');
    Route::delete('{event}/admin', [EventController::class, 'adminDestroy'])->name('events.admin.destroy');
    
    // Restore and permanent delete
    Route::post('{event}/restore', [EventController::class, 'adminRestore'])->name('events.admin.restore');
    Route::delete('{event}/permanent', [EventController::class, 'adminPermanentDelete'])->name('events.admin.permanentDelete');
    
    // Ticket purchase routes
    Route::get('{event}/tickets/purchase', [EventController::class, 'showPurchaseForm'])->name('events.tickets.purchase');
    Route::post('{event}/tickets/purchase', [EventController::class, 'processPurchase'])->name('events.tickets.purchase.submit');
});

// Event join (student-only)
Route::middleware(['auth'])->group(function () {
    Route::post('events/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::get('events', [EventController::class, 'index'])->name('events.index');
});

// AUTHENTICATED Event Routes - User edit/update/delete (student-only)
Route::middleware(['auth'])->group(function () {
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

// Event Edit Route - Student-only
Route::middleware(['auth'])->group(function () {
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
});

// Admin Event Edit Routes (admin access)
Route::middleware(['auth'])->group(function () {
    Route::get('events/{event}/edit-admin', function() {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return app(EventController::class)->edit(request('event'));
    })->name('events.edit.admin');
});

// PUBLIC Event Show - MUST BE LAST (it's a wildcard)

Route::get('event/{event}', [EventController::class, 'show'])->name('event.show');
Route::get('eventSo/{event}', [EventController::class, 'showSo'])->name('event.societyShow');
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
Route::middleware(['auth', 'student.only'])->group(function () {
    Route::get(
        '/profile/my-tickets',
        [ProfileTicketController::class, 'index']
    )->name('user.viewMyTickets');
});
// SOAP Server Route
Route::any('/public/soap/society-server.php', function () {
    require_once base_path('vendor/econea/nusoap/src/nusoap.php');
    require_once base_path('public/soap/soap-functions.php');
});