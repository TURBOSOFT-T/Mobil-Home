<?php

namespace App\Livewire\Produits;

use App\Models\historiques_stock;
use App\Models\produits;
use Livewire\Component;

class AddStock extends Component
{
    public $produit, $produits, $id, $quantite;


    public function updatedProduit($value)
    {
        $this->id = null;
        $this->quantite = null;

        $this->produits = produits::where('nom', 'like', '%' . $value . '%')
            ->Orwhere('reference', 'like', '%' . $value . '%')
            ->select('id', 'nom', 'photo')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.produits.add-stock');
    }

    public function copier($id)
    {
        $pro = produits::find($id);
        if ($pro) {
            $this->id = $id;
        }
    }


    public function add()
    {
        if (!$this->id) {
            //flash message
            session()->flash('error', 'Veuillez sélectionner un produit');
            return;
        }

        $pro = produits::find($this->id);
        if (!$pro) {
            //flash message
            session()->flash('error', 'Veuillez sélectionner un produit .');
            return;
        }
        $pro->stock = $pro->stock + $this->quantite;
       
        $pro->save();

        //enregistrer lhistorique du stock 
        $historique_stock = new historiques_stock();
        $historique_stock->quantite = $this->quantite;
        $historique_stock->id_produit = $pro->id;
        $historique_stock->save();


        //reset input
        $this->produit = null;

        //flash message
        session()->flash('success', 'Stock ajouté avec succès');
        $this->id = null;
        $this->dispatch('add-stock');
    }
}
