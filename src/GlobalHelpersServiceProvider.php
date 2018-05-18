<?php

namespace ITCAN\LaravelHelpers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GlobalHelpersServiceProvider extends ServiceProvider
{
    /**
     * Register Blade directives
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('nl2br', function ($string) {
            return "<?php echo nl2br(e($string)); ?>";
        });

        Blade::directive('production', function () {
            return "<?php if (isProduction()): ?>";
        });

        Blade::directive('endproduction', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('notproduction', function () {
            return "<?php if ( ! isProduction()): ?>";
        });

        Blade::directive('endnotproduction', function () {
            return "<?php endif; ?>";
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
            return "<?php echo markdown($expression, true); ?>";
        });
    }
}
