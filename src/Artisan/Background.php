<?php
/**
 * https://github.com/dmitry-ivanov/laravel-helper-functions.
 */

namespace ITCAN\LaravelHelpers\Artisan;

use Symfony\Component\Process\PhpExecutableFinder;

class Background
{
    private $command;
    private $before;
    private $after;

    /**
     * @param      $command
     * @param null $before
     * @param null $after
     */
    public function __construct($command, $before = null, $after = null)
    {
        $this->command = $command;
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * @param      $command
     * @param null $before
     * @param null $after
     * @return Background
     */
    public static function factory($command, $before = null, $after = null)
    {
        return new self($command, $before, $after);
    }

    /**
     * Execute command.
     */
    public function runInBackground()
    {
        exec($this->composeForRunInBackground());
    }

    /**
     * @return false|string
     */
    protected function phpBinary()
    {
        return (new PhpExecutableFinder)->find(false);
    }

    /**
     * Composer background script.
     *
     * @return string
     */
    protected function composeForRunInBackground()
    {
        return "({$this->composeForRun()}) > /dev/null 2>&1 &";
    }

    /**
     * Compile command.
     *
     * @return string
     */
    protected function composeForRun()
    {
        $parts = [];

        if ( ! empty($this->before)) {
            $parts[] = (string) $this->before;
        }

        $parts[] = "{$this->phpBinary()} {$this->getArtisan()} {$this->command}";

        if ( ! empty($this->after)) {
            $parts[] = (string) $this->after;
        }

        return implode(' && ', $parts);
    }

    /**
     * Get artisan path.
     *
     * @return string
     */
    protected function getArtisan()
    {
        $artisan = defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'artisan';

        return base_path($artisan);
    }
}
