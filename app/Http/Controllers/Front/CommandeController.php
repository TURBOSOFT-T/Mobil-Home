<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;



use App\Http\Requests\commandes\CommandesRequest;
use Illuminate\Http\Request;
use App\Models\{commandes, produits, Coupon, clients, contenu_commande, config, Historique_points, notifications, Transaction, Transport, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
//use Illuminate\Support\Facade\Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderMail;
use App\Mail\FirstOrder;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Mail\Mailable;
use App\Services\PayUService\Exception;
use Illuminate\Validation\ValidationException;

use App\Http\Traits\ListGouvernorats;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{

  public $cart;
  use ListGouvernorats;


  /*  public function __construct()
  {
    $this->middleware('auth');
  } */



  public function commander()
  {
    $configs = config::firstOrFail();
    // $paniers_session = session('cart');

    $paniers_session = session('cart', []);




    foreach ($paniers_session as $session) {
      $produit = produits::find($session['id_produit']);
      if ($produit) {
        $paniers[] = [
          'nom' => $produit->nom,
          'id_produit' => $produit->id,
          'photo' => $produit->photo,
          'quantite' => $session['quantite'],
          'prix' => $produit->getPrice(),
          'total' => $session['quantite'] * $produit->getPrice(),
        ];
        if (session()->has('coupon')) {
          $total += $session['quantite'] * $produit->getPrice() - session('coupon')['value'];
        } else {
          $total += $session['quantite'] * $produit->getPrice();
        }

        //  dd($total);
      }
    }

    $gouvernorats = $this->getListGouvernorat();
  
    return view('front.commandes.checkout', compact('configs', 'paniers', 'total', 'gouvernorats', 'transports'));
  }
public function confirmOrder(Request $request)
{
    // 1. Validation stricte des données d'entrée
    $data = $request->validate([
        'nom'            => ['nullable', 'string', 'max:255'],
        'prenom'         => ['nullable', 'string', 'max:255'],
        'email'          => ['nullable', 'email', 'max:255'],
        'phone'          => ['required', 'string', 'max:50'],
        'coupon'         => ['nullable', 'numeric'],
        'buy_with_point' => ['nullable', 'boolean'],
        'mode'           => ['nullable', 'string', 'in:espèce,orange money,momo,solde,points'],
        'transport_id'   => ['nullable', 'exists:transports,id'],
        'type_commande'  => ['required', 'string', 'in:livraison,boutique'],
        'adresse'        => ['nullable', 'string', 'max:500'],
        'gouvernorat'    => ['nullable', 'string', 'max:255'],
        'pays'           => ['nullable', 'string', 'max:255'],
        'note'           => ['nullable', 'string', 'max:1000'],
    ]);

    // 2. Vérification du panier en session
    $paniers_session = session('cart', []);
    if (empty($paniers_session)) {
        return back()->withErrors(['cart' => 'Le panier est vide !']);
    }

  
    // 4. Gestion de l'utilisateur / Client
    $connecte = Auth::user();
    $userId = null;
    $clientId = null;

    if (Auth::check()) {
        $userId = Auth::id();
    } else {
        // Bloquer l'usage du solde/points si non connecté
        if (in_array($data['mode'], ['solde', 'points'])) {
            return redirect()->back()->with('swal_error', 'Vous devez être connecté pour utiliser ce mode de paiement.');
        }

        $client = clients::updateOrCreate(
            ['phone' => $request->phone],
            [
                'nom'         => $request->nom,
            //    'prenom'      => $request->prenom,
              //  'email'       => $request->email,
                'adresse'     => $request->adresse,
                'gouvernorat' => $request->gouvernorat,
            ]
        );
        $clientId = $client->id;
    }

    // 5. Calcul du montant brut de la commande
    $totalCommande = 0;
    foreach ($paniers_session as $item) {
        $produit = produits::find($item['id_produit']);
        if ($produit) {
            $totalCommande += $produit->getPrice() * $item['quantite'];
        }
    }

    // Application des réductions et frais de port
  
    $totalCommande += $transport->frais ?? 0;

 


    // 7. Création de la commande
    $reference = 'SWB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    
    $order = new commandes([
        'user_id'        => $userId,
        'client_id'      => $clientId,
        'reference'      => $reference,
        'nom'            => $request->nom,
        'prenom'         => $request->prenom,
        'email'          => $request->email,
        'adresse'        => $request->adresse,
        'phone'          => $request->phone,
        'pays'           => $request->pays,
        'note'           => $request->note,
        'gouvernorat'    => $request->gouvernorat,
        'mode'           => $data['mode'],
      
        'type_commande'  => $request->type_commande,
        'transport_id'   => $transport->id ?? null,
        'frais'          => $transport->frais ?? 0,
        'montant_total'  => $totalCommande,
    ]);

    if (in_array($data['mode'], ['solde', 'points'])) {
        $order->etat = 'confirmé';
        $order->statut = 'traitement';
        if ($data['mode'] === 'solde') {
            $order->solde_utilise = $totalCommande;
        } else {
            $order->points_utilise = $totalCommande;
        }
    }

    $order->save();

    // 8. Traitement des lignes du panier (Contenu commande & déstockage)
    $total_gain_points = 0;
    $historiquePoints = [];

    foreach ($paniers_session as $session) {
        $produit = produits::find($session['id_produit']);
        if ($produit) {
            $quantite = $session['quantite'];
            $gainPointsProduit = ($produit->points ?? 0) * $quantite;

            $contenu = new contenu_commande();
            $contenu->id_commande       = $order->id;
            $contenu->id_produit         = $produit->id;
            $contenu->prix_unitaire      = $produit->getPrice();
            $contenu->quantite           = $quantite;
            $contenu->benefice           = ($produit->getPrice() - $produit->prix_achat) * $quantite;
            $contenu->total_gain_points  = $gainPointsProduit;
            $contenu->save();

            $total_gain_points += $gainPointsProduit;

            $historiquePoints[] = [
                'produit_id' => $produit->id,
                'montant'    => $gainPointsProduit,
            ];

            $produit->diminuer_stock($quantite);
        }
    }

   
    // 10. Nettoyage de la session
    session()->forget(['cart', 'coupon']);

    // 11. Génération de la notification admin
    $notification = new notifications();
    $notification->url     = route('details_commande', ['id' => $order->id]);
    $notification->titre   = "Nouvelle commande.";
    $notification->message = "Commande passée par " . $order->nom;
    $notification->type    = "commande";
    $notification->save();


    return redirect()->route('thank-you');
}







  public function sendOrderConfirmationMail($order)
  {

    Mail::to($order->email)->send(new OrderMail($order));
  }

  public function index(Request $request)
  {

    return view('front.commandes.thankyou');
  }
}
