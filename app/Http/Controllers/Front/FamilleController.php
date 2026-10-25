<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use App\Http\Requests\StoreFamilleRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateFamilleRequest;

class FamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFamilleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Famille $famille)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Famille $famille)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $famille = Famille::findOrFail($id);
        
        // Valider les données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
        ]);
    
        // Mettre à jour les données
        $famille->update([
            'nom' => $validatedData['nom'],
        ]);
    
        // Rediriger avec un message de succès
        return redirect()->route('familles')->with('success', 'La famille a été modifiée avec succès.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Famille $famille)
    {
        //
    }
}
