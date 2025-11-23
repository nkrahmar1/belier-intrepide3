<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'extrait',
        'category_id',
        'user_id',
        'image',
        'document_path',
        'file_original_name',
        'file_size',
        'storage_size',
        'is_published',
        'is_premium',
        'is_featured',
        'article_type',
        'content_quality',
        'downloads_count',
        'unit_price',
        'free_download_limit',
        'published_at',
        'featured_on_homepage',
        'homepage_featured_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_premium' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'featured_on_homepage' => 'boolean',
        'homepage_featured_at' => 'datetime',
        'storage_size' => 'integer',
        'unit_price' => 'decimal:2',
        'free_download_limit' => 'integer',
    ];

    protected $dates = [
        'published_at', 'created_at', 'updated_at'
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Mutators
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getExtraitAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->contenu), 150);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return null;

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size > 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // Méthodes utilitaires
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function canBeDownloadedBy(User $user = null)
    {
        // Article gratuit : accessible à tous
        if (!$this->is_premium) {
            return true;
        }
        
        // Article premium : nécessite un abonnement actif
        return $user && $user->hasActiveSubscription();
    }

    /**
     * Vérifier si un utilisateur peut télécharger selon le type d'article
     */
    public function canUserDownload($user = null)
    {
        // Utilisateur non connecté
        if (!$user) {
            return $this->isGratuitType() ? ['can_download' => true, 'reason' => 'free'] : 
                   ['can_download' => false, 'reason' => 'login_required'];
        }

        // Types d'articles gratuits
        if ($this->isGratuitType()) {
            // Vérifier les limites de téléchargement gratuit
            if ($this->free_download_limit > 0) {
                $userDownloads = $this->getUserDownloadCount($user);
                if ($userDownloads >= $this->free_download_limit) {
                    return ['can_download' => false, 'reason' => 'limit_exceeded'];
                }
            }
            return ['can_download' => true, 'reason' => 'free'];
        }

        // Types d'articles premium
        if ($this->isPremiumType()) {
            if ($user->hasActiveSubscription()) {
                return ['can_download' => true, 'reason' => 'premium_subscription'];
            }
            
            // Possibilité d'achat unitaire
            if ($this->unit_price > 0) {
                return ['can_download' => false, 'reason' => 'purchase_required', 'price' => $this->unit_price];
            }
            
            return ['can_download' => false, 'reason' => 'subscription_required'];
        }

        return ['can_download' => false, 'reason' => 'unknown'];
    }

    /**
     * Types d'articles gratuits
     */
    public function isGratuitType()
    {
        return in_array($this->article_type, ['breve', 'communique', 'tutoriel']);
    }

    /**
     * Types d'articles premium
     */
    public function isPremiumType()
    {
        return in_array($this->article_type, ['analyse', 'enquete', 'interview', 'explicatif']) 
               || $this->is_premium;
    }

    /**
     * Obtenir le nombre de téléchargements d'un utilisateur pour cet article
     */
    public function getUserDownloadCount($user)
    {
        return \App\Models\ArticleDownload::where('user_id', $user->id)
                                         ->where('article_id', $this->id)
                                         ->count();
    }

    /**
     * Relation avec les téléchargements
     */
    public function downloads()
    {
        return $this->hasMany(\App\Models\ArticleDownload::class);
    }

    /**
     * Obtenir la classification complète de l'article
     */
    public function getArticleClassification()
    {
        $classifications = [
            'breve' => ['name' => 'Brève', 'access' => 'Gratuit', 'quality' => 'Court'],
            'communique' => ['name' => 'Communiqué', 'access' => 'Gratuit', 'quality' => 'Court'],
            'analyse' => ['name' => 'Analyse', 'access' => 'Premium', 'quality' => 'Profond'],
            'enquete' => ['name' => 'Enquête', 'access' => 'Premium', 'quality' => 'Profond'],
            'interview' => ['name' => 'Interview', 'access' => 'Premium', 'quality' => 'Exclusif'],
            'tutoriel' => ['name' => 'Tutoriel', 'access' => 'Gratuit/Premium', 'quality' => 'Moyen'],
            'explicatif' => ['name' => 'Explicatif', 'access' => 'Gratuit/Premium', 'quality' => 'Moyen'],
        ];

        return $classifications[$this->article_type] ?? ['name' => 'Standard', 'access' => 'Gratuit', 'quality' => 'Court'];
    }

    /**
     * Calculer l'espace de stockage utilisé par l'article
     */
    public function getStorageUsage()
    {
        $size = $this->storage_size ?: $this->file_size ?: 0;
        
        if ($size >= 1073741824) { // GB
            return round($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) { // MB
            return round($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) { // KB
            return round($size / 1024, 2) . ' KB';
        }
        
        return $size . ' B';
    }

    public function getDocumentUrl()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }

    // Méthode incrementDownloadsCount supprimée - colonne downloads_count non existante
    // Les téléchargements sont trackés via les logs
}
