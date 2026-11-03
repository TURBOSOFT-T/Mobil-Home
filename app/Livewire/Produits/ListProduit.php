<?php

namespace App\Livewire\Produits;

use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListProduit extends Component
{
    protected $listeners = ['add-stock' => '$refresh'];
    use WithPagination;
    
    public $key;
    public $showModal = false;
    public $selectedProduit;
   

    // Réinitialise la pagination lors de la saisie d'une recherche
    public function updatedKey()
    {
        $this->resetPage();
    }

    public function toggleStatut($id)
{
    $produit = \App\Models\produits::find($id);
    if ($produit) {
        $produit->statut = ($produit->statut === 'disponible') ? 'indisponible' : 'disponible';
        $produit->save();
        
        session()->flash('success', 'Statut du bien mis à jour avec succès.');
    }
}

    public function render()
    {
        $Query = produits::query();

        if (!is_null($this->key) && trim($this->key) !== '') {
            $Query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->key . '%')
                  ->orWhere('reference', 'like', '%' . $this->key . '%')
                  ->orWhere('ville', 'like', '%' . $this->key . '%')
                  ->orWhere('quartier', 'like', '%' . $this->key . '%')
                  ->orWhere('type_habitation', 'like', '%' . $this->key . '%');
            });
        }

        $produits = $Query->latest()->paginate(30);
        $total = produits::count();
        $total_supprimers = produits::onlyTrashed()->count();

        return view('livewire.produits.list-produit', compact('produits', 'total', 'total_supprimers'));
    }

    public function openModal($produitId)
    {
        $this->selectedProduit = $produitId;
        
        $this->showModal = true;
    }

    public function delete($id)
    {
        $produit = produits::find($id);
        if ($produit) {
            $produit->delete();
            session()->flash('info', 'Bien immobilier supprimé avec succès');
        }
    }

    public function add_top($id)
    {
        $produit = produits::find($id);
        if ($produit) {
            $produit->top = $produit->top == 1 ? 0 : 1;
            $produit->save();
        }
    }

    public function filtrer()
    {
        $this->resetPage();
    }
}