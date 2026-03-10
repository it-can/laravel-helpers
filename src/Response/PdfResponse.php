<?php

namespace ITCAN\LaravelHelpers\Response;

use Illuminate\Http\Response;
use ITCAN\LaravelHelpers\Helpers\StreamHelper;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PdfResponse extends Response
{
    public function __construct($content, string $filename, array $extraHeaders = [])
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => HeaderUtils::makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, StreamHelper::sanitizeDownloadName($filename)),
        ];

        if (is_array($extraHeaders)) {
            $headers += $extraHeaders;
        }

        parent::__construct($content, 200, $headers);
    }
}
