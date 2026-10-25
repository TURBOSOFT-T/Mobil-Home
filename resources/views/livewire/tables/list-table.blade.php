<div class="row">
    <div class="col-sm-8">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0 my-auto">
                        Liste des tables
                    </h5>
                </div>
                <div class="table-responsive-sm">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead class="table-dark cusor">
                            <tr>

                                <th>Image</th>

                                <th>Nom</th>

                                <th>création</th>
                                <th style="text-align: right;">
                                    <span wire:loading>
                                        <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20"
                                            class="rounded shadow" alt="">
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tables as $transport)
                                <tr data-id="{{ $transport->id }}" class="cusor">
                                    <td>
                                        <img src="{{ Storage::url($transport->photo) }}" width="40 " height="40 "
                                            class="rounded shadow" alt="">
                                    </td>
                                    <td> {{ $transport->nom }} </td>


                                    <td> {{ $transport->created_at->format('d/m/Y') }} </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary shadow-sm px-2"
                                            wire:click="edit({{ $transport->id }})">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </button>


                                        <button class="btn btn-sm btn-danger"
                                            onclick="toggle_confirmation({{ $transport->id }})">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success d-none" type="button"
                                            id="confirmBtn{{ $transport->id }}"
                                            wire:click="delete({{ $transport->id }})">
                                            <i class="bi bi-check-circle"></i>
                                            <span class="hide-tablete">
                                                Confirmer
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center p-3">
                                            <p>Aucune table trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @if ($showEditModal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Modifier la Table</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="update">
                            <div class="mb-3">
                                <label>Nom</label>
                                <input type="text" class="form-control" wire:model="nom">
                                @error('nom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Photo actuelle :</label><br>
                                @if ($oldPhoto)
                                    <img src="{{ Storage::url($oldPhoto) }}" width="80" class="rounded mb-2">
                                @endif
                                <input type="file" class="form-control" wire:model="photo">
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showEditModal', false)">Annuler</button>
                                <button type="submit" class="btn btn-success">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="col-sm-4">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0 my-auto">
                        Enregistrement
                    </h5>
                </div>
                <form wire:submit="save">
                    <div class="mb-2">
                        <label for="">Nom</label>
                        <input type="text" name="nom" wire:model="nom" class="form-control" id="">
                        @error('nom')
                            <span class="small text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="form-control" wire:model="photo">
                        @error('photo')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading>
                                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                            </span>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
