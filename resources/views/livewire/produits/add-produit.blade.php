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
                                Nouveau bien
                            </label>
                        </div>

                        <!-- Activer la commission -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" wire:model.live="avec_commission" id="avec_commission">
                            <label class="form-check-label font-weight-bold" for="avec_commission">
                                Activer une commission
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" wire:model.live="avec_caracteristique" id="avec_caracteristique">
                            <label class="form-check-label font-weight-bold" for="avec_caracteristique">Propriétés spécifiques / Commodités</label>
                        </div>
                    </div>

                    <!-- Montant de la commission (Affiché conditionnellement) -->
                    @if($avec_commission)
                    <div class="mb-3 p-3 border rounded bg-light">
                        <label for="commission">Montant ou pourcentage de la commission</label>
                        <input type="number" step="0.01" id="commission" class="form-control @error('commission') is-invalid @enderror"
                            wire:model="commission" placeholder="Ex: 10 ou 5000">
                        @error('commission')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <!-- Nom du bien -->
                    <div class="mb-3">
                        <label for="">Nom du bien immobilier</label>
                        <input type="text" class="form-control" wire:model.live="nom" placeholder="Ex: Studio meublé moderne">
                        @error('nom')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    <!-- Slug automatique -->
                    <div class="mb-3">
                        <label for="slug">Slug (URL)</label>
                        <input type="text" id="slug" class="form-control" wire:model="slug" placeholder="genere-automatiquement">
                        @error('slug')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    <!-- Type d'habitation -->
                    <div class="mb-3">
                        <label for="type_habitation">Type d'habitation <span class="text-danger">*</span></label>
                        <select id="type_habitation" wire:model="type_habitation" class="form-control @error('type_habitation') is-invalid @enderror">
                            <option value="chambre">Chambre</option>
                            <option value="studio">Studio</option>
                            <option value="appartement">Appartement</option>
                            <option value="duplex">Duplex</option>
                            <option value="villa">Villa</option>
                            <option value="maison">Maison</option>
                            <option value="bureau">Bureau</option>
                            <option value="commerce">Commerce</option>
                            <option value="boutique">Boutique</option>
                            
                            <option value="terrain">Terrain</option>
                            <option value="autre">Autre</option>
                        </select>
                        @error('type_habitation')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    @if($avec_caracteristique)
                    <div class="mb-3 p-3 border rounded bg-light">
                        @foreach($caracteristiques as $index => $caracteristique)
                        <div class="row mb-2" wire:key="carac-{{ $index }}">
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Ex: Climatisation" wire:model="caracteristiques.{{ $index }}.cle">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Ex: Oui / 2 splits" wire:model="caracteristiques.{{ $index }}.valeur">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" wire:click="removeCaracteristique({{ $index }})">Supprimer</button>
                            </div>
                        </div>
                        @endforeach
                        <button type="button" class="btn btn-primary btn-sm mt-2" wire:click="addCaracteristique">Ajouter une caractéristique</button>
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
                            <label for="sub_category">Sous-catégorie :</label>
                            <select id="sub_category" wire:model="sous_categorie_id" wire:change="loadFamilles" class="form-control @error('sous_categorie_id') is-invalid @enderror">
                                <option value="">Choisir une sous-catégorie</option>
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

                     

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="">Prix / Loyer</label>
                                <input type="number" step="0.01" class="form-control" wire:model="prix">
                                @error('prix')
                                <span class="text-danger small"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>

                       

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Photo d'illustration</label>
                        <div class="preview-produit-illustration" onclick="preview_illustration('new-prosduit')">
                            @if ($produit)
                                @if ($photo2 && is_null($photo))
                                <img src="{{ Storage::url($photo2) }}" alt="" class="w-100">
                                @else
                                <img src="{{ $photo ? $photo->temporaryUrl() : '/icons/no-image.webp' }}" alt="" class="w-100">
                                @endif
                            @else
                                @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-100">
                                @else
                                <img src="/icons/no-image.webp" alt="" class="w-100">
                                @endif
                            @endif
                        </div>
                        <input type="file" accept="image/*" class="d-none" id="file-input-new-prosduit" wire:model="photo">
                        @error('photo')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="">Autres photos (Galerie)</label>
                        <input type="file" multiple accept="image/*" class="form-control" wire:model="photos">
                        @error('photos.*')
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
                    Enregistrer le bien
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
                editor.model.document.on('change:data', () => {
                    @this.set('description', editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>