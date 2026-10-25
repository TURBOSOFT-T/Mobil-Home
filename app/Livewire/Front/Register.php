<?php

namespace App\Livewire\Front;

use App\Mail\register as MailRegister;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $nom;
    public $prenom;
    public $phone;       // Remplacement de l'identifiant principal
    public $email;       // Devient optionnel
    public $password;
    public $password_confirmation;
    public $isRegistered = false;

    protected $rules = [
        'nom' => 'required|string|max:255', // Rendu obligatoire pour SWOOT BIO, plus propre
        'prenom' => 'nullable|string|max:255',
        'phone' => 'required|unique:users,phone',
        'email' => 'nullable|email|unique:users,email', // Nullable suite à la modif de la BDD
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required|min:8',
    ];

    protected $messages = [
        'nom.required' => 'Le nom est obligatoire',
        'phone.required' => 'Le numéro de téléphone est obligatoire',
        'phone.unique' => 'Ce numéro de téléphone est déjà utilisé',
        'email.email' => 'L\'email n\'est pas valide',
        'email.unique' => 'L\'email existe déjà',
        'password.required' => 'Le mot de passe est obligatoire',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        'password.confirmed' => 'Les mots de passe ne correspondent pas',
        'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire',
        'password_confirmation.min' => 'La confirmation du mot de passe doit contenir au moins 8 caractères',
    ];

    public function save()
    {
        $this->validate();

        $user = User::create([
            'nom' => $this->nom,
    // 'prenom' => $this->prenom,
            'phone' => $this->phone,
           // 'email' => $this->email ?: null, // S'assure de stocker du NULL propre si vide
            'password' => Hash::make($this->password),
            'role' => 'client', // Rôle par défaut explicite pour SWOOT BIO
        ]);

        $this->isRegistered = true;
 
        // On n'envoie le mail de bienvenue QUE si l'adresse email a été fournie
       /*  if ($user->email) {
            Mail::to($user->email)->send(new MailRegister($user));
        } */
       
        Auth::login($user);
        session()->flash('success', 'Votre compte a été créé avec succès!');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.front.register');
    }
}