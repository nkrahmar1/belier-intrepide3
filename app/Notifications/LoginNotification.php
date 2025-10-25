<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    protected $loginTime;
    protected $ipAddress;
    protected $userAgent;

    /**
     * Crée une nouvelle instance de notification.
     */
    public function __construct($ipAddress = null, $userAgent = null)
    {
        $this->loginTime = Carbon::now();
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Détermine les canaux de livraison de la notification.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Représentation de l’e-mail de notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(env('NOTIFICATION_EMAIL_SUBJECT'))
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line(env('NOTIFICATION_EMAIL_BODY'))
            ->line('**Détails de la connexion :**')
            ->line('• **Date et heure :** ' . $this->loginTime->format('d/m/Y à H:i:s'))
            ->line('• **Adresse IP :** ' . ($this->ipAddress ?? 'Non disponible'))
            ->line('• **Navigateur :** ' . $this->getUserAgentInfo())
            ->line('Si ce n\'était pas vous, veuillez changer votre mot de passe immédiatement.')
            ->action('Accéder à votre compte', url('/dashboard'))
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Donne une version simplifiée du user agent.
     */
    private function getUserAgentInfo()
    {
        if (!$this->userAgent) {
            return 'Non disponible';
        }

        if (strpos($this->userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($this->userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($this->userAgent, 'Safari') !== false && strpos($this->userAgent, 'Chrome') === false) {
            return 'Safari';
        } elseif (strpos($this->userAgent, 'Edge') !== false) {
            return 'Microsoft Edge';
        }

        return 'Autre navigateur';
    }

    /**
     * Représentation tableau de la notification (facultatif).
     */
    public function toArray($notifiable)
    {
        return [
            'login_time' => $this->loginTime,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }
}
