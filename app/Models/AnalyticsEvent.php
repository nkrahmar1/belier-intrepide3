<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AnalyticsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'category',
        'label',
        'value',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
        'custom_data',
    ];

    protected $casts = [
        'custom_data' => 'array',
        'value' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour une période donnée
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Obtenir les événements les plus fréquents
     */
    public static function getTopEvents($limit = 10, $days = 30)
    {
        return static::select('action', 'category', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('action', 'category')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques par jour
     */
    public static function getDailyStats($days = 30)
    {
        return static::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_events'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users'),
                DB::raw('COUNT(DISTINCT session_id) as unique_sessions')
            )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Obtenir les événements par catégorie
     */
    public static function getEventsByCategory($days = 30)
    {
        return static::select('category', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Traquer un événement
     */
    public static function track($action, $category, $label = null, $value = null, $customData = [])
    {
        $event = [
            'user_id' => auth::check() ? auth::id() : null,
            'action' => $action,
            'category' => $category,
            'label' => $label,
            'value' => $value,
            'session_id' => session()->getId(),
            'ip_address' => request::ip(),
            'user_agent' => request::userAgent(),
            'referrer' => request::header('referer'),
            'custom_data' => $customData,
        ];

        return static::create($event);
    }

    /**
     * Obtenir le taux de conversion
     */
    public static function getConversionRate($fromAction, $toAction, $days = 30)
    {
        $startDate = now()->subDays($days);
        
        $fromCount = static::where('action', $fromAction)
            ->where('created_at', '>=', $startDate)
            ->distinct('session_id')
            ->count('session_id');
            
        $toCount = static::where('action', $toAction)
            ->where('created_at', '>=', $startDate)
            ->distinct('session_id')
            ->count('session_id');

        return $fromCount > 0 ? ($toCount / $fromCount) * 100 : 0;
    }

    /**
     * Obtenir les pages les plus visitées
     */
    public static function getTopPages($limit = 10, $days = 30)
    {
        return static::select('label', DB::raw('COUNT(*) as views'))
            ->where('action', 'page_view')
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('label')
            ->groupBy('label')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }
}