<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AuthService
{
    /**
     * Create or update a user from OAuth data.
     */
    public function updateOrCreateFromOAuth(array $oauthData): User
    {
        return DB::transaction(function () use ($oauthData) {
            $user = User::where('email', $oauthData['email'])->first();

            $userData = [
                'email' => $oauthData['email'],
                'username' => $oauthData['username'] ?? $this->generateUsername($oauthData['email']),
                'first_name' => $oauthData['first_name'] ?? null,
                'last_name' => $oauthData['last_name'] ?? null,
                'avatar_url' => $oauthData['avatar_url'] ?? null,
                'oauth_provider' => $oauthData['provider'],
                'oauth_provider_id' => $oauthData['provider_id'],
                'oauth_access_token' => encrypt($oauthData['access_token']),
                'oauth_refresh_token' => isset($oauthData['refresh_token']) ? encrypt($oauthData['refresh_token']) : null,
                'oauth_token_expires_at' => isset($oauthData['expires_at']) ? Carbon::parse($oauthData['expires_at']) : null,
                'email_verified' => true,
            ];

            if ($user) {
                $user->update($userData);
            } else {
                $user = User::create($userData);
                // Assign regular role by default if no role is assigned
                if ($user->roles()->count() === 0) {
                    $user->roles()->attach(Role::where('name', 'regular')->first()->id);
                }
            }

            return $user;
        });
    }

    /**
     * Generate a unique OTP code and send via Email or SMS (Arkasel).
     */
    public function sendOtp(string $identifier, string $purpose = 'login'): OtpVerification
    {
        $otpCode = (string) rand(100000, 999999);
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $otp = OtpVerification::create([
            'email' => $isEmail ? $identifier : null,
            'phone_number' => ! $isEmail ? $identifier : null,
            'otp_code' => $otpCode,
            'purpose' => $purpose,
            'expires_at' => Carbon::now()->addMinutes(15),
            'delivery_method' => $isEmail ? 'email' : 'sms',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($isEmail) {
            $this->sendEmailOtp($identifier, $otpCode);
        } else {
            $this->sendSmsOtp($identifier, $otpCode);
        }

        return $otp;
    }

    /**
     * Send OTP via Email.
     */
    private function sendEmailOtp(string $email, string $code): void
    {
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($code));
    }

    /**
     * Send OTP via Arkasel SMS.
     */
    private function sendSmsOtp(string $phone, string $code): void
    {
        $apiKey = config('services.arkasel.api_key');
        $senderId = config('services.arkasel.sender_id');
        $message = "Your YIT verification code is: {$code}. Valid for 15 mins.";

        try {
            $response = Http::get('https://sms.arkasel.com/sms/api', [
                'action' => 'send-sms',
                'api_key' => $apiKey,
                'to' => $phone,
                'from' => $senderId,
                'sms' => $message,
            ]);

            if ($response->failed()) {
                \Log::error("Arkasel SMS failed for {$phone}: " . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error("Arkasel SMS exception: " . $e->getMessage());
        }
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(string $identifier, string $code, string $purpose = 'login'): ?User
    {
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $otp = OtpVerification::where($isEmail ? 'email' : 'phone_number', $identifier)
            ->where('otp_code', $code)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $otp) {
            return null;
        }

        $otp->update(['is_used' => true]);

        $user = User::where($isEmail ? 'email' : 'phone_number', $identifier)->first();

        if (! $user) {
            // User needs to register
            return null;
        }

        return $user;
    }

    /**
     * Generate a unique username from email.
     */
    private function generateUsername(string $email): string
    {
        $base = explode('@', $email)[0];
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base.$counter;
            $counter++;
        }

        return $username;
    }
}
