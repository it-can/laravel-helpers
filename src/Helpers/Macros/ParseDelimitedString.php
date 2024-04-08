<?php

namespace ITCAN\LaravelHelpers\Helpers\Macros;

use Illuminate\Support\Str;

class ParseDelimitedString
{
    public function __invoke()
    {
        return function ($value, $separator = ',') {
            if (empty($value)) {
                return [];
            }

            // If the input is already an array, return it as is.
            if (is_array($value)) {
                return $value;
            }

            // Process the string to trim each element after splitting.
            return Str::of($value)
                ->explode($separator)
                ->map(function ($item) {
                    return Str::trim($item);
                })
                ->filter()
                ->values()
                ->all();
        };
    }
}
