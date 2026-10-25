<?php

namespace App\Livewire\Produits;

use App\Models\{produits, Category, Famille, Marque, Sous_category};
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Http\Traits\{TailleProduit, ListColor, ListColors};

class AddProduit extends Component
{
    use WithFileUploads;
    use ListColor;
    use ListColors;
    use TailleProduit;

    // Propriétés du formulaire
    public $nom, $tags, $taille = [], $couleur = [], $points, $prix, $category_id, $photo, $photos, $prix_achat, $photo2, $photos2, $produit, $reference, $description, $marque_id;
    public $free_shipping = false;
    public $is_new = false;
    public $meta_description, $sous_categorie_id, $famille_id;

    // NOUVELLES PROPRIÉTÉS POUR LA COMMISSION
    public $avec_commission = false;
    public   $avec_caracteristique = false;

    public $commission;

    // Listes dynamiques pour les selects dépendants
    public $sous_categories = [];
    public $familles = [];
    // NOUVELLE PROPRIÉTÉ POUR LES CARACTÉRISTIQUES (JSON)
    public $caracteristiques = [];

    public function mount($produit = null)
    {
        if ($produit) {
            $this->produit = $produit;
            $this->nom = $produit->nom;
            $this->tags = $produit->tags;
            $this->category_id = $produit->category_id;
            $this->marque_id = $produit->marque_id;
            $this->sous_categorie_id = $produit->sous_categorie_id;
            $this->famille_id = $produit->famille_id;
            $this->points = $produit->points;
            $this->reference = $produit->reference;
            $this->prix = $produit->prix;
            $this->prix_achat = $produit->prix_achat;
            $this->photo2 = $produit->photo;
            $this->photos2 = $produit->photos;
            $this->description = $produit->description;
            //  $this->meta_description = $produit->meta_description;
            $this->free_shipping = (bool)$produit->free_shipping;
            $this->is_new = (bool)$produit->is_new;
            $this->meta_description = $produit->meta_description;

            // Chargement initial de la commission
            $this->avec_commission = (bool)$produit->avec_commission;
            $this->avec_caracteristique = (bool)$produit->avec_caracteristique;
            $this->commission = $produit->commission;
            $this->caracteristiques = is_string($produit->caracteristiques)
                ? json_decode($produit->caracteristiques, true)
                : ($produit->caracteristiques ?? []);

            // Gestion de la désérialisation
            $this->taille = is_string($produit->taille) ? json_decode($produit->taille, true) : $produit->taille;
            $this->couleur = is_string($produit->couleur) ? json_decode($produit->couleur, true) : $produit->couleur;

            // Chargement initial des listes dépendantes
            $this->sous_categories = Sous_category::where('categorie_id', $this->category_id)->get();
            $this->familles = Famille::where('sous_categorie_id', $this->sous_categorie_id)->get();
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
        $this->caracteristiques = array_values($this->caracteristiques); // Réindexation du tableau
    }
    public function render()
    {
        return view('livewire.produits.add-produit', [
            'categories' => Category::all(),
            'marques'    => Marque::all(),
            'couleurs'   => $this->getListColor(),
            'colors'     => $this->getListColors(),
            'tailles'    => $this->getListTailleProduit(),
        ]);
    }

    public function create()
    {
        $this->validate([
            'nom'               => 'required|string',
            'description'       => 'required|string|max:50000',
            'meta_description' => 'nullable|string|max:255',
            'reference'         => 'required|string|unique:produits,reference',
            'prix'              => 'required|numeric|gt:prix_achat',
            'prix_achat'        => 'required|numeric',
            'photo'             => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'photos.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_id'       => 'required|integer|exists:categories,id',
            'sous_categorie_id' => 'nullable|integer|exists:sous_categories,id',
            'famille_id'        => 'nullable|integer|exists:familles,id',
            'points'            => 'nullable|numeric',
            'free_shipping'     => 'nullable|boolean',
            'is_new'            => 'nullable|boolean',
            'marque_id'         => 'nullable|integer|exists:marques,id',
            'couleur'           => 'nullable|array',
            'taille'            => 'nullable|array',
            'caracteristiques'           => 'nullable|array',
            'caracteristiques.*.cle'    => 'required_with:caracteristiques.*.valeur|nullable|string',
            'caracteristiques.*.valeur' => 'required_with:caracteristiques.*.cle|nullable|string',

            // Validation sécurisée pour la commission
            'avec_commission' => 'nullable|boolean',
            'commission'      => 'required_if_accepted:avec_commission|nullable|numeric|min:0',
        ]);

        // Note : Pensez à renommer votre modèle en "Produit" (singulier) pour respecter les conventions Laravel
        $produit = new produits();
        $produit->nom = $this->nom;
        $produit->description = $this->description;
        $produit->meta_description = $this->meta_description;
        $produit->reference = $this->reference;
        $produit->prix = $this->prix;
        $produit->prix_achat = $this->prix_achat;
        $produit->points = $this->points ?? 0;
        $produit->category_id = $this->category_id;
        $produit->sous_categorie_id = $this->sous_categorie_id;
        $produit->famille_id = $this->famille_id;
        $produit->marque_id = $this->marque_id;
        $produit->free_shipping = (bool)$this->free_shipping;
        $produit->is_new = (bool)$this->is_new;

        // Attribution de la commission
        $produit->avec_commission = (bool)$this->avec_commission;
        $produit->commission = $this->avec_commission ? $this->commission : null;
        $produit->avec_caracteristique = (bool)$this->avec_caracteristique;

        // Filtrer les lignes vides en sécurisant le tableau
        if ($this->avec_caracteristique && is_array($this->caracteristiques)) {
            $filteredCaracteristiques = array_filter($this->caracteristiques, function ($item) {
                return !empty($item['cle']) && !empty($item['valeur']);
            });
            $produit->caracteristiques = json_encode(array_values($filteredCaracteristiques));
        } else {
            $produit->caracteristiques = json_encode([]);
        }

        // Upload photo principale
        $produit->photo = $this->photo->store('produits', 'public');

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
        session()->flash('success', 'Produit ajouté avec succès');
    }


    public function update_produit()
    {
        if ($this->produit) {
            $this->validate([
                'nom'               => 'required|string',
                'prix'              => 'required|numeric|gt:prix_achat',
                'meta_description' => 'nullable|string|max:255',
                'prix_achat'        => 'required|numeric',
                'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'photos.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'marque_id'         => 'nullable|integer|exists:marques,id',
                'category_id'       => 'required|integer|exists:categories,id',
                'sous_categorie_id' => 'nullable|integer|exists:sous_categories,id',
                'famille_id'        => 'nullable|integer|exists:familles,id',
                'free_shipping'     => 'nullable|boolean',
                'is_new'            => 'nullable|boolean',
                'caracteristiques'          => 'nullable|array',
                'caracteristiques.*.cle'    => 'required_with:caracteristiques.*.valeur|nullable|string',
                'caracteristiques.*.valeur' => 'required_with:caracteristiques.*.cle|nullable|string',


                // Validation de la commission en modification
                'avec_commission'   => 'required|boolean',
                'commission'        => 'required_if:avec_commission,true|nullable|numeric|min:0',
            ]);

            $this->produit->nom = $this->nom;
            $this->produit->description = $this->description;
            $this->meta_description = $this->meta_description;
            $this->produit->points = $this->points;
            $this->produit->prix = $this->prix;
            $this->produit->prix_achat = $this->prix_achat;
            $this->produit->marque_id = $this->marque_id;
            $this->produit->category_id = $this->category_id;
            $this->produit->sous_categorie_id = $this->sous_categorie_id;
            $this->produit->famille_id = $this->famille_id;
            $this->produit->free_shipping = $this->free_shipping;
            $this->produit->is_new = $this->is_new;

            // Attribution de la commission avec sécurité en modification
            $this->produit->avec_commission = $this->avec_commission;
            $this->produit->commission = $this->avec_commission ? $this->commission : null;
            $this->produit->avec_caracteristique = $this->avec_caracteristique;

            $this->produit->couleur = json_encode($this->couleur);
            $this->produit->taille = json_encode($this->taille);

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

            return redirect()->route('produits')->with('success', "Produit modifié avec succès");
        }
    }
}
