<?php

namespace ITCAN\LaravelHelpers\Helpers;

use RuntimeException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

final class StreamHelper
{
    public static function fromPath(string $filePath): void
    {
        self::copyStreamToOutput(self::openPathStream($filePath));
    }

    public static function fromDisk(string|Filesystem $disk, string $filePath): void
    {
        $filesystem = is_string($disk) ? Storage::disk($disk) : $disk;

        self::copyStreamToOutput(self::openDiskStream($filesystem, $filePath));
    }

    /**
     * @param  resource  $stream
     */
    public static function copyStreamToOutput($stream): void
    {
        if (! is_resource($stream)) {
            throw new RuntimeException('Stream is not a valid resource.');
        }

        $output = fopen('php://output', 'wb');

        if ($output === false) {
            fclose($stream);

            throw new RuntimeException('Could not open output stream for writing.');
        }

        try {
            if (stream_copy_to_stream($stream, $output) === false) {
                throw new RuntimeException('Failed to copy stream to output.');
            }
        } finally {
            fclose($output);
            fclose($stream);
        }
    }

    public static function sanitizeDownloadName(string $fileName): string
    {
        $fileName = trim($fileName);
        $fileName = preg_replace('/[[:cntrl:]]+/', '', $fileName);
        $fileName = str_replace(['"', '/', '\\'], '-', $fileName);

        return $fileName !== '' ? $fileName : 'download';
    }

    public static function resolveDiskName(string|Filesystem $disk): string
    {
        if (is_string($disk)) {
            return $disk;
        }

        if (! method_exists($disk, 'getConfig')) {
            return 'unknown';
        }

        $config = $disk->getConfig();

        if (! is_array($config)) {
            return 'unknown';
        }

        return $config['disk_name'] ?? 'unknown';
    }

    /**
     * @return resource
     */
    private static function openPathStream(string $filePath)
    {
        if (! is_file($filePath) || ! is_readable($filePath)) {
            throw new RuntimeException(sprintf('Could not open file for reading: %s', $filePath));
        }

        $stream = fopen($filePath, 'rb');

        if ($stream === false) {
            throw new RuntimeException(sprintf('Could not open file for reading: %s', $filePath));
        }

        return $stream;
    }

    /**
     * @return resource
     */
    private static function openDiskStream(Filesystem $disk, string $filePath)
    {
        $stream = $disk->readStream($filePath);

        if ($stream === false) {
            $diskName = self::resolveDiskName($disk);

            throw new RuntimeException(sprintf(
                'Could not open disk (%s) file for reading: %s',
                $diskName,
                $filePath
            ));
        }

        return $stream;
    }
}
