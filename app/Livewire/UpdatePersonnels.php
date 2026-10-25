<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Shop;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UpdatePersonnels extends Component
{
    public $personnelId;
    public $nom;
    public $prenom;
    public $email;
    public $phone;
    public $shop_id;
    public $role = 'caisse';

    /**
     * Initialisation des données de l'utilisateur à modifier
     */
    public function mount($personnelId)
    {
        $this->personnelId = $personnelId;
        $personnel = User::findOrFail($personnelId);

        $this->nom = $personnel->nom;
        $this->prenom = $personnel->prenom;
        $this->email = $personnel->email;
        $this->phone = $personnel->phone;
        $this->role = $personnel->role ?? 'caisse';

       
    }

    /**
     * Règles de validation dynamiques avec exclusion de l'utilisateur courant
     */
    protected function rules()
    {
        return [
            'nom'          => 'required|string|max:255',
            'prenom'       => 'required|string|max:255',
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($this->personnelId)],
            'phone'        => ['required', 'numeric', Rule::unique('users', 'phone')->ignore($this->personnelId)],
            'shop_id'      => 'nullable|exists:shops,id',
            'role' => 'required|in:caisse,personnel,vendeur,gerant,commercial',
        ];
    }

    public function render()
    {
      

        return view('livewire.update-personnels');
    }

    public function update()
    {
        // 1. Validation des champs
        $this->validate();

        // 2. Recherche et mise à jour de l'utilisateur
        $personnel = User::findOrFail($this->personnelId);
        $personnel->nom   = $this->nom;
        $personnel->prenom = $this->prenom;
        $personnel->email  = $this->email;
        $personnel->phone  = $this->phone;
        $personnel->role   = $this->role;
        $personnel->save();

        // 3. Synchronisation de la boutique et du rôle pivot
      
        // 5. Notification et redirection
        session()->flash('success', 'Personnel mis à jour avec succès !');

        return redirect()->route('personnels');
    }
}