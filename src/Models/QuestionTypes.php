<?php

namespace R64\Webforms\Models;

use Symfony\Component\Finder\Finder;

class QuestionTypes
{
    public static function getAllQuestionTypes()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/../QuestionTypes');

        if ($finder->hasResults()) {
            return collect($finder)->map(function ($file) {
                $className = $file->getFilenameWithoutExtension();
                $classFQDN = 'R64\Webforms\QuestionTypes\\' . $className;

                return $classFQDN::TYPE;
            })->values();
        }

        return collect();
    }
}
