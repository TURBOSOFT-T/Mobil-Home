<?php

namespace App\Livewire\Front;

use App\Mail\forget;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ForgetPassword extends Component
{

    public $email,$code,$enter_code,$password,$password_confirmation;
    public $step = 1,$user;


    
    public function render()
    {
        return view('livewire.front.forget-password');
    }


    
    public function form_1(){
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ],[
            'email.exists' => 'Email ou mot de passe incorrect',
            'email.required' => 'Email ou mot de passe incorrect',
            'email.email' => 'Email ou mot de passe incorrect',
        ]);

        //generate token
        $user = User::where('email', $this->email)->first();

        //generer un code random de 6 chiffres
        $code = rand(100000,999999);
        $this->code = $code ;            
    
        //send email
        Mail::to($this->email)->send(new forget($user,$code));

        session()->flash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé');
        $this->step = 2;
        $this->user = $user;
        $this->email = "";
    }


    public function form_2(){
        $this->validate([
            'enter_code' => 'required|integer',
        ],[
            'enter_code.required' => 'Code incorrect',
            'enter_code.integer' => 'Code incorrect',
        ]);

        if($this->code !=  $this->enter_code){
            //flash error message
            session()->flash('error', 'Code entré est incorrect !');
            return;
        }

        $this->step = 3;
    }


    public function form_3(){
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ],[
            'password.required' => 'Mot de passe incorrect',
            'password.min' => 'Mot de passe incorrect',
            'password.confirmed' => 'Mot de passe incorrect',
        ]);

        $user = $this->user;
        $user->password =  bcrypt($this->password);
        $user->save();
        

        Auth::login($user);
        return redirect('/');
        
    }
    
}
