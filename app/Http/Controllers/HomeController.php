<?php

namespace App\Http\Controllers;

//require './vendor/autoload.php';


use App\Models\{commandes,config, User,produits, Category, Service,Marque, Testimonial};
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
       
       $produits = produits::select('*')->latest()->take(20)->get();
    
       $configs= config::all();
       $banners = Banners::select("titre","sous_titre","image")->get();

       $testimonials = Testimonial::orderBy('created_at', 'desc')
       ->where('active', '1')
       ->limit(20)->get();

       $services = Service::all();
      return view('front.index', compact('produits','configs','banners','services','testimonials'));

    }
     
   

    public function shop(Request $request){
        $key = $request->input("key", null);
        $id_categorie = $request->get("id_categorie", null);
        $price_range = $request->input("price_range", null);
        $ordre_affichage = $request->input("ordre_affichage", null);
        
        $produits = produits::query();

        if(!is_null($key)){
            $produits->where('nom', 'like', '%'.$key.'%')
            ->Orwhere('description', 'like', '%'.$key.'%');
        }
        if(!is_null($id_categorie)){
            $produits->where('category_id', $id_categorie);
        }
        if(!is_null($price_range)){
            $produits->whereBetween('prix', explode('-', $price_range));
        }

        if($request->sort_by == 'lowest_price'){
            $$produits = produits::orderBy('prix','asc')->get();
        }
        if($request->sort_by == 'highest_price'){
            $$produits = produits::orderBy('prix','desc')->get();
        }
        if(!is_null($ordre_affichage)){
            //if is not null will be Asc or Desc
            $produits->orderBy('prix', $ordre_affichage ? 'asc' : 'desc');
        }

        $produits = $produits->paginate(16);
        
        $total_produit = produits::count();
        $max_prix = produits::max('prix');
     
      $categories =Category::has('produits')->get();
   
        $configs= config::all();
        return view('front.shop.index',compact('produits', 'categories', 'configs','key','total_produit','max_prix','ordre_affichage'));
    }

   

    ///////////Login///////////////////////////////////////////////////
    public function login()
    {
        return view('auth.login');
    }
   

    public function Not_Autorized(){
        $template = templates::where('meta_error',true)->first();
        $meta = $template->meta ?? "";
        return view("front.Not_Autorized", compact("meta"));
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

    public function print($id)
{
    $commande = commandes::with('contenus.produit')->findOrFail($id);
    $pdf = Pdf::loadView('pdf.commande', compact('commande'));
    return $pdf->stream('recu_commande_'.$commande->reference.'.pdf');
}


    public function print_bordereau(Request $request, $ids)
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


    public function inscription()
    {
        return view('front.inscription');
    }


    public function confirmation(Request $request){
        $produit = null;
        if ($request->session()->has('produit')) {
            $produit = Session::get('produit');
        }
        return view('front.confirmation',compact("produit"));
    }


}
