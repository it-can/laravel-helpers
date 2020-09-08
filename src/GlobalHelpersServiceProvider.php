<?php

namespace ITCAN\LaravelHelpers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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

        if (! Response::hasMacro('pdf')) {
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
        }
    }
}
