<div>

    <form wire:submit="filtrer">
        <div class="row">
            <div class="col-sm-6">
                <span>
                    <b>{{ $produits->count() }}</b> Résultats sur {{ $total }}.
                </span>
            </div>
            <div class="col-sm-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control btn-sm" wire:model="key"
                        placeholder="Rechercher par titre, ville, quartier...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('components.alert')

    <div class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark cusor">
                <tr>
                    <th title="Mettre en avant / Coup de cœur">
                        Top
                    </th>
                    <th>Photo</th>
                    <th>Nom du bien</th>
                    <th>Type</th>
                    <th>Disponibilité</th>
                    <th>Prix / Loyer</th>
                    <th>Commission</th>
                    <th>Création</th>
                    <th style="text-align: right;">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow" alt="">
                        </span>
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($produits as $produit)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input" @checked($produit->top == 1)
                        wire:click="add_top({{ $produit->id }})">
                    </td>
                    <td>
                        <img src="{{ Storage::url($produit->photo) }}" width="40" height="40"
                            class="rounded shadow" alt="">
                    </td>
                    <td>
                        <!-- Bouton pour ouvrir la modale de détails -->
                        <button class="btn btn-sm btn-info text-white" title="Voir les détails" data-bs-toggle="modal"
                            data-bs-target="#details-produit-{{ $produit->id }}">
                            <i class="ri-eye-line"></i>
                        </button>
                        {{ $produit->nom }}
                    </td>

                    <td>
                        <span class="badge bg-info text-dark text-capitalize">
                            {{ $produit->type_habitation ?? 'Appartement' }}
                        </span>
                    </td>

                    <td class="cursor">
                        @if ($produit->statut == 'disponible')
                        <span class="text-success" title="Disponible">
                            <i class="fas fa-check-circle"></i>
                            <span class="badge bg-success">Disponible</span>
                        </span>
                        @elseif ($produit->statut == 'loue')
                        <span class="text-warning" title="Loué">
                            <span class="badge bg-warning text-dark">Loué</span>
                        </span>
                        @elseif ($produit->statut == 'vendu')
                        <span class="text-info" title="Vendu">
                            <span class="badge bg-info">Vendu</span>
                        </span>
                        @else
                        <span class="text-danger" title="Indisponible">
                            <i class="fas fa-times-circle"></i>
                            <span class="badge bg-danger">Indisponible</span>
                        </span>
                        @endif
                    </td>

                    <td>
                        <b>{{ number_format($produit->prix, 0, ',', ' ') }}</b> <x-devise></x-devise>
                    </td>

                    <td>
                        @if($produit->avec_commission)
                        {{ number_format($produit->commission, 0, ',', ' ') }} <x-devise></x-devise>
                        @else
                        <span class="badge bg-secondary">Pas de commission</span>
                        @endif
                    </td>
                    <td>{{ $produit->created_at->format('d/m/Y') }}</td>

                    <td style="text-align: right;">
                        <div class="btn-group">

                        <!-- Bouton de bascule de statut (Disponible / Indisponible) -->
<button type="button" 
    class="btn btn-sm {{ $produit->statut == 'disponible' ? 'btn-outline-danger' : 'btn-outline-success' }}" 
    wire:click="toggleStatut({{ $produit->id }})"
    title="{{ $produit->statut == 'disponible' ? 'Rendre indisponible' : 'Rendre disponible' }}">
    <i class="{{ $produit->statut == 'disponible' ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
    {{ $produit->statut == 'disponible' ? 'Indisponible' : 'Rendre disponible' }}
</button>

                            <button class="btn btn-sm btn-info text-white" title="Voir les détails" data-bs-toggle="modal" data-bs-target="#details-produit-{{ $produit->id }}">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-dark"
                                onclick="url(' {{ route('produits.update', ['id' => $produit->id]) }} ')">
                                <i class="ri-edit-box-line"></i>
                            </button>


                            @can('product_delete')
                            <button class="btn btn-sm btn-danger"
                                onclick="toggle_confirmation({{ $produit->id }})">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                            @endcan
                        </div>

                        <!-- Modal des détails complets du bien -->
                        <div class="modal fade" id="details-produit-{{ $produit->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content text-start">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">
                                            Détails du bien : <strong>{{ $produit->nom }}</strong>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Image principale -->
                                            <div class="col-md-5 text-center mb-3">
                                                @if ($produit->photo)
                                                <img src="{{ Storage::url($produit->photo) }}" alt="{{ $produit->nom }}" class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: cover;">
                                                @else
                                                <img src="/icons/no-image.webp" alt="Aucune image" class="img-fluid rounded shadow-sm" style="max-height: 220px;">
                                                @endif
                                            </div>

                                            <!-- Informations principales -->
                                            <div class="col-md-7">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td class="fw-bold">Type d'habitation :</td>
                                                        <td class="text-capitalize"><span class="badge bg-secondary">{{ $produit->type_habitation }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Prix / Loyer :</td>
                                                        <td class="text-success fw-bold">{{ number_format($produit->prix, 0, ',', ' ') }} <x-devise></x-devise></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Commission :</td>
                                                        <td>
                                                            @if($produit->avec_commission)
                                                            <span class="badge bg-success">{{ number_format($produit->commission, 0, ',', ' ') }} <x-devise></x-devise></span>
                                                            @else
                                                            <span class="badge bg-dark">Aucune</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Statut :</td>
                                                        <td class="text-capitalize">
                                                            <span class="badge bg-{{ $produit->statut == 'disponible' ? 'success' : 'warning' }}">
                                                                {{ $produit->statut ?? 'Disponible' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Ajouté le :</td>
                                                        <td>{{ $produit->created_at->format('d/m/Y à H:i') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Description complète -->
                                        <div class="mt-2">
                                            <h6 class="fw-bold">Description :</h6>
                                            <div class="p-3 bg-light rounded text-muted" style="max-height: 150px; overflow-y: auto;">
                                                {!! $produit->description ?? 'Aucune description fournie.' !!}
                                            </div>
                                        </div>

                                        <!-- Caractéristiques spécifiques (si présentes) -->
                                        @if(!empty($produit->caracteristiques) && is_array($produit->caracteristiques))
                                        <div class="mt-3">
                                            <h6 class="fw-bold">Caractéristiques / Commodités :</h6>
                                            <ul class="list-group list-group-flush">
                                                @foreach($produit->caracteristiques as $carac)
                                                @if(!empty($carac['cle']) && !empty($carac['valeur']))
                                                <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                                    <span>{{ $carac['cle'] }}</span>
                                                    <span class="fw-bold text-dark">{{ $carac['valeur'] }}</span>
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-success d-none" type="button"
                            id="confirmBtn{{ $produit->id }}" wire:click="delete({{ $produit->id }})">
                            <i class="bi bi-check-circle"></i>
                            <span>Confirmer</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div>
                            <img src="/icons/icons8-ticket-100.png" height="80" width="80" alt="">
                        </div>
                        Aucun bien immobilier trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $produits->links('pagination::bootstrap-4') }}

    @role('admin')
    <div class="text-end p-2">
        <a href="{{ route('corbeille') }}" class="text-danger">
            <i class="ri-delete-bin-line"></i>
            Corbeille ( {{ $total_supprimers }} )
        </a>
    </div>
    @endrole

    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
    </style>

</div>