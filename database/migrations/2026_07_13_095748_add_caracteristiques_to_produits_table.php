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
        Schema::table('produits', function (Blueprint $table) {
            // Ajoute le champ JSON après la colonne 'couleur'
            $table->json('caracteristiques')->nullable()->after('couleur');
               $table->boolean('avec_caracteristique')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('caracteristiques');
              $table->dropColumn('avec_caracteristique');

        });
    }
};