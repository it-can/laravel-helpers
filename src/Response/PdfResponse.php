<?php

use Illuminate\Http\Response;

class PdfResponse extends Response
{
    /**
     * @param $content
     * @param $filename
     */
    public function __construct($content, $filename)
    {
        $headers = [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ];

        parent::__construct($content, 200, $headers);
    }
}
