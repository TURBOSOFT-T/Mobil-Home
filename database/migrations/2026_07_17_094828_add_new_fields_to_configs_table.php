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
        Schema::table('configs', function (Blueprint $table) {
            // Ajoutez ici uniquement les nouveaux champs que vous souhaitez créer
       
             $table->string("facebook")->nullable();
             $table->string("messenger")->nullable();
            $table->string("twitter")->nullable();
            $table->string("instagram")->nullable();
            $table->string("youtube")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("tiktok")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // Pensez à supprimer les colonnes si vous faites un "rollback"
            $table->dropColumn([
                'facebook',
                'messenger',
                'twitter',
                'instagram',
                'youtube',
                'linkedin',
                'tiktok',
                
            ]);
        });
    }
};