<?php

namespace App\Livewire\Commandes;

use App\Http\Traits\ListGouvernorats as TraitsListGouvernorats;
use App\Models\commandes;
use App\Models\produits;
use Livewire\Component;
use Livewire\WithPagination;
use App\Mail\{OrderChangeStatut, ChangeStatut};
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ListCommande extends Component
{
    use WithPagination;
    use TraitsListGouvernorats;
 public $selectedCommandes = [];
    public $date, $statut, $key, $gouvernoratsTunisie, $gouvernorat, $statut2, $user_id;

    public  $total_gain_points = 0;
    public  $historiquePoints = [];
    public $commercial_id;
    public $date_debut;
    public $date_fin;

    
 
public  $dateDebut;
public  $dateFin;
public $totauxParCommercial = [];


  public function mount()
    {
        // Récupérer la date depuis la session si elle existe
        $this->date = Session::get('commande_date', null);
         $this->date = session('date') ?? null;
    $this->date_debut = session('date_debut') ?? null;
    $this->date_fin = session('date_fin') ?? null;
    }

       public function updatedDate($value)
    {
        // Stocker la date dans la session chaque fois qu'elle est modifiée
        Session::put('commande_date', $value);
    }


     public function filtrer()
    {
        //reset page
        $this->resetPage();
        Session::put('commande_date', $this->date);
        session(['date' => $this->date]);
    session(['date_debut' => $this->date_debut]);
    session(['date_fin' => $this->date_fin]);
    }

    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }
 public function resetFilters()
{
    $this->key = '';
    $this->statut = '';
    $this->statut2 = '';
    $this->commercial_id = '';
    $this->date = '';
    $this->date_debut = '';
    $this->date_fin = '';

    // Recharge les commandes après réinitialisation
    $this->filtrer();
}


