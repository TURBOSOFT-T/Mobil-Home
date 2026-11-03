<?php

namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class TranslationHelper
{
    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

        // Si on est en français, si le texte est vide ou sans caractères alphanumériques
        if ($locale == "fr" || empty($text) || !preg_match('/[a-zA-Z0-9]/', $text)) {
            return $text;
        }

        $cacheKey = 'translation_' . md5($text . '_' . $locale);

        return cache()->remember($cacheKey, now()->addDays(7), function () use ($text, $locale) {
            try {
                $tr = new GoogleTranslate();
                $tr->setSource('fr');
                $tr->setTarget($locale);

                // 🔥 On force des options Guzzle personnalisées pour faire croire à Google 
                // qu'il s'agit d'une requête provenant d'un vrai navigateur web (Chrome)
                $tr->setOptions([
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7',
                    ],
                    'timeout' => 5, // Évite que le serveur ne bloque trop longtemps si Google met du temps à répondre
                ]);

                return $tr->translate($text);

            } catch (\Exception $e) {
                // Si malgré tout Google bloque l'IP du serveur, on consigne l'alerte et on retourne le texte original
                Log::warning('Contournement Google Translate échoué : ' . $e->getMessage());
                return $text;
            }
        });
    }
}