<?php

namespace App\Http\Controllers;

use App\Models\commandes;

use App\Models\{produits, Category, favoris as ModelsFavoris};

use App\Http\Traits\ListGouvernorats;


class MyAccountController extends Controller
{
    use ListGouvernorats;




    public function comptes()
    {

        $commandes = commandes::where('user_id', auth()->id())->get();
        return view('front.comptes.commandes', compact('commandes'));
    }

    public function favories()
    {

        return view('front.comptes.favories');
    }

    public function profile()
    {
        return view('front.comptes.profile');
    }

    public function account()
    {

        //$commandes= commandes::where('user_id', auth()->id() )->get();
        $commandes = commandes::where('user_id', auth()->id())->latest()->paginate(10);
        $favoris = ModelsFavoris::where('id_user', auth()->id())->latest()->paginate(10);
        // $totalCommand = $commandes->count();
        $totalCommand = commandes::where('user_id', auth()->id())
            ->WhereIn('statut', ['livrée', 'payée'])
            ->count();
        $totalFavoris = ModelsFavoris::where('id_user', auth()->id())->count();
        $commandesEnCours = commandes::where('user_id', auth()->id())
            ->whereIn('statut', ['attente', 'traitement', 'En cours livraison', 'planification'])
            ->count();



        return view('front.comptes.account', compact('commandes', 'totalCommand', 'totalFavoris', 'favoris', 'commandesEnCours'));
    }
}
