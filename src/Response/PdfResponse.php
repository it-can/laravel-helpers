<?php

namespace ITCAN\LaravelHelpers\Response;

use Illuminate\Http\Response;

class PdfResponse extends Response
{
    public function __construct($content, $filename, $extraHeaders = [])
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ];

        if (is_array($extraHeaders)) {
            $headers = $headers + $extraHeaders;
        }

        parent::__construct($content, 200, $headers);
    }
}
