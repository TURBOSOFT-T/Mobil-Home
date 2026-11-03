<?php

namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class TranslationHelper
{
    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

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
                // En cas de blocage IP ou de problème réseau en production, on retourne le texte d'origine sans bloquer le site
                Log::warning('Erreur de traduction Google : ' . $e->getMessage());
                return $text;
            }
        });
    }
}