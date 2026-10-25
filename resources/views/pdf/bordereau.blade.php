<!DOCTYPE html>
<html lang="en">
@php
$config = DB::table('configs')->select('icon', 'logo')->first();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ \App\Helpers\TranslationHelper::TranslateText('Reçu de commande') }}</title>
    <style>
        body {
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
            background-color: #0d1033;
            padding: 10px 40px;
        }

        .logo {
            width: 50%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .body-section {
            padding: 16px;
            border: 1px solid gray;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: end;
        }

        .w-20 {
            width: 20%;
        }

        .float-right {
            float: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="brand-section">
            <div class="row">
                <div class="col-6">
                    <h1 class="text-white">SWOOT BIO</h1>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <p class="text-white">assdad asd asda asdad a sd</p>
                        <p class="text-white">assdad asd asd</p>
                        <p class="text-white">+91 888555XXXX</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading"> {{ \App\Helpers\TranslationHelper::TranslateText('Informations dur la commande') }}: #{{ $commande->id }}</h2>
                    <p class="sub-heading">Tracking No. fabcart2025 </p>
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Date de commande') }}:</strong> {{ $commande->created_at }}</p>

                    </p>
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('E-mail') }}:</strong> {{ $commande->email ?? 'N/A' }} </p>
                    </p>
                </div>
                <div class="col-6">
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Nom complet') }}:</strong> {{ $commande->prenom }} {{ $commande->nom }}</p>
                    </p>
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Addresse') }}:</strong> {{ $commande->adresse ?? 'N/A' }}</p>
                    </p>
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Numéro de téléphone') }}:</strong> {{ $commande->phone ?? 'N/A' }}</p>
                    </p>
                    <p class="sub-heading">
                    <p><strong> {{ \App\Helpers\TranslationHelper::TranslateText('Ville') }}:</strong> {{ $commande->gouvernorat ?? 'N/A' }} </p>
                    </p>
                </div>
            </div>
        </div>

        <div class="body-section">
            <h3 class="heading"> {{ \App\Helpers\TranslationHelper::TranslateText('La commande') }}
            </h3>
            <br>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>{{ \App\Helpers\TranslationHelper::TranslateText('Produit') }}</th>
                        <th class="w-20">{{ \App\Helpers\TranslationHelper::TranslateText('Prix') }}</th>
                        <th class="w-20">{{ \App\Helpers\TranslationHelper::TranslateText('Quantité') }}</th>
                        <th class="w-20">{{ \App\Helpers\TranslationHelper::TranslateText('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                     @php
                    $total = $commande->frais ?? 0 ;
                @endphp
                @foreach ($commande->contenus as $item)
                    <tr>
                        <td>  @if ($item->type == 'produit')
                                {{ $item->produit->nom }}
                            @else
                                {{ $item->pack->nom }}
                            @endif
                            ( {{ $item->type }} )</td>
                        <td>{{ $item->prix_unitaire }}  <x-devise></x-devise></td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ $item->prix_unitaire * $item->quantite }}  <x-devise></x-devise></td>
                    </tr>
                     @php
                        $total += $item->prix_unitaire * $item->quantite - $commande->coupon ?? 0 + $commande->frais ?? 0;
                    @endphp
                @endforeach
                    <tr>
                        <td colspan="3" class="text-right">         <b> {{ \App\Helpers\TranslationHelper::TranslateText('Frais de livraison') }} </b>
             </td>
                        <td> {{ $commande->frais ?? 0 }} </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">        <b> {{ \App\Helpers\TranslationHelper::TranslateText('Coupon de réduction') }} </b>
               </td>
                        <td> {{ $commande->coupon ?? 0 }} </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">  <b> {{ \App\Helpers\TranslationHelper::TranslateText('Total de la commande') }}:</b>
               </td>
                        <td>          {{ $commande->montant() - $commande->coupon ?? 0  + $commande->frais ?? 0 }}   <x-devise></x-devise>
             </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <h3 class="heading">Payment Status: Paid</h3>
            <h3 class="heading">Payment Mode: Cash on Delivery</h3>
        </div>

        <div class="body-section">
            <p>&copy; Copyright {{ date('Y') }} - SWOOT BIO. All rights reserved.
                <a href="#" class="float-right">www.swootbio.com</a>
            </p>
        </div>
    </div>

</body>

</html>