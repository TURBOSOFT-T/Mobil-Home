<div>

    @include('components.alert')

    @if ($service)
        <form wire:submit="update_service">
        @else
            <form wire:submit="create">
    @endif

    <div class="row">
        <div class="col-sm-8">
            <div class="mb-3">
                <label for="">Nom</label>
                <input type="text" name="nom" class="form-control" wire:model="nom">
                @error('nom')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>

            <div class="mb-3" wire:ignore>
                <label><strong>Description :</strong></label>
                <textarea class="form-control" name="description" wire:model="description" rows="10"></textarea>
                @error('description')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>

        </div>
        <div class="col-sm-4">
            <div class="mb-3">
                <label for="">Image d'illustration</label>
                <div class="preview-produit-illustration" onclick="preview_illustration('new-prosduit')">
                    @if ($service)
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="" srcset="">
                        @else
                            <img src="{{ Storage::url($OldPhoto) }}" alt="" class="w-100">
                        @endif
                    @else
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="" class="w-100">
                        @else
                            <img src="/icons/no-image.webp" alt="" class="w-100">
                        @endif
                    @endif
                </div>
                <input type="file" name="image" accept="image/*" class=" d-none" id="file-input-new-prosduit"
                    wire:model="image">
                @error('image')
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
            @if ($service)
                Mettre a jour
            @else
                Enregistrer la service
            @endif
        </button>
    </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        editor.model.document.on('change:data', () => {
            @this.set('description', editor.getData());
        })
    })
    .catch(error => {
        console.error(error);
    });
</script>

