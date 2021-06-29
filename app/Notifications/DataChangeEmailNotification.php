<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DataChangeEmailNotification extends Notification
{
    use Queueable;

    public function __construct($data)
    {
        $this->data = $data;
        $this->ticket = $data['ticket'];
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return $this->getMessage();
    }

    public function getMessage()
    {
        return (new MailMessage)
            ->subject($this->data['action'])
            ->greeting('Hola estimado '.$this->ticket->author_name)
            ->line($this->data['action'])
            ->line("Customer: ")
            ->line("Ticket: ".$this->ticket->title)
            ->line("Contenido: ".Str::limit($this->ticket->content, 200))
            ->action('Ver ticket', route('admin.tickets.show', $this->ticket->id))
            ->line('Gracias')
            ->line(' Team' . config('app.name'))
            ->salutation(' ');
    }
}
