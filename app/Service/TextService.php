<?php

namespace App\Service;

class TextService
{
    /**
     * Get term type for page title or etc
     * @param $term
     * @return string
     */
    public static function getTermTypeTitle($term): string
    {
        return match ($term) {
            'first' => "First Term",
            'second' => "Second Term",
            'retake' => "Retake ",
            default => "",
        };
    }

    /**
     * Get question type title
     * @param $type
     * @return string
     */
    public static function getQuestionTypeTitle($type): string
    {
        return ExamService::getQuestionTypes($type);
    }
}