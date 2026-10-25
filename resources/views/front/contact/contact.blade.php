@extends('front.fixe')
@section('titre', "Contact")
@section('body')
<main>



    <!-- Start Breadcrumb Area  -->
    <div class="axil-breadcrumb-area">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 col-md-8">
                    <div class="inner">
                        <ul class="axil-breadcrumb">
                            <li class="axil-breadcrumb-item"><a href="{{ route('home') }}"> {{ \App\Helpers\TranslationHelper::TranslateText('Accueil') }}</a></li>
                            <li class="separator"></li>
                            <li class="axil-breadcrumb-item1 active" aria-current="page"> {{ \App\Helpers\TranslationHelper::TranslateText('Contact') }}</li>
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
                            {{ \App\Helpers\TranslationHelper::TranslateText('Contactez-nous') }}
                        </h1>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4">
    <div class="inner">
        <div class="bradcrumb-thumb">
            <img src="{{ Storage::url($configs->image_contact) }}" 
                 alt="Image de contact" 
                 style="width: 960px; height: 300px; object-fit: cover; width: 100%; max-width: 960px;" 
                 loading="lazy">
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area  -->

    <!-- Start Contact Area  -->
    <div class="axil-contact-page-area axil-section-gap">
        <div class="container">
            <div class="axil-contact-page">
                <div class="row row--30">
                   <div class="col-lg-8">
    <div class="contact-form">
        <h3 class="title mb--10">
            {{ \App\Helpers\TranslationHelper::TranslateText('Prêt à booster vos performances ?') }}
        </h3>
        
        <p>
            {{ \App\Helpers\TranslationHelper::TranslateText('Que vous recherchiez l\'ordinateur idéal pour vos projets, une configuration sur-mesure ou que vous souhaitiez collaborer avec nous, écrivez-nous. Notre équipe d\'experts est à votre écoute pour vous guider.') }}
        </p>

        @livewire('Front.ContactForm')
    </div>
</div>
                    <div class="col-lg-4">
                        <div class="contact-location mb--40">
                            <h4 class="title mb--20">
                                {{ \App\Helpers\TranslationHelper::TranslateText('Notre magasin') }}
                            </h4>
                            <span class="address mb--20"> {{ $configs->addresse ?? ' ' }}</span>
                            <span class="phone">Télphone: {{ $configs->telephone ?? ' ' }}</span>
                            <span class="email">Email: {{ $configs->email ?? ' ' }}</span>
                        </div>

                        <div class="opening-hour">
                            <h4 class="title mb--20">
                                {{ \App\Helpers\TranslationHelper::TranslateText('Horaires ouverture') }}:
                            </h4>
                            <p>
                                {{ \App\Helpers\TranslationHelper::TranslateText('Du lundi  au samedi: 9h00-22h') }}

                                <br>
                                {{ \App\Helpers\TranslationHelper::TranslateText('Dimanche : 10h00 - 18h00') }}

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Google Map Area  -->
            <div class="axil-google-map-wrap axil-section-gap pb--0">
                <div class="mapouter">
                    <!-- <div class="gmap_canvas">
                            <iframe width="1080" height="500" id="gmap_canvas" src="https://www.bing.com/maps/search?v=2&pc=FACEBK&mid=8100&mkt=fr-FR&fbclid=IwY2xjawTAXllleHRuA2FlbQIxMABicmlkETJjUkpjMHJyR0tUQkVDNGJUc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHtiRT79ie1Fh06pRrf4VtGVCC-um7QcFY5bFNr_fQeKbcq42poD4EEc9b0JQ_aem_yRIuSvuUaWHt-1J72KYDaw&FORM=FBKPL1&style=r&q=Douala+Makepe&cp=4.053274~9.742023&lvl=14.3"></iframe>
                        </div> -->
                </div>
            </div>
            <!-- End Google Map Area  -->
        </div>
    </div>
    <!-- End Contact Area  -->


</main>
@endsection