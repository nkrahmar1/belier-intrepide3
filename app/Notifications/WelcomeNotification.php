<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(env('NOTIFICATION_EMAIL_SUBJECT'))
            ->greeting('Bienvenue ' . $notifiable->name . ' !')
            ->line(env('NOTIFICATION_EMAIL_BODY'))
            ->line('Voici vos informations de compte :')
            ->line('- Nom : ' . $notifiable->name)
            ->line('- Email : ' . $notifiable->email)
            ->line('- Date d\'inscription : ' . $notifiable->created_at->format('d/m/Y'))
            ->action('Accéder à mon compte', route('dashboard'))
            ->line('N\'hésitez pas à explorer notre plateforme et à découvrir nos articles.')
            ->line('Merci de nous faire confiance !');
    }
}
