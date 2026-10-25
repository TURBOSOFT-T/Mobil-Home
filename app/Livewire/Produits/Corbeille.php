<?php

namespace App\Livewire\Produits;

use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Corbeille extends Component
{
    use WithPagination;
    public function render()
    {
        //get trsahed produits
        $produits = produits::onlyTrashed()->paginate(30);
        $total = produits::onlyTrashed()->count();
        return view('livewire.produits.corbeille', compact('produits','total'));
    }

    public function restore($id){
        $produit = produits::onlyTrashed()->find($id);
        $produit->restore();
        $this->resetPage();

        //flash message
        session()->flash('success', 'Produit restauré avec succès');
    }

    public function delete_definitif($id){
        $produit = produits::onlyTrashed()->find($id);
        //delete storage image
        if ($produit->image) {
            Storage::disk('local')->delete($produit->photo); 
        };
        $produit->forceDelete();
        //flash success message
        session()->flash('danger', 'Suppression définitive effectuée avec succès');
    }

    
}
