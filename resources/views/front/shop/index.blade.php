@extends('front.fixe')
@section('titre', 'Shop')
@section('body')

<head>
    <!--  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  -->
</head>


<main>
    


    <body class="sticky-header">

        <a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>

        <main class="main-wrapper">
            <!-- Start Breadcrumb Area  -->
            <div class="axil-breadcrumb-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <div class="inner">
                                <ul class="axil-breadcrumb">
                                    <li class="axil-breadcrumb-item"><a href="{{ route('home') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Accueil') }}</a></li>
                                    <li class="separator"></li>
                                    <li class="axil-breadcrumb-item1 active" aria-current="page"> {{ __('boutique') }}</li>
                                </ul>


                                <style>
                                    .axil-breadcrumb-item1 {
                                        font-size: 14px;
                                        color: #EFB121;
                                        /* Default breadcrumb color */
                                    }

                                    .axil-breadcrumb-item.active {
                                        font-weight: bold;
                                        color: #EFB121;
                                        /* Distinct color for active item */
                                    }
                                </style>
                                <h1 class="title">
                                    {{ \App\Helpers\TranslationHelper::TranslateText('Explorez tous les produits') }}
                                </h1>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            {{-- <div class="inner">
                                    <div class="bradcrumb-thumb">
                                        <img src="{{ Storage::url($config->image_shop) }}" alt="Image">
                        </div>
                    </div> --}}
                </div>
            </div>
            </div>
            </div>
            <!-- End Breadcrumb Area  -->

            <!-- Start Shop Area  -->
            <div class="axil-shop-area axil-section-gap bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="axil-shop-sidebar">
                                <div class="d-lg-none">
                                    <button class="sidebar-close filter-close-btn"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="toggle-list product-categories active">
                                    <h6 class="title"> {{ \App\Helpers\TranslationHelper::TranslateText('CATEGORIES') }}</h6>
                                    <div class="shop-submenu">
                                        <ul>
                                            <li class="current-cat"><a href="/shop"> {{ \App\Helpers\TranslationHelper::TranslateText('Tous les produits') }}</a></li>

                                            @foreach ($categories as $category)
                                            <li>

                                                <a href="/shop?id_categorie={{ $category->id }}"
                                                    class="small {{ isset($current_category) && $current_category->id === $category->id ? 'selected' : '' }}">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText( Str::limit($category->nom, 25)) }}
                                                    <span>({{ $category->produits->count() }})</span>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="toggle-list product-categories product-gender active">
                                    <h6 class="title">MARQUES</h6>
                                    <div class="shop-submenu">
                                        <ul>
                                            @foreach ($marques as $marque)
                                            <li><a href="/shop?marque_id={{ $marque->id }}"
                                                    class="{{ isset($current_marque) && $current_marque->id === $marque->id ? 'selected' : '' }}">{{ $marque->nom }}
                                                    ({{ $marque->produits->count() }})</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>


                                  <div class="toggle-list product-categories product-gender active">
                                    <h6 class="title">RAYONS</h6>
                                    <div class="shop-submenu">
                                        <ul>
                                            @foreach ($sous_categories as $sous_categorie)
                                            <li><a href="/shop?sous_categorie_id={{ $sous_categorie->id }}"
                                                    class="{{ isset($current_sous_categorie) && $current_sous_categorie->id === $sous_categorie->id ? 'selected' : '' }}">{{ $sous_categorie->nom }}
                                                    ({{ $sous_categorie->produits->count() }})</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                  <div class="toggle-list product-categories product-gender active">
                                    <h6 class="title">Familles</h6>
                                    <div class="shop-submenu">
                                        <ul>
                                            @foreach ($familles as $famille)
                                            <li><a href="/shop?famille_id={{ $famille->id }}"
                                                    class="{{ isset($current_famille) && $current_famille->id === $famille->id ? 'selected' : '' }}">{{ $famille->nom }}
                                                    ({{ $famille->produits->count() }})</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>


                                <div class="toggle-list product-price-range active">
                                    <h6 class="title">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('PRIX') }}
                                    </h6>

                                    <div class="shop-submenu">

                                        <form action="{{ route('shop') }}" method="GET" id="priceForm">

                                            {{-- conserver les autres filtres --}}
                                            <input type="hidden" name="key" value="{{ request('key') }}">
                                            <input type="hidden" name="id_categorie" value="{{ request('id_categorie') }}">
                                            <input type="hidden" name="highlight" value="{{ request('highlight') }}">
                                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">

                                            <input type="hidden" name="min_price" id="min_price">
                                            <input type="hidden" name="max_price" id="max_price">

                                            <div id="slider-range"></div>

                                            <div class="flex-center mt--20">
                                                <span class="input-range">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Prix:') }}
                                                </span>
                                                <input type="text" id="amount" class="amount-range" readonly> 
                                            </div>

                                            <button type="submit" class="axil-btn btn-bg-primary mt-3 w-100">

                                                {{ \App\Helpers\TranslationHelper::TranslateText('Appliquer les filtres') }}
                                            </button>

                                        </form>

                                    </div>
                                </div>



                                <a href="{{ route('shop.reset') }}" class="axil-btn btn-bg-primary mt-3 w-100">
                                    {{ \App\Helpers\TranslationHelper::TranslateText('Réinitialiser les filtres') }}
                                </a>
                            </div>
                            <!-- End .axil-shop-sidebar -->
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="axil-shop-top mb--40">
                                        <div
                                            class="category-select align-items-center justify-content-lg-end justify-content-between">
                                            <!-- Start Single Select  -->
                                            <span class="filter-results tp-product__filter"> {{ \App\Helpers\TranslationHelper::TranslateText('Filtrer') }}</span>


                                            <select class="single-select" onchange="applyFilter(this)">
                                                <option value="/shop">Default</option>

                                                <option value="promo">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Promotions') }}
                                                </option>
                                                <option value="is_new">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Nouveautés') }}
                                                </option>
                                                <option value="best">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Les plus vendus') }}
                                                </option>

                                                <option value="most_viewed">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Les plus vus') }}
                                                </option>

                                                <option value="lowest_price">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Prix croissant') }}
                                                </option>
                                                <option value="highest_price">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Prix décroissant') }}
                                                </option>
                                            </select>
                                            <!-- End Single Select  -->
                                            <div class="d-lg-none">
                                                <button class="product-filter-mobile filter-toggle"><i
                                                        class="fas fa-filter"></i> {{ \App\Helpers\TranslationHelper::TranslateText('Filtrer') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End .row -->
                                <div class="row row--15">
                                    @if ($produits)

                                    @foreach ($produits as $key => $produit)
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="axil-product product-style-one mb--30">
                                            <div class="thumbnail">
                                                <a
                                                    href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                    @if ($produit->inPromotion())
                                                    <span class="promo-badge">PROMO</span>
                                                    <span class="promo-badge right">
                                                        -{{ $produit->inPromotion()->pourcentage }}%
                                                    </span>
                                                    @endif
                                                    <img class="main-img product-img" src="{{ Storage::url($produit->photo) }}"
                                                        alt="{{ $produit->nom }}"
                                                        style="max-width: 300px; max-height: 300px;">

                                                    @if ($produit->new)
                                                    <span class="badge-new">NEW</span>
                                                    @endif
                                                    @if ($produit->vendus_sum_quantite > 0)
                                                    <span class="badge-sold">
                                                        {{ $produit->vendus_sum_quantite }}
                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Vendu(s)') }}
                                                    </span>
                                                    @endif

                                                </a>
                                                <div class="product-hover-action">
                                                    <ul class="cart-action">

                                                        <li class="quickview"><a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#{{ $produit->id }}"><i
                                                                    class="far fa-eye"></i></a></li>
                                                        {{-- <li class="quickview"><a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#{{ $produit->id }}"><i class="far fa-eye"></i></a></li> --}}
                                                        <li class="select-option2">
                                                            <a onclick="AddToCart( {{ $produit->id }} )">
                                                                {{ \App\Helpers\TranslationHelper::TranslateText('Ajouter au panier') }}
                                                            </a>
                                                        </li>


                                                        @if (Auth()->user())

                                                        @php

                                                        $count = DB::table('favoris')
                                                        ->where('id_user', Auth()->user()->id)
                                                        ->where('id_produit', $produit->id)
                                                        ->count();
                                                        @endphp


                                                        <li class="wishlist"><a onclick="AddFavoris({{ $produit->id }})" @if ($count==0) class="" style="color:#000000" @else class="" style="color: #dc3545; background-color:#dc3545" @endif>

                                                                <i class="far fa-heart"></i></a></li>
                                                        @endif



                                                        <style>
                                                            .select-option2 {
                                                                background-color: #5EA13C;
                                                                color: #ffffff;
                                                                border: none;
                                                                padding: 10px 20px;
                                                                border-radius: 5px;
                                                                text-decoration: none;
                                                            }

                                                            .favori-actif {
                                                                color: red;
                                                                /* Changez la couleur selon votre besoin */
                                                            }
                                                        </style>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <div class="inner">
                                                    <h5 class="title"><a
                                                            href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                                            {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->nom, 15)) }}
                                                           
                                                        </a>
                                                    </h5>

                                                    <div class="product-price-variant">
                                                        <h6 class="product-price--main">


                                                            @if ($produit->inPromotion())
                                                            <div class="row">
                                                                <div class="col-sm-6 col-6">

                                                                    <b class="text-success"
                                                                        style="color: #4169E1">
                                                                        {{ $produit->getPrice() }} <x-devise></x-devise>
                                                                    </b>
                                                                </div>

                                                                <div class="col-sm-6 col-6 text-end">




                                                                    <span class="price old-price"
                                                                        style="position: relative; font-size: 1.5rem; color: #dc3545; font-weight: bold;">
                                                                        {{ $produit->prix }} <x-devise></x-devise>
                                                                        <span
                                                                            style="position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background-color: black;"></span>
                                                                    </span>


                                                                </div>
                                                                @else
                                                                {{ $produit->getPrice() }} <x-devise></x-devise>
                                                                @endif



                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif

                                    <!-- End Single Product  -->
                                </div>
                                <div class="text-center pt--20">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End .container -->
                </div>
                <!-- End Shop Area  -->

                <!-- End Axil Newsletter Area  -->
        </main>




        <!-- Product Quick View Modal Start -->
        @if ($produits)
        @foreach ($produits as $key => $produit)
        @include('front.components.modal')
        @endforeach
        @endif
        <!-- Product Quick View Modal End -->
        </div>
        <script>
            function applyFilter(select) {
                const url = new URL(window.location.href);
                const value = select.value;

                // uniquement reset des filtres concernés
                url.searchParams.delete('highlight');
                url.searchParams.delete('sort_by');

                if (value === 'promo' || value === 'is_new' || value === 'best' || value === 'most_viewed') {
                    url.searchParams.set('highlight', value);
                }

                if (value === 'lowest_price' || value === 'highest_price') {
                    url.searchParams.set('sort_by', value);
                }

                if (value === 'default') {
                    // rien à ajouter
                }

                window.location.href = url.toString();
            }
        </script>
<script>
    $(function() {
        // Utilisation correcte de la syntaxe Blade
        var maxPrix = {{ $max_prix ?? 1000000 }};
        var minSelected = {{ request('min_price', 0) }};
        var maxSelected = {{ request('max_price', $max_prix ?? 1000000) }};

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: maxPrix,
            values: [minSelected, maxSelected],
            slide: function(event, ui) {
                // Mise à jour avec le suffixe XFCA
                $("#amount").val(ui.values[0] + " - " + ui.values[1] + " XFCA");
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            },
            change: function(event, ui) {
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            }
        });

        // Initialisation de l'affichage au chargement de la page
        $("#amount").val(
            $("#slider-range").slider("values", 0) + " - " + 
            $("#slider-range").slider("values", 1) + " XFCA"
        );
        
        // Remplissage des inputs cachés au chargement
        $("#min_price").val($("#slider-range").slider("values", 0));
        $("#max_price").val($("#slider-range").slider("values", 1));
    });
</script>

</main>
@endsection

</body>