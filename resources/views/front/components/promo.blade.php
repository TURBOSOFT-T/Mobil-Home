<style>
    .product-img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 767px) {
        .product-img {
            height: 180px;
        }
    }

    /* Ajustement local pour resserrer les zones de ce composant */
    .custom-tight-gap {
        padding-top: 30px !important;
        padding-bottom: 20px !important;
    }

    .custom-product-area {
        padding-bottom: 15px !important;
    }
</style>
 <div class="row">
           <div class="col-xl-12">
               <div class="tp-blog-title-box text-center mb-60">

                   <span class="promo-subtitle">

                       {{ \App\Helpers\TranslationHelper::TranslateText(' 🔥 OFFRES SPÉCIALES') }}
                   </span>

                   <h4 class="promo-title">
                       {{ \App\Helpers\TranslationHelper::TranslateText('Produits en promotion') }}💥

                   </h4>

                   <div class="mt-2">
                       <span class="promos-badge">


                           {{ \App\Helpers\TranslationHelper::TranslateText('JUSQU\'À -50%') }}
                       </span>
                   </div>

               </div>
           </div>
       </div>
<br>
<div class="axil-product-area bg-color-white axil-section-gap">
    <!-- En-tête de la section -->

    <!-- Zone des produits -->
    <div class="axil-best-seller-product-area bg-color-white pb--0">
        <!-- Remplacement de pb--50 par pb--10 pour remonter le bas du composant -->
        <div class="product-area pb--10">
            <div class="new-arrivals-product-activation-2 slick-layout-wrapper--15 axil-slick-arrow arrow-top-slide product-slide-mobile">

                @foreach ($produitsPromos as $key => $produit)
                @if ($produit->inPromotion())
                <div class="slick-single-layout">
                    <div class="axil-product product-style-four">
                        <div class="thumbnail">
                            <a href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                <img data-sal="zoom-out" data-sal-delay="200" data-sal-duration="800" loading="lazy" class="main-img product-img" src="{{ Storage::url($produit->photo) }}" alt="{{ $produit->nom }}">
                                <img class="hover-img product-img" src="{{ Storage::url($produit->photo) }}" alt="{{ $produit->nom }}">
                            </a>

                            <style>
                                .top-left {
                                    position: absolute;
                                    top: 8px;
                                    right: 18px;
                                    color: #EFB121;
                                }
                            </style>

                            <div class="top-left">
                                <span>
                                    @if ($produit->inPromotion())
                                    <span class="promo-badge right">-{{ $produit->inPromotion()->pourcentage }}%</span>
                                    @endif
                                </span>
                            </div>

                            @if ($produit->new)
                            <span class="badge-new">NEW</span>
                            @endif

                            <div class="product-hover-action">
                                <ul class="cart-action">
                                    <li class="quickview">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#{{ $produit->id }}">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
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
                                    <li class="wishlist">
                                        <a onclick="AddFavoris({{ $produit->id }})" @if ($count==0) style="color:#000000" @else style="color: #dc3545; background-color:#dc3545" @endif>
                                            <i class="far fa-heart"></i>
                                        </a>
                                    </li>
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
                                        }
                                    </style>
                                </ul>
                            </div>
                        </div>

                        <div class="product-content">
                            <div class="inner">
                                <h5 class="title">
                                    <a href="{{ route('details-produits', ['id' => $produit->id, 'slug' => Str::slug(Str::limit($produit->nom, 10))]) }}">
                                        {{ \App\Helpers\TranslationHelper::TranslateText( Str::limit($produit->nom, 15)) }}
                                    </a>
                                </h5>

                                <div class="product__price__wrapper">
                                    <h6 class="product-price--main">
                                        @if ($produit->inPromotion())
                                        <div class="row">
                                            <div class="col-sm-6 col-6">
                                                <b class="text-success" style="color: #4169E1">
                                                    {{ $produit->getPrice() }} <x-devise></x-devise>
                                                </b>
                                            </div>
                                            <div class="col-sm-6 col-6 text-end">
                                                <strike>
                                                    <span style="font-size: 1.7rem; color: #dc3545; font-weight: bold;">
                                                        {{ $produit->prix }} <x-devise></x-devise>
                                                    </span>
                                                </strike>
                                            </div>
                                        </div>
                                        @else
                                        <span class="price current-price" style="font-size: 1.7rem;">
                                            {{ $produit->getPrice() }} <x-devise></x-devise>
                                        </span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach


            </div>
        </div>

        <!-- Section du bouton externe : Nettoyée de mt--20 pour un alignement optimal -->
        <div class="text-center mb-3">
            <a href="{{ route('shop', ['highlight' => 'promo']) }}" class="axil-btn btn-bg-primary2 btn-load-more">
                {{ \App\Helpers\TranslationHelper::TranslateText('Voir toutes les promotions') }}
            </a>
        </div>
    </div>
</div>