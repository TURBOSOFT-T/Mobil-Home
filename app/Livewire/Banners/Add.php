<?php

namespace App\Livewire\Banners;

use App\Models\banner;
use Livewire\Component;
use Livewire\WithFileUploads;

class Add extends Component
{
    use WithFileUploads;
    public $photo, $titre, $sous_titre;


    public function render()
    {
        return view('livewire.banners.add');
    }


    public function save()
    {
        $this->validate([
            'titre' => 'nullable|string|max:500',
            'sous_titre' => 'nullable|string|max:2050',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp',
        ], [
           // 'titre.required' => 'Le titre est obligatoire',
          //  'sous_titre.required' => 'Le sous titre est obligatoire',
            'photo.required' => 'La photo est obligatoire',
            'photo.image' => 'La photo doit être une image',
            'photo.mimes' => 'La photo doit être au format jpg,jpeg,png,webp',
            'photo.max' => 'La photo ne doit pas dépasser 4024 ko',
            'titre.max' => 'Le titre ne doit pas dépasser 50 caractères',
           'sous_titre.max' => 'Le sous titre ne doit pas dépasser 70 caractères',
        ]);

        $banner = new banner();
        $banner->titre = $this->titre;
        $banner->sous_titre = $this->sous_titre;
        $banner->image = $this->photo->store('bannners', 'public');
        $banner->save();

        // flash success message
        session()->flash('success', 'banner ajouté avec succès');

        // reset input
        $this->reset();

        //dispach event
        $this->dispatch('BannerAdded');

    }

}
