<?php
/*
 * Copyright (c) $year.
 * @author IT Can (Michiel Vugteveen) <info@it-can.nl>
 */

namespace ITCAN\LaravelHelpers\Helpers\Macros;

class Trim
{
    public function __invoke()
    {
        return function ($value, $charlist = null) {
            if ($charlist === null) {
                return preg_replace('~^[\s\x{FEFF}\x{200B}\x{200E}]+|[\s\x{FEFF}\x{200B}\x{200E}]+$~u', '', $value) ?? trim($value);
            }

            return trim($value, $charlist);
        };
    }
}
