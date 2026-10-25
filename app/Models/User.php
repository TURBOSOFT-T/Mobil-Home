<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles,SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'avatar',
        'password',
        'phone',
         'solde',
        'points'
    ];

       public function peutPasserLaCommande($totalCommande)
    {
        return $this->solde >= $totalCommande;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function avatar()
    {
        if (is_null($this->avatar)) {
            return "/icons/default-no-profile-pic.webp";
        } else {
            return Storage::url($this->avatar);
        }
    }


    public function commandes()
    {
        return $this->hasMany(commandes::class);
    }

        public function commercial()
    {
        return $this->hasMany(commandes::class, 'commercial_id');
    }
 public function seller()
    {
        return $this->hasMany(commandes::class, 'seller_id');
    }

     public function contenus()
    {
        return $this->hasMany(contenu_commande::class, 'commercial_id');
    }

      public function coupons()
    {
        return $this->hasMany(Coupon::class, 'commercial_id');
    }


    public function favoris()
    {
        return $this->hasMany(favoris::class, 'id_user');
    }


    public function getIsAdminAttribute()
    {
        $admins = User::where('role', 'admin')
            ->get();

       // return $this->role()->where('id', 1)->exists();
       return $this ->$admins;
    }
    public function reviews()
    {
   return $this->hasMany('App\Review');
    }
    public function collection()
    {
        return User::all();
    }
}
