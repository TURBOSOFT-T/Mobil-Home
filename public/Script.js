function ShowProduitModal(id) {
    Livewire.dispatch("setPostId", { id: id });
    $("#product-view").modal("toggle");
}


    function sweet_alert(titre, type, text, duree) {
        Swal.fire({
            position: "center",
            icon: type,
            title: titre,
            text: text,
            showConfirmButton: false,
            timer: duree,
        });
    }


function AddToCart(id) {
    var quantityElement = $("#qte-" + id);
    if (quantityElement.length) {
        var quantity = quantityElement.val();
    } else {
        var quantity = 1;
    }

    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var data = {
        id_produit: id,
        quantite: quantity,
        _token: csrfToken,
    };
    fetch("/client/ajouter_au_panier", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statut) {
                get_panier();
            
                sweet_alert("Produit ajouté", "success", data.message, 1500);
                sweet_alert("Félicitation", "success", data.message, 1500);
             
            } else {
                sweet_alert("Attention !", "warning", data.message, 2500);
            }
        })
        .catch((error) => {
            console.error("Erreur:", error);
        });
}



function DeleteToCart(id) {
    //delete produit from cart
    $.get(
        "/client/delete_produit_au_panier",
        {
            id_produit: id,
        },
        function (data, status) {
            if (status) {
                if (data.statut) {
                    get_panier();
                } else {
                    console.log("error delete product");
                }
            } else {
                console.log("error delete product reuest");
            }
        }
    );
}


get_panier();

function get_panier() {
    $.get("/client/count_panier", function (data, status) {
        if (status === 'success') {
            console.log(data.list);
            $("#count-panier-span").text(data.total);
        /*    $("#list_content_panier").html(data.list);   */
         $("#list_content_panier").html(data.html);  
            $("#montant_total_panier").html(data.montant_total + " XCFA");
        } else {
            console.log("error get panier");
        }
    });
}

function AddFavoris(id) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var data = {
        id_produit: id,
        _token: csrfToken,
    };
  //  var icon = document.querySelector(`#favori-icon-${id}`);
    fetch("/client/ajouter_favoris", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statut) {
                sweet_alert("Félicitation", "success", data.message, 1500);
            } else {
                console.log("Erreur lors de l'ajout du produit  aux favoris .");
            }
        })
        .catch((error) => {
            console.error("Erreur:", error);
        });

       
}

//changement de l'ordre des prix dans le shop
$(document).ready(function () {
    $("#OrdrePrix").change(function () {
        var selectedValue = $(this).val();
        Livewire.dispatch("ChangeOrdrePrix", { ordre: selectedValue });
    });
});




   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });


   $(document).ready(function(e){
      $('.range_slider').on('change',function(){
          let left_value = $('#input_left').val();
          let right_value = $('#input_right').val();
          // alert(left_value+right_value);
          $.ajax({
            //  url:"{{ route('search.products') }}",
              method:"GET",
              data:{left_value:left_value, right_value:right_value},
              success:function(res){
                 $('.search-result').html(res);
              }
          });
      });

      $('#sort_by').on('change',function(){
          let sort_by = $('#sort_by').val();
          $.ajax({
            url:"{{ route('sort.by') }}",
              method:"GET",
              data:{sort_by:sort_by},
              success:function(res){
                  $('.search-result').html(res);
              }
          });
      });
   })

