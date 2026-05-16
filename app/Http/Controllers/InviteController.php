<?php

namespace App\Http\Controllers;

use App\Mail\UserInviteMail;
use App\Models\MagicLink;
use App\Services\InviteService;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class InviteController extends Controller
{
    protected InviteService $inviteService;

    protected SocialAuthService $socialAuthService;

    public function __construct(InviteService $inviteService, SocialAuthService $socialAuthService)
    {
        $this->inviteService = $inviteService;
        $this->socialAuthService = $socialAuthService;
    }

    /**
     * List all pending invites for the supervisor.
     */
    public function index()
    {
        $this->authorize('invite-user');

        $invites = MagicLink::where('is_used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Invites/Index', [
            'invites' => $invites,
        ]);
    }

    /**
     * Show invite verification page or redirect to OAuth.
     */
    public function verify(string $token)
    {
        $inviteData = $this->inviteService->verifyInvite($token);

        if (! $inviteData) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid or expired invite.']);
        }

        // Store invite data in session for the OAuth callback to use
        session(['pending_invite' => $inviteData]);

        // Redirect to Google OAuth to finalize registration/login
        return $this->socialAuthService->redirectToProvider('google');
    }

    /**
     * Supervisor sends an invite.
     */
    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:supervisor,instructor,editor',
        ]);

        $this->authorize('invite-user'); // Should define this permission or gate

        $inviteLink = $this->inviteService->generateInvite(
            $request->email,
            $request->role,
            $request->user()
        );

        Mail::to($request->email)->send(new UserInviteMail($inviteLink, $request->role));

        // In a real app, send email with $inviteLink
        return back()->with('status', 'Invite link generated: '.$inviteLink);
    }
}
