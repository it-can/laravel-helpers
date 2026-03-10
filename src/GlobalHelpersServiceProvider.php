<?php

namespace ITCAN\LaravelHelpers;

use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use ITCAN\LaravelHelpers\Helpers\CryptHelper;
use ITCAN\LaravelHelpers\Helpers\Macros\Trim;
use ITCAN\LaravelHelpers\Helpers\StreamHelper;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use ITCAN\LaravelHelpers\Helpers\Macros\ParseDelimitedString;

class GlobalHelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        Collection::make($this->strMacros())
            ->reject(fn ($class, $macro) => Str::hasMacro($macro) || method_exists(Str::class, $macro))
            ->each(fn ($class, $macro) => Str::macro($macro, app($class)()));
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('nl2br', function ($string) {
            return "<?php echo nl2br(e($string)); ?>";
        });

        Blade::directive('lower', function ($string) {
            return "<?php echo strtolower($string); ?>";
        });

        Blade::directive('upper', function ($string) {
            return "<?php echo strtoupper($string); ?>";
        });

        Blade::directive('dd', function ($param = null) {
            return "<pre><?php dd({$param}); ?></pre>";
        });

        Blade::directive('markdown', function ($expression = '') {
            return "<?php echo markdown($expression); ?>";
        });

        Blade::directive('webp', function () {
            return '<?php if (webPSupported()): ?>';
        });

        Blade::directive('endwebp', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('transparent_pixel', function () {
            return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII=';
        });

        /**
         * Add dot function to Collection.
         */
        Collection::macro('dot', function ($key, $default = null) {
            $value = Arr::get($this, $key, $default);

            return is_iterable($value) ? collect($value) : $value;
        });

        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        // Original idea by @therobfonz
        // https://twitter.com/kirschbaum_dev/status/1418590965368074241
        // Refined by BinaryKitten
        // https://gist.github.com/BinaryKitten/2873e11daf3c0130b5a19f6b94315033
        QueryBuilder::macro(
            'toRawSql',
            function () {
                return array_reduce(
                    $this->getBindings(),
                    static function ($sql, $binding) {
                        if ($binding instanceof DateTime) {
                            $binding = $binding->format('Y-m-d H:i:s');
                        }

                        return preg_replace(
                            '/\?/',
                            is_string($binding) ? "'" . $binding . "'" : $binding,
                            $sql,
                            1
                        );
                    },
                    $this->toSql()
                );
            }
        );

        EloquentBuilder::macro(
            'toRawSql',
            function () {
                return $this->getQuery()->toRawSql();
            }
        );

        Response::macro('streamDecryptedFile', function (string $filePath, string $filename, array $headers = [], string $disposition = 'attachment'): StreamedResponse {
            return Response::streamDownload(function () use ($filePath) {
                CryptHelper::streamDecrypt($filePath);
            }, $filename, $headers, $disposition);
        });

        Response::macro('pdfStream', function (string $filePath, ?string $fileName = null): StreamedResponse {
            $downloadName = $fileName ?? basename($filePath);
            $downloadName = StreamHelper::sanitizeDownloadName($downloadName);

            return new StreamedResponse(function () use ($filePath): void {
                StreamHelper::fromPath($filePath);
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => HeaderUtils::makeDisposition(
                    ResponseHeaderBag::DISPOSITION_INLINE,
                    $downloadName
                ),
            ]);
        });

        Response::macro('fileStream', function (
            string $filePath,
            ?string $fileName = null,
            array $headers = [],
            string $disposition = 'attachment'
        ): StreamedResponse {
            $downloadName = $fileName ?? basename($filePath);
            $downloadName = StreamHelper::sanitizeDownloadName($downloadName);

            return Response::streamDownload(function () use ($filePath): void {
                StreamHelper::fromPath($filePath);
            }, $downloadName, $headers, $disposition);
        });

        Response::macro('streamFileFromDisk', function (
            string|Filesystem $disk,
            string $filePath,
            ?string $fileName = null,
            array $headers = [],
            string $disposition = 'attachment'
        ): StreamedResponse {
            $downloadName = $fileName ?? basename($filePath);
            $downloadName = StreamHelper::sanitizeDownloadName($downloadName);

            return Response::streamDownload(function () use ($disk, $filePath): void {
                StreamHelper::fromDisk($disk, $filePath);
            }, $downloadName, $headers, $disposition);
        });
    }

    protected function strMacros(): array
    {
        return [
            'trim' => Trim::class,
            'parseDelimitedString' => ParseDelimitedString::class,
        ];
    }
}
