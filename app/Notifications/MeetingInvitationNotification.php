<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Meeting $meeting) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Add 'sms' if driver configured
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('meeting.join', $this->meeting->room_id);

        return (new MailMessage)
            ->subject("Board Meeting Invitation: {$this->meeting->title}")
            ->greeting("Hello " . ($notifiable->first_name ?? 'there') . ",")
            ->line("You have been invited to a private board meeting: **{$this->meeting->title}**.")
            ->line("Scheduled for: " . $this->meeting->start_time->format('F j, Y, g:i a'))
            ->action('Join Meeting', $url)
            ->line('Thank you for using YIT Learning!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'url' => route('meeting.join', $this->meeting->room_id),
            'type' => 'board_meeting',
        ];
    }

    /**
     * Placeholder for SMS (Twilio/Vonage)
     */
    public function toSms($notifiable)
    {
        $url = route('meeting.join', $this->meeting->room_id);
        return "YIT Learning: You're invited to '{$this->meeting->title}'. Join at: {$url}";
    }
}
