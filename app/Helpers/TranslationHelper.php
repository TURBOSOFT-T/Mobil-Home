<?php

namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class TranslationHelper
{
    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

        // Si la langue est le français, si le texte est vide, ou s'il ne contient aucun caractère alphanumérique
        if ($locale == "fr" || empty($text) || !preg_match('/[a-zA-Z0-9]/', $text)) {
            return $text;
        }

        $cacheKey = 'translation_' . md5($text . '_' . $locale);

        return cache()->remember($cacheKey, now()->addDays(7), function () use ($text, $locale) {
            try {
                $tr = new GoogleTranslate();
                $tr->setSource('fr');
                $tr->setTarget($locale);
                
                return $tr->translate($text);
            } catch (\Exception $e) {
                // En production, si l'API est injoignable ou l'IP bloquée, on log l'erreur discrètement et on retourne le texte original
                Log::warning('Erreur TranslationHelper : ' . $e->getMessage());
                
                return $text;
            }
        });
    }
}