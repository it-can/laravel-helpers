<?php

namespace ITCAN\LaravelHelpers\Response;

use Illuminate\Http\Response;

class PdfResponse extends Response
{
    public function __construct($content, string $filename, array $extraHeaders = [])
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
        ];

        if (is_array($extraHeaders)) {
            $headers += $extraHeaders;
        }

        parent::__construct($content, 200, $headers);
    }
}
