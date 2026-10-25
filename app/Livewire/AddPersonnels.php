<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AddPersonnels extends Component
{
    public $nom,$prenom,$email,$phone;

    public function render()
    {
        return view('livewire.add-personnels');
    }

    public function create(){
        $this->validate([
            'nom' =>'required|string',
            'prenom' =>'required|string',
            'email' =>'required|email|unique:users,email',
            'phone' =>'required|numeric',
        ]);

        
        $personnels = new User();
        $personnels->nom = $this->nom;
        $personnels->prenom = $this->prenom;
        $personnels->email = $this->email;
        $personnels->phone = $this->phone;
        $personnels->role = "personnel";
        $personnels->password = Hash::make('123456789');
        $personnels->save();

        $role = Role::where('name', 'personnel')->first();
        if ( $role) {
            $personnels->assignRole($role->id); 
        }

        return redirect()->route('personnels');


    }
}
