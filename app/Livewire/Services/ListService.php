<?php

namespace App\Livewire\services;

use App\Models\Service;
use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListService extends Component
{

    use WithPagination;
    public $key;




    public function render()
    {
        $Query = Service::query();
        if(!is_null($this->key)){
            $Query->where('nom', 'like', '%'.$this->key.'%');
        }
        $services = $Query->paginate(30);
        $total = Service::count();
     
       
        return view('livewire.services.list-service', compact('services','total'));
    }




    public function delete($id)
    {
        $cat = Service::find($id);
        if ($cat) {
            $cat->delete();
            session()->flash('info', 'Service supprimé avec succès');
        }
    }







    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }
}
