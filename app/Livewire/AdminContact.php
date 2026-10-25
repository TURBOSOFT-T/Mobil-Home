<?php

namespace App\Livewire;

use App\Models\config;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminContact extends Component
{
    use WithFileUploads;

    public $logo, $icon, $logo2, $icon2, $frais, $logoHeader, $telephone, $addresse, $email, $description, $logofooter, $logofooter2,
        $satisfaction, $icone_satisfaction, $icone_satisfaction2, $des_satisfaction,
        $annee, $icone_annee, $icone_annee2, $des_annee,
        $prix, $icone_prix, $des_prix, $icone_prix2,
        $titre_apropos, $des_apropos, $image_apropos, $image_apropos0,
        $titre_apropos1, $des_apropos1, $image_apropos1, $image_apropos12,
        $titre_apropos2, $des_apropos2, $image_apropos2, $image_apropos22,
        $image_contact, $image_shop, $image_about,
        $image_contact2, $image_shop2, $image_about2,
        $image_login, $image_register,
        $image_login2, $image_register2,
        $titre_annee, $titre_prix, $titre_satisfaction;
    public  $slogan,   $facebook, $messenger, $twitter, $instagram, $youtube, $linkedin, $tiktok;






    public function mount()
    {
        $config = config::first();
        $this->icone_annee2 = $config->icone_annee;
        $this->image_shop2 = $config->image_shop;
        $this->icone_satisfaction2 = $config->icone_satisfaction;
        $this->icone_prix2 = $config->icone_prix;
        $this->image_apropos0 = $config->image_apropos;
        $this->image_apropos12 = $config->image_apropos1;
        $this->image_apropos12 = $config->image_apropos1;
        $this->titre_apropos2 = $config->titre_apropos2;
        $this->image_contact2 = $config->image_contact;
        $this->image_shop2 = $config->image_shop;
        $this->image_about2 = $config->image_about;
        $this->image_login2 = $config->image_login;
        $this->image_register2 = $config->image_register;


        $this->logo2 = $config->logo;
        $this->logofooter2 = $config->logofooter;
        $this->icon2 = $config->icon;
        $this->frais = $config->frais;
        $this->logoHeader = $config->logoHeader;
        $this->email = $config->email;
        $this->telephone = $config->telephone;
        $this->addresse = $config->addresse;
        $this->description = $config->description;
        $this->slogan = $config->slogan;
        // $this->logofooter= $config->logofooter;

        $this->annee = $config->annee;
        $this->titre_annee = $config->titre_annee;
        $this->des_annee = $config->des_annee;
        $this->satisfaction = $config->satisfaction;
        $this->titre_satisfaction = $config->titre_satisfaction;

        $this->des_satisfaction = $config->des_satisfaction;
        $this->prix = $config->prix;
        $this->des_prix = $config->des_prix;
        $this->titre_prix = $config->titre_prix;

        $this->titre_apropos = $config->titre_apropos;
        $this->des_apropos = $config->des_apropos;

        $this->titre_apropos1 = $config->titre_apropos1;
        $this->des_apropos1 = $config->des_apropos1;

        $this->titre_apropos2 = $config->titre_apropos2;
        $this->des_apropos2 = $config->des_apropos2;

        $this->facebook = $config->facebook;
        $this->messenger = $config->messenger;
        $this->twitter = $config->twitter;
        $this->instagram = $config->instagram;
        $this->youtube = $config->youtube;
        $this->linkedin = $config->linkedin;
        $this->tiktok = $config->tiktok;
    }

    public function render()
    {
        return view('livewire.admin-contact');
    }

    public function update_form()
    {
        // valid all form fields as nulable
        $this->validate([
            'logo' =>  'image|nullable|max:20024',   // 1MB Max
            //  'logoHeader' =>  'image|nullable|max:2024',   // 1MB Max
            'icon' =>  'image|nullable|max:20024', //
            'logofooter' =>  'image|nullable|max:20024', //
            // 'logo' => 'sometimes|nullable|file|mimetypes:image/*',   

            // 'icon' => 'sometimes|nullable|file|mimetypes:image/*',
            //'logofooter'  => 'sometimes|nullable|file|mimetypes:image/*',
            'frais' => 'nullable|numeric',
            'satisfaction' => 'nullable|numeric',
            'telephone' => 'nullable|numeric',
            'email' => 'nullable',
            'addresse' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
            
            'facebook' => 'nullable|string',
            'twitter' => 'nullable|string',
            'instagram' => 'nullable|string',
            'youtube' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'tiktok' => 'nullable|string',
        ]);

        // update the user
        $config = config::first();
        if ($this->logo) {
            //delete old logo
            if ($this->logo2) {
                Storage::disk('public')->delete($this->logo2);
            }
            $config->logo = $this->logo->store('logo', 'public');
        }
        if ($this->logoHeader) {
            //delete old logo
            if ($this->logoHeader2) {
                Storage::disk('public')->delete($this->logoHeader2);
            }
            $config->logoHeader = $this->logoHeader->store('logoHeader', 'public');
        }

        if ($this->icon) {
            //delete old icon
            if ($this->icon2) {
                Storage::disk('public')->delete($this->icon2);
            }
            $config->icon = $this->icon->store('icon', 'public');
        }

        if ($this->logofooter) {
            //delete old logo
            if ($this->logofooter2) {
                Storage::disk('public')->delete($this->logofooter2);
            }
            $config->logofooter = $this->logofooter->store('logofooter', 'public');
        }


        if ($this->image_apropos) {
            //delete old image
            if ($this->image_apropos0) {
                Storage::disk('public')->delete($this->image_apropos0);
            }
            $config->image_apropos = $this->image_apropos->store('image_apropos', 'public');
        }
        if ($this->image_apropos1) {
            //delete old image
            if ($this->image_apropos12) {
                Storage::disk('public')->delete($this->image_apropos12);
            }
            $config->image_apropos1 = $this->image_apropos1->store('image_apropos1', 'public');
        }
        if ($this->image_apropos2) {
            //delete old image
            if ($this->image_apropos22) {
                Storage::disk('public')->delete($this->image_apropos22);
            }
            $config->image_apropos2 = $this->image_apropos2->store('image_apropos2', 'public');
        }
        if ($this->image_contact) {
            //delete old image
            if ($this->image_contact2) {
                Storage::disk('public')->delete($this->image_contact2);
            }
            $config->image_contact = $this->image_contact->store('image_contact', 'public');
        }
        if ($this->image_shop) {
            //delete old image
            if ($this->image_shop2) {
                Storage::disk('public')->delete($this->image_shop2);
            }
            $config->image_shop = $this->image_shop->store('image_shop', 'public');
        }
        if ($this->image_about) {
            //delete old image
            if ($this->image_about2) {
                Storage::disk('public')->delete($this->image_about2);
            }
            $config->image_about = $this->image_about->store('image_about', 'public');
        }
        if ($this->image_login) {
            //delete old image
            if ($this->image_login2) {
                Storage::disk('public')->delete($this->image_login2);
            }
            $config->image_login = $this->image_login->store('image_login', 'public');
        }
        if ($this->image_register) {
            //delete old image
            if ($this->image_register2) {
                Storage::disk('public')->delete($this->image_register2);
            }
            $config->image_register = $this->image_register->store('image_register', 'public');
        }

        if ($this->icone_annee) {
            //delete old icon
            if ($this->icone_annee2) {
                Storage::disk('public')->delete($this->icone_annee2);
            }
            $config->icone_annee = $this->icone_annee->store('icon', 'public');
        }

        if ($this->icone_satisfaction) {
            //delete old icon
            if ($this->icone_satisfaction2) {
                Storage::disk('public')->delete($this->icone_satisfaction2);
            }
            $config->icone_satisfaction = $this->icone_satisfaction->store('icon', 'public');
        }

        if ($this->icone_prix) {
            //delete old icon
            if ($this->icone_prix2) {
                Storage::disk('public')->delete($this->icone_prix2);
            }
            $config->icone_prix = $this->icone_prix->store('icon', 'public');
        }



        $config->frais = $this->frais;
        $config->telephone = $this->telephone;
        $config->email = $this->email;
        $config->addresse = $this->addresse;
        $config->description = $this->description;

        //   $config->annee = $this->annee;
        $config->des_annee = $this->des_annee;
        $config->titre_annee = $this->titre_annee;
        //  $config->satisfaction = $this->satisfaction;
        $config->des_satisfaction = $this->des_satisfaction;
        $config->titre_satisfaction = $this->titre_satisfaction;
        // $config->prix = $this->prix;
        $config->slogan = $this->slogan;
        $config->des_prix = $this->des_prix;
        $config->titre_prix = $this->titre_prix;

        $config->titre_apropos = $this->titre_apropos;
        $config->des_apropos = $this->des_apropos;

        $config->titre_apropos1 = $this->titre_apropos1;
        $config->des_apropos1 = $this->des_apropos1;

        $config->titre_apropos2 = $this->titre_apropos2;
        $config->des_apropos2 = $this->des_apropos2;
         $config->facebook = $this->facebook;
        //$config->twitter = $this->twitter;
        $config->instagram = $this->instagram;
        $config->youtube = $this->youtube;
        $config->linkedin = $this->linkedin;
        $config->tiktok = $this->tiktok;
        $config->messenger = $this->messenger;




        if ($config->save()) {
            //flash message
            session()->flash('info', 'Vos modifications ont été enregistrées.');
        } else {
            //flash message
            session()->flash('danger', 'Vos modifications n\'ont pas été enregistrées.');
        }
    }
}
