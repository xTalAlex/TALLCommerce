<?php

if (!function_exists('insensitiveLike')) {
    function insensitiveLike($connection = null): string {
        $like = 'like';
        if( (!$connection && config('database.default') == 'pgsql') || ($connection == 'pgsql') )
        {
            $like = 'ilike';
        }
        return $like;
    }
}

if (!function_exists('priceLabel')) {
    function priceLabel($price): string {
        return number_format($price,2) . '€';
    }
}