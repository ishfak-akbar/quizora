<?php

namespace App\Helpers;

class QuizHelper
{
    /**
     * Returns a Tabler icon class for a given quiz category string.
     */
    public static function categoryIcon(?string $category): string
    {
        if (!$category) return 'ti ti-tag';

        $map = [
            'math'       => 'ti ti-math',
            'calculus'   => 'ti ti-math',
            'science'    => 'ti ti-flask',
            'physics'    => 'ti ti-atom',
            'chemistry'  => 'ti ti-flask-2',
            'biology'    => 'ti ti-dna',
            'english'    => 'ti ti-alphabet-latin',
            'language'   => 'ti ti-language',
            'history'    => 'ti ti-building-castle',
            'geography'  => 'ti ti-map',
            'bcs'        => 'ti ti-building-bank',
            'bangladesh' => 'ti ti-building-bank',
            'computer'   => 'ti ti-device-desktop',
            'coding'     => 'ti ti-code',
            'algorithm'  => 'ti ti-binary-tree',
            'data'       => 'ti ti-chart-dots',
            'general'    => 'ti ti-bulb',
            'gk'         => 'ti ti-bulb',
            'religion'   => 'ti ti-moon-stars',
            'islamic'    => 'ti ti-moon-stars',
            'economics'  => 'ti ti-chart-line',
            'business'   => 'ti ti-briefcase',
        ];

        $lower = strtolower($category);
        foreach ($map as $keyword => $icon) {
            if (str_contains($lower, $keyword)) {
                return $icon;
            }
        }

        return 'ti ti-tag';
    }
}
