@extends('front.fixe')
@section('titre', $produit->nom)
@section('body')
@php

$config = DB::table('configs')->first();
@endphp

<head>
    @section('SEO')
    <meta name="description" content="{{ $produit->description ?? ' ' }}">
    <meta name="author" content="new-laptop-deals.com">
    <meta property="og:title" content="{{ $produit->nom }}">
    <meta property="og:description" content="{{ $produit->description ?? '' }}">

    <meta property="og:brand" content="{{ $produit->marques->nom ?? '' }}">
    <meta property="og:image" content="{{ $produit->photo }}">
    <meta property="og:type" content="product">
    <meta property="og:price:amount" content="{{ $produit->prix }} ">

    <meta property="og:availability" content="{{ $produit->statut }}">

    <meta property="product:price:amount" content="{{ $produit->prix }} ">

    <meta property="product:availability" content="{{ $produit->statut }}">
    <meta name="robots" content="index, follow">


    @endsection

</head>
<script src="path/to/jquery.js"></script>
<script src="path/to/jquery.elevatezoom.js"></script>


<main>

    <main class="main-wrapper">
        <!-- Start Shop Area  -->
        <div class="axil-single-product-area axil-section-gap pb--0 bg-color-white">
            <div class="single-product-thumb mb--40">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="shop-details-img">
                                <div class="tab-content" id="v-pills-tabContent">

                                    <div class="shop-details-tab-img product-img--main" id="zoomContainers"
                                        data-scale="1.4" style="overflow: hidden; position: relative;">
                                        @if ($produit->inPromotion())
                                        <span class="promo-badge">PROMO</span>
                                        <span class="promo-badge right">
                                            -{{ $produit->inPromotion()->pourcentage }}%
                                        </span>
                                        @endif
                                        <img id="mainImage" src="{{ Storage::url($produit->photo) }}" height="600"
                                            width="600" alt="Product image"
                                            style="transition: transform 0.3s ease;" />

                                        @if ($produit->new)
                                        <span class="badge-new">NEW</span>
                                        @endif
                                        @if ($produit->vendus_sum_quantite > 0)
                                        <span class="badge-sold">
                                            {{ $produit->vendus_sum_quantite }}
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Vendu(s)') }}
                                        </span>
                                        @endif
                                    </div>


                                </div>
                                <br><br>

                                <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach (json_decode($produit->photos) ?? [] as $image)
                                    <div class="slider__item">
                                        <img onclick="changeMainImage('{{ Storage::url($image) }}')"
                                            src="{{ Storage::url($image) }}" width="100" height="100"
                                            style="border-radius: 8px;" alt="Additional product image" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <script>
                                function changeMainImage(imageUrl) {
                                    document.getElementById('mainImage').src = imageUrl;
                                }
                            </script>

                            <script>
                                const zoomContainers = document.getElementById('zoomContainers');
                                const mainImage = document.getElementById('mainImage');
                                const scale = zoomContainers.getAttribute('data-scale') || 1.4;


                                zoomContainers.addEventListener('mouseover', function() {
                                    mainImage.style.transform = `scale(${scale})`;
                                    mainImage.style.cursor = "zoom-in";
                                });


                                zoomContainers.addEventListener('mouseout', function() {
                                    mainImage.style.transform = "scale(1)";
                                });


                                function changeMainImage(imageUrl) {
                                    mainImage.src = imageUrl;
                                    mainImage.style.transform = "scale(1)";
                                }
                            </script>




                        </div>

                        <div class="col-lg-5 mb--40">
                            <div class="single-product-content">
                                <div class="inner">
                                    <h3 class="product-title"> {{ \App\Helpers\TranslationHelper::TranslateText($produit->nom) }}
                                       
                                    </h3>

                                    <span class="price-amount">

                                        @if ($produit->inPromotion())
                                        <div class="row">
                                            <div class="col-sm-6 col-6">

                                                <b class="text-success" style="color: #4169E1">
                                                    {{ $produit->getPrice() }} <x-devise></x-devise>
                                                </b>
                                            </div>

                                            <div class="col-sm-6 col-6 text-end">


                                                <span
                                                    style="position: relative; font-size: 1.7rem; color: #dc3545; font-weight: bold;">
                                                    {{ $produit->prix }} <x-devise></x-devise>
                                                    <span
                                                        style="position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background-color: black;"></span>
                                                </span>


                                            </div>
                                            @else

                                            <span class="price current-price">

                                                {{ $produit->getPrice() }} <x-devise></x-devise>
                                                </b></span>

                                            @endif
                                    </span>
                                    <div class="product-rating">

                                    </div>
                                    <ul class="product-meta">
                                        @if ($produit->stock > 0)
                                        <label class="badge btn-bg-primary2"> {{ \App\Helpers\TranslationHelper::TranslateText('Stock disponible') }}</label>
                                        @else
                                        <label class="badge bg-danger"> {{ \App\Helpers\TranslationHelper::TranslateText('Stock non disponible') }}</label>
                                        @endif
                                        <br>

                                        <li><span style="color: #EFB121"> {{ \App\Helpers\TranslationHelper::TranslateText('Categorie') }}:</span> <span style="color: #5EA13C">

                                                {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->categories->nom, 30)) }}
                                            </span></li>

                                        <li><span style="color: #EFB121"> {{ \App\Helpers\TranslationHelper::TranslateText('Famille') }}:</span> <span style="color: #5EA13C">

                                                {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->familles->nom ?? '', 300)) }}
                                            </span></li>


                                        <li><span style="color: #EFB121"> {{ \App\Helpers\TranslationHelper::TranslateText('Royon') }}:</span> <span style="color: #5EA13C">

                                                {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->sous_categories->nom ?? ' ', 300)) }}
                                            </span></li>



                                        <li><span style="color: #EFB121"> {{ \App\Helpers\TranslationHelper::TranslateText('Reference') }}:</span> <span style="color: #5EA13C">

                                                {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->reference, 300)) }}
                                            </span></li>
                                    </ul>
                                    


                                    <div class="product-variations-wrapper">

                                        @if($produit->caracteristiques)
                                        @php
                                        // Décode le JSON en tableau si c'est encore une chaîne de caractères
                                        $items = is_string($produit->caracteristiques)
                                        ? json_decode($produit->caracteristiques, true)
                                        : $produit->caracteristiques;
                                        @endphp

                                        @if(is_array($items) || is_object($items))
                                        <ul>
                                            @foreach($items as $item)
                                            @if(!empty($item['cle']) && !empty($item['valeur']))
                                            <li><strong>{{ $item['cle'] }} :</strong> {{ $item['valeur'] }}</li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        @endif
                                        @endif
                                        <div class="product-variation">

                                        </div>



                                        <div class="product-variation product-size-variation">

                                        </div>


                                    </div>

                                    <!-- Start Product Action Wrapper  -->
                                    <div class="product-action-wrapper d-flex-center">
                                        <!-- Start Quentity Action  -->
                                        <div class="pro-qty">
                                            {{-- <input type="text" value="1"> --}}
                                            <span class="quantity-control minus"></span>
                                            <input type="number" class="input-text qty text" name="quantite"
                                                min="1" value="1" id="qte-{{ $produit->id }}"
                                                autocomplete="off">
                                            <span class="quantity-control plus"></i></span>
                                        </div>



                                        <!-- End Quentity Action  -->

                                        <!-- Start Product Action  -->
                                        <ul class="product-action d-flex-center mb--0">

                                            <li class="select-option2"><a
                                                    onclick="AddToCart( {{ $produit->id }} )">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Ajouter au panier') }}</a></li>
                                            <style>
                                                .select-option2 {
                                                    background-color: #5EA13C;
                                                    color: #ffffff;
                                                    border: none;
                                                    padding: 10px 20px;
                                                    border-radius: 5px;
                                                    text-decoration: none;
                                                }
                                            </style>
                                            {{-- <li class="add-to-cart"><a onclick="AddToCart( {{ $produit->id }} )"
                                            class=" badge btn-bg-primary2">Ajouter au panier</a></li> --}}
                                            @if (Auth()->user())

                                            @php

                                            $count = DB::table('favoris')
                                            ->where('id_user', Auth()->user()->id)
                                            ->where('id_produit', $produit->id)
                                            ->count();
                                            @endphp
                                            <li class="wishlist">

                                                <a onclick="AddFavoris({{ $produit->id }})"

                                                    class="axil-btn wishlist-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            @endif

                                            <style>
                                                .btn-bg-primary2 {
                                                    background-color: #5EA13C;
                                                    color: #ffffff;
                                                    border: none;
                                                    padding: 10px 20px;
                                                    border-radius: 5px;
                                                    text-decoration: none;
                                                }
                                            </style>
                                        </ul>
                                        <!-- End Product Action  -->

                                    </div>
                                    <!-- End Product Action Wrapper  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End .single-product-thumb -->

            <div class="woocommerce-tabs wc-tabs-wrapper bg-vista-white">
                <div class="container">
                    <ul class="nav tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="active" id="description-tab" data-bs-toggle="tab" href="#description"
                                role="tab" aria-controls="description" aria-selected="true"><span style="color: #EFB121"> {{ \App\Helpers\TranslationHelper::TranslateText('Description') }}</span></a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel"
                            aria-labelledby="description-tab">
                            <div class="product-desc-wrapper">
                                <div class="row">
                                    <div class="col-lg-12 mb--30">
                                        <div class="single-desc">

                                            <p>
                                                {!! \App\Helpers\TranslationHelper::TranslateText($produit->description ?? ' ') !!}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- End .col-lg-6 -->

                                    <!-- End .col-lg-6 -->
                                </div>
                                <!-- End .row -->

                                <!-- End .row -->
                            </div>
                            <!-- End .product-desc-wrapper -->
                        </div>

                    </div>
                </div>
            </div>
            <!-- woocommerce-tabs -->

        </div>
        <!-- End Shop Area  -->

        <!-- Start Recently Viewed Product Area  -->
        <div class="axil-product-area bg-color-white axil-section-gap pb--50 pb_sm--30">
            <div class="container">
                <div class="section-title-wrapper">
                    <h4> <span class="axil-breadcrumb-item1 active" aria-current="page"> <i class="far fa-shopping-basket"></i>

                            {{ \App\Helpers\TranslationHelper::TranslateText('Les produits de la même categorie') }}
                        </span> </h4>


                    <h2 class="title"> {{ \App\Helpers\TranslationHelper::TranslateText('Parcourir') }}</h2>
                </div>
                <div class="recent-product-activation slick-layout-wrapper--15 axil-slick-arrow arrow-top-slide">

                    @if ($produitsSimilaires)
                    @foreach ($produitsSimilaires as $produit)
                    <div class="slick-single-layout">
                        <div class="axil-product">
                            <div class="thumbnail">
                                <a

                                    href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                    @if ($produit->inPromotion())
                                    <span class="promo-badge">PROMO</span>
                                    <span class="promo-badge right">
                                        -{{ $produit->inPromotion()->pourcentage }}%
                                    </span>
                                    @endif
                                    <img src="{{ Storage::url($produit->photo) }}" alt="Product Images">

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
                                        @if (Auth()->user())
                                        <li class="wishlist"><a
                                                onclick="AddFavoris({{ $produit->id }})"><i
                                                    class="far fa-heart"></i></a></li>
                                        @endif
                                        <li class="axil-btn  btn-bg-primary2 "><a
                                                onclick="AddToCart( {{ $produit->id }} )">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Ajouter au panier') }}
                                            </a></li>
                                        {{-- <li class="quickview"><a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#quick-view-modal"><i
                                                            class="far fa-eye"></i></a></li> --}}

                                    </ul>
                                </div>
                            </div>
                            <div class="product-content">
                                <div class="inner">
                                    <h5 class="title"><a
                                            href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}"> {{ \App\Helpers\TranslationHelper::TranslateText(Str::limit($produit->nom, 15)) }}
                                         

                                        </a>
                                    </h5>
                                    <div class="product-price-variant">
                                        <h6 class="product-price--main">


                                            @if ($produit->inPromotion())
                                            <div class="row">
                                                <div class="col-sm-6 col-6">

                                                    <b class="text-success" style="color: #4169E1">
                                                        {{ $produit->getPrice() }} <x-devise></x-devise>
                                                    </b>
                                                </div>

                                                <div class="col-sm-6 col-6 text-end">



                                                    {{-- <span style="font-size: 1.2rem; color: #dc3545; font-weight: bold;">
                                                            {{ $produit->prix }} DT
                                                    </span> --}}
                                                    <span class="price old-price"
                                                        style="position: relative; font-size: 1.2rem; color: #dc3545; font-weight: bold;">
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
                                    <div class="color-variant-wrapper">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif



                </div>
            </div>
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

                .axil-breadcrumb-item:not(.active)::after {
                    content: " / ";
                    /* Adds a separator after non-active items */
                    color: #EFB121;
                }
            </style>
            <!-- End Axil Newsletter Area  -->
    </main>

</main>
@endsection