<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(env('PUBLICATION_NOTIFICATION_SUBJECT'))
            ->line(env('PUBLICATION_NOTIFICATION_BODY'))
            ->line('Un nouvel article a été publié dans la catégorie ' . $this->article->category->name)
            ->line('Titre : ' . $this->article->titre)
            ->action('Lire l\'article', route('articles.show', $this->article->id))
            ->line('Merci d\'utiliser notre plateforme!');
    }

    public function toArray($notifiable)
    {
        return [
            'article_id' => $this->article->id,
            'titre' => $this->article->titre,
            'category' => $this->article->category->name,
        ];
    }
}
