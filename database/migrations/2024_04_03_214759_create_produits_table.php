<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {

            $table->id();

            // Tiers / Propriétaire / Agent qui publie le bien
            $table->unsignedBigInteger('user_id')->nullable(); // Lié à la table users

            // Informations principales du bien
            $table->string('nom'); // Ex: "Studio meublé moderne", "Duplex de standing"
            $table->string('slug')->unique(); // URL propre
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable();

         

            // Type de bien et d'offre
            $table->enum('type_habitation', [
                'chambre',
                'studio',
                'appartement',
                'duplex',
                'villa',
                'bureau',
                'commerce',
                'terrain',
                'maison',
                'boutique',
                'local_commercial',
                'autre'
            ])->default('appartement');
            
            $table->enum('type_offre', ['location', 'vente'])->default('location');

            // Tarification
            $table->decimal('prix', 12, 2)->nullable(); // Loyer mensuel ou prix de vente
            $table->decimal('caution', 12, 2)->nullable(); // Caution / Dépôt de garantie
            $table->decimal('charges', 12, 2)->nullable(); // Charges locatives éventuelles

            // Gestion de la Commission (pour les tiers)
            $table->boolean('avec_commission')->default(false);
            $table->enum('type_commission', ['pourcentage', 'fixe'])->default('pourcentage');
            $table->decimal('commission', 12, 2)->nullable(); // Montant ou pourcentage prélevé par la plateforme

            // Caractéristiques physiques du bien
            $table->decimal('surface', 8, 2)->nullable(); // En m²
            $table->integer('chambres')->nullable(); // Nombre de chambres
            $table->integer('salles_de_bain')->nullable(); // Nombre de salles de bain
            $table->integer('etage')->nullable(); // Numéro d'étage (si applicable)

            // Localisation
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->text('adresse_complete')->nullable();

            // Médias
            $table->string('photo')->nullable(); // Image principale
            $table->json('photos')->nullable(); // Galerie d'images supplémentaires

            // Options et équipements
            $table->json('caracteristiques')->nullable();
            $table->boolean('avec_caracteristique')->default(false);
            
            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sous_category_id')->nullable();
            $table->unsignedBigInteger('famille_id')->nullable();

            // Statut et Disponibilité
            $table->enum('statut', [
                'disponible',
                'loue',
                'vendu',
                'indisponible'
            ])->default('disponible');

            // Options d'affichage / mise en avant
            $table->boolean('top')->default(false); // Coup de cœur / En vedette
            $table->boolean('active')->default(false); // Publication active sur le site (soumise à modération si tiers)
            $table->boolean('new')->default(false);
            $table->boolean('is_new')->default(false);

            $table->softDeletes();
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEYS
            |--------------------------------------------------------------------------
            */
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};