<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
        'download_type',
        'ip_address',
        'user_agent',
        'file_size',
        'cost'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'file_size' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'article
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Enregistrer un téléchargement
     */
    public static function recordDownload($userId, $articleId, $downloadType = 'free', $cost = 0)
    {
        return self::create([
            'user_id' => $userId,
            'article_id' => $articleId,
            'download_type' => $downloadType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'file_size' => 0, // Sera mis à jour avec la taille réelle
            'cost' => $cost
        ]);
    }

    /**
     * Statistiques de téléchargement pour un utilisateur
     */
    public static function getUserStats($userId)
    {
        return [
            'total_downloads' => self::where('user_id', $userId)->count(),
            'free_downloads' => self::where('user_id', $userId)->where('download_type', 'free')->count(),
            'premium_downloads' => self::where('user_id', $userId)->where('download_type', 'premium')->count(),
            'purchased_downloads' => self::where('user_id', $userId)->where('download_type', 'purchase')->count(),
            'total_cost' => self::where('user_id', $userId)->sum('cost'),
        ];
    }
}