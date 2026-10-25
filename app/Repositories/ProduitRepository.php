<?php

namespace App\Repositories;
use App\Models\produits;
use Illuminate\Http\Request;

class ProduitRepository
{
    
    public function search(Request $request)
    {
        $produits = produits::where('nom', 'like', '%'.$request->search_string.'%')
        ->orWhere('prix', 'like', '%'.$request->search_string.'%')
        ->orderBy('id', 'desc')
        ->get();
        /* DB::table('produits')
                    ->where(function ($q) use ($search) {
                        $q->where('nom', 'like', "%$search%")
                          ->orWhere('reference', 'like', "%$search%")
                          ->orWhere('prix', 'like', "%$search%");
                    })->get(); */

                    return $this->$produits;
    }
}
