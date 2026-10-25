<?php

namespace App\Livewire\Commandes;

use App\Http\Traits\ListGouvernorats as TraitsListGouvernorats;
use App\Models\commandes;
use App\Models\produits;
use App\Models\User;
use App\Mail\OrderChangeStatut;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Commerciaux extends Component
{
    use WithPagination;
    use TraitsListGouvernorats;

    public $selectedCommandes = [];
    public $date, $statut, $key, $gouvernoratsTunisie, $gouvernorat, $statut2;
    public $commercial_id;
    public $date_debut;
    public $date_fin;
    public $totauxParCommercial = [];

    public function mount()
    {
        $this->date = session('date') ?? session('commande_date');
        $this->date_debut = session('date_debut');
        $this->date_fin = session('date_fin');
    }

    public function updatedDate($value)
    {
        Session::put('commande_date', $value);
        session(['date' => $value]);
    }

    public function filtrer()
    {
        $this->resetPage();
        session([
            'date' => $this->date,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin
        ]);
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

        $this->filtrer();
    }

    public function render()
    {
        $commandesQuery = commandes::query()->whereNotNull('commercial_id');

        // Restriction selon le rôle
        if (auth()->user()->role !== 'admin') {
            $commandesQuery->where('commercial_id', auth()->id());
        } elseif (!empty($this->commercial_id)) {
            $commandesQuery->where('commercial_id', $this->commercial_id);
        }

        // Filtre Date Unique
        if (!empty($this->date)) {
            $dateTime = str_replace('T', ' ', $this->date);
            $commandesQuery->whereDate('created_at', $dateTime);
        }

        // Filtre Plage de Dates
        if (!empty($this->date_debut) && !empty($this->date_fin)) {
            $debut = str_replace('T', ' ', $this->date_debut);
            $fin = str_replace('T', ' ', $this->date_fin);
            $commandesQuery->whereBetween('created_at', [$debut, $fin]);
        }

        // Filtre Statut de livraison
        if (!empty($this->statut)) {
            $commandesQuery->where('statut', $this->statut);
        }

        // Filtre État de validation
        if (!empty($this->statut2)) {
            $commandesQuery->where('etat', $this->statut2 === 'confirmer' ? 'confirmé' : 'annulé');
        }

        // Filtre Recherche textuelle sécurisé
        if (!empty($this->key)) {
            $commandesQuery->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->key . '%')
                  ->orWhere('adresse', 'like', '%' . $this->key . '%')
                  ->orWhere('phone', 'like', '%' . $this->key . '%')
                  ->orWhere('reference', 'like', '%' . $this->key . '%')
                  ->orWhere('prenom', 'like', '%' . $this->key . '%');
            });
        }

        // --- CALCUL DES CHIFFRES ET COMMISSIONS ---
        if (!empty($this->commercial_id) || auth()->user()->role !== 'admin') {
            
            // On charge la relation 'contenus.produit' et 'commercial' pour éviter le problème de requêtes N+1
            $allCommandesForCalcul = (clone $commandesQuery)
                ->with(['contenus.produit', 'commercial'])
                ->get();
            
            $this->totauxParCommercial = $allCommandesForCalcul->groupBy('commercial_id')->map(function ($commandesDuCommercial) {
                $commercial = $commandesDuCommercial->first()->commercial;
                $codePromoCommercial = $commercial->code_promo ?? null; // Assurez-vous d'avoir ce champ en BD

                $chiffreAffaire = 0;
                $totalCommissions = 0;

                foreach ($commandesDuCommercial as $commande) {
                    // 1. Calcul du chiffre d'affaires net de la commande
                    $chiffreAffaire += ($commande->montant() - ($commande->coupon ?? 0));

                    // 2. Calcul de la commission par produit si le code promo correspond
                    // (Optionnel : Ajoutez une vérification du statut si vous ne payez que sur commande 'livrée')
                    if ($codePromoCommercial && $commande->code_promo === $codePromoCommercial) {
                        foreach ($commande->contenus as $contenu) {
                            $produit = $contenu->produit;
                            
                            // Ajustez 'commission' selon le nom exact de votre colonne dans la table produits
                            if ($produit && isset($produit->commission) && $produit->commission > 0) {
                                // Exemple : commission fixe par unité vendue multiplier par la quantité
                                $totalCommissions += ($produit->commission * $contenu->quantite);
                                
                                // SI votre commission est un pourcentage (ex: 10 pour 10%), utilisez plutôt :
                                // $totalCommissions += (($produit->prix * ($produit->commission / 100)) * $contenu->quantite);
                            }
                        }
                    }
                }

                return [
                    'nom' => $commercial->nom ?? 'Commercial',
                    'total' => $chiffreAffaire,
                    'commission' => $totalCommissions,
                ];
            })->toArray();

        } else {
            $this->totauxParCommercial = [];
        }

        // Génération de la liste finale paginée pour la vue
        $commandes = $commandesQuery->orderBy('id', 'desc')->paginate(80);
        $total = commandes::count();
        
        $commerciaux = auth()->user()->role === 'admin'
            ? User::where('role', 'personnel')->get()
            : [];

        return view('livewire.commandes.commerciaux', compact('commandes', 'total', 'commerciaux'));
    }

    public function updateStatus($commandeId, $newStatus)
    {
        $commande = commandes::findOrFail($commandeId);
        $commande->statut = $newStatus;

        if ($newStatus === 'retournée') {
            foreach ($commande->contenus as $contenu) {
                $article = produits::find($contenu->id_produit);
                if ($article) {
                    $article->retourner_stock($contenu->quantite);
                }
            }
        }

        if (in_array($newStatus, ['retournée', 'En cours livraison', 'traitement', 'planification'])) {
            $this->sendOrderConfirmationMail($commande);
        }

        $commande->save();
    }

    public function sendOrderConfirmationMail($commande)
    {
        try {
            if ($commande->email) {
                Mail::to($commande->email)->send(new OrderChangeStatut($commande));
            }
        } catch (\Exception $e) {
            // Log de l'erreur si nécessaire
        }
    }

    public function delete($id)
    {
        $commande = commandes::findOrFail($id);

        if (in_array($commande->statut, ['attente', 'créé', 'traitement', 'planification', 'livrée'])) {
            foreach ($commande->contenus as $contenu) {
                $article = produits::find($contenu->id_produit);
                if ($article) {
                    $article->retourner_stock($contenu->quantite);
                }
            }
            $commande->delete();
            session()->flash('success', 'Commande supprimée avec succès');
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
        if (count($this->selectedCommandes) > 0) {
            return redirect()->route('print_bordereau', ['ids' => json_encode($this->selectedCommandes)]);
        }
        return false;
    }
}