@include('sweetalert::alert')
@php
$config = DB::table('configs')->first();
@endphp

@php
$locales = [
'fr' => ['name' => 'Français', 'flag' => 'https://img.icons8.com/color/20/france-circular.png'],
'en' => ['name' => 'English', 'flag' => 'https://img.icons8.com/color/20/great-britain-circular.png'],

];
$currentLocale = app()->getLocale();
@endphp

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Mobil-Home</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ Storage::url($config->icon ?? ' ') }}">

    <!-- CSS
    ============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/font-awesome.css">
    <link rel="stylesheet" href="/assets/css/vendor/flaticon/flaticon.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick-theme.css">
    <link rel="stylesheet" href="/assets/css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/sal.css">
    <link rel="stylesheet" href="/assets/css/vendor/magnific-popup.css">
    <link rel="stylesheet" href="/assets/css/vendor/base.css">
    <link rel="stylesheet" href="/assets/css/style.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Script.js"></script>
    @yield('SEO')

</head>

<style>
    .cart-action li a {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }
</style>

<style>
       .badge-view {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #1E7CE2;
        color: #fff;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 20px;
        font-weight: 500;
        z-index: 2;
    }
    .product-img {
        width: 300px;
        height: 300px;
        object-fit: cover;
        border-radius: 8px;
    }

    .category-img {
        width: 200px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
<style>
    .badge-sold {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #3ac050;
        color: #fff;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: bold;

    }
</style>

<style>
    .best-seller-subtitle {
        display: block;
       color: #F46B02;
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .best-seller-title {
        font-size: 40px;
        font-weight: 800;
        color: #222;
        margin-top: 10px;
    }

    .best-seller-badge {
        display: inline-block;
       background: linear-gradient(45deg, #F46B02, #bf8b11);
        color: #222;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 5px 15px rgba(255, 193, 7, .4);
        animation: goldPulse 1.5s infinite;
    }

    @keyframes goldPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }
    }
</style>
<style>
    .promo-subtitle {
        display: block;
        color: #ff5722;
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .promo-title {
        font-size: 40px;
        font-weight: 800;
        color: #222;
        margin-top: 10px;
    }

    .promos-badge {
        display: inline-block;
      background: linear-gradient(45deg, #F46B02, #ff9800);
        color: #fff;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        animation: flashPromo 1s infinite;
        box-shadow: 0 5px 15px rgba(255, 0, 0, .3);
    }

    @keyframes flashPromo {

        0%,
        100% {
            transform: scale(2);
        }

        50% {
            transform: scale(1.08);
        }
    }
</style>


<style>
    .badge-new-title {
        display: inline-block;
       background: #1E7CE2;
        color: #fff;
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 1px;
        animation: pulseNew 1.2s infinite;
    }

    @keyframes pulseNew {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 200, 83, .5);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(0, 200, 83, 0);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
<style>
    .badge-new {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: #00c853;
        color: white;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: bold;
        border-radius: 6px;
        text-transform: uppercase;
        z-index: 10;
        animation: pulseNew 1.2s infinite;
    }

    /* petite animation */
    @keyframes pulseNew {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
<style>
    .tp-blog-thumb {
        position: relative;
        overflow: hidden;
    }

    /* base badge */
    .promo-badge {
        position: absolute;
        top: 10px;
      background: #EFB121;
        color: #000;
        font-weight: bold;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        z-index: 10;
        animation: pulsePromo 1.2s infinite;
    }

    /* gauche */
    .promo-badge.left {
        left: 10px;
    }



    /* droite */
    .promo-badge.right {
        right: 5px;
        background: #ff3b3b;
        color: #fff;
    }

    /* animation douce */
    @keyframes pulsePromo {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

<style>
    @keyframes clignoter {
        0% {
            opacity: 1;
            /* Visible */
        }

        100% {
            opacity: 0;
            /* Invisible */
        }
    }

    @keyframes defilement {
        0% {
            transform: translateX(100%);
            /* Commence à droite */
        }

        100% {
            transform: translateX(-100%);
            /* Va à gauche */
        }
    }

    .header-top-text {
        width: 100%;
        /* Prend toute la largeur */
        overflow: hidden;
        background: #1E7CE2;
        /* Couleur de fond (modifiable) */
        padding: 10px 0;
        /* Espace haut/bas */
        /*   border: 5px solid #ffffff; */
        /* Bordure blanche de 5px */
        border-radius: 10px;
        /* Arrondi des coins */
    }

    .scroll-text {
        display: block;
        width: 100%;
        white-space: nowrap;
        /* Évite le retour à la ligne */
        overflow: hidden;
        position: relative;
        /* Position relative pour l'animation */
    }

    .scroll-text p {
        display: inline-block;
        padding-left: 100%;
        /* Départ hors écran */
        animation: scroll 40s linear infinite;
        /* Animation infinie */
        font-size: 16px;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        /* Optionnel pour le style */
    }

    @keyframes scroll {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Pause au survol */
    .scroll-text:hover p {
        animation-play-state: paused;
    }
</style>

<style>
    .nav-brand {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 0px;
    }

    .nav-brand img {
        height: 90px;
        width: 80px;
        object-fit: contain;
        transition: transform 0.3s ease;
        margin-top: -11px;
    }

    @media (max-width: 768px) {
        .nav-brand img {
            height: 100px;
            width: 100px;
            margin-top: 30;
            padding: 10;
            margin-left: 20px;



        }
    }

    .menu-toggle {
        display: none;
        font-size: 2em;
        cursor: pointer;
        margin-left: auto;
    }


    .nav-brand:hover img {
        transform: scale(1.6);
    }


    .navbar .nav-brand {
        padding: 5px;
    }

    .navbar .nav-brand img {
        max-height: 50px;
    }
</style>



<body class="sticky-header overflow-md-visible">

    <!-- Start Header -->
    <a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>
    <!-- Start Header -->
    <header class="header axil-header header-style-5">


        <div class="header-top-text">
            <div class="row align-items-center">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="header-top-text">
                        <div class="scroll-text">
                            <p>
                                <i class="fas fa-star" style="color: #f3ba0e; margin-right: 8px;"></i>
                                {!! \App\Helpers\TranslationHelper::TranslateText($config->slogan ?? '') !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="axil-sticky-placeholder"></div>
        <div class="axil-mainmenu">
            <div class="container">
                <div class="header-navbar">
                    <div class="header-brand">
                        <a class="nav-brand" href="{{ route('home') }}">

                            <img src="{{ Storage::url($config->logo ?? ' ') }}" alt="Logo" />
                        </a>
                    </div>
                    <div class="header-main-nav">

                        <nav class="mainmenu-nav">
                            <button class="mobile-close-btn mobile-nav-toggler"><i class="fas fa-times"></i></button>
                            <div class="mobile-nav-brand" style="margin-top: 45px; margin-bottom: 15px; padding-left: 20px;">
                                <a href="{{ route('home') }}" class="logo">
                                    
                                    <img src="{{ Storage::url($config->logo ?? ' ') }}"
                                        alt="Logo"
                                        style="height: 100px; width: 100; object-fit: contain; display: inline-block;" />
                                </a>
                            </div>
                            <ul class="mainmenu">
                                <li><a href="{{ route('home') }}">{{ __('accueil') }}</a>

                                </li>


                                <li><a href="{{ route('shop') }}">{{ __('boutique') }}</a></li>
                                </li class="menu-item">
                                <li><a href="{{ route('about') }}">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('A propos de nous') }}
                                    </a></li>



                                <li><a href="{{ route('contact') }}">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Contact') }}
                                    </a></li>


                                @guest
                                @else
                                @if (auth()->user()->role != 'client')
                                <li><a href="{{ url('dashboard') }}" class="nav-item nav-link">Dashboard</a>
                                </li>
                                @endif







                                @endguest
                            </ul>
                        </nav>

                    </div>
                    <div class="header-action">
                        <ul class="action-list">
                            <li class="axil-search d-xl-block d-none">
                                <input type="search" class="placeholder product-search-input" name="search2"
                                    id="search2" value="" maxlength="128"
                                    placeholder="{{ \App\Helpers\TranslationHelper::TranslateText(" Rechercher
                                                                            produit") }}"
                                    autocomplete="off">
                                <button type="submit" class="icon wooc-btn-search">
                                    <i class="flaticon-magnifying-glass"></i>
                                </button>
                            </li>
                            <li class="axil-search d-xl-none d-block">
                                <a href="javascript:void(0)" class="header-search-icon" title="Search">
                                    <i class="flaticon-magnifying-glass"></i>
                                </a>
                            </li>
                            <li class="wishlist">
                                <a href="{{ route('favories') }}">
                                    <i class="flaticon-heart"></i>
                                </a>
                            </li>
                            <li class="shopping-cart">
                                <a href="#" class="cart-dropdown-btn">
                                    <span class="cart-count" id="count-panier-span">00</span>
                                    <i class="flaticon-shopping-cart"></i>
                                </a>
                            </li>

                            <li class="my-account">
                                <a href="javascript:void(0)">
                                    <i class="far fa-user"></i>
                                </a>
                                <div class="my-account-dropdown">

                                    @if (Auth()->user())
                                    <ul>
                                        @if (auth()->user()->role != 'client')
                                        <li><a href="{{ url('dashboard') }}">Dashboard</a>
                                        </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('account') }}">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Mon compte') }}
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('favories') }}">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Mes favoris') }}
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('cart') }}">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Mon panier') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('profile') }}">{{ __('parametres') }}
                                            </a>
                                        </li>
                                        <li>

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();   document.getElementById('logout-form').submit();">

                                                {{ \App\Helpers\TranslationHelper::TranslateText('Déconnexion') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>




                                    </ul>
                                    @else
                                    <div class="login-btn">
                                        <a href="{{ url('login') }}"
                                            class="axil-btn btn-bg-primary2">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Connexion') }}
                                        </a>
                                    </div>

                                    <div class="reg-footer text-center">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Pas de compte?') }}
                                        <a
                                            href="{{ url('register') }}" class="btn-link">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('S\'inscrire ici.') }}
                                        </a>
                                    </div>
                                    @endif

                                </div>



                            </li>




                            <li>
                                <div class="custom-dropdown">
                                    <form action="{{ route('locale.change') }}" method="POST">
                                        @csrf
                                        <div class="dropdown">
                                            <button type="button" class="dropbtn">
                                                <img src="{{ $locales[$currentLocale]['flag'] ?? $locales['fr']['flag'] }}" alt="{{ $currentLocale }}">
                                                {{ $locales[$currentLocale]['name'] ?? 'Français' }}
                                            </button>

                                            <div class="dropdown-content">
                                                @foreach ($locales as $code => $locale)
                                                <button type="submit" name="locale" value="{{ $code }}" class="dropdown-item">
                                                    <img src="{{ $locale['flag'] }}" alt="{{ $code }}">
                                                    {{ $locale['name'] }}
                                                </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <style>
                                .custom-dropdown {
                                    position: relative;
                                    display: inline-block;
                                }

                                .dropbtn {
                                    display: flex;
                                    align-items: center;
                                    gap: 6px;
                                    background: none;
                                    border: none;
                                    font-family: 'Times New Roman', Times, serif;
                                    font-size: 14px;
                                    font-weight: normal;
                                    color: #003DA5;
                                    cursor: pointer;
                                    padding: 8px 12px;
                                }

                                .dropdown-content {
                                    display: none;
                                    position: absolute;
                                    background-color: #fff;
                                    min-width: 160px;
                                    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                                    z-index: 999;
                                    border-radius: 6px;
                                }

                                .dropdown-content .dropdown-item {
                                    background-color: white;
                                    border: none;
                                    width: 100%;
                                    text-align: left;
                                    padding: 10px 16px;
                                    cursor: pointer;
                                    display: flex;
                                    align-items: center;
                                    font-size: 14px;
                                    color: #000;
                                    /* Texte en noir */
                                }

                                .dropdown-content .dropdown-item img {
                                    margin-right: 8px;
                                }

                                .dropdown-content .dropdown-item:hover {
                                    background-color: #f2f2f2;
                                }

                                .dropdown:hover .dropdown-content {
                                    display: block;
                                }

                                .dropdown:hover .dropbtn {
                                    background-color: #eef4ee;
                                }

                                /* 📱 Mobile (<768px) */
                                @media (max-width: 768px) {
                                    .dropbtn {
                                        font-size: 12px;
                                        padding: 8px;
                                    }

                                    .dropdown-content {
                                        position: fixed;
                                        top: 60px;
                                        right: 0;
                                        width: 70%;
                                        max-width: 280px;
                                        border-radius: 0 0 0 10px;
                                        box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
                                    }

                                    .dropdown-content .dropdown-item {
                                        font-size: 16px;
                                        padding: 14px 20px;
                                        color: #000;
                                        /* Texte en noir */
                                    }
                                }
                            </style>



                            <li class="axil-mobile-toggle">
                                <button class="menu-btn mobile-nav-toggler">
                                    <i class="flaticon-menu-2"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </header>
    <!-- End Header -->


    <!-- End Header -->



    <main>



        @yield('body')




    </main>

    <!-- Bouton TikTok -->
      @if(!empty($config->tiktok))
    <div class="tiktok-dark">
        <a href="https://www.tiktok.com/@{{ $config->tiktok ?? '' }}" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-tiktok"></i>
        </a>
    </div>
    @endif

    <!-- Bouton Facebook Messenger -->
       @if(!empty($config->messenger))
    <div class="messenger-dark">
        <a href="https://m.me/{{ $config->messenger ?? '' }}" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-messenger"></i>
        </a>
    </div>
    @endif

    <!-- Bouton WhatsApp -->
        @if(!empty($config->telephone))
    <div class="whatsapp-dark">
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $config->telephone) }}" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    @endif

    <style>
        /* Style commun pour les boutons flottants */
        .messenger-dark,
        .whatsapp-dark,
        .tiktok-dark {
            position: fixed;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .messenger-dark a,
        .whatsapp-dark a,
        .tiktok-dark a {
            font-size: 30px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Positionnement et styles spécifiques : TikTok */
        .tiktok-dark {
            bottom: 230px;
            /* Au-dessus de Messenger */
            background-color: #010101;
            border: 2px solid #ffffff;
        }

        .tiktok-dark a {
            color: #ffffff;
        }

        .tiktok-dark:hover {
            background-color: #ffffff;
        }

        .tiktok-dark:hover a {
            color: #010101;
        }

        /* Positionnement et styles spécifiques : Messenger */
        .messenger-dark {
            bottom: 160px;
            /* Au-dessus de WhatsApp */
            background-color: #006AFF;
            border: 2px solid #ffffff;
        }

        .messenger-dark a {
            color: #ffffff;
        }

        .messenger-dark:hover {
            background-color: #ffffff;
        }

        .messenger-dark:hover a {
            color: #006AFF;
        }

        /* Positionnement et styles spécifiques : WhatsApp */
        .whatsapp-dark {
            bottom: 90px;
            background-color: #202c33;
            border: 2px solid #25D366;
        }

        .whatsapp-dark a {
            color: #25D366;
        }

        .whatsapp-dark:hover {
            background-color: #25D366;
        }

        .whatsapp-dark:hover a {
            color: #ffffff;
        }
    </style>

    <footer class="axil-footer-area footer-style-2">
        <!-- Start Footer Top Area  -->
        <div class="footer-top separator-top">
            <div class="container">
                <div class="row">
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title"></h5>
                            <style>
                                .logo {
                                    position: relative;
                                    top: -30px;
                                    /* Déplace le logo de 30px vers le haut */
                                }
                            </style>
                            <div class="logo mb--30">
                                <a href="{{ route('home') }}">
                                    <img class="light-logo" src="{{ Storage::url($config->logofooter ?? ' ') }}" alt="Logo" height="200" width="200">
                                </a>
                            </div>

                            <p class="logo" style="font-size: 18px; line-height: 1.6; text-align: justify;">

                                {!! \App\Helpers\TranslationHelper::TranslateText($config->description) !!}
                            </p>


                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title"> {{ \App\Helpers\TranslationHelper::TranslateText('Mon compte') }}</h5>
                            <div class="inner">
                                <ul>
                                    @if (Auth()->user())
                                    <li><a href="{{ route('profile') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Paramètres') }}</a></li>
                                    <li><a href="{{ route('favories') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Mes favoris') }}</a></li>
                                    <li><a href="{{ route('cart') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Mon panier') }}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title"> {{ \App\Helpers\TranslationHelper::TranslateText(' Pages') }}</h5>
                            <div class="inner">
                                <ul>
                                    <li><a href="{{ route('home') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Accueil') }}</a></li>
                                    <li><a href="{{ route('about') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('A propos de nous') }}</a></li>

                                    <li><a href="{{ route('shop') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Produits') }}</a></li>
                                    <li><a href="{{ route('contact') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title">
                                {{ \App\Helpers\TranslationHelper::TranslateText('Contact info') }}
                            </h5>
                            <div class="inner">
                                {{-- <span>Save $3 With App & New User only</span> --}}
                                <div class="download-btn-group">

                                    <div class="inner">

                                        <ul class="support-list-item">
                                            <li><a href="mailto:example@domain.com"><i class="fal fa-envelope-open"></i>
                                                    {{ $config->email ?? ' ' }}</a></li>
                                            <li><a href="tel:(+01)850-315-5862"><i class="fal fa-phone-alt"></i>{{ $config->telephone ?? ' ' }}</a>
                                            </li>
                                            <li><i class="fal fa-map-marker-alt"></i>{{ $config->addresse ?? ' ' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                </div>
            </div>
        </div>
        <!-- End Footer Top Area  -->
        <!-- Start Copyright Area  -->
        <div class="copyright-area copyright-default separator-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-4">

                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="copyright-left d-flex flex-wrap justify-content-center">
                            <ul class="quick-link">
                                <li>©{{ date('Y') }} Market | Design By<a href="#" style="color: #c71f17;">
                                        <b> TURBOSOFT </b>
                                    </a>.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="copyright-right d-flex flex-wrap justify-content-xl-end justify-content-center align-items-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Copyright Area  -->
    </footer>
    <!-- End Footer Top Area  -->


    <!-- End Footer Area  -->



    <!-- Header Search Modal End -->
    @include('front.components.recherche')
    <!-- Header Search Modal End -->


    <!-- le panier  -->
    @include('front.components.panier')



    <style>
        .btn-bg-primary2 {
             background-color: #1E7CE2;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-bg-secondary2 {
              background-color: #F46B02;
            /* Couleur de fond, bleu dans cet exemple */
            color: #ffffff;
            /* Couleur du texte, blanc dans cet exemple */
            border: none;
            padding: 10px 20px;
            /* Optionnel, ajuste la taille */
            border-radius: 5px;
            /* Optionnel, arrondit les coins */
            text-decoration: none;
            /* Supprime le soulignement */
        }
    </style>
    <!-- JS
============================================ -->
    <!-- Modernizer JS -->
    <script src="/assets/js/vendor/modernizr.min.js"></script>
    <!-- jQuery JS -->
    <script src="/assets/js/vendor/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="/assets/js/vendor/popper.min.js"></script>
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <script src="/assets/js/vendor/slick.min.js"></script>
    <script src="/assets/js/vendor/js.cookie.js"></script>
    <!-- <script src="assets/js/vendor/jquery.style.switcher.js"></script> -->
    <script src="/assets/js/vendor/jquery-ui.min.js"></script>
    <script src="/assets/js/vendor/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/js/vendor/jquery.countdown.min.js"></script>
    <script src="/assets/js/vendor/sal.js"></script>
    <script src="/assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="/assets/js/vendor/imagesloaded.pkgd.min.js"></script>
    <script src="/assets/js/vendor/isotope.pkgd.min.js"></script>
    <script src="/assets/js/vendor/counterup.js"></script>
    <script src="/assets/js/vendor/waypoints.min.js"></script>

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>

</body>

</html>