<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\{Category, Service, config, Marque, produits, favoris, Testimonial};

class HomeComposer
{

  public function compose(View $view)
  {
    $view->with([
      //  'categories' => Category::has('produits')->take(8)->get(),
      //  'categories' => Category::has('produits')->take(8)->get(), // Pour la catégorie page
      'categories' => Category::has('produits')->get(), // Pour la catégorie page
     
      'produitshome' => produits::select('*')->latest()->take(12)->get(),
      'testimonials' => Testimonial::orderBy('created_at', 'desc')
        ->where('active', '1')
        ->limit(20)->get(),
      //  'marques' =>Marque::has('produits')->take(6)->get(), /// Pour le home page
      //   'brands' =>Marque::has('produits')->get(), // Pour le  sop page
      // 'categorie'=>Category::all(),
      'configs' => config::all(),
      'config' => config::all(),
      'services' => Service::select('*')->get(),
      'favoris' => Favoris::where('id_produit', '!=', null)
        ->where('id_user', auth()->id())->get(),

   

      // 🆕 NOUVEAUTÉS
      'nouveautes' => produits::where('is_new', 1)
        ->latest()
        ->take(30)
        ->get(),

      // 🕒 LES PLUS VUS
      'mostViewed' => produits::withCount('vues')
        ->orderByDesc('vues_count')
        ->having('vues_count', '>', 0)
        ->take(30)
        ->get(),

  
    ]);
  }
}
