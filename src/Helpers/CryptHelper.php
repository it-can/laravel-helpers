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
     * @return bool
     */
    public static function encrypt($sourcePath, $destPath, $deleteSourceFile = false)
    {
        $encrypter = self::fileEncrypter();

        $encrypted = $encrypter->encrypt($sourcePath, $destPath);

        // If encryption is successful, delete the source file
        if ($encrypted && $deleteSourceFile) {
            File::delete($sourcePath);
        }

        return $encrypted;
    }

    /**
     * @param  $sourcePath
     * @param  $destPath
     * @return bool
     *
     * @throws \Exception
     */
    public static function decrypt($sourcePath, $destPath = null)
    {
        $encrypter = self::fileEncrypter();

        return $encrypter->decrypt($sourcePath, $destPath);
    }

    /**
     * @param $sourcePath
     * @return bool
     * @throws \Exception
     */
    public static function streamDecrypt($sourcePath)
    {
        $encrypter = self::fileEncrypter();

        return $encrypter->decrypt($sourcePath, 'php://output');
    }

    /**
     * @return \ITCAN\LaravelHelpers\Helpers\FileEncrypter
     */
    private static function fileEncrypter()
    {
        return new FileEncrypter(config('app.file_encrypt.key'), config('app.file_encrypt.cipher'));
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
