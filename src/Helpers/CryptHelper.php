<?php

namespace ITCAN\LaravelHelpers\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class CryptHelper
{
    /**
     * @param  string  $sourcePath
     * @param  string  $destPath
     * @param  bool  $deleteSourceFile
     * @return mixed
     */
    public static function encrypt($sourcePath, $destPath, $deleteSourceFile = false)
    {
        $encrypter = self::fileEncrypter();

        // If encryption is successful, delete the source file
        if ($encrypter->encrypt($sourcePath, $destPath) && $deleteSourceFile) {
            File::delete($sourcePath);
        }
    }

    /**
     * @param  $sourcePath
     * @param  $destPath
     * @return void
     *
     * @throws \Exception
     */
    public static function decrypt($sourcePath, $destPath = null)
    {
        $encrypter = self::fileEncrypter();

        $encrypter->decrypt($sourcePath, $destPath);
    }

    /**
     * @param  $sourcePath
     * @param  $destPath
     * @return void
     *
     * @throws \Exception
     */
    public static function streamDecrypt($sourcePath)
    {
        $encrypter = self::fileEncrypter();

        $encrypter->decrypt($sourcePath, 'php://output');
    }

    /**
     * @return \ITCAN\LaravelHelpers\Helpers\FileEncrypter
     */
    private static function fileEncrypter()
    {
        return new FileEncrypter(config('app.key'), config('app.cipher'));
    }

    /**
     * @param  $baseFilePath
     * @param  $finalFilePath
     * @param  $deleteBaseFile
     * @return mixed
     *
     * @deprecated
     */
    public static function encryptLargeFile($baseFilePath, $finalFilePath, $deleteBaseFile = false)
    {
        $chunkSize = 1024 * 1024;

        // Open the file for reading
        $fileHandle = fopen($baseFilePath, 'r');

        // Open a new file for writing the encrypted chunks
        File::put($finalFilePath, '');

        // Loop through the file in chunks and encrypt each chunk
        while (! feof($fileHandle)) {
            $chunk = fread($fileHandle, $chunkSize);

            File::append($finalFilePath, Crypt::encrypt($chunk));

            unset($chunk);
        }

        // Close the file handles
        fclose($fileHandle);

        if ($deleteBaseFile) {
            File::delete($baseFilePath);
        }

        // Return the filename of the encrypted file
        return $finalFilePath;
    }
}
