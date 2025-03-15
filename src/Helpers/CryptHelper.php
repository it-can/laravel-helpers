<?php

namespace ITCAN\LaravelHelpers\Helpers;

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
     * @return bool
     *
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
}
