@extends('front.fixe')
@section('titre', 'Paiement')
@section('body')
    <main>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/form.css') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



        <body class="sticky-header">
            <!--[if lte IE 9]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
            <![endif]-->
            <a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>

            <main class="main-wrapper">

                <!-- Start Checkout Area  -->
                <div class="axil-checkout-area axil-section-gap">
                    <div class="container">
                        <form action="{{ route('order.confirm') }}" method="post">
                            @if ($errors->any())
                                {!! implode('', $errors->all('<div>:message</div>')) !!}
                            @endif
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="axil-checkout-billing">
                                        <h4 class="title mb--40">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Détails factures') }}</h4>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label> {{ \App\Helpers\TranslationHelper::TranslateText('Nom') }}
                                                        <span>*</span></label>
                                                    <input type="text" name="nom"
                                                        @if (Auth::user()) value="{{ Auth::user()->nom }}" @endif
                                                        required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>
                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Téléphone') }}<span>*</span></label>
                                                    <input type="number" name="phone"   @if (Auth::user()) value="{{ Auth::user()->phone }}" @endif required />

                                                </div>
                                            </div>
                                        </div>
                                      
                                       <!--  <div class="form-group">
                                            <label> {{ \App\Helpers\TranslationHelper::TranslateText('Adresse') }}
                                                <span>*</span></label>

                                            <input type="text" name="adresse" class="mb--15"
                                                placeholder=" {{ \App\Helpers\TranslationHelper::TranslateText('Votre adresse') }}"
                                              @if (Auth::user()) value="{{ Auth::user()->adresse }}" @endif    required />
                                        </div> -->
<!-- 
                                        <div class="form-group">
                                            <label> {{ \App\Helpers\TranslationHelper::TranslateText('Ville') }}
                                                <span>*</span></label>
                                            <select name="gouvernorat" id="Region">
                                                <option value="">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Ville') }}
                                                </option>
                                                @foreach ($gouvernorats as $gouvernorat)
                                                    <option value="{{ $gouvernorat }}">
                                                        {{ $gouvernorat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
 -->                                        <br>
                                        <div class="form-group">
                                            <label>{{ \App\Helpers\TranslationHelper::TranslateText('Méthode de récupération') }}
                                                <span>*</span></label>
                                            <select name="type_commande" id="type_commande" class="form-control" required>
                                                <option value="">-- Choisir --</option>
                                                <option value="boutique">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Retrait en Boutique (Gratuit)') }}
                                                </option>
                                                <option value="livraison">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Livraison à une destination') }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="transport-group" style="display:none;">

                                        
                                        <div class="form-group">
                                            <label> {{ \App\Helpers\TranslationHelper::TranslateText('Ville') }}
                                                <span>*</span></label>
                                            <select name="gouvernorat" id="Region">
                                                <option value="">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Ville') }}
                                                </option>
                                                @foreach ($gouvernorats as $gouvernorat)
                                                    <option value="{{ $gouvernorat }}">
                                                        {{ $gouvernorat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                         <div class="form-group">
                                            <label> {{ \App\Helpers\TranslationHelper::TranslateText('Adresse de livraison') }}
                                                <span>*</span></label>

                                            <input type="text" name="adresse" class="mb--15"
                                                placeholder=" {{ \App\Helpers\TranslationHelper::TranslateText('Votre adresse') }}"
                                              @if (Auth::user()) value="{{ Auth::user()->adresse }}" @endif  />
                                        </div>
                                         <div class="form-group">

                                            <label>{{ \App\Helpers\TranslationHelper::TranslateText('Frais de transport') }}
                                                <span>*</span></label>
                                            <select name="transport_id" id="transport" class="mb--15"
                                                style="background-color: #fbecec">
                                                <option value="">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('--Frais de transport--') }}
                                                </option>
                                                @foreach ($transports as $country)
                                                    <option value="{{ $country->id }}"
                                                        data-price="{{ $country->frais }}">
                                                        {{ $country->ville }} - Frais Transport: {{ $country->frais }}
                                                        <x-devise></x-devise>
                                                    </option>
                                                @endforeach
                                            </select>
                                         </div>
                                        </div>
                                        <br>

                                        <div class="form-group">
                                            <label for="mode_paiement">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Mode de paiement') }}
                                            </label>
                                            <select name="mode" id="mode_paiement" class="form-control" required>
                                                <option value="espèce">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Payement à la livraison') }}
                                                </option>
                                                <option value="orange money">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Orange Money') }}
                                                </option>
                                                <option value="momo">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('MOMO') }}</option>

<!-- 
                                                @if (Auth::check() /* && Auth::user()->solde > 0 */)
                                                    <option value="solde" data-solde="{{ Auth::user()->solde }}">
                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Payer avec mon solde') }}
                                                        ({{ Auth::user()->solde }}
                                                        <x-devise></x-devise>)
                                                    </option>
                                                @endif -->



                                               <!--  @if (Auth::check())
                                                    <option value="points" data-point="{{ Auth::user()->points }}">
                                                        {{ \App\Helpers\TranslationHelper::TranslateText('Payer Mes points') }}
                                                        ({{ Auth::user()->points }}
                                                        <x-devise></x-devise>)
                                                    </option>
                                                @endif -->
                                            </select>


