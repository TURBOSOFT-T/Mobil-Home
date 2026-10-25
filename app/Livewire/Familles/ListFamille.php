<?php

namespace App\Livewire\Familles;

use Livewire\Component;
use App\Models\Famille;
use App\Models\Sous_category;

class ListFamille extends Component
{
    public $nom;
      public $key;
    public $sous_categorie_id; // La propriété est correcte ici

    public function save()
    {
        $data = $this->validate([
            'nom' => ['required', 'string', 'max:255'],
            'sous_categorie_id' => 'required|integer|exists:sous_categories,id',
        ]);

        // Correction : Une seule sauvegarde suffit
        $famille = new Famille();
        $famille->nom = $this->nom;
        $famille->sous_categorie_id = $this->sous_categorie_id;
        $famille->save(); 

        $this->reset('nom', 'sous_categorie_id');

        session()->flash('success', 'Famille ajoutée avec succès');
        return redirect()->route('familles');
    }

    public function delete($id)
    {
        $famille = Famille::find($id);
        
        if ($famille) {
            $famille->delete();
            session()->flash('success', 'Famille supprimée avec succès');
        }
        
        return redirect()->route('familles');
    }

    public function render()
    {

      $Query = Famille::query();
        if(!is_null($this->key)){
            $Query->where('titre', 'like', '%'.$this->key.'%');
        }
        $familles = $Query->paginate(10);
$sous_categories = Sous_category::all();
        $total = Sous_category::count();
       
        return view('livewire.familles.list-famille', compact('familles', 'sous_categories', 'total'));
    }
}