<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0">
        <div class="position-relative">
            @if($produit->photo)
            <img src="{{ Storage::url($produit->photo) }}" class="card-img-top" alt="{{ $produit->nom }}" style="height: 200px; object-fit: cover;">
            @else
            <img src="/icons/no-image.webp" class="card-img-top" alt="Aucune image" style="height: 200px; object-fit: cover;">
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
                <!-- Bouton pour ouvrir le modal -->
                  <div class="d-flex gap-2 mt-auto">
                <button type="button" class="axil-btn btn-bg-primary2" wire:click="voirDetails({{ $produit->id }})">
                    Voir les détails
                </button>


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

    <!-- Modal Bootstrap des détails -->
    <div class="modal fade" id="produitModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                @if($selectedProduit)
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">{{ $selectedProduit->nom }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Image -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            @if($selectedProduit->photo)
                            <img src="{{ Storage::url($selectedProduit->photo) }}" class="img-fluid rounded w-100" style="height: 280px; object-fit: cover;" alt="{{ $selectedProduit->nom }}">
                            @else
                            <img src="/icons/no-image.webp" class="img-fluid rounded w-100" style="height: 280px; object-fit: cover;" alt="Aucune image">
                            @endif
                        </div>
                        <!-- Infos -->
                        <div class="col-md-6">
                            <span class="badge bg-dark text-capitalize mb-2">{{ $selectedProduit->type_habitation }}</span>
                            <h3 class="text-success fw-bold mb-3">
                                {{ number_format($selectedProduit->prix, 0, ',', ' ') }} <x-devise></x-devise>
                            </h3>
                            <p class="text-muted small mb-2">
                                <strong>Statut :</strong> <span class="badge bg-success text-capitalize">{{ $selectedProduit->statut }}</span>
                            </p>
                            <hr>
                            <div class="text-muted small overflow-auto" style="max-height: 120px;">
                                {!! $selectedProduit->description ?? 'Aucune description disponible.' !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <a href="https://wa.me/?text={{ urlencode('Bonjour, je suis intéressé par votre bien : ' . $selectedProduit->nom) }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Contacter pour ce bien
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Script d'écoute Livewire pour afficher le modal Bootstrap -->
    @script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const modalElement = document.getElementById('produitModal');
            const produitModal = new bootstrap.Modal(modalElement);

            $wire.on('open-modal', () => {
                produitModal.show();
            });
        });
    </script>
    @endscript


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
document.addEventListener('DOMContentLoaded', function () {
    const reservationModal = document.getElementById('reservationModal');
    reservationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const produitId = button.getAttribute('data-id');
        const produitNom = button.getAttribute('data-nom');

        document.getElementById('reservationProduitId').value = produitId;
        document.getElementById('reservationProduitNom').value = produitNom;
    });
});
</script>
</div>