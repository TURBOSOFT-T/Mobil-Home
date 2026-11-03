<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\clients;
use App\Models\commandes;
use App\Models\contenu_commande;
use App\Models\produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function store(Request $request)
    {



        $data =      $request->validate([
            'nom'            => ['nullable', 'string', 'max:255'],
            'prenom'         => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:50'],

            'adresse'        => ['nullable', 'string', 'max:500'],

            'note'           => ['nullable', 'string', 'max:1000'],
        ]);

        $connecte = Auth::user();
        $userId = null;
        $clientId = null;

        if (Auth::check()) {
            $userId = Auth::id();
        } else {


            $client = clients::updateOrCreate(
                ['phone' => $request->phone],
                [
                    'nom'         => $request->nom,
                    //    'prenom'      => $request->prenom,
                    //  'email'       => $request->email,


                ]
            );
            $clientId = $client->id;
        }

        $reference = 'SWB-' . date('Ymd') . '-' . strtoupper(Str::random(6));

  $produit = produits::find($request->produit_id);
        $order = new commandes([
            'user_id'        => $userId,
            'client_id'      => $clientId,
            'reference'      => $reference,
            'nom'            => $request->nom,
            
            
    
            'phone'          => $request->phone,
            
            'note'           => $request->note,
              'montant_total'  => $produit->getPrice(),


        ]);
         $order->save();


        $contenu = new contenu_commande();
        $contenu->id_commande       = $order->id;
        $contenu->id_produit         =  $request->produit_id;
       $contenu->save();



        return redirect()->back()->with('success', 'Votre demande de réservation a été envoyée avec succès ! Nous vous contacterons rapidement.');
    }
}
