<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\produits;
use App\Models\configs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class panier_client extends Controller
{
    
    public function count_panier()
    {
        // Vérifier si la session 'panier' existe, sinon initialiser une session vide
        if (!session()->has('cart')) {
            session(['cart' => []]);
        }

        // Récupérer le panier de la session
        $panier_temporaire = session('cart');
        $total = count($panier_temporaire);
        $montant_total = 0;
        $Html = "";
        $produits = [];

        foreach ($panier_temporaire as $data) {
            $produit = produits::select('id','photo','prix','nom')->find($data['id_produit']);
            if ($produit) {
                $produits[] = [
                    'id_produit' => $produit->id,
                    'nom' => $produit->nom,
                    'photo' => Storage::url($produit->photo),
                    'quantite' => $data['quantite'],
              
                    'prix' => $produit->getPrice(),
                    'total' => $data["quantite"] * $produit->getPrice(),
                ];
                $montant_total += $data["quantite"] * $produit->getPrice();
            }
            $Html = view('components.cart',['produits' => $produits])->render();
        }
       

        return response()->json(
            [
                "total" => $total,
                "html" => $Html,
                "montant_total" => $montant_total
            ]
        );
    }
 
    public function updateQuantity(Request $request)
    {
        $panier = session('cart', []);
    
        foreach ($panier as &$item) {
            if ($item['id_produit'] == $request->id_produit) {
                $item['quantite'] = max(1, intval($request->quantite));  // Assurer que la quantité est au moins 1
                break;
            }
        }
    
        session(['cart' => $panier]);
    
        // Retourner la mise à jour du panier
        return $this->count_panier();
    }
    


    public function cart()
    {
      //  $configs = configs::first();
        return view('front.cart.cart');
    }



    public function add(Request $request)
    {
        $id_produit = $request->input('id_produit');
        $type = $request->input('type', 'produit');
        $quantite = $request->input('quantite', 1);
        

        $user = Auth::user();


        $produit = produits::where('id', $id_produit)
            ->first();


        //verifier que le produit existe et est disponible
        if (!$produit) {
            return response()->json([
                'statut' => false,
             'message' => __('messages.product_not_found'),
            ]);
        }


        /* if ($produit->statut == "disponible") {
            return response()->json([
                'statut' => false,
                'message' => __('messages.product_not_found'),
            ]);
        }
 */
         //si l'user est un grossite on ajute sa quantite si il a prix moind de la quantite_minimal_grossiste	
         if ($user && $user->role == "grossiste") {
            if ($quantite < $produit->quantite_minimal_grossiste) {
                $quantite = $produit->quantite_minimal_grossiste ;
            }
        }else{
            $quantite = $request->input('quantite', 1);
        }


        //verifier que le stock demander est disponible
        if ($produit->stock < $quantite) {
            return response()->json([
                'statut' => false,
                'message' => "Quantité insuffisante en stock !",
            ]);
        }
 
       


        $panier = session('cart', []);
        $produit_existe = false;

        foreach ($panier as &$item) {
            if ($item['id_produit'] == $id_produit) {
                $item['quantite'] += $quantite;
                
                $produit_existe = true;
                break;
            }
        }

        if (!$produit_existe) {
            $panier[] = [
                'id_produit' => $id_produit,
                'quantite' => $quantite,
            ];
        }

        session(['cart' => $panier]);

        return response()->json([
            'statut' => true,
            'message' => __('messages.product_added_to_cart'),
        ]);
    }


    public function delete_produit(Request $request)
    {
        
        $id_produit = $request->input('id_produit');
        $panier = session('cart', []);
        foreach ($panier as $key => $item) {
            if ($item['id_produit'] == $id_produit) {
                unset($panier[$key]);
                break;
            }
        }
        session(['cart' => $panier]);
        return response()->json([
            "statut" => true,
            'message' => __('messages.product_removed'),
        ]);
    }




    public function update($id_produit, $quantite)
{
    // Find the product in the session cart and update the quantity
    $cart = session()->get('cart', []);

    foreach ($cart as &$item) {
        if ($item['id_produit'] == $id_produit) {
            $item['quantite'] = $quantite;
            break;
        }
    }

    // Save the updated cart back to session
    session(['cart' => $cart]);

    // Optionally, re-render the component or update the cart total
    $this->emit('cartUpdated');
}






}
