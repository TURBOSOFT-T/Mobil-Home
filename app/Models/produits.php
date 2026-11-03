<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class produits extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produits';

    protected $fillable = [
        'user_id',
        'nom',
        'slug',
        'description',
        'meta_description',
        'type_habitation',
        'prix',
        'photo',
        'photos',
        'category_id',
        'sous_categorie_id',
        'famille_id',
        'id_shop',
        'statut',
    
        'top',
        'active',
        'new',
        'is_new',
        'caracteristiques',
        'avec_caracteristique',
        'avec_commission',
        'commission',
    ];

    protected $casts = [
        'photos' => 'json',
        'caracteristiques' => 'array',
        'prix' => 'decimal:2',
        'commission' => 'decimal:2',
    ];

    protected $appends = [
        'final_price',
        'total_sold',
        'slug',
    ];

    // Relation avec l'utilisateur tiers (propriétaire/agent)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendus()
    {
        return $this->hasMany(contenu_commande::class, 'id_produit');
    }

    public function getPrice()
    {
        return $this->prix;
    }

    public function getFinalPriceAttribute()
    {
        return $this->getPrice();
    }

    public function getTotalSoldAttribute()
    {
        return $this->vendus()->sum('quantite') ?? 0;
    }

    public function getTotalSoldAttribute1()
    {
        return $this->vendus()->count();
    }

    public function vues()
    {
        return $this->hasMany(views::class, 'id_produit');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getSlugAttribute($value)
    {
        return $value ?: Str::slug($this->nom . '-' . $this->id);
    }

    public function sous_categories()
    {
        return $this->belongsTo(Sous_category::class, 'sous_categorie_id');
    }

    public function familles()
    {
        return $this->belongsTo(Famille::class, 'famille_id', 'id');
    }
}