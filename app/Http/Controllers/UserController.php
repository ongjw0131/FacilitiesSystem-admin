<?php

namespace App\Http\Controllers;

use App\Factories\UserFactory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
        ]);

        // Call factory to create user
        $user = UserFactory::create($validated);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        // Redirect to login page
        return redirect()->route('user.login')->with('success', 'Account created! Please verify your email and log in.');
    }

    public function login(Request $request)
    {
        // Validate request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is deleted
            $user = Auth::user();
            if ($user->is_deleted) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'This account has been deleted and is no longer active.',
                ])->onlyInput('email');
            }

            // Check if email is verified
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.',
                ])->onlyInput('email');
            }

            // Check if user is admin and redirect to admin page
            if ($user->role === 'admin') {
                return redirect()->route('user.admin')->with('success', 'Welcome back, ' . $user->name . '!');
            }

            return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Verify the hash
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('user.login')->with('error', 'Invalid verification link.');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            return redirect()->route('user.login')->with('success', 'Email verified successfully! You can now log in.');
        }

        return redirect()->route('user.login')->with('info', 'Email already verified.');
    }

    public function requestPasswordReset(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        // Generate reset token
        $resetToken = Str::random(64);
        $expiresAt = Carbon::now()->addHours(1);

        // Save reset token to database
        $user->update([
            'password_reset_token' => $resetToken,
            'password_reset_expires_at' => $expiresAt,
        ]);

        // Send password reset email with signed URL
        $resetUrl = route('password.reset.form', ['token' => $resetToken], true);
        
        // Send via Mail facade (using configured SMTP)
        Mail::raw("Click the link below to reset your password. This link expires in 1 hour.\n\n$resetUrl", function($message) use ($user) {
            $message->to($user->email)->subject('Password Reset Link - UniEvent');
        });

        return back()->with('success', 'Password reset link sent to your email.');
    }

    public function showPasswordResetForm($token)
    {
        $user = User::where('password_reset_token', $token)->first();

        // Verify token exists and hasn't expired
        if (!$user || Carbon::now()->isAfter($user->password_reset_expires_at)) {
            return redirect()->route('user.login')->with('error', 'Password reset link has expired or is invalid.');
        }

        return view('user.reset_password', ['token' => $token, 'email' => $user->email]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
        ]);

        $user = User::where('email', $validated['email'])
                    ->where('password_reset_token', $validated['token'])
                    ->first();

        // Verify token and user exist
        if (!$user) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Verify token hasn't expired
        if (Carbon::now()->isAfter($user->password_reset_expires_at)) {
            return back()->withErrors(['email' => 'Password reset link has expired.']);
        }

        // Update password and clear reset token
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
        ]);

        return redirect()->route('user.login')->with('success', 'Password reset successfully! You can now log in with your new password.');
    }

    public function adminUserList()
    {
        $users = User::where('is_deleted', 0)->get();
        return view('user.admin_user', ['users' => $users]);
    }

    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';
        $validated['status'] = 'active';

        $user = User::create($validated);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Admin user created successfully. Verification email has been sent.',
            'user' => $user
        ], 201);
    }

    /**
     * Display single user profile by ID or all users with filtering
     * Purpose: Get single user profile or admin dashboard/management
     */
    public function show(Request $request, $id = null)
    {
        // If ID is provided, return single user profile
        if ($id) {
            try {
                $user = User::findOrFail($id);
                return response()->json([
                    'status' => 'success',
                    'data' => $user
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
        }

        // Otherwise, get all users with filtering and sorting (admin list)
        // Get query parameters for filtering and sorting
        $role = $request->query('role');
        $status = $request->query('status');
        $search = $request->query('search');
        $sortBy = $request->query('sortBy', 'created_at');
        $sortOrder = $request->query('sortOrder', 'desc');

        // Build query
        $query = User::query();

        // Filter by role if provided
        if ($role) {
            $query->where('role', $role);
        }

        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        // Search by name or email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        if (in_array($sortBy, ['name', 'email', 'role', 'status', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get paginated results (15 per page)
        $users = $query->paginate(15);

        // Get statistics for dashboard
        $statistics = [
            'total_users' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'student_count' => User::where('role', 'student')->count(),
            'society_head_count' => User::where('role', 'society_head')->count(),
            'active_count' => User::where('status', 'active')->count(),
            'inactive_count' => User::where('status', 'inactive')->count(),
            'pending_count' => User::where('status', 'pending')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $users,
            'statistics' => $statistics,
            'filters' => [
                'role' => $role,
                'status' => $status,
                'search' => $search,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
            ]
        ]);
    }

    /**
     * Delete a user by marking is_deleted as 1
     * @param int $id
     */
    public function deleteUser($id)
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Find the user
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Update is_deleted to 1
        $user->update(['is_deleted' => 1]);

        return redirect()->route('user.admin_user')->with('success', 'User deleted successfully.');
    }
}

