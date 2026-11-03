<div class="container py-4">

<div class="row mb-5 text-center bg-light p-5 rounded shadow-sm">
        <div class="col-lg-8 mx-auto">
            <h1 class="fw-bold mb-3">
                 {{ \App\Helpers\TranslationHelper::TranslateText('Trouvez votre bien immobilier idéal') }} .</h3>
          
            </h1>
            <p class="text-muted mb-4">
                 {{ \App\Helpers\TranslationHelper::TranslateText('Appartements, villas, studios et terrains disponibles') }} .</h3>
          
            </p>
            
            <form action="{{ route('recherche') }}" method="GET" class="card shadow-sm border-0 p-4 mb-4 bg-light">
                 <div class="row g-3">
                    <div class="col-md-5">
                <input type="text" name="key" class="form-control form-control-lg" placeholder="
              {{ \App\Helpers\TranslationHelper::TranslateText('Rechercher par titre, ville, quartier...') }}  
                ">
                 </div>
                 <div class="col-md-4">
                <select name="type" class="form-select text-capitalize" style="max-width: 200px;">
                    <option value=""> {{ \App\Helpers\TranslationHelper::TranslateText('Type habitation') }} .</h3>
          </option>
                    <option value="appartement">
                         {{ \App\Helpers\TranslationHelper::TranslateText('Appartement') }} .</h3>
          
                    </option>
                    <option value="villa">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Villa') }} .</h3>
                    </option>
                    <option value="studio">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Studio') }} .</h3>
                    </option>
                    <option value="terrain">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Terrain') }} .</h3>
                    </option>
                    <option value="duplex">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Duplex') }} .</h3>
                    </option>
                    <option value="commerce">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Commerce') }} .</h3>
                    </option>
                    <option value="chambre">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Chambre') }} .</h3>
                    </option>
                    <option value="boutique">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Boutique') }} .</h3>
                    </option>
                    <option value="maison">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Maison') }} .</h3>
                    </option>
                       
                </select>
                    </div>
                 <div class="col-md-3 d-flex align-items-end gap-2">
                <button class="axil-btn btn-bg-primary2" type="submit">
                    {{ \App\Helpers\TranslationHelper::TranslateText('Rechercher') }}
                </button>
                 </div>
                 </div>
            </form>
        </div>
    </div>

   <!-- Section 1 : Coups de Cœur & Biens à la Une -->
    @if($topBiens->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">⭐ Coups de cœur & Biens à la une</h3>
        </div>
        <div class="row">
            @foreach($topBiens as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 2 : Appartements -->
    @if($appartements->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🏢 Appartements récents</h3>
            <a href="{{ route('recherche', ['type' => 'appartement']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($appartements as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 3 : Villas -->
    @if($villas->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🏡 Villas de standing</h3>
            <a href="{{ route('recherche', ['type' => 'villa']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($villas as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 4 : Studios -->
    @if($studios->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🚪 Studios disponibles</h3>
            <a href="{{ route('recherche', ['type' => 'studio']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($studios as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 5 : Terrains -->
    @if($terrains->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🗺️ Terrains en vente</h3>
            <a href="{{ route('recherche', ['type' => 'terrain']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($terrains as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 6 : Maisons -->
    @if($maisons->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🏠 Maisons individuelles</h3>
            <a href="{{ route('recherche', ['type' => 'maison']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($maisons as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 7 : Boutiques -->
    @if($boutiques->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🛍️ Boutiques</h3>
            <a href="{{ route('recherche', ['type' => 'boutique']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($boutiques as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Section 8 : Chambres -->
    @if($chambres->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🛏️ Chambres</h3>
            <a href="{{ route('recherche', ['type' => 'chambre']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($chambres as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif



    <!-- Section 10 : Commerces -->
    @if($commerces->count() > 0)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">🏪 Commerces</h3>
            <a href="{{ route('recherche', ['type' => 'commerce']) }}" class="text-decoration-none">Voir tout →</a>
        </div>
        <div class="row">
            @foreach($commerces as $bien)
                @include('front.partials.bien-card', ['produit' => $bien])
            @endforeach
        </div>
    </section>
    @endif

</div>