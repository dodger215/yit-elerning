<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Redirect to OAuth provider.
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)
            ->redirectUrl(config("services.{$provider}.redirect"))
            ->redirect();
    }

    /**
     * Handle callback from OAuth provider.
     */
    public function handleProviderCallback(string $provider): User
    {
        $socialUser = Socialite::driver($provider)
            ->redirectUrl(config("services.{$provider}.redirect"))
            ->user();

        $oauthData = [
            'email' => $socialUser->getEmail(),
            'username' => $socialUser->getNickname(),
            'first_name' => $this->getFirstName($socialUser->getName()),
            'last_name' => $this->getLastName($socialUser->getName()),
            'avatar_url' => $socialUser->getAvatar(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'access_token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
            'expires_at' => property_exists($socialUser, 'expiresIn') ? now()->addSeconds($socialUser->expiresIn) : null,
        ];

        $user = \App\Models\User::where('email', $oauthData['email'])->first();

        if (!$user) {
            session(['oauth_pending_register' => $oauthData]);
            throw new \Exception('USER_NOT_FOUND');
        }

        return $this->authService->updateOrCreateFromOAuth($oauthData);
    }

    private function getFirstName(string $fullName): string
    {
        $parts = explode(' ', $fullName);

        return $parts[0] ?? '';
    }

    private function getLastName(string $fullName): string
    {
        $parts = explode(' ', $fullName);
        array_shift($parts);

        return implode(' ', $parts);
    }
}
