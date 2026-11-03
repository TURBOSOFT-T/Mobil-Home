<?php

namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationHelper
{
    public static function TranslateText($text)
    {
        $locale = app()->getLocale();

        // Si la langue est le français, si le texte est vide, ou s'il ne contient aucun caractère alphanumérique (ex: juste un espace)
        if ($locale == "fr" || empty($text) || !preg_match('/[a-zA-Z0-9]/', $text)) {
            return $text;
        }

        $cacheKey = 'translation_' . md5($text . '_' . $locale);

        return cache()->remember($cacheKey, now()->addDays(7), function () use ($text, $locale) {
            try {
                $tr = new GoogleTranslate($locale);
                return $tr->translate($text);
            } catch (\Exception $e) {
                // En cas d'erreur de l'API ou de coupure réseau, on retourne le texte d'origine pour ne pas bloquer le site
                return $text;
            }
        });
    }
}