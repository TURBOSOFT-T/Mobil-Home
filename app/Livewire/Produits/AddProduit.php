<?php

namespace App\Livewire\Produits;

use App\Models\{produits, Category, Famille, Sous_category};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddProduit extends Component
{
    use WithFileUploads;

    // Propriétés du formulaire
    public $nom, $slug, $tags, $prix, $category_id, $photo, $photos, $photo2, $photos2, $produit,  $description;
    
    // Type d'habitation
    public $type_habitation = 'appartement';
  
    public $is_new = false;
    public $meta_description, $sous_categorie_id, $famille_id;

    // Propriétés pour la commission et caractéristiques
    public $avec_commission = false;
    public $avec_caracteristique = false;
    public $commission;

    // Listes dynamiques pour les selects dépendants
    public $sous_categories = [];
    public $familles = [];
    public $caracteristiques = [];

    // Génération automatique du slug en temps réel lorsque l'utilisateur tape le nom
    public function updatedNom($value)
    {
        $this->slug = Str::slug($value);
    }

    public function mount($produit = null)
    {
        if ($produit) {
            $this->produit = $produit;
            $this->nom = $produit->nom;
            $this->slug = $produit->slug;
            $this->tags = $produit->tags;
            $this->category_id = $produit->category_id;
         
            $this->sous_categorie_id = $produit->sous_categorie_id;
            $this->famille_id = $produit->famille_id;
            

            $this->prix = $produit->prix;
            
            $this->photo2 = $produit->photo;
            $this->photos2 = $produit->photos;
            $this->description = $produit->description;
            $this->meta_description = $produit->meta_description;
            
            // Chargement du type d'habitation
            $this->type_habitation = $produit->type_habitation ?? 'appartement';

            $this->is_new = (bool)$produit->is_new;

            // Chargement initial de la commission et caractéristiques
            $this->avec_commission = (bool)$produit->avec_commission;
            $this->avec_caracteristique = (bool)$produit->avec_caracteristique;
            $this->commission = $produit->commission;
            $this->caracteristiques = is_string($produit->caracteristiques)
                ? json_decode($produit->caracteristiques, true)
                : ($produit->caracteristiques ?? []);

            // Chargement initial des listes dépendantes
            $this->sous_categories = Sous_category::where('categorie_id', $this->category_id)->get();
            $this->familles = Famille::where('sous_categorie_id', $this->sous_categorie_id)->get();
        }
    }

    public function toggleStatut($id)
{
    $produit = produits::find($id);
    if ($produit) {
        // Alterne entre disponible et indisponible
        $produit->statut = ($produit->statut === 'disponible') ? 'indisponible' : 'disponible';
        $produit->save();
        
        session()->flash('success', 'Le statut du bien a été mis à jour avec succès.');
    }
}
    // Déclenché à la modification de la catégorie principale dans la vue
    public function loadSubCategories()
    {
        $this->sous_categorie_id = null;
        $this->famille_id = null;
        $this->familles = [];
        $this->sous_categories = Sous_category::where('categorie_id', $this->category_id)->get();
    }

    // Déclenché à la modification de la sous-catégorie
    public function loadFamilles()
    {
        $this->famille_id = null;
        $this->familles = Famille::where('sous_categorie_id', $this->sous_categorie_id)->get();
    }

    // Ajouter une ligne de caractéristique
    public function addCaracteristique()
    {
        $this->caracteristiques[] = ['cle' => '', 'valeur' => ''];
    }

    // Supprimer une ligne de caractéristique
    public function removeCaracteristique($index)
    {
        unset($this->caracteristiques[$index]);
        $this->caracteristiques = array_values($this->caracteristiques);
    }

    public function render()
    {
        return view('livewire.produits.add-produit', [
            'categories' => Category::all(),
           
        ]);
    }

    // Fonction utilitaire pour garantir l'unicité du slug
    private function generateUniqueSlug($nom, $ignoreId = null)
    {
        $slug = Str::slug($nom);
        $originalSlug = $slug;
        $count = 1;

        while (produits::where('slug', $slug)->when($ignoreId, function ($query, $ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function create()
    {
        $this->validate([
            'nom'               => 'required|string',
            'slug'              => 'nullable|string|unique:produits,slug',
            'description'       => 'required|string|max:50000',
            'meta_description'  => 'nullable|string|max:255',
          
            'type_habitation'   => 'required|in:chambre,studio,appartement,duplex,villa,bureau,commerce,terrain,maison,boutique,local_commercial,autre',
            'prix'              => 'required|numeric',
            'photo'             => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'photos.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
          //  'category_id'       => 'required|integer|exists:categories,id',
            'sous_categorie_id' => 'nullable|integer|exists:sous_categories,id',
            'famille_id'        => 'nullable|integer|exists:familles,id',
       
            'is_new'            => 'nullable|boolean',
         
            'caracteristiques'  => 'nullable|array',
            'caracteristiques.*.cle'    => 'required_with:caracteristiques.*.valeur|nullable|string',
            'caracteristiques.*.valeur' => 'required_with:caracteristiques.*.cle|nullable|string',

            // Validation pour la commission
            'avec_commission'   => 'nullable|boolean',
            'commission'        => 'required_if_accepted:avec_commission|nullable|numeric|min:0',
        ]);

        $produit = new produits();
        $produit->user_id = auth()->id();

        $produit->nom = $this->nom;
        // Génération automatique et sécurisée du slug
        $produit->slug = $this->slug ? $this->generateUniqueSlug($this->slug) : $this->generateUniqueSlug($this->nom);
        
        $produit->description = $this->description;
        $produit->meta_description = $this->meta_description;
       
        $produit->type_habitation = $this->type_habitation;
        $produit->prix = $this->prix;
        
        $produit->category_id = $this->category_id;
        $produit->sous_categorie_id = $this->sous_categorie_id;
        $produit->famille_id = $this->famille_id;
   
        $produit->is_new = (bool)$this->is_new;

        // Attribution de la commission
        $produit->avec_commission = (bool)$this->avec_commission;
        $produit->commission = $this->avec_commission ? $this->commission : null;
        $produit->avec_caracteristique = (bool)$this->avec_caracteristique;

        // Filtrer les lignes de caractéristiques vides
        if ($this->avec_caracteristique && is_array($this->caracteristiques)) {
            $filteredCaracteristiques = array_filter($this->caracteristiques, function ($item) {
                return !empty($item['cle']) && !empty($item['valeur']);
            });
            $produit->caracteristiques = json_encode(array_values($filteredCaracteristiques));
        } else {
            $produit->caracteristiques = json_encode([]);
        }

        // Upload photo principale
        if ($this->photo) {
            $produit->photo = $this->photo->store('produits', 'public');
        }

        // Upload galerie photos
        if ($this->photos) {
            $photosPaths = [];
            foreach ($this->photos as $p) {
                $photosPaths[] = $p->store('produits', 'public');
            }
            $produit->photos = json_encode($photosPaths);
        }

        $produit->save();

        $this->reset();
        session()->flash('success', 'Bien immobilier ajouté avec succès');
    }

    public function update_produit()
    {
        if ($this->produit) {
            $this->validate([
                'nom'               => 'required|string',
                'slug'              => 'nullable|string|unique:produits,slug,' . $this->produit->id,
                'prix'              => 'required|numeric',
                'meta_description'  => 'nullable|string|max:255',
                'type_habitation'   => 'required|in:chambre,studio,appartement,duplex,villa,bureau,commerce,terrain,maison,boutique,local_commercial,autre',
                'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'photos.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
           
            //    'category_id'       => 'required|integer|exists:categories,id',
                'sous_categorie_id' => 'nullable|integer|exists:sous_categories,id',
                'famille_id'        => 'nullable|integer|exists:familles,id',
          
                'is_new'            => 'nullable|boolean',
                'caracteristiques'  => 'nullable|array',
                'caracteristiques.*.cle'    => 'required_with:caracteristiques.*.valeur|nullable|string',
                'caracteristiques.*.valeur' => 'required_with:caracteristiques.*.cle|nullable|string',

                // Validation de la commission en modification
                'avec_commission'   => 'required|boolean',
                'commission'        => 'required_if:avec_commission,true|nullable|numeric|min:0',
            ]);

            $this->produit->nom = $this->nom;
            // Mise à jour automatique ou personnalisée du slug en excluant l'ID courant
            $baseForSlug = $this->slug ?: $this->nom;
            $this->produit->slug = $this->generateUniqueSlug($baseForSlug, $this->produit->id);

            $this->produit->description = $this->description;
            $this->produit->meta_description = $this->meta_description;
            $this->produit->type_habitation = $this->type_habitation;
            $this->produit->prix = $this->prix;
            
          
            $this->produit->category_id = $this->category_id;
            $this->produit->sous_categorie_id = $this->sous_categorie_id;
            $this->produit->famille_id = $this->famille_id;
        
            $this->produit->is_new = $this->is_new;

            // Attribution de la commission
            $this->produit->avec_commission = $this->avec_commission;
            $this->produit->commission = $this->avec_commission ? $this->commission : null;
            $this->produit->avec_caracteristique = $this->avec_caracteristique;

            // Filtrer et sauvegarder les caractéristiques
            $filteredCaracteristiques = array_filter($this->caracteristiques, function ($item) {
                return !empty($item['cle']) && !empty($item['valeur']);
            });
            $this->produit->caracteristiques = json_encode(array_values($filteredCaracteristiques));

            if ($this->photo) {
                if ($this->produit->photo) {
                    Storage::disk('public')->delete($this->produit->photo);
                }
                $this->produit->photo = $this->photo->store('produits', 'public');
            }

            if ($this->photos) {
                $photosPaths = [];
                foreach ($this->photos as $p) {
                    $photosPaths[] = $p->store('produits', 'public');
                }
                $this->produit->photos = json_encode($photosPaths);
            }

            $this->produit->save();

            return redirect()->route('produits')->with('success', "Bien immobilier modifié avec succès");
        }
    }
}