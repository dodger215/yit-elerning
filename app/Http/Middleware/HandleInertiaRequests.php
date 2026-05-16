<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'username' => $request->user()->username,
                    'email' => $request->user()->email,
                    'first_name' => $request->user()->first_name,
                    'last_name' => $request->user()->last_name,
                    'roles' => $request->user()->roles->pluck('name'),
                ] : null,
                'active_role' => session('active_role') ?? ($request->user() ? $request->user()->roles->first()?->name : null),
                'active_public_meetings' => \App\Models\Meeting::where('meeting_type', 'public')
                    ->where('status', 'active')
                    ->where('start_time', '>=', now()->subMinutes(5))
                    ->get(['room_id', 'title']),
            ],
            'navigation' => $request->user() ? $this->getNavigation($request) : [],
        ];
    }

    /**
     * Get role-based navigation items.
     */
    protected function getNavigation(Request $request): array
    {
        $user = $request->user();
        $activeRole = session('active_role') ?? $user->roles->first()?->name;
        $config = config('navigation');
        $nav = [];

        // If we have an active role, prioritize it
        if ($activeRole && isset($config['roles'][$activeRole])) {
            $nav = $config['roles'][$activeRole];
        } else {
            // Fallback to merging all roles if no active role is set (shouldn't happen with our flow)
            foreach ($user->roles as $role) {
                if (isset($config['roles'][$role->name])) {
                    $nav = array_merge($nav, $config['roles'][$role->name]);
                }
            }
        }

        // Add common items
        $nav = array_merge($nav, $config['common'] ?? []);

        // Unique by label to avoid duplicates
        return array_values(collect($nav)->unique('label')->toArray());
    }
}
