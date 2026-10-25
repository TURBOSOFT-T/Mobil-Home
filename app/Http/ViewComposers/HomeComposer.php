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
      'searchproducts' => produits::select('*')->latest()->take(5)->get(),
      'lastproduits' => produits::orderBy('created_at', 'desc')->take(12)->get(),
      'marques' => Marque::has('produits')->get(),
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

      'produitsPromo' => produits::whereNotNull('id_promotion')
        ->latest()
        ->take(6)
        ->get(),

        
      'produitsPromos' => produits::whereNotNull('id_promotion')
        ->latest()
        ->take(20)
        ->get(),

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

      // 🏆 BEST SELLERS
      'bestSellers' => produits::withSum('vendus', 'quantite')
        ->orderByDesc('vendus_sum_quantite')
        ->having('vendus_sum_quantite', '>', 0)
        ->take(30)
        ->get(),
    ]);
  }
}
