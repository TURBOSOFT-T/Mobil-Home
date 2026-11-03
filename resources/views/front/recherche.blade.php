@extends('front.fixe')

@section('body')
@php
$config = DB::table('configs')->first();
@endphp

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h2 class="fw-bold">🔍 
                 {{ \App\Helpers\TranslationHelper::TranslateText('Recherche de biens immobiliers') }} .</h3>
          
            </h2>
            <p class="text-muted">
             {{ \App\Helpers\TranslationHelper::TranslateText(' Trouvez le logement ou le terrain qui vous correspond') }} .</h3>
              
           </p>
        </div>
    </div>

    <!-- Barre de filtres (Formulaire GET classique) -->
    <form method="GET" action="{{ route('recherche') }}" class="card shadow-sm border-0 p-4 mb-4 bg-light">
        <div class="row g-3">
            <!-- Recherche par mot-clé -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    {{ \App\Helpers\TranslationHelper::TranslateText('Mot-clé') }}
                </label>
                <input type="text" name="key" value="{{ request('key') }}" class="form-control" placeholder="Ex: Villa de luxe...">
            </div>

            <!-- Filtrer par type d'habitation -->
            <div class="col-md-3">
                <label class="form-label fw-bold">
                     {{ \App\Helpers\TranslationHelper::TranslateText('Type d\'habitation') }} .</h3>
          
                </label>
                <select name="type" class="form-select text-capitalize">
                    <option value="">Tous les types</option>
                    <option value="appartement" @selected(request('type')=='appartement' )>Appartement</option>
                    <option value="villa" @selected(request('type')=='villa' )>Villa</option>
                    <option value="studio" @selected(request('type')=='studio' )>Studio</option>
                    <option value="terrain" @selected(request('type')=='terrain' )>Terrain</option>
                    <option value="duplex" @selected(request('type')=='duplex' )>Duplex</option>
                    <option value="commerce" @selected(request('type')=='commerce' )>Commerce</option>
                    <option value="chambre" @selected(request('type')=='chambre' )>Chambre</option>
                    <option value="boutique" @selected(request('type')=='boutique' )>Boutique</option>
                    <option value="maison" @selected(request('type')=='maison' )>Maison</option>
                      
                </select>
            </div>

            <!-- Boutons d'action -->
            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="axil-btn btn-bg-primary2">Filtrer</button>
                <a href="{{ route('recherche') }}" class="axil-btn btn-bg-primary2">Reset</a>
            </div>
        </div>
    </form>

    <!-- Résultats -->
    <div class="row">
        @forelse ($produits as $produit)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="position-relative">
                   @if($produit->photo)
    <img src="{{ Storage::url($produit->photo) }}" class="card-img-top" alt="{{ $produit->nom }}" style="height: 200px; object-fit: cover;" loading="lazy">
@else
    <img src="{{ asset('icons/no-image.webp') }}" class="card-img-top" alt="Aucune image" style="height: 200px; object-fit: cover;" loading="lazy">
@endif
                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark text-capitalize">
                        {{ $produit->type_habitation }}
                    </span>
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">{{ $produit->nom }}</h5>
                    <p class="card-text text-success fw-bold fs-5 mb-2">
                        {{ number_format($produit->prix, 0, ',', ' ') }} <x-devise></x-devise>
                    </p>
                    <p class="card-text text-muted small flex-grow-1">
                        {!! Str::limit(strip_tags($produit->description), 80) !!}
                    </p>

                    <!-- Bouton déclenchant la modale -->
                    <div class="d-flex gap-2 mt-auto">
                        <!-- Bouton Détails (Modal) -->
                        <button type="button" class="axil-btn btn-bg-primary2"
                            data-bs-toggle="modal"
                            data-bs-target="#detailBienModal"
                            data-id="{{ $produit->id }}">
                            Détails
                        </button>

                        <!-- Bouton Réservation (Modal) -->
                        <button type="button" class="axil-btn btn-bg-primary2"
                            data-bs-toggle="modal"
                            data-bs-target="#reservationModal"
                            data-id="{{ $produit->id }}"
                            data-nom="{{ $produit->nom }}">
                            Réserver
                        </button>
                    </div>


                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">
                 {{ \App\Helpers\TranslationHelper::TranslateText('Aucun bien immobilier ne correspond à vos critères de recherche.') }} .</h3>
          
            </h5>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $produits->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal Bootstrap pour les détails -->
<div class="modal fade" id="detailBienModal" tabindex="-1" aria-labelledby="detailBienModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalTitre">Chargement...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JS pour charger dynamiquement les données dans la modale -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailBienModal');

        detailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const produitId = button.getAttribute('data-id');

            const modalTitre = document.getElementById('modalTitre');
            const modalBody = document.getElementById('modalBodyContent');

            // État de chargement initial
            modalTitre.textContent = 'Chargement...';
            modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="text-muted mt-2">Récupération des informations...</p>
            </div>`;

            // Requête Fetch vers le contrôleur Laravel
            fetch(`/produit/json/${produitId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    modalTitre.textContent = data.nom;
                    modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <img src="${data.photo_url || '/icons/no-image.webp'}" class="img-fluid rounded shadow-sm w-100" alt="${data.nom}" style="height: 250px; object-fit: cover;">
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-dark text-capitalize">${data.type_habitation}</span>
                                <span class="badge bg-success text-capitalize">${data.statut}</span>
                            </div>
                            <h4 class="text-success fw-bold mb-3">${Number(data.prix).toLocaleString()} XAF</h4>
                            <hr class="my-2">
                            <div class="text-muted small flex-grow-1" style="max-height: 150px; overflow-y: auto;">
                                ${data.description || 'Aucune description détaillée fournie pour ce bien.'}
                            </div>
                        </div>
                    </div>
                `;
                })
                .catch(error => {
                    modalBody.innerHTML = `
                    <div class="alert alert-danger text-center my-3">
                        Impossible de charger les détails de ce bien pour le moment.
                    </div>`;
                });
        });
    });
</script>


<!-- Modal Bootstrap pour la Réservation -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="reservationModalLabel">Réserver ce bien</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reservation.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="produit_id" id="reservationProduitId">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bien concerné :</label>
                        <input type="text" id="reservationProduitNom" class="form-control-plaintext fw-bold text-success" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Votre Nom complet</label>
                        <input type="text" name="nom" class="form-control" required placeholder="Ex: Thomas Tuemo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Votre Téléphone</label>
                        <input type="tel" name="phone" class="form-control" required placeholder="Ex: +237 ...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message / Date souhaitée</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Précisez votre date de visite ou vos questions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="axil-btn btn-bg-primary2">Envoyer la réservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script JS pour injecter l'ID et le nom dans la modale de réservation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reservationModal = document.getElementById('reservationModal');
        reservationModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const produitId = button.getAttribute('data-id');
            const produitNom = button.getAttribute('data-nom');

            document.getElementById('reservationProduitId').value = produitId;
            document.getElementById('reservationProduitNom').value = produitNom;
        });
    });
</script>
@endsection