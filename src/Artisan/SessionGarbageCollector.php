<?php
/*
 * Copyright (c) $year.
 * @author IT Can (Michiel Vugteveen) <info@it-can.nl>
 */

namespace ITCAN\LaravelHelpers\Artisan;

use Illuminate\Console\Command;

class SessionGarbageCollector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:gc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the sessions garbage if applicable to the current driver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        session()->getHandler()->gc($this->getSessionLifetimeInSeconds());
    }

    /**
     * Get the session lifetime in seconds.
     *
     * @return int
     */
    protected function getSessionLifetimeInSeconds()
    {
        return config('session.lifetime', null) * 60;
    }
}
