<?php

namespace App\Livewire\SousCategories;
use Livewire\Component;
use App\Models\Category;
use App\Models\produits;
use Illuminate\Support\Facades\Storage;
use App\Models\Sous_category;
use Livewire\WithPagination;

class ListSousCategories extends Component
{


    use WithPagination;
    public $key;
    public $nom, $image,$image2,$sous_category,$description,$categorie_id;

 public function mount($sous_category){
        if($sous_category){
            $this->sous_category = $sous_category;
            $this->categorie_id = $sous_category->categorie_id;
            $this->nom = $sous_category->nom;

           
           
           
        }
    }
     
    public function delete($id)
    {
        $cat = Sous_category::find($id);
        if ($cat) {
            $cat->delete();
            session()->flash('info', 'Sous category supprimée avec succès');
        }
    }
 
    public function save()
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

     
      // $this->resetInputFields();


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
        
        $Query = Sous_category::query();
        if(!is_null($this->key)){
            $Query->where('titre', 'like', '%'.$this->key.'%');
        }
        $categories = $Query->paginate(10);
        $total = Sous_category::count();
        $cats = Category::all();
        return view('livewire.sous-categories.list-sous-categories', compact('cats','categories','total'));
    }
}
