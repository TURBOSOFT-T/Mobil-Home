<?php

namespace App\Livewire\Front;

use App\Models\produits;
use Livewire\Component;

class Accueil extends Component
{

public $selectedProduit = null;

    public function voirDetails($id)
    {
        // Récupère le produit
        $this->selectedProduit = produits::find($id);
        
        // Déclenche l'événement JavaScript pour ouvrir le modal Bootstrap
        $this->dispatch('open-modal');
    }
    
    public function render()
    {
        return view('livewire.front.accueil', [
           'topBiens'           => produits::where('top', 1)->where('statut', 'disponible')->latest()->take(6)->get(),
            'appartements'       => produits::where('type_habitation', 'appartement')->where('statut', 'disponible')->latest()->take(6)->get(),
            'villas'             => produits::where('type_habitation', 'villa')->where('statut', 'disponible')->latest()->take(6)->get(),
            'studios'            => produits::where('type_habitation', 'studio')->where('statut', 'disponible')->latest()->take(6)->get(),
            'terrains'           => produits::where('type_habitation', 'terrain')->where('statut', 'disponible')->latest()->take(6)->get(),
            'duplexs'            => produits::where('type_habitation', 'duplex')->where('statut', 'disponible')->latest()->take(6)->get(),
            'boutiques'          => produits::where('type_habitation', 'boutique')->where('statut', 'disponible')->latest()->take(6)->get(),
            'commerces'          => produits::where('type_habitation', 'commerce')->where('statut', 'disponible')->latest()->take(6)->get(),
            'maisons'            => produits::where('type_habitation', 'maison')->where('statut', 'disponible')->latest()->take(6)->get(),
            'chambres'           => produits::where('type_habitation', 'chambre')->where('statut', 'disponible')->latest()->take(6)->get(),
               ]);
    }
}