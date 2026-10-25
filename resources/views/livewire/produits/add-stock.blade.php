<div>

    @include('components.alert')

 
    

    <div class="mb-3">
        <label for="">
            Recherche du produit
        </label>
        <input type="text" class="form-control" wire:model.live="produit" placeholder="Nom,Description du produit">

    </div>
    @if ($produits && !is_null($produits) && !$id)
        <table class="table">
            @forelse ($produits as $item)
                <tr>
                    <td>
                        <img src="{{ Storage::url($item->photo) }}" width="30" height="30" class="rounded "
                            alt="">
                    </td>
                    <td>
                        {{ $item->nom }}
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-sm " wire:click="copier({{ $item->id }})">
                            <i class="ri-file-copy-2-line small"></i>
                            Ajouter
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">
                        <div class="text-center">
                            Aucun produit trouvé !
                        </div>
                    </td>
                </tr>
            @endforelse
        </table>
    @endif

    @if ($id)
        <form wire:submit="add">
            <div class="input-group mb-3">
                <input type="number" required class="form-control" wire:model="quantite" placeholder="Quantité">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                        </span>
                        Ajouter
                    </button>
                </div>
            </div>
        </form>
    @endif 
</div>
