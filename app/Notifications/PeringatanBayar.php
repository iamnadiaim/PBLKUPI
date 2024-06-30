<?php

namespace App\Notifications;

use App\Models\Hutang;
use App\Models\Piutang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PeringatanBayar extends Notification
{
    use Queueable;

    public $hutang;
    public $piutang;

    /**
     * Create a new notification instance.
     *
     * @param Hutang|null $hutang
     * @param Piutang|null $piutang
     */
    public function __construct(Hutang $hutang = null, Piutang $piutang = null)
    {
        $this->hutang = $hutang;
        $this->piutang = $piutang;
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
        if ($this->hutang) {
            return [
                'message' => "Assalamualikum, hutang" . $this->hutang->name . " Jatuh Tempo Kurang Dari 1 Hari",
            ];
        }

        if ($this->piutang) {
            return [
                'message' => "Assalamualikum, piutang " . $this->piutang->name . " Jatuh Tempo Kurang Dari 1 Hari",
            ];
        }

        return [];
    }
}
