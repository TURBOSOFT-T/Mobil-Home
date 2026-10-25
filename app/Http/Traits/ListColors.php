<?php

namespace App\Http\Traits;

trait ListColors
{

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function getListColors()
    {
        // Les couleurs et leurs codes correspondants
        $colors = [
           


             [ "#C0C0C0"],
            [ "#F5F5DC"],
            [ "#FFFFFF"],
            ["#0000FF"],
            [ "#008080"],
            [  "#800000"],
            [ "#C19A6B"],
            [ "#FF7F50"],
            ["#FFD700"],
            [ "#FF00FF"],
            ["#808080"],
            [ "#FFFF00"],
            [ "#800000"],
            ["#000000"],
            ["#F5DEB3"],
            [ "#FFA500"],
            ["#FFC0CB"],
            [ "#FF0000"],
            [ "#40E0D0"],
            [ "#483C32"],
            ["#008000"],
            [ "#800080"],
            [ "#000000000"], 

        ];

        return collect($colors);
    }
}