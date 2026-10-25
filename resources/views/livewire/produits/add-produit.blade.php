<div>

    @include('components.alert')

    @if ($produit)
    <form wire:submit="update_produit">
        @else
        <form wire:submit="create">
            @endif

            <div class="row">
                <div class="col-sm-8">

                    <div class="d-flex gap-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" wire:model="is_new" id="is_new">
                            <label class="form-check-label" for="is_new">
                                Nouveau produit
                            </label>
                        </div>

                        <!-- Nouveau champ : Activer la commission -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" wire:model.live="avec_commission" id="avec_commission">
                            <label class="form-check-label font-weight-bold" for="avec_commission">
                                Activer une commission
                            </label>
                        </div>


                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" wire:model.live="avec_caracteristique" id="avec_caracteristique">
                            <!-- Après (Correction) -->
                            <label class="form-check-label font-weight-bold" for="avec_caracteristique">Produit avec des propriétés spécifiques</label>
                        </div>
                    </div>

                    <!-- Nouveau champ : Montant de la commission (Affiché conditionnellement) -->
                    @if($avec_commission)
                    <div class="mb-3 p-3 border rounded bg-light">
                        <label for="commission">Montant de la commission</label>
                        <input type="number" id="commission" class="form-control @error('commission') is-invalid @enderror"
                            wire:model="commission" placeholder="Ex: 500">
                        @error('commission')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="">Nom du produit</label>
                        <input type="text" name="nom" class="form-control" wire:model="nom">
                        @error('nom')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    @if($avec_caracteristique)
                    <div class="mb-3 p-3 border rounded bg-light">
                        @foreach($caracteristiques as $index => $caracteristique)
                        <div class="row mb-2" wire:key="carac-{{ $index }}">
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Ex: RAM" wire:model="caracteristiques.{{ $index }}.cle">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Ex: 16 Go" wire:model="caracteristiques.{{ $index }}.valeur">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger" wire:click="removeCaracteristique({{ $index }})">Supprimer</button>
                            </div>
                        </div>
                        @endforeach
                        <button type="button" class="btn btn-primary" wire:click="addCaracteristique">Ajouter une caractéristique</button>
                    </div>
                    @endif

                    
                

                    <div class="mb-3" wire:ignore>
                        <label><strong>Description :</strong></label>
                        <textarea
                            rows="5"
                            id="description"
                            wire:model="description"
                            name="description"
                            class="form-control">{{ old('description', $produit->description ?? '') }}</textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="category_id">Catégorie</label>
                            <select id="category_id" wire:model="category_id" wire:change="loadSubCategories" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">Choisir une catégorie</option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="sub_category">Rayon :</label>
                            <select id="sub_category" wire:model="sous_categorie_id" wire:change="loadFamilles" class="form-control @error('sous_categorie_id') is-invalid @enderror">
                                <option value="">Choisir un rayon</option>
                                @foreach ($sous_categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            @error('sous_categorie_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="famille">Famille :</label>
                            <select id="famille" wire:model="famille_id" class="form-control @error('famille_id') is-invalid @enderror">
                                <option value="">Choisir une famille</option>
                                @foreach ($familles as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            @error('famille_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="marque_id">Marque </label>
                            <select id="marque_id" wire:model='marque_id' class="form-control @error('marque_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($marques as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            <!-- Correction de l'index d'erreur ici : marque_id au lieu de category_id -->
                            @error('marque_id')
                            <span class="text-danger small"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="">Prix de vente</label>
                                <input type="number" step="0.1" name="prix" class="form-control" wire:model="prix">
                                @error('prix')
                                <span class="text-danger small"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="">Prix d'achat</label>
                                <input type="number" step="0.1" name="prix_achat" class="form-control"
                                    wire:model="prix_achat">
                                @error('prix_achat')
                                <span class="text-danger small"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="">Référence du produit</label>
                                <input type="text" step="0.1" name="reference" class="form-control"
                                    wire:model="reference">
                                @error('reference')
                                <span class="text-danger small"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Photo d'illustration (300*300)</label>
                        <div class="preview-produit-illustration" onclick="preview_illustration('new-prosduit')">
                            @if ($produit)
                            @if ($photo2 && is_null($photo))
                            <img src="{{ Storage::url($photo2) }}" alt="" class="w-100">
                            @else
                            <img src="{{ $photo->temporaryUrl() }}" alt="" srcset="">
                            @endif
                            @else
                            @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-100">
                            @else
                            <img src="/icons/no-image.webp" alt="" class="w-100">
                            @endif
                            @endif
                        </div>
                        <input type="file" name="photo" accept="image/*" class=" d-none" id="file-input-new-prosduit"
                            wire:model="photo">
                        @error('photo')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="">Autres photos</label>
                        <input type="file" multiple name="photos" accept="image/*" class="form-control" wire:model="photos">
                        @error('photos')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                </div>
            </div>
            <div style="text-align: right;">
                <button class="btn btn-primary btn-sm px-5" type="submit" wire:loading.attr="disabled">
                    <span wire:loading>
                        <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                    </span>
                    @if ($produit)
                    Mettre a jour
                    @else
                    Enregistrer le produit
                    @endif
                </button>
            </div>
        </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    document.addEventListener('livewire:navigated', () => {
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                // Met à jour Livewire dès que le texte change dans CKEditor
                editor.model.document.on('change:data', () => {
                    @this.set('description', editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>