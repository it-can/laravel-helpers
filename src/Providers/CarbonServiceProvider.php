<?php

/*
 * Portal LFH
 *
 * @copyright Copyright (c) 2023-2024
 * @author    IT Can (M. Vugteveen) <info@it-can.nl>
 */

namespace ITCAN\LaravelHelpers\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // List of diffIn* methods
        $methods = [
            'diffInDays',
            'diffInDaysFiltered',
            'diffInHours',
            'diffInHoursFiltered',
            'diffInMicroseconds',
            'diffInMilliseconds',
            'diffInMinutes',
            'diffInMonths',
            'diffInQuarters',
            'diffInSeconds',
            'diffInUnit',
            'diffInWeekdays',
            'diffInWeekendDays',
            'diffInWeeks',
            'diffInYears',
        ];

        // Loop through each method and define a macro
        foreach ($methods as $method) {
            $macro = 'int'.ucfirst($method);

            Carbon::macro($macro, function (...$args) use ($method) {
                return (int) abs(call_user_func_array([$this, $method], $args));
            });
        }

        Carbon::macro('isJanuary', function () {
            return $this->month === Carbon::JANUARY;
        });

        Carbon::macro('isFebruary', function () {
            return $this->month === Carbon::FEBRUARY;
        });

        Carbon::macro('isMarch', function () {
            return $this->month === Carbon::MARCH;
        });

        Carbon::macro('isApril', function () {
            return $this->month === Carbon::APRIL;
        });

        Carbon::macro('isMay', function () {
            return $this->month === Carbon::MAY;
        });

        Carbon::macro('isJune', function () {
            return $this->month === Carbon::JUNE;
        });

        Carbon::macro('isJuly', function () {
            return $this->month === Carbon::JULY;
        });

        Carbon::macro('isAugust', function () {
            return $this->month === Carbon::AUGUST;
        });

        Carbon::macro('isSeptember', function () {
            return $this->month === Carbon::SEPTEMBER;
        });

        Carbon::macro('isOctober', function () {
            return $this->month === Carbon::OCTOBER;
        });

        Carbon::macro('isNovember', function () {
            return $this->month === Carbon::NOVEMBER;
        });

        Carbon::macro('isDecember', function () {
            return $this->month === Carbon::DECEMBER;
        });
    }
}
