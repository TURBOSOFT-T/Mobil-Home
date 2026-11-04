<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\produits;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $query = produits::where('statut', 'disponible');

        // Filtrer par type d'habitation
        if ($request->filled('type')) {
            $query->where('type_habitation', $request->input('type'));
        }

        // Filtrer par mot-clé (nom ou description)
        if ($request->filled('key')) {
            $key = $request->input('key');
            $query->where(function($q) use ($key) {
                $q->where('nom', 'like', '%' . $key . '%')
                  ->orWhere('description', 'like', '%' . $key . '%');
            });
        }

        $produits = $query->latest()->paginate(12)->withQueryString();

        return view('front.recherche', compact('produits'));
    }

    // Méthode pour alimenter la modale en JSON
    public function detailsJson($id)
    {
        $produit = produits::find($id);

        if (!$produit) {
            return response()->json(['error' => 'Bien introuvable'], 404);
        }

        return response()->json([
            'id' => $produit->id,
            'nom' => $produit->nom,
            'type_habitation' => $produit->type_habitation,
            'prix' => $produit->prix,
            'statut' => $produit->statut,
            'description' => $produit->description,
            'photo_url' => $produit->photo ? asset('storage/' . $produit->photo) : null,
        ]);
    }
}