<?php

namespace App\Notifications;

use App\Models\hutang;
use App\Models\piutang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PeringatanBayar extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $hutang;
    public function __construct(hutang $hutang)
    {
        $this->hutang = $hutang;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' =>"Assalamualikum " . $this->hutang->name . " Jatuh Tempo Kurang Dari 1 Hari",
        ];
    }
}
