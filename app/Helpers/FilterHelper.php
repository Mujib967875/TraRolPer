<?php

namespace App\Helpers;
class FilterHelper
{
    public static function containsBadWords($text)
    {
        $badWords = include storage_path('app/badwords.php');
        
        foreach ($badWords as $word) {
            if (stripos($text, $word) !== false) {
                return true; // Ada kata kasar
            }
        }
        return false; // Aman
    }
}
