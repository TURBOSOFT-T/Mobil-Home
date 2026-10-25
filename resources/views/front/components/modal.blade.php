<!-- Product Quick View Modal Start -->

<div class="modal fade quick-view-product" id="{{ $produit->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="single-product-thumb">
                    <div class="row">
                        <div class="col-lg-7 mb--40">
                            {{-- <div class="col-lg-6"> --}}
                            <div class="shop-details-img">
                                <div class="tab-content" id="v-pills-tabContent">

                                    <div class="shop-details-tab-img product-img--main" id="zoomContaine" data-scale="1.4" style="overflow: hidden; position: relative;">
                                        @if ($produit->inPromotion())
                                        <span class="promo-badge">PROMO</span>
                                        <span class="promo-badge right">
                                            -{{ $produit->inPromotion()->pourcentage }}%
                                        </span>
                                        @endif


                                        <img id="mainImage" src="{{ Storage::url($produit->photo) }}" height="600" width="700" alt="Product image" style="transition: transform 0.3s ease;" />

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
                                    <!--   @foreach (json_decode($produit->photos) ?? [] as $image)
                                    <div class="slider__item">
                                        <img onclick="changeMainImage('{{ Storage::url($image) }}')" src="{{ Storage::url($image) }}" width="100" height="100" style="border-radius: 8px;" alt="Additional product image" />
                                    </div>
                                    @endforeach -->

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 mb--40">
                            <div class="single-product-content">
                                <div class="inner">

                                    <h3 class="product-title">{{ $produit->nom }}
                                     
                                      
                                        <br>
                                        @if($produit->vues_count > 0)
                                        <span class="badge-vie">
                                            👁️ {{ number_format($produit->vues_count) }} vues
                                        </span>
                                        @endif
                                    </h3>
                                    <span class="price-amount">
                                        @if ($produit->inPromotion())
                                        <b class="text-success" style="color: #4169E1">
                                            {{ $produit->getPrice() }} <x-devise></x-devise>
                                        </b>

                                        <span style="position: relative; font-size: 1.5rem; color: #dc3545; font-weight: bold;">
                                            {{ $produit->prix }} <x-devise></x-devise>
                                            <span style="position: absolute; top: 50%; left: 0; width: 100%; height: 2px; background-color: black;"></span>
                                        </span>
                                        @else
                                        {{ $produit->getPrice() }} <x-devise></x-devise>
                                        @endif
                                    </span>
                                    <ul class="product-meta">
                                        @if ($produit->stock > 0)
                                        <label class="badge btn-bg-primary2"> Stock disponible</label>
                                        @else
                                        <label class="badge bg-danger"> Stock non
                                            disponible</label>
                                        @endif

                                       <!--  <li>Categorie:<span>
                                                {{ Str::limit($produit->categories->nom, 30) }}</span>
                                        </li>
                                        <li> <span>Reference:</span> {{ $produit->reference }}</li> -->
                                    </ul>
                                    <p class="description">{!! $produit->meta_description !!}</p>

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
                                    </div>


                                    <div class="product-action-wrapper d-flex-center">

                                        <div class="pro-qty">
                                            <span class="quantity-control minus"></span>
                                            <input type="number" class="input-text qty text" name="quantite" min="1" value="1" id="qte-{{ $produit->id }}" autocomplete="off">
                                            <span class="quantity-control plus"></i></span>
                                        </div>

                                        <ul class="product-action d-flex-center mb--0">
                                            <li class="add-to-cart"><a onclick="AddToCart( {{ $produit->id }} )" class="axil-btn btn-bg-primary2">Ajouter au
                                                    panier</a></li>
                                            @if (Auth()->user())
                                            <li class="wishlist"><a onclick="AddFavoris({{ $produit->id }})"><i class="far fa-heart"></i></a></li>
                                            @endif
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
        </div>
    </div>
</div>