public function render()
    {
        $commandesQuery = commandes::query();


         /*  if (auth()->user()->role != 'admin') {
            $commandesQuery->where('commercial_id', auth()->id());
           
        } else {


            if (!empty($this->commercial_id)) {
                $commandesQuery->where('commercial_id', $this->commercial_id);
            }
        } */
 

        $user = auth()->user();

        // Filtre par commercial (admin seulement)
        if ($user->role == 'admin' && !empty($this->commercial_id)) {
            $commandesQuery->where('commercial_id', $this->commercial_id);
        }


       // $commandesQuery->where('statut', '!=', 'payée');
       // $commandesQuery->where('etat', '!=', 'annulé');
      //  $commandesQuery->where('statut', '!=', 'retournée');
        if (strlen($this->date) > 0) {
            $commandesQuery->whereDate('created_at', $this->date);
        }

        // Filtre par date + heure
        if (!empty($this->date)) {
            $dateTime = str_replace('T', ' ', $this->date);
            $commandesQuery->where('created_at', '>=', $dateTime);
        }

        // Filtre entre date + heure
        if (!empty($this->date_debut) && !empty($this->date_fin)) {
            $debut = str_replace('T', ' ', $this->date_debut);
            $fin = str_replace('T', ' ', $this->date_fin);

            $commandesQuery->whereBetween('created_at', [$debut, $fin]);
        }



        if (strlen($this->statut) > 0) {
            $commandesQuery->where('statut', $this->statut);
        }
        if (strlen($this->statut2) > 0) {
            if ($this->statut2 == "confirmer") {
                $commandesQuery->where('etat', "confirmé");
            } else {
                $commandesQuery->where('etat', "annulé");
            }
        }
        if (strlen($this->key) > 0) {
            $commandesQuery->where('nom', 'like', '%' . $this->key . '%')
                ->orWhere('adresse', 'like', '%' . $this->key . '%')
                ->orWhere('phone', 'like', '%' . $this->key . '%')
                ->orWhere('reference', 'like', '%' . $this->key . '%')
                ->orWhere('prenom', 'like', '%' . $this->key . '%');
        }
        $commandes = $commandesQuery->Orderby('id', "Desc")->paginate(10);
        $total = commandes::count();

        // ✅ Envoyer aussi la liste des commerciaux au view si admin
        $commerciaux = auth()->user()->role == 'admin'
            ? User::where('role', 'personnel')->get()
            : [];

        return view('livewire.commandes.list-commande', compact("commandes", "total", "commerciaux"));
    }

    

    public function updateStatus($commandeId, $newStatus)
    {
        $commande = commandes::findOrFail($commandeId);
        $user = auth()->user();
        $client = $commande->user;

        // 🛑 Si la commande est confirmée et que l'utilisateur n'est ni
        // le caissier qui a confirmé, ni un admin → INTERDIT
        if (
            $commande->etat === 'confirmé'
            && $commande->caisse_id != $user->id
            && $user->role != 'admin'
        ) {
            session()->flash('warning', 'Vous ne pouvez pas modifier cette commande confirmée par un autre caissier.');
            return;
        }

        // 🛑 Si la commande n’est pas confirmée, seul le caissier qui confirme OU admin peut continuer
        if (
            $commande->etat !== 'confirmé'
            && $user->role != 'admin'
            && $commande->caisse_id != $user->id
            && $newStatus !== 'confirmé'
        ) {
            session()->flash('warning', 'Vous devez confirmer la commande avant de la modifier.');
            return;
        }

        // -----------------------
        // 🔥 Mise à jour du statut
        // -----------------------

        $commande->statut = $newStatus;

        // ➤ Gestion des commandes retournées
        if ($newStatus == "retournée") {

            if ($client) {
                if ($commande->mode === 'solde' && $commande->solde_utilise > 0) {
                    $client->solde += $commande->solde_utilise;
                }

                if ($commande->mode === 'points' && $commande->points_utilise > 0) {
                    $client->points += $commande->points_utilise;
                }
                $client->save();
            }

            foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->retourner_stock($contenus->quantite);
                }
            }

            $this->sendOrderConfirmationMail($commande);
        }

        // ➤ Commande payée
        if ($newStatus == "payée") {
            foreach ($commande->contenus as $contenus) {
                if ($client) {
                    $client->points += $contenus->total_gain_points;
                    $client->save();
                }
            }
        }

        // ➤ Autres statuts avec email
        if (in_array($newStatus, ["En cours livraison", "traitement", "planification"])) {
            $this->sendOrderConfirmationMail($commande);
        }

        $commande->save();
    }

    public function sendOrderConfirmationMail($commande)
    {
        try {
           // Mail::to($commande->email)->send(new OrderChangeStatut($commande));
        } catch (\Exception $e) {
   
          //  \Log::error('Erreur lors de l\'envoi de l\'email de confirmation de commande : ' . $e->getMessage());
         
        }
    }
    

    public function delete($id)
    {

        $commande = commandes::find($id);
        $client = $commande->user;

        if ($commande->statut == "attente" || $commande->statut == "créé" || $commande->statut == "traitement" || $commande->statut == "planification" || $commande->statut == "livrée") {

            foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->retourner_stock($contenus->quantite);
                }
                if ($client) {

                    if ($commande->mode === 'solde' && $commande->solde_utilise > 0) {
                        $client->solde += $commande->solde_utilise;
                    }

                    if ($commande->mode === 'points' && $commande->points_utilise > 0) {
                        $client->points += $commande->points_utilise - $contenus->total_gain_points;
                    }
                    $client->save();
                }
            }


            $commande->delete();
            session()->flash('success', 'Commande supprimée avec succès');
        }
        if ($commande->statut == 'payée' || $commande->statut == "retournée") {
            $commande->delete();
            session()->flash('success', 'Commande supprimée avec succès');
        }
        return view('livewire.commandes.list-commande');
    }



    


    public function confirmer($id)
    {
        $commande = commandes::find($id);
        $user = auth()->user();
        if ($commande) {

            $commande->etat = "confirmé";
            $commande->caisse_id = $user->id;
            $commande->save();
            // $this->sendOrderConfirmationMail($commande);
        }
    }


   public function annuler($id)
    {
        $user = auth()->user();
        $commande = commandes::find($id);
        $client = $commande->user;
        if ($commande) {
            foreach ($commande->contenus as $contenus) {
                $article = produits::find($contenus->id_produit);
                if ($article) {
                    $article->retourner_stock($contenus->quantite);
                }
            }
            $commande->statut = "retournée";
            $commande->etat = "annulé";
            $commande->caisse_id = $user->id;

            $commande->save();
            // $this->sendOrderConfirmationMail($commande);
        }
    }



    public function toggleCommandeSelection($commandeId)
    {
        if (in_array($commandeId, $this->selectedCommandes)) {
            $this->selectedCommandes = array_diff($this->selectedCommandes, [$commandeId]);
        } else {
            $this->selectedCommandes[] = $commandeId;
        }
    }


    public function getSelectedCommandes()
    {
        //check if $this->selectedCommandes is not empty
        if (count($this->selectedCommandes) > 0) {
            $ids = json_encode($this->selectedCommandes);
            return redirect()->route('print_bordereau', ["ids" => $ids]);
        } else {
            return false;
        }
    }


}
