<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


class ChatbotMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'type',
        'metadata',
        'read_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (optionnelle pour les invités)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Invité',
            'email' => 'guest@example.com'
        ]);
    }
    
    /**
     * Vérifier si c'est un utilisateur invité
     */
    public function isGuest(): bool
    {
        return str_starts_with($this->user_id, 'guest_') || 
               (isset($this->metadata['is_guest']) && $this->metadata['is_guest']);
    }
    
    /**
     * Obtenir le nom d'affichage de l'utilisateur
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isGuest()) {
            return 'Invité #' . substr($this->user_id, -4);
        }
        
        return $this->user->name ?? 'Utilisateur';
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les messages de l'utilisateur
     */
    public function scopeUserMessages($query)
    {
        return $query->where('type', 'user');
    }

    /**
     * Scope pour les messages du bot
     */
    public function scopeBotMessages($query)
    {
        return $query->where('type', 'bot');
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Vérifier si le message est lu
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Obtenir les messages d'une conversation
     */
    public static function getConversation($userId, $limit = 50)
    {
        return static::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }
}