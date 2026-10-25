<div>

    @foreach ($produits as $produit)
        <li class="cart-item">
            <div class="item-img">
                <a href="#"><img src="{{ $produit['photo'] }}" alt="Commodo Blown Lamp"></a>
                <button onclick="DeleteToCart({{ $produit['id_produit'] }})"class="close-btn"> <svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" style=" fill:red"
                        height="15" fill="currentColor">
                        <path
                            d="M6.45455 19L2 22.5V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V18C22 18.5523 21.5523 19 21 19H6.45455ZM13.4142 11L15.8891 8.52513L14.4749 7.11091L12 9.58579L9.52513 7.11091L8.11091 8.52513L10.5858 11L8.11091 13.4749L9.52513 14.8891L12 12.4142L14.4749 14.8891L15.8891 13.4749L13.4142 11Z">
                        </path>
                    </svg></i></button>
            </div>
            <div class="item-content">
                <div class="product-rating">

                </div>
                <h3 class="item-title"><a href="#"> {{ Str::limit($produit['nom'], 15) }}</a></h3>
                <div class="item-price"><span class="currency-symbol"></span> {{ $produit['prix'] }}  <x-devise></x-devise></div>
                <div class="pro-qty item-quantity">

                    <span class="quantity-control minus"></span>
                    <input type="number" value="{{ $produit['quantite'] }}" min="0"
                        wire:change="update({{ $produit['id_produit'] }}, $event.target.value)"class="quantity-input"
                        autocomplete="off">

                    <span class="quantity-control plus"></span>
                </div>

            </div>
        </li>
    @endforeach

    <script>
        $(document).on('click', '.quantity-control.plus', function () {
    var input = $(this).closest('.pro-qty').find('.quantity-input');
    var newVal = parseInt(input.val()) + 1;
    input.val(newVal).trigger('change'); // Trigger Livewire update
});

$(document).on('click', '.quantity-control.minus', function () {
    var input = $(this).closest('.pro-qty').find('.quantity-input');
    var newVal = parseInt(input.val()) - 1;
    if (newVal >= 0) {
        input.val(newVal).trigger('change'); // Trigger Livewire update
    }
});

    </script>
</div>
