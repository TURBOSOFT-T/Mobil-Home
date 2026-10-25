@extends('front.fixe')
@section('titre', 'Mes commandes ')
@section('body')


<main>
    <div class="breadcrumb">
        <div class="container">
            <h2>
                {{ \App\Helpers\TranslationHelper::TranslateText('Mes Commandes') }}
            </h2>
          
        </div>
    </div>
<div class="page-content-wrapper">
    <div class="page-content">
        <section class="cart-area pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                    {{--     @livewire('Front.Favoris') --}}
                   {{--  @livewire('Commandes.MesCommandes') --}}
                   <div>
                    <div id="content">
                        
                        <div class="wishlist">
                            <div class="container">
                                <div class="wishlist__table">
                                    <div class="wishlist__table__wrapper">
                                        <table>
                                            <colgroup>
                                                <col style="width: 20%" />
                                                <col style="width: 10%" />
                                               
                                                <col style="width: 15%" />
                                                <col style="width: 10%" />
                                                <col style="width: 20%" />
                                            </colgroup>
                                            <thead  style=" background-color: #b2e21522;">
                                                <tr>
                                                    <th style="width: 80px;"> {{ \App\Helpers\TranslationHelper::TranslateText('Article') }}</th>
                                                    <th class="product-thumbnail"> {{ \App\Helpers\TranslationHelper::TranslateText('Montant') }}</th>
                                                    <th class="product-thumbnail"> {{ \App\Helpers\TranslationHelper::TranslateText('Frais') }}</th>
                                                   
                                                    <th class="product-thumbnail"> {{ \App\Helpers\TranslationHelper::TranslateText('Date') }}</th>
                                                    <th class="product-quantity"> {{ \App\Helpers\TranslationHelper::TranslateText('Status') }}</th>
                                                    <th class="product-remove">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($commandes as $key => $commande)
                                                                <tr>
                                                                    <td>
                                                                       
                                                                        {{ $commande->contenus->count() }}
                                                                    </td>
                                                                    <td >
                                                                        {{ $commande->montant() }}  <x-devise></x-devise>
                                                                    </td>
                                                                    <td>
                                                                        {{ $commande->frais ?? 0}}  <x-devise></x-devise>
                                                                    </td>
                                                                    <td>
                                                                        {{ $commande->created_at }}
                                                                       
                                                                    </td>
                                                                    <td>
                                                                        
                                                                       {{--  {{ $commande->statut }} --}}
                                                                       
    @switch($commande->statut)
        @case('attente')
            <span class="badge bg-warning text-dark">En attente</span>
            @break

        @case('traitement')
            <span class="badge bg-info text-dark">En Traitemet</span>
            @break

        @case('En cours livraison')
            <span class="badge bg-success">En cours livraison</span>
            @break

        @case('livrée')
            <span class="badge bg-primary">Livrée</span>
            @break

        @case('retournée')
            <span class="badge bg-danger">Retournée</span>
            @break

        @default
            <span class="badge bg-secondary">{{ ucfirst($commande->statut) }}</span>
    @endswitch



                                                                    </td>
                                                                    <td>
                                                                    

                                                                        <a  href="{{ route('print_commande',['id'=> $commande->id ]) }}" class="axil-btn btn-bg-primary2">
                                                                            
                                                                            <img width="20" height="20" src="https://img.icons8.com/wired/20/FFFFFF/bill.png" alt="bill"/>
                                                                            Factureddddd</a>
                                                                    </td>
                                                                   
                                                                </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="6 ">
                                                                    
                                                                    <div class="text-center p-5"><img width="50" height="50" src="https://img.icons8.com/?size=100&id=15867&format=png&color=000000" alt="shopping-cart--v1"/>
                                                                        <br>
                                                                        <h5>
                                                                        Pas de  commandes enregistrées pour l'instant.
                                                                        </h5>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                            </tbody>
                                        </table>
                                    </div>   <style>
                                        .btn2 {
                                            background-color: #5EA13C;
                                            color: #ffffff;
                                            border: none;
                                            padding: 10px 20px;
                                            border-radius: 5px;
                                            text-decoration: none;
                                        }
                                
                                        .btn-bg-secondary2 {
                                        background-color: #EFB121; /* Couleur de fond, bleu dans cet exemple */
                                        color: #ffffff; /* Couleur du texte, blanc dans cet exemple */
                                        border: none;
                                        padding: 10px 20px; /* Optionnel, ajuste la taille */
                                        border-radius: 5px; /* Optionnel, arrondit les coins */
                                        text-decoration: none; /* Supprime le soulignement */
                                    }
                                    </style>
                                </div>
                            </div>
                        </div>
                </div>
                
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



</main>

@endsection
