<div class="row">
    <div class="col-sm-8">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Familles</h5>
                    <span wire:loading>
                        <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow" alt="Chargement...">
                    </span>
                </div>
                
                <div class="table-responsive-sm mt-3">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Libellé</th>
                                <th>Nombre de produits</th>
                                <th>Création</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($familles as $famile)
                                <tr data-id="{{ $famile->id }}">
                                    <td>{{ $famile->nom }}</td>
                                    <td>{{ $famile->produits ? $famile->produits->count() : 0 }}</td>
                                    <td>{{ $famile->created_at ? $famile->created_at->format('d/m/Y') : '-' }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $famile->id }}">
                                            <i class="ri-edit-line"></i> Modifier
                                        </button>
                                       
                                        <button class="btn btn-sm btn-danger" onclick="toggle_confirmation({{ $famile->id }})">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-success d-none" type="button" id="confirmBtn{{ $famile->id }}" wire:click="delete({{ $famile->id }})">
                                            <i class="bi bi-check-circle"></i> Confirmer
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center p-3">
                                            <p class="mb-0">Aucune famille trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                  {{ $familles->links('pagination::bootstrap-4') }}

            </div>
        </div>
    </div>

    @foreach ($familles as $famile)
        <div class="modal fade" id="editModal{{ $famile->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $famile->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $famile->id }}">Modifier la famille : {{ $famile->nom }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('famille.update', $famile->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nom_{{ $famile->id }}" class="form-label">Nom de la famille</label>
                                <input type="text" class="form-control" id="nom_{{ $famile->id }}" name="nom" value="{{ $famile->nom }}" required>
                            </div>

                            <div class="modal-footer px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-sm-4">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Enregistrement</h5>
                </div>
                
                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="libelle_famille">Libellé</label>
                        <input type="text" id="libelle_famille" wire:model="nom" class="form-control @error('nom') is-invalid @enderror">
                        @error('nom')
                            <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="select_rayon">Rayon</label>
                        <select id="select_rayon" wire:model="sous_categorie_id" class="form-control @error('sous_categorie_id') is-invalid @enderror">
                            <option value="">Sélectionner un rayon</option>
                            @foreach ($sous_categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                        @error('sous_categorie_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div> 
             
                    <div class="modal-footer px-0 pb-0">
                        <button type="submit" class="btn btn-primary w-100">
                            <span wire:loading class="me-1">
                                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="Loading...">
                            </span>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>