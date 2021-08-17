<?php

namespace ITCAN\LaravelHelpers;

use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class GlobalHelpersServiceProvider extends ServiceProvider
{
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
            return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
        });

        /*
         * Allow to return PDF files in browser instead of download
         */
        Response::macro('pdf', function ($content, $filename, $return_string = false) {
            if ($return_string) {
                return $content;
            }

            $headers = [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ];

            return Response::make($content, 200, $headers);
        });

        /**
         * Add dot function to Collection.
         */
        Collection::macro('dot', function ($key, $default = null) {
            $value = Arr::get($this, $key, $default);

            return is_iterable($value) ? collect($value) : $value;
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
    }
}
