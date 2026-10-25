<?php

namespace App\Livewire\Tables;

use App\Models\Table;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
class ListTable extends Component
{
    use WithFileUploads;
     public $nom, $frais,  $photo;

     
    public $table_id, $oldPhoto;
    public $showEditModal = false;

     

   public function save()
{
    $this->validate([
        'nom' => ['required', 'string', 'max:255'],
        'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
    ]);

    $table = new Table();
    $table->nom = $this->nom;
    $table->photo = $this->photo->store('tables', 'public');
    $table->save();

    // Réinitialisation
    $this->reset(['nom', 'photo']);

    // Message flash de confirmation
    session()->flash('success', 'Table ajoutée avec succès !');

    return redirect()->route('tables');
}


 // ✅ Ouvre le modal avec les infos à modifier
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        $this->table_id = $table->id;
        $this->nom = $table->nom;
        $this->oldPhoto = $table->photo;
        $this->showEditModal = true;
    }

    // ✅ Sauvegarde les modifications
    public function update()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $table = Table::findOrFail($this->table_id);
        $table->nom = $this->nom;

        // Supprimer l'ancienne photo si une nouvelle est téléchargée
        if ($this->photo) {
            if ($table->photo && Storage::disk('public')->exists($table->photo)) {
                Storage::disk('public')->delete($table->photo);
            }
            $table->photo = $this->photo->store('tables', 'public');
        }

        $table->save();
        $this->reset(['nom', 'photo', 'table_id', 'showEditModal']);
        session()->flash('success', 'La table a été mise à jour avec succès.');
    }


    public function render()
    {
          $tables = Table::all();
        return view('livewire.tables.list-table', compact('tables'));
    }




    public function delete($id){
  $table = Table::findOrFail($id);

    // Supprimer la photo du stockage
    if ($table->photo && Storage::disk('public')->exists($table->photo)) {
        Storage::disk('public')->delete($table->photo);
    }

    // Supprimer la table de la base de données
    $table->delete();

    session()->flash('success', 'Table et photo supprimées avec succès !');
}
}
