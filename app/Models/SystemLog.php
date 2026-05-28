<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'category',
        'title',
        'message',
        'context',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'is_read',
        'resolved_at',
    ];

    protected $casts = [
        'context' => 'array',
        'is_read' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Constantes pour les niveaux de log
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';

    // Constantes pour les catégories
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_SECURITY = 'security';
    const CATEGORY_PERFORMANCE = 'performance';
    const CATEGORY_DATABASE = 'database';
    const CATEGORY_API = 'api';
    const CATEGORY_USER = 'user';

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les logs non lus
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour les logs critiques
     */
    public function scopeCritical($query)
    {
        return $query->where('level', self::LEVEL_CRITICAL);
    }

    /**
     * Scope pour les logs d'erreur
     */
    public function scopeErrors($query)
    {
        return $query->where('level', self::LEVEL_ERROR);
    }

    /**
     * Scope pour les logs non résolus
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Marquer comme résolu
     */
    public function markAsResolved()
    {
        $this->update([
            'resolved_at' => now(),
            'is_read' => true
        ]);
    }

    /**
     * Vérifier si le log est résolu
     */
    public function isResolved(): bool
    {
        return !is_null($this->resolved_at);
    }

    /**
     * Créer un log système
     */
    public static function createLog($level, $category, $title, $message, $context = [])
    {
        return static::create([
            'level' => $level,
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'context' => $context,
            'user_id' => Auth::check() ? Auth::id() : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl()
        ]);
    }

    /**
     * Log d'information
     */
    public static function info($category, $title, $message, $context = [])
    {
        return static::createLog(self::LEVEL_INFO, $category, $title, $message, $context);
    }

    /**
     * Log d'avertissement
     */
    public static function warning($category, $title, $message, $context = [])
    {
        return static::createLog(self::LEVEL_WARNING, $category, $title, $message, $context);
    }

    /**
     * Log d'erreur
     */
    public static function error($category, $title, $message, $context = [])
    {
        return static::createLog(self::LEVEL_ERROR, $category, $title, $message, $context);
    }

    /**
     * Log critique
     */
    public static function critical($category, $title, $message, $context = [])
    {
        return static::createLog(self::LEVEL_CRITICAL, $category, $title, $message, $context);
    }

    /**
     * Obtenir les statistiques des logs
     */
    public static function getStats($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'total' => static::where('created_at', '>=', $startDate)->count(),
            'unread' => static::where('created_at', '>=', $startDate)->unread()->count(),
            'critical' => static::where('created_at', '>=', $startDate)->critical()->count(),
            'errors' => static::where('created_at', '>=', $startDate)->errors()->count(),
            'unresolved' => static::where('created_at', '>=', $startDate)->unresolved()->count(),
            'by_level' => static::select('level', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('level')
                ->pluck('count', 'level')
                ->toArray(),
            'by_category' => static::select('category', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];
    }

    /**
     * Nettoyer les anciens logs
     */
    public static function cleanup($daysToKeep = 90)
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return static::where('created_at', '<', $cutoffDate)
            ->where('level', '!=', self::LEVEL_CRITICAL)
            ->delete();
    }

    /**
     * Obtenir les logs récents par niveau de priorité
     */
    public static function getRecentByPriority($limit = 50)
    {
        return static::select('*', DB::raw('
            CASE 
                WHEN level = "critical" THEN 1
                WHEN level = "error" THEN 2
                WHEN level = "warning" THEN 3
                WHEN level = "info" THEN 4
                ELSE 5
            END as priority_order
        '))
        ->orderBy('priority_order')
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
    }
}