<?php

namespace App\Http\Controllers;

use App\Models\MagicLink;
use App\Models\OtpVerification;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    protected AuthService $authService;

    protected SocialAuthService $socialAuthService;

    public function __construct(AuthService $authService, SocialAuthService $socialAuthService)
    {
        $this->authService = $authService;
        $this->socialAuthService = $socialAuthService;
    }

    /**
     * Show login page.
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Send OTP to user email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $this->authService->sendOtp($request->email);

        return back()->with('status', 'OTP sent to your email.');
    }

    /**
     * Verify OTP and login.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = $this->authService->verifyOtp($request->email, $request->code);

        if (! $user) {
            // Check if it's a new user (OTP verified but no user record)
            $otp = OtpVerification::where('email', $request->email)
                ->where('is_used', true)
                ->latest()
                ->first();

            if ($otp) {
                return redirect()->route('register', ['email' => $request->email]);
            }

            return back()->withErrors(['code' => 'Invalid or expired OTP.']);
        }

        Auth::login($user);

        if ($user->roles()->count() > 1) {
            return redirect()->route('auth.role-selection');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Redirect to Google.
     */
    public function redirectToGoogle()
    {
        return $this->socialAuthService->redirectToProvider('google');
    }

    /**
     * Handle Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $user = $this->socialAuthService->handleProviderCallback('google');

            // Handle pending invite if exists
            if (session()->has('pending_invite')) {
                $inviteData = session('pending_invite');

                // Ensure email matches the invite
                if ($user->email === $inviteData['email']) {
                    $role = Role::where('name', $inviteData['role'])->first();
                    if ($role) {
                        $user->roles()->syncWithoutDetaching([$role->id]);
                    }

                    // Mark invite as used
                    MagicLink::where('token', $inviteData['token'])->update(['is_used' => true]);
                }

                session()->forget('pending_invite');
            }

            Auth::login($user);

            if ($user->roles()->count() > 1) {
                return redirect()->route('auth.role-selection');
            }

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed.']);
        }
    }

    /**
     * Show role selection page for multi-role users.
     */
    public function showRoleSelection()
    {
        $user = Auth::user();

        if (! $user || $user->roles()->count() <= 1) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/RoleSelection', [
            'roles' => $user->roles()->get(),
        ]);
    }

    /**
     * Store the selected role in session.
     */
    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = Auth::user();

        if (! $user->hasRole($request->role)) {
            abort(403);
        }

        session(['active_role' => $request->role]);

        return redirect()->route('dashboard');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_verified' => true, // Assuming OTP was already verified
        ]);

        $user->roles()->attach(Role::where('name', 'regular')->first()->id);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
