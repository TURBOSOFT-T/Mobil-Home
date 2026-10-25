<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddService extends Component
{
    use WithFileUploads;
    public $nom, $image, $service, $description, $OldPhoto;

    public function mount($service)
    {
        if ($service) {
            $this->service = $service;
            $this->nom = $service->nom;
            $this->description = $service->description;
            $this->OldPhoto = $service->image;
        }
    }

    private function resetInputFields()
    {
        $this->nom = '';
        $this->description = '';
    }



    public function render()
    {
        return view('livewire.services.add-service');
    }






    //validation
    public function create()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|Max:5000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'description.required' => 'La description est obligatoire',
            'image.required' => 'L\'image est obligatoire',
            'image.image' => 'L\'image doit être une image',
            'image.mimes' => 'L\'image doit être au format jpg,jpeg,png,webp',
            'image.max' => 'L\'image ne doit pas dépasser 4024 ko',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'description.max' => 'La description ne doit pas dépasser 5000 caractères',
        ]);



        $service = new Service();
        $service->nom = $this->nom;
        $service->description = $this->description;
        $service->image = $this->image->store('services', 'public');
        $service->save();
        $this->resetInput();
        session()->flash('success', 'Service ajoutée avec succès');
    }


    public function update_service()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|Max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'description.required' => 'La description est obligatoire',
            'image.required' => 'L\'image est obligatoire',
            'image.image' => 'L\'image doit être une image',
            'image.mimes' => 'L\'image doit être au format jpg,jpeg,png,webp',
            'image.max' => 'L\'image ne doit pas dépasser 4024 ko',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'description.max' => 'La description ne doit pas dépasser 5000 caractères',
        ]);

        if ($this->service) {
            $this->service->nom = $this->nom;
            $this->service->description = $this->description;
            if ($this->image) {
                //delete old photo
                if ($this->service->image) {
                    Storage::disk('public')->delete($this->service->image);
                }
                $this->service->image = $this->image->store('services', 'public');
            }
            $this->service->save();
            session()->flash('success', "Service modifié avec succès");
        }
    }

    public function resetInput()
    {
        $this->nom = '';
        $this->description = '';
        $this->image = '';
    }
}
