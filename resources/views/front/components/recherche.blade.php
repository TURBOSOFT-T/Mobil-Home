<div class="header-search-modal" id="header-search-modal">
    <button class="card-close sidebar-close"><i class="fas fa-times"></i></button>
    <div class="header-search-wrap">
        <div class="card-header">
            <form action="{{ route('recherche') }}" method="GET">
                <div class="input-group">
                    <input
                        type="text"
                        name="key"
                        id="search"
                        class="form-control"
                        value="{{ request('key') }}"
                        placeholder="{{ \App\Helpers\TranslationHelper::TranslateText('Rechercher un bien, un quartier...') }}">

                    <button type="submit" class="axil-btn btn-bg-primary">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="search-result-header">
                <h6 class="title">{{ \App\Helpers\TranslationHelper::TranslateText('Résultats de recherche') }}</h6>
                <a href="{{ route('recherche') }}" class="view-all">
                    {{ \App\Helpers\TranslationHelper::TranslateText('Voir tout') }}
                </a>
            </div>
            <div class="psearch-results">
                @if (isset($searchproducts) && count($searchproducts) > 0)
                    @foreach ($searchproducts as $produit)
                    <div class="axil-product-list mb-3">
                        <div class="thumbnail">
                            <a href="{{ route('produit.details', $produit->slug ?? $produit->id) }}">
                                <img width="80" height="80" src="{{ $produit->photo ? Storage::url($produit->photo) : asset('icons/no-image.webp') }}" alt="{{ $produit->nom }}" style="object-fit: cover; border-radius: 6px;">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6 class="product-title">
                                <a href="{{ route('produit.details', $produit->slug ?? $produit->id) }}">
                                    {!! \App\Helpers\TranslationHelper::TranslateText($produit->nom ?? '') !!}
                                </a>
                            </h6>

                            <div class="product-price-variant text-success fw-bold">
                                {{ number_format($produit->prix, 0, ',', ' ') }} <x-devise></x-devise>
                            </div>

                            <div class="product-cart mt-1">
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#detailBienModal" data-id="{{ $produit->id }}">
                                    {{ \App\Helpers\TranslationHelper::TranslateText('Détails') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Aucun résultat trouvé.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>