@extends('front.fixe')
@section('titre', 'Accueil')
@section('body')

@php
$config = DB::table('configs')->first();
$service = DB::table('services')->get();
$produit = DB::table('produits')->get();
@endphp
<style>
    .axil-breadcrumb-item1 {
        font-size: 14px;
        color: #EFB121;
    }

    .axil-breadcrumb-item.active {
        font-weight: bold;
        color: #EFB121;
    }

    .axil-breadcrumb-item:not(.active)::after {
        content: " / ";
        color: #EFB121;
    }

    /* --- Réduction des écarts entre les composants vers le bas --- */

    /* Réduit l'espace interne (haut/bas) de toutes les sections majeures */
    .axil-section-gap,
    .axil-section-gapcommon,
    .axil-product-area,
    .axil-most-view-area,
    .tp-blog-area {
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }

    /* Réduit l'espace sous les blocs de titres de chaque section */
    .section-title-wrapper,
    .tp-blog-title-box,
    .mb-60,
    .mb-55,
    .mb-40 {
        margin-bottom: 20px !important;
    }

    /* Ajustements spécifiques pour les écrans mobiles */
    @media (max-width: 767px) {

        .axil-section-gap,
        .axil-section-gapcommon,
        .axil-product-area {
            padding-top: 25px !important;
            padding-bottom: 25px !important;
        }

        .mb-60,
        .mb-55,
        .mb-40 {
            margin-bottom: 15px !important;
        }

        /* Supprime les marges négatives hautes qui créent parfois des décalages bizarres */
        .mt--20,
        .mt--40 {
            margin-top: 0px !important;
        }
    }
</style>
<main class="main-wrapper">


    @include('front.components.banner')
    @include('front.components.nouveautes')
    @include('front.components.bestseller')

    @include('front.components.promo')


    @include('front.components.produits')

    @if ($produitshome)
    @foreach ($produitshome as $key => $produit)
    @include('front.components.modal')
    @endforeach
    @endif




    @include('front.components.stat')

    <!-- End About Area  -->


    @include('front.components.promotion')
    <!-- End Expolre Product Area  -->
    @include('front.components.testimonials')

    @include('front.components.mostviews')
    @include('front.components.category')
</main>





@endsection