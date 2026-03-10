<?php

namespace ITCAN\LaravelHelpers\Helpers;

use RuntimeException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

final class StreamHelper
{
    public static function fromPath(string $filePath): void
    {
        $stream = fopen($filePath, 'rb');

        if ($stream === false) {
            throw new RuntimeException(sprintf('Could not open file for reading: %s', $filePath));
        }

        self::streamToOutput($stream);
    }

    public static function fromDisk(string|Filesystem $disk, string $filePath): void
    {
        if (is_string($disk)) {
            $disk = Storage::disk($disk);
        }

        $stream = $disk->readStream($filePath);

        if ($stream === false) {
            throw new RuntimeException(sprintf('Could not open disk file for reading: %s', $filePath));
        }

        self::streamToOutput($stream);
    }

    /**
     * @param  resource|false  $stream
     */
    public static function streamToOutput($stream): void
    {
        if (! is_resource($stream)) {
            return;
        }

        try {
            $output = fopen('php://output', 'wb');

            if (! is_resource($output)) {
                return;
            }

            try {
                stream_copy_to_stream($stream, $output);
            } finally {
                fclose($output);
            }
        } finally {
            fclose($stream);
        }
    }

    public static function sanitizeDownloadName(string $fileName): string
    {
        return str_replace(['"', "\r", "\n"], '', $fileName);
    }
}
