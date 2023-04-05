<?php

namespace ITCAN\LaravelHelpers\Response;

use Illuminate\Http\Response;

class PdfResponse extends Response
{
    public function __construct($content, $filename)
    {
        $headers = [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ];

        parent::__construct($content, 200, $headers);
    }
}
