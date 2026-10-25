<!-- Start Testimonial Area -->
<style>
    /* Styles de la section et des utilitaires */
    .btn-bg-primary2 {
        background-color: #F46B02;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn-bg-primary2:hover {
        background-color: #F46B02;
        color: #ffffff;
    }

    .forced-testimonial-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Styles du formulaire interne au Modal */
    .testimonial-form {
        max-width: 600px;
        margin: 0 auto;
        background-color: #f8f9fa;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 1rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #495057;
        background-color: #fff;
        border-radius: 25px;
    }

    textarea.form-control {
        border-radius: 15px;
    }

    button.btn-submit-form {
        padding: 0.5rem 2rem;
        font-size: 1.125rem;
        background-color: #EFB121;
        border-color: #EFB121;
        color: #fff;
        transition: background-color 0.3s ease;
    }

    button.btn-submit-form:hover {
        background-color: #d69e1c;
        border-color: #d69e1c;
        color: #fff;
    }

    .alert {
        max-width: 600px;
        margin: 1rem auto;
    }
</style>

<div class="axil-product-area bg-color-white axil-section-gap">
    <div class="container">
        <div class="section-title-wrapper">
            <h4>
                <span class="axil-breadcrumb-item1 active" aria-current="page">
                    <i class="fal fa-quote-left"></i> {{ \App\Helpers\TranslationHelper::TranslateText('Témoignages') }}
                </span>
            </h4>
            <h2 class="title"> {{ \App\Helpers\TranslationHelper::TranslateText('Les retours de nos clients') }}</h2>
        </div>

        <div class="testimonial-slick-activation testimonial-style-one-wrapper slick-layout-wrapper--20 axil-slick-arrow arrow-top-slide">
            @if ($testimonials->isEmpty())
            <p class="text-center"> {{ \App\Helpers\TranslationHelper::TranslateText('Aucun témoignage disponible') }}.</p>
            @else
            @foreach ($testimonials as $testimonial)
            <div class="slick-single-layout testimonial-style-one">
                <div class="review-speech">
                    <p>“{!! \App\Helpers\TranslationHelper::TranslateText($testimonial->message) !!}“</p>
                </div>
                <div class="media">
                    <div class="thumbnail">
                        @if ($testimonial->photo)
                        <img src="{{ asset('uploads/testimonials/' . $testimonial->photo) }}" class="forced-testimonial-img" alt="Photo Témoignage">
                        @else
                        <img src="{{ asset('assets/images/testimonial/image-1.png') }}" class="forced-testimonial-img" alt="Default testimonial image">
                        @endif
                    </div>
                    <div class="media-body">
                        <span class="designation">{{ $testimonial->name }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            @endif


        </div>

        <div class="col-12 d-flex justify-content-center mt-5">
            <div class="form-group mb--0">
                <button class="axil-btn btn-bg-primary2" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">
                    <span> {{ \App\Helpers\TranslationHelper::TranslateText('Laisser un témoignage') }}</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Messages de notification AJAX -->
    <div class="container mt-3">
        <div id="successMessage" class="alert alert-success" style="display:none;"></div>
        <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> {{ \App\Helpers\TranslationHelper::TranslateText('Témoignage') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="testimonialForm" action="{{ route('testimonial.store') }}" method="POST" class="testimonial-form p-4 rounded shadow-sm">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="name" class="form-label text-muted"> {{ \App\Helpers\TranslationHelper::TranslateText('Nom') }}</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="testimonial" class="form-label text-muted"> {{ \App\Helpers\TranslationHelper::TranslateText('Message') }}</label>
                        <textarea class="form-control border-0 shadow-sm" id="testimonial" name="message" rows="6" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit-form rounded-pill shadow"> {{ \App\Helpers\TranslationHelper::TranslateText('Envoyer') }}</button>
                    </div>
                </form>

                @if ($errors->any())
                <div class="alert alert-danger mt-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#testimonialForm').on('submit', function(e) {
            e.preventDefault();

            const modalElement = document.getElementById('exampleModal');

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    // Fermeture propre du modal d'après l'API Bootstrap 5
                    if (modalElement) {
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                        modalInstance.hide();
                    }

                    // Notification et rechargement
                    $('#successMessage').text('Témoignage créé avec succès ! Il sera affiché après validation des administrateurs.').show();
                    $('#errorMessage').hide();
                    $('#testimonialForm')[0].reset();

                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(response) {
                    $('#errorMessage').text('Une erreur est survenue lors de l\'envoi de votre témoignage.').show();
                    $('#successMessage').hide();
                }
            });
        });
    });
</script>