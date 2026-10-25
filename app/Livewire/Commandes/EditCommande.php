<?php

namespace App\Livewire\Commandes;

use App\Models\Transport;
use App\Http\Traits\ListGouvernorats as TraitsListGouvernorats;
use App\Models\config;
use App\Models\contenu_commande;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EditCommande extends Component
{
    use TraitsListGouvernorats;

    public $commande, $gouvernoratsTunisie, $nom, $prenom, $adresse, $gouvernorat, $phone, $frais,  $commercial_id, $solde = 0, $caisse_id;
    public $transports = [];
    public $transport_id;
    public $showTransportForm = false;

    public function mount($commande)
    {
        $this->commande = $commande;
        $this->commercial_id = $commande->commercial_id  ?? null;
        $this->caisse_id = $commande->caisse_id  ?? null;


        $this->frais = $commande->frais;
        $this->nom = $commande->nom;
        $this->prenom = $commande->prenom;
        $this->adresse = $commande->adresse;
        $this->gouvernorat = $commande->gouvernorat;
        $this->phone = $commande->phone;
        // 🔥 Charger tous les transports
        $this->transports = Transport::all();

        // 🔥 Transport déjà lié à la commande (si existe)
        $this->transport_id = $commande->transport_id ?? null;
    }
    public function toggleTransportForm()
    {
        $this->showTransportForm = !$this->showTransportForm;
    }

    public function openTransportForm()
    {
        $this->showTransportForm = true;
    }

    public function closeTransportForm()
    {
        $this->showTransportForm = false;
    }
    public function applyTransport()
    {
        $transport = Transport::find($this->transport_id);

        if (!$transport) {
            session()->flash('error', 'Transport invalide');
            return;
        }

        $this->commande->transport_id = $transport->id;
        $this->commande->frais = $transport->frais;

        $this->commande->save();

        $this->showTransportForm = false;

        session()->flash('success', 'Transport appliqué');
    }


    public function removeTransport()
    {
        $config = config::first();

        $this->commande->transport_id = null;
        $this->commande->frais = $this->frais ? $config->frais : null;

        $this->commande->save();

        $this->transport_id = null;

        session()->flash('success', 'Transport annulé');
    }
    public function render()
    {

        $this->gouvernoratsTunisie = $this->getListGouvernorat();
        return view('livewire.commandes.edit-commande');
    }

    public function update_user_info()
    {
        $this->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:150',
            'phone' => 'required|string|max:100',
            'frais' => 'nullable',
        ]);

        $config = config::first();

        // =====================
        // UPDATE COMMANDE
        // =====================
        $this->commande->nom = $this->nom;
      //  $this->commande->prenom = $this->prenom;
      //  $this->commande->adresse = $this->adresse;
        $this->commande->phone = $this->phone;
        $this->commande->commercial_id = $this->commercial_id ?? null;
        $this->commande->caisse_id = Auth::id();

        // =====================
        // TRANSPORT (IMPORTANT)
        // =====================
        if ($this->transport_id) {
            $transport = Transport::find($this->transport_id);

            if ($transport) {
                $this->commande->transport_id = $transport->id;
                $this->commande->frais = $transport->frais;
            }
        } else {
            $this->commande->frais = $this->frais ? $config->frais : null;
        }

        // =====================
        // PERSON (USER OU CLIENT)
        // =====================
        $person = $this->commande->user ?? $this->commande->client;

        if (!$person) {
            session()->flash('error', 'Client introuvable');
            return;
        }

        // =====================
        // SOLDE
        // =====================
        if ($this->commande->mode == 'solde') {

            if ($this->frais) {
                $person->solde += $config->frais;
            } else {
                $person->solde -= $config->frais;
            }
        }

        // =====================
        // POINTS
        // =====================
        if ($this->commande->mode == 'points') {

            if ($this->frais) {
                $person->points += $config->frais;
            } else {
                $person->points -= $config->frais;
            }
        }

        // =====================
        // SAVE
        // =====================
        $person->save();
        $this->commande->save();

        session()->flash('success', 'Commande mise à jour avec succès !');
    }
    
