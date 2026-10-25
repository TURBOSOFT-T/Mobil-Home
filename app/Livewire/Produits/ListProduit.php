<?php

namespace App\Livewire\Produits;

use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\historiques_stock;

class ListProduit extends Component
{

    protected $listeners = ['add-stock' => '$refresh'];
    use WithPagination;
    public $key;




    public function render()
    {
        $Query = produits::query();
        if(!is_null($this->key)){
            $Query->where('nom', 'like', '%'.$this->key.'%');
        }
        $produits = $Query->paginate(30);
        $total = produits::count();
        $total_supprimers = produits::onlyTrashed()->count();
        return view('livewire.produits.list-produit',compact('produits','total','total_supprimers'));
    }

   /*  public $stock;

    public function addStock($produitId)
    {
        $produit = produits::find($produitId);
        $produit->stock += $this->stock;
        $produit->save();

        
       
        $historique_stock = new historiques_stock();
        $historique_stock->quantite = $request->quantite;
       $historique_stock->id_produit = $pro->id;
       $historique_stock->save();
    
        session()->flash('message', 'Stock ajouté avec succès.');
        $this->reset('stock'); 
    }
     */

     public $showModal = false; // Propriété pour contrôler l'affichage du modal
     public $selectedProduit;
     public $stock = 1; // Valeur par défaut pour le stock à ajouter
 
     public function openModal($produitId)
     {
         $this->selectedProduit = $produitId; // Définir le produit sélectionné
         $this->stock = 1; // Réinitialiser la quantité
         $this->showModal = true; // Ouvrir le modal
     }
 
     public function addStock()
     {
         $produit = produits::find($this->selectedProduit);
         if ($produit) {
             $produit->stock += $this->stock;
             $produit->save();
             
        $historique_stock = new historiques_stock();
        $historique_stock->quantite = $this->stock;
       $historique_stock->id_produit = $produit->id;
       $historique_stock->save();
             session()->flash('message', 'Stock ajouté avec succès.');
             $this->showModal = false; // Fermer le modal après l'ajout
         }
     }



    public function delete($id)
    {
        $produit = produits::find($id);
        if ($produit) {
            $produit->delete();
            session()->flash('info', 'Produit supprimé avec succès');
        }
    }




    public function add_top($id)
    {
        $produit = produits::find($id);
        if ($produit) {
            if ($produit->top == 1) {
                $produit->top = 0;
            } else {
                $produit->top = 1;
            }
            $produit->save();
        }
    }





    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }
}
