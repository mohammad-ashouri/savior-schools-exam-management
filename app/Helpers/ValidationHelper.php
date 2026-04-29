<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function process($message, $modelName = null)
    {
        if ($modelName) {
            return str_replace($modelName . '.', '', $message);
        }

        return $message;
    }
}