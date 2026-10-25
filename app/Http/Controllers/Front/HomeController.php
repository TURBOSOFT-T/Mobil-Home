<?php


namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

//require './vendor/autoload.php';


use App\Models\{commandes, config, User, produits, Category, Service, Marque, Testimonial, views, Sous_category, Famille};
use App\Models\Banners;
use App\Models\templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Front\SearchRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{



    public function home(Request $request)
    {




        // $produits = produits::select('*')->latest()->take(16)->get();
        $categoryProducts = DB::table('produits')
            ->select('*')
            ->where('category_id', 'category_id')
            ->get();


        $banners = Banners::select("titre", "sous_titre", "image")->get();


        return view('front.index', compact('banners',  'categoryProducts'));
    }

    public function detailsServices($id, $slug)
    {
        $service = Service::findOrFail($id);

        return view('front.services.details', compact('service', 'services'));
    }

    public function shop(Request $request)
    {
        $query = produits::query();
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        if ($min_price !== null && $max_price !== null) {
            $query->whereBetween('prix', [$min_price, $max_price]);
        }
        // CATEGORY
        if ($request->id_categorie) {
            $query->where('category_id', $request->id_categorie);
        }

        // Marque
        if ($request->marque_id) {
            $query->where('marque_id', $request->marque_id);
        }
        // SOUS-CATEGORY
        if ($request->sous_categorie_id) {
            $query->where('sous_categorie_id', $request->sous_categorie_id);
        }
        // FAMILLE
        if ($request->famille_id) {
            $query->where('famille_id', $request->famille_id);
        }


        // SEARCH
        if ($request->key) {
            $key = $request->key;

            $query->where(function ($q) use ($key) {
                $q->where('nom', 'like', "%$key%")
                    ->orWhere('description', 'like', "%$key%");
            });
        }

        // HIGHLIGHT FILTER

        // 🔥 FILTERS
        $highlight = $request->input("highlight");
        if ($highlight === 'promo') {
            $query->whereNotNull('id_promotion');
        }

        if ($highlight === 'is_new') {
            $query->where('is_new', 1);
        }

        if ($highlight === 'best') {
            $query->withSum('vendus', 'quantite')
                ->orderByDesc('vendus_sum_quantite')
                ->having('vendus_sum_quantite', '>', 0);
        }

        if ($highlight === 'most_viewed') {
            $query->withCount('vues')
                ->orderByDesc('vues_count')
                ->having('vues_count', '>', 0);
        }
       
        if ($min_price !== null || $max_price !== null) {
            $query->where(function ($q) use ($min_price, $max_price) {
                if ($min_price !== null && $max_price !== null) {
                    $q->whereBetween('prix', [$min_price, $max_price]);
                } elseif ($min_price !== null) {
                    $q->where('prix', '>=', $min_price);
                } elseif ($max_price !== null) {
                    $q->where('prix', '<=', $max_price);
                }
            });
        }



        $produits = $query->paginate(24);

        return view('front.shop.index', [
            'produits' => $produits,
            'categories' => Category::with('produits')->get(),
            'marques' => Marque::has('produits')->get(),
            'sous_categories' => Sous_category::has('produits')->get(),
            'familles' => Famille::has('produits')->get(),
            'highlight' => $request->highlight,
            'sort_by' => $request->sort_by,
        ]);
    }


    public function resetShop()
    {
        session()->forget([
            'key',
            'id_categorie',
            'highlight',
            'sort_by',
            'min_price',
            'max_price',
            'marque_id',
            'sous_categorie_id',
            'famille_id'
        ]);

        return redirect()->route('shop');
    }


    public function details($id)
    {
        $produit = produits::findOrFail($id);

        $produitsSimilaires = produits::where('category_id', $produit->category_id)
            ->where('id', '!=', $produit->id)
            ->latest()
            ->take(8)
            ->get();
        $ip = request()->ip();

        $alreadyViewed = views::where('id_produit', $produit->id)
            ->where('ip_address', $ip)
            ->exists();

        if (!$alreadyViewed) {
            views::create([
                'id_produit' => $produit->id,
                'ip_address' => $ip,
            ]);

            //$produit->increment('vues');
        }

        ///dd($produitsSimilaires);
        return view('front.shop.details', compact(
            'produit',

            'produitsSimilaires'
        ));
    }

    public function products(Request $request, $id)
    {
        $key = $request->input("key");
        $price_range = $request->input("price_range");
        $ordre_affichage = $request->input("ordre_affichage");
        $highlight = $request->input("highlight");

        $current_category = Category::findOrFail($id);

        // ✅ IMPORTANT : on part de la relation MAIS EN QUERY BUILDER
        $produits = $current_category->produits()->newQuery();

        // 🔍 SEARCH
        if ($key) {
            $produits->where(function ($q) use ($key) {
                $q->where('nom', 'like', "%$key%")
                    ->orWhere('description', 'like', "%$key%")
                    ->orWhere('tags', 'like', "%$key%");
            });
        }

        // 💰 PRICE
        if ($price_range) {
            $produits->whereBetween('prix', explode('-', $price_range));
        }

        // 📊 SORT
        if ($ordre_affichage) {
            $produits->orderBy('prix', $ordre_affichage);
        }

        // 🔥 FILTERS
        if ($highlight === 'promo') {
            $produits->whereNotNull('id_promotion');
        }

        if ($highlight === 'new') {
            $produits->where('new', 1);
        }

        if ($highlight === 'best') {
            $produits->withSum('vendus', 'quantite')
                ->orderByDesc('vendus_sum_quantite');
        }

        $produits = $produits->paginate(24);

        $categories = Category::withCount('produits')->get();

        return view('front.shop.index', compact(
            'current_category',
            'categories',
            'produits',
            'key',
            'ordre_affichage',
            'highlight'
        ));
    }



    ///////////Login///////////////////////////////////////////////////
    public function login()
    {
        return view('auth.login');
    }


    public function print_commande($id)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            abort('404');
        }

        $pdf = PDF::loadView('pdf.commande', compact('commande'));
        return $pdf->download("Facture-#" . $commande->id . ".pdf");
    }


    public function print_bordereau(Request $request)
    {
        $ids = json_decode($request->get('ids'));
        $pdf = PDF::loadView('pdf.bordereau', compact("ids"));
        return $pdf->download("bordereau.pdf");
    }



    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }

    public function verify()
    {
        return view('auth.verify-2fa');
    }

    public function registerverify()
    {
        return view('auth.verify-register-2fa');
    }


    public function inscription()
    {
        return view('front.inscription');
    }


    public function confirmation(Request $request)
    {
        $produit = null;
        if ($request->session()->has('produit')) {
            $produit = Session::get('produit');
        }
        return view('front.confirmation', compact("produit"));
    }
}
