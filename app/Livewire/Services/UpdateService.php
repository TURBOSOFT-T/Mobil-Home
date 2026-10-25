<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;

class UpdateService extends Component
{

    use WithFileUploads;
    public $nom,$description,$photo,$service;

    public function mount($service){
        $this->service = $service;
        $this->nom = $this->service->nom;
        $this->description = $this->service->description;
    }

    public function update(){
        $this->validate([
            'nom' =>'required|string|max:200',
            'description' =>'required|string|max:5000',
            'photo' =>'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $this->service->nom = $this->nom;
        $this->service->description = $this->description;
        if($this->photo){
            Storage::disk('public', 'services')->delete($this->service->image); 
            $this->service->image = $this->photo->store('services', "public");
        }
        $this->service->save();


        return redirect('/admin/services')->with('success', "Marque modifié !");
    }
    public function render()
    {
        return view('livewire.services.update-service');
    }
}
