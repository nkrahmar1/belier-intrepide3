<?php

namespace App\Models;

//use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'content',
        'is_read',
        'status',
        'phone'
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour les messages par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true, 'status' => 'lu']);
    }

    /**
     * Obtenir le nom complet avec l'email
     */
    public function getFullContactAttribute()
    {
        return $this->name . ' (' . $this->email . ')';
    }
}
