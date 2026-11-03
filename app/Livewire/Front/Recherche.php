<?php

namespace App\Http\Livewire\Front; // Ajustez selon votre namespace (ex: App\Livewire\Front)

use App\Models\produits;
use Livewire\Component;
use Livewire\WithPagination;

class Recherche extends Component
{
    use WithPagination;

    public $key = '';
    public $type = '';
    public $statut = 'disponible';

    // Permet de récupérer le paramètre ?type=... transmis dans l'URL
    protected $queryString = ['key', 'type', 'statut'];

    public function mount()
    {
        if (request()->has('type')) {
            $this->type = request()->query('type');
        }
    }

    public function updatingKey()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = produits::query();

        // Filtrer par statut (uniquement les disponibles par défaut pour les clients)
        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        // Filtrer par type d'habitation si sélectionné
        if (!empty($this->type)) {
            $query->where('type_habitation', $this->type);
        }

        // Filtrer par mot-clé (nom ou description)
        if (!empty($this->key)) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->key . '%')
                  ->orWhere('description', 'like', '%' . $this->key . '%');
            });
        }

        return view('livewire.front.recherche', [
            'produits' => $query->latest()->paginate(9)
        ]);
    }
}