<!-- 
                                            @if (Auth::check() && Auth::user()->solde == 0)
                                                <p class="text-danger small">
                                                    {{ \App\Helpers\TranslationHelper::TranslateText('Votre solde est insuffisant pour utiliser ce mode de paiement.') }}
                                                    <br>
                                                    <span>Solde:</span>({{ Auth::user()->solde }}
                                                    <x-devise></x-devise>)
                                                </p>
                                            @endif
 -->
                                        </div>

                                        <br>

                                        <div class="form-group">
                                            <label>
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Message(optionnel)') }}
                                            </label>
                                            <textarea id="message" rows="2"
                                                placeholder=" {{ \App\Helpers\TranslationHelper::TranslateText('Note sur votre commande(Optionnel)') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="axil-order-summery order-checkout-summery">
                                        <h5 class="title mb--20">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Votre commande') }}</h5>
                                        <div class="summery-table-wrap">
                                            <table class="table summery-table">
                                                <thead>
                                                    <tr>
                                                        <th> {{ \App\Helpers\TranslationHelper::TranslateText('Produit') }}
                                                        </th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paniers as $id => $details)
                                                        <tr class="order-product">
                                                            <td>{{ $details['nom'] }} <span class="quantity">x
                                                                    {{ $details['quantite'] }}</span></td>
                                                            <td> {{ $details['total'] }} <x-devise></x-devise></td>

                                                        </tr>
                                                    @endforeach

                                                    <tr class="order-subtotal">
                                                        <td>Subtotal</td>
                                                        <td id="subtotal">{{ $total }} <x-devise></x-devise>
                                                        </td>
                                                    </tr>




                                                <tbody>
                                                    <td colspan="2">


                                                        <tr>
                                                            <td class="tax">
                                                                {{ \App\Helpers\TranslationHelper::TranslateText('Frais de livraison') }}
                                                            </td>
                                                            <td><span id="shipping-cost">0</span> <x-devise></x-devise>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tax">
                                                                {{ \App\Helpers\TranslationHelper::TranslateText('Coupon de réduction') }}
                                                            </td>
                                                            <td>-{{ session('coupon')['value'] ?? 0 }}
                                                                <x-devise></x-devise>
                                                            </td>
                                                        </tr>
                                                    </td>

                                                </tbody>


                                                <tr class="order-shipping">
                                                </tr>


                                                <tr class="order-total">
                                                    <td>Total</td>
                                                    <td class="order-total-amount" id="total">{{ $total }}
                                                        <x-devise></x-devise>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="submit" class="axil-btn btn-bg-primary2 checkout-btn">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Confirmation') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{--     <script>
                     document.getElementById('transport').addEventListener('change', function() {
                        let selectedOption = this.options[this.selectedIndex];
                        let fraisTransport = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        let subtotal = parseFloat(document.getElementById('subtotal').textContent);
                        let total = subtotal + fraisTransport;

                        document.getElementById('shipping-cost').textContent = fraisTransport;
                        document.getElementById('total').textContent = total;
                    }); 
                </script> --}}

                <script>
                    document.getElementById('type_commande').addEventListener('change', function() {
                        const transportGroup = document.getElementById('transport-group');
                        if (this.value === 'livraison') {
                            transportGroup.style.display = 'block';
                        } else {
                            transportGroup.style.display = 'none';
                            document.getElementById('transport').value = '';
                            document.getElementById('shipping-cost').textContent = 0;
                            const subtotal = parseFloat(document.getElementById('subtotal').textContent);
                            document.getElementById('total').textContent = subtotal;
                        }
                    });

                    // Mettre à jour le total lorsque le transport change
                    document.getElementById('transport').addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const fraisTransport = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const subtotal = parseFloat(document.getElementById('subtotal').textContent);
                        const total = subtotal + fraisTransport;

                        document.getElementById('shipping-cost').textContent = fraisTransport;
                        document.getElementById('total').textContent = total;
                    });
                </script>
                <!-- End Checkout Area  -->
                <style>
                    .btn-bg-primary2 {
                        background-color: #5EA13C;
                        color: #ffffff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        text-decoration: none;
                    }

                    .btn-bg-secondary2 {
                        background-color: #EFB121;
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




                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const select = document.getElementById('mode_paiement');

                        select.addEventListener('change', function() {
                            const selectedOption = select.options[select.selectedIndex];

                            if (selectedOption.value === 'solde') {
                                const solde = selectedOption.getAttribute('data-solde') || 0;

                                if (solde > 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Paiement avec solde',
                                        text: `Votre solde disponible est de ${solde} XFCA`,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Solde insuffisant',
                                        text: 'Votre solde est insuffisant pour utiliser ce mode de paiement........',
                                        confirmButtonColor: '#d33',
                                        confirmButtonText: 'OK'
                                    });
                                    
                                    select.value = '';
                                }
                            }



                                             if (selectedOption.value === 'points') {
                                const solde = selectedOption.getAttribute('data-point') || 0;

                                if (solde > 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Paiement avec les points',
                                        text: `Votre total de points disponible est de ${solde} XFCA`,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Points insuffisants',
                                        text: 'Votre total de points est insuffisant pour utiliser ce mode de paiement........',
                                        confirmButtonColor: '#d33',
                                        confirmButtonText: 'OK'
                                    });
                                    
                                    select.value = '';
                                }
                            }
                        });
                    });
                </script>
            </main>

    </main>

@endsection
