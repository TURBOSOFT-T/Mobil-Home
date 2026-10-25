<?php

namespace App\Livewire\Front;

use App\Models\produits;

use Livewire\Component;

class Panier extends Component
{
    public $total =0;

   
    public function render()
{
    // Récupérer le panier de la session
    $paniers_session = session('cart', []);

    // Vérifier que $paniers_session est bien un tableau
    if (!is_array($paniers_session)) {
        $paniers_session = [];
    }

    $paniers = [];

    // Boucler uniquement si $paniers_session est un tableau valide
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
            $this->total += $session['quantite'] * $produit->getPrice();
        } else {
            // Supprimer l'élément du panier s'il n'existe plus
            $this->delete($session['id_produit']);
        }
    }

  //  dd($paniers_session);


    return view('livewire.front.panier', compact("paniers"));
}




    public function update($id_produit,$quantite){
        //find produit in session car and update quantity
        $panier = session('cart', []);
        $produit_existe = false;

        foreach ($panier as &$item) {
            if ($item['id_produit'] == $id_produit) {
                $item['quantite'] = $quantite;
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

        $this->total =0 ;
    }





    public function delete($id_produit){
        //delete produit from cart
        $panier = session('cart', []);
        $produit_existe = false;

        foreach ($panier as $key => &$item) {
            if ($item['id_produit'] == $id_produit) {
                unset($panier[$key]);
                $produit_existe = true;
                break;
            }
        }

        if (!$produit_existe) {
            $panier[] = [
                'id_produit' => $id_produit,
                'quantite' => 1,
            ];
        }

        session(['cart' => $panier]);

        $this->total =0 ;
    }




}

