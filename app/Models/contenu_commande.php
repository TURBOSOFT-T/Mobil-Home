<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contenu_commande extends Model
{
    use HasFactory;
protected $table ='contenu_commandes';
    protected $fillable = [
        'id_produit',
        'id_commande',
    
        'quantite',
        'total_gain_points',
        'prix_unitaire',
        'prix',
        'quantity',
        'benefice',
        'table_id',
        'commercial_id'
    ];

    public function produits(){
        return $this->belongsTo(produits::class ,'id_produit')->withDefault();
    }
    public function produit(){
        return $this->belongsTo(produits::class ,'id_produit')->withDefault();
    }

     public function commercial(){
        return $this->belongsTo(User::class ,'commercial_id')->withDefault();
    }
 public function table(){
        return $this->belongsTo(Table::class ,'table_id')->withDefault();
    }
    public function commandes(){
        return $this->belongsTo(commandes::class ,'id_commande');
    }
   
  


    public static function boot()
{
    parent::boot();

    // 1) Si on essaie d'enregistrer un contenu dont le produit n'existe plus → supprimer automatiquement
    static::saving(function ($contenu) {
        if (!$contenu->produit()->exists()) {
            $contenu->delete();
        }
    });

    // 2) Quand un contenu est supprimé → vérifier si la commande n'est pas vide
    static::deleted(function ($contenu) {
        if ($contenu->commandes && $contenu->commandes->contenus()->count() === 0) {
            $contenu->commandes->delete();
        }
    });
}


}
