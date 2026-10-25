<?php

namespace App\Livewire\SousCategories;

use Livewire\Component;
use App\Models\Category;
use App\Models\Sous_category;
use Illuminate\Support\Facades\Storage;

use Livewire\WithFileUploads;

class AddSousCategories extends Component
{

    use WithFileUploads;

    public $posts, $title, $body, $post_id;
    public $updateMode = false;

    
    public $nom, $image,$image2,$sous_category,$description,$categorie_id;


    public function mount($sous_category){
        if($sous_category){
            $this->sous_category = $sous_category;
            $this->categorie_id = $sous_category->categorie_id;
            $this->nom = $sous_category->nom;

           
           
           
        }
    }

    private function resetInputFields(){
        $this->nom= '';
       
    }

    

  
    public function create()
    {
    $data=    $this->validate([
            'nom' => 'required|string',
           
            'categorie_id' => 'required|integer|exists:categories,id',


        ]);
        ;[
          
            'nom.required' => 'Veuillez entrer le titre ',
          
      
      
          ];

          $categories = Category::findOrFail($data[('categorie_id')]);

        $sous_category = new Sous_category();
        $sous_category->nom = $this->nom;
      
        $sous_category->categorie_id = $this->categorie_id;
        
       
  
        $categories->sous_categories()->save($sous_category);

     
       $this->resetInputFields();


        session()->flash('success', 'sous_category ajoutée avec succès');
    }


    public function update_sous_category(){
        if($this->sous_category){
            $this->validate([
                'nom' => 'required|string',
              
                'categorie_id' => 'required|integer|exists:categories,id',
              
              
             
               
            ]);

          
          $sous_category = new Sous_category();

            $this->sous_category->nom = $this->nom;
         
            $this->sous_category->categorie_id = $this->categorie_id;
            
            

    
            $this->sous_category->save();
    
  
            $this->resetInputFields();

    
            return redirect()->route('sous_categories')->with('success',"sous_category modifié avec succès");



        }

    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.sous-categories.add-sous-categories', compact('categories'));
    }
}
