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
        'is_published',
        'is_premium',
        'is_featured',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_premium' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
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

    public function canBeDownloadedBy(User $user)
    {
        if (!$this->is_premium) {
            return true;
        }
        return $user && $user->hasActiveSubscription();
    }

    public function getDocumentUrl()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }

    // Méthode incrementDownloadsCount supprimée - colonne downloads_count non existante
    // Les téléchargements sont trackés via les logs
}
