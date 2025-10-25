<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ['nom', 'description', 'slug'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $dates = ['deleted_at'];

    // === Relations ===

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Scope pour les articles publiés de cette catégorie
    public function articlesPublished()
    {
        return $this->hasMany(Article::class, 'category_id')->where('is_published', true);
    }

    // === Mutateurs ===

    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = ucfirst(trim($value));
        
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // === Boot pour événements ===

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->nom);
            }
        });
    }
}