public function updatePrice($id_contenu, $nouveauPrix)
{
    $contenu = contenu_commande::find(intval($id_contenu));
    $client = $contenu->commandes->user ?? $contenu->commandes->client;

    if (!$contenu) {
        session()->flash('warning', 'Contenu non trouvé');
        return;
    }

    $nouveauPrix = floatval($nouveauPrix);

    if ($nouveauPrix < 0) {
        session()->flash('error', 'Le prix ne peut pas être négatif');
        return;
    }

    $ancienPrix = $contenu->prix_unitaire;
    $diffPrix = $nouveauPrix - $ancienPrix;
    
    // Calcul de l'impact financier global pour la ligne (différence de prix * quantité)
    $montantVariation = abs($diffPrix) * $contenu->quantite;

    // Si le prix augmente
    if ($diffPrix > 0) {
        if ($contenu->commandes->mode === 'solde') {
            if ($client && $client->solde < $montantVariation) {
                session()->flash('error', 'Solde insuffisant pour augmenter le prix');
                return;
            }
            if ($client) {
                $client->solde -= $montantVariation;
            }
        }

        if ($contenu->commandes->mode === 'points') {
            if ($client && $client->points < $montantVariation) {
                session()->flash('error', 'Points insuffisants pour augmenter le prix');
                return;
            }
            if ($client) {
                $client->points -= $montantVariation;
            }
        }
    } 
    // Si le prix diminue
    elseif ($diffPrix < 0) {
        if ($client) {
            if ($contenu->commandes->mode === 'solde') {
                $client->solde += $montantVariation;
            }
            if ($contenu->commandes->mode === 'points') {
                $client->points += $montantVariation;
            }
        }
    }

    // Sauvegarde des modifications
    if ($client) {
        $client->save();
    }

    $contenu->prix_unitaire = $nouveauPrix;
    $contenu->save();

    // Force la commande à rafraîchir son instance et ses totaux pour la vue
    $this->commande->refresh();

    session()->flash('success', 'Le prix unitaire a été modifié');
}

    public function change($id_contenu, $quantite, $type)
    {
        $contenu = contenu_commande::find(intval($id_contenu));
        //  $client = $contenu->commandes->user;

        //  $client = $contenu->commandes->user; // le client
        $client = $contenu->commandes->user ?? $contenu->commandes->client;
        $produit = $contenu->produit;
        // dd($produit);

        //  dd($client);
        if (!$contenu) {
            session()->flash('warning', 'Contenu non trouvé');
            return;
        }

        $ancienneQuantite = $contenu->quantite;
        $quantite = intval($quantite);
        $diff = $quantite - $ancienneQuantite;

        // Vérifier que la nouvelle quantité est supérieure à 0
        if ($quantite <= 0) {
            session()->flash('error', 'La quantité doit être supérieure à 0');
            return;
        }
        $montantVariation = $produit->getPrice() * abs($diff);

        // dd($montantVariation);
        // Si augmentation
        if ($diff > 0) {
            if ($contenu->produit->stock < $diff) {
                session()->flash('error', 'Quantité demandée excède le stock disponible');
                return;
            }
            if ($contenu->commandes->mode === 'solde') {
                // Solde client diminue (mais vérifier qu'il y a assez de solde)
                if ($client->solde < $montantVariation) {
                    session()->flash('error', 'Solde insuffisant pour augpenter la quantité');
                    return;
                }
                $client->solde -= $montantVariation;
            }

            if ($contenu->commandes->mode === 'points') {
                // Solde client diminue (mais vérifier qu'il y a assez de solde)
                if ($client->points < $montantVariation) {
                    session()->flash('error', 'Le nombre de points insuffisant pour augpenter la quantité');
                    return;
                }
                $client->points -= $montantVariation;
            }


            $contenu->produit->diminuer_stock($diff);
            // $client->solde -= $montantVariation;
            $client->save();
        }
        // Si diminution
        elseif ($diff < 0) {


            if ($contenu->commandes->mode === 'solde') {

                $client->solde += $montantVariation;
            }

            if ($contenu->commandes->mode === 'points') {

                $client->points += $montantVariation;
            }
            $contenu->produit->retourner_stock(abs($diff));
            // $client->solde += $montantVariation;
            $client->save();
        }

        // Mettre à jour la quantité
        $contenu->quantite = $quantite;
        $contenu->total_gain_points = $contenu->produit->points * $quantite;
        $contenu->save();
    }



    public function delete($id)
    {
        $contenu = contenu_commande::find(intval($id));
        $client = $contenu->commandes->user;

        //   dd($newsolde);
        // dd($client->solde);


        if (!$contenu) {
            session()->flash('warning', 'Contenu non trouvé');
            return;
        }

        if ($client) {
            //  $client->points = $client->points  + $contenu->commandes->points_utilise ;
            if ($contenu->commandes->mode === 'solde') {
                $client->solde += $contenu->prix_unitaire * $contenu->quantite;;
            }

            if ($contenu->commandes->mode === 'points') {
                $client->points += $contenu->produit->points * $contenu->quantite;
            }
            $client->save();
        }

        // Retourner le stock du produit avant suppression
        $contenu->produit->retourner_stock($contenu->quantite);

        // Supprimer le contenu
        $contenu->delete();
        session()->flash('success', 'Le contenu a été supprimé de votre commande');

        // Vérifier s'il reste des contenus dans la commande
        $contenusRestants = $this->commande->contenus()->count();

        if ($contenusRestants == 0) {
            // Supprimer la commande si vide
            $this->commande->delete();
            return redirect()->route('commandes')->with('success', 'La commande a été supprimée car elle était vide');
        }
    }
}
