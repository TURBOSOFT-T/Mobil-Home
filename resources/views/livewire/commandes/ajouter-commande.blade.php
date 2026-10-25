<div>
    <div class="row">
        <div class="col-sm-4">
            <div class="d-flex justify-content-between">
                <h6>
                    Ajouter un produit a la commande
                </h6>

                <div>
                    <span wire:loading>
                        <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <input type="text" placeholder="Recherche d'un produit" wire:model.live="key" class="form-control">
            </div>

            <div>
                <table class="w-100">
                    @if ($produits)
                        @forelse ($produits as $key=>$produit)
                            <tr>
                                <td class="my-auto">
                                    {{ $produit['nom'] }}
                                    <div class="small">
                                        <span class="text-primary text-capitalize"> {{ $produit['type'] }} </span> |
                                        {{ $produit['prix'] }} <x-devise></x-devise>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="input-group mb-3">
                                        <input id="quantite_{{ $produit['id'] }}" style="width: 50px !important"
                                        wire:model="quantites.{{ $produit['id'] }}" type="number" min="1"
                                        max="5" class="form-control" placeholder="Qté">

                                        <button class="btn btn-primary" type="button"
                                            wire:click="ajouterProduit({{ $produit['id'] }},'{{ $produit['type'] }}','{{ $produit['reference'] }}')">
                                            <i class="ri-add-circle-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="text-center p-2">
                                        <i> <i class="ri-information-line"></i> Aucun résultat.</i>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    @endif
                </table>
            </div>
        </div>
        <div class="col-sm-8">
            @include('components.alert')

            <div class="table-responsive-sm">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th>Produit</th>
                            <th>quantite</th>
                            <th>Prix</th>
                            <th>Montant</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @forelse ($paniers ?? [] as $panier)
                            <tr>
                                <td>
                                    {{ $panier['nom'] }}
                                    <div class="small text-capitalize">
                                        {{ $panier['type'] }}
                                    </div>
                                </td>
                                <td> {{ $panier['nom'] }} </td>
                                <td> x{{ $panier['quantite'] }} </td>
                                <td> {{ $panier['prix'] }} <x-devise></x-devise> </td>
                                <td> {{ $panier['prix'] * $panier['quantite'] }} <x-devise></x-devise> </td>
                                <td>
                                    {{-- <button class="btn btn-sm btn-danger" type="danger"
                                        wire:click="delete_from_session({{ $panier['id'] }})">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button> --}}

                                    <button class="btn btn-sm btn-danger" type="danger"
                                    wire:click="delete_from_session({{ $panier['id'] }})">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                                </td>
                            </tr>
                            @php
                                $total += $panier['prix'] * $panier['quantite'];
                            @endphp
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <br>
        <hr>
        <br>
        @if ($paniers)
            <div class="d-flex justify-content-between p-2 card" style="background-color: #027461;color:white;">
                <h5 class="header-title">
                    Finalisation de la commande
                </h5>
            </div>
            <div class="modal-body">
                <form wire:submit="order">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="">Nom *</label>
                                        <input type="text" placeholder="Nom du client" required wire:model="nom"
                                            class="form-control">
                                        @error('nom')
                                            <span class="small text-danger" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                               
                              <div class="col-sm-6">
                                    <div class="mb-3">
                                    <label for="">Numéro de téléphone *</label>
                                    <input type="tel" placeholder="Numéro de téléphone du client" wire:model="phone"
                                        class="form-control">
                                    @error('phone')
                                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                                    @enderror
                                </div>
                           
                            </div>
                              
                            </div>
                           
                            <div class="mt-3 mb-1">
                                
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h6>
                                Reherche d'un client déja enregistré !
                            </h6>
                            <div class="mb-3">
                                <input type="text" wire:model.live="recherche"
                                    placeholder="Nom, prenom ,téléphone" class="form-control">
                            </div>
                            <div>
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Téléphone</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>
                                                <span wire:loading>
                                                    <img src="https://i.gifer.com/ZKZg.gif" height="15"
                                                        alt="" srcset="">
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($clients as $client)
                                            <tr>
                                                <td>{{ $client->phone }}</td>
                                                <td>{{ $client->nom }}</td>
                                                <td>{{ $client->prenom }}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm"
                                                        wire:click="import({{ $client }})">
                                                        <i class="ri-import-line"></i>
                                                        Importer
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="alert">
                                                        Aucun résultat disponible
                                                        @if ($recherche)
                                                            " <b> {{ $recherche }} </b> "
                                                        @endif
                                                        !
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>

                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>
                                Montant de la commande : <b> {{ $total }} <x-devise></x-devise></b>
                            </h6>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ri-check-double-line"></i>
                                Valider cette commande
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        @endif





    </div>
