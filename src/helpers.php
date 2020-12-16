<?php

use Pdp\Cache;
use Pdp\Manager;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Pdp\CurlHttpClient;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use League\CommonMark\Environment;
use Illuminate\Support\Facades\Auth;
use Collective\Html\HtmlFacade as Html;
use League\CommonMark\CommonMarkConverter;
use ITCAN\LaravelHelpers\Artisan\Background;
use League\CommonMark\Extension\Table\TableExtension;

if (! function_exists('fatal')) {
    /**
     * Dump the passed variables and end the script.
     * This will pass it through to dd() function of Laravel.
     *
     * @param mixed
     *
     * @return void
     * @deprecated
     */
    function fatal()
    {
        dd(func_get_args());
    }
}

if (! function_exists('cdnUrl')) {
    /**
     * Get cdn baseurl or normal baseurl.
     *
     * @param string $path Path to file
     *
     * @return string
     */
    function cdnUrl($path = '')
    {
        $cdn = (config('settings.cdn')) ?: config('app.url', url('/'));

        return rtrim($cdn, '/') . '/' . ltrim($path, '/');
    }
}

if (! function_exists('elixirCDN')) {
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param string $file
     *
     * @return string
     */
    function elixirCDN($file)
    {
        return cdnUrl(elixir($file));
    }
}

if (! function_exists('mixCDN')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $file
     *
     * @return string
     */
    function mixCDN($file)
    {
        return cdnUrl(mix($file));
    }
}

if (! function_exists('uploadUrl')) {
    /**
     * Get upload url.
     *
     * @param string $url
     *
     * @return string
     */
    function uploadUrl($url)
    {
        return cdnUrl('uploads/' . ltrim($url, '/'));
    }
}

if (! function_exists('imgUrl')) {
    /**
     * Get image url.
     *
     * @param string $url
     *
     * @return string
     */
    function imgUrl($url)
    {
        return cdnUrl('img/' . ltrim($url, '/'));
    }
}

if (! function_exists('img')) {
    /**
     * Simple image helper wrapper for Html::image.
     *
     * @param string $url
     * @param string $alt
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return string
     */
    function img($url, $alt = null, $attributes = [], $secure = null)
    {
        return Html::image(imgUrl($url), $alt, $attributes, $secure);
    }
}

if (! function_exists('convertFloat')) {
    /**
     * Convert to float.
     *
     * @param mixed $value
     *
     * @return float
     */
    function convertFloat($value)
    {
        return is_float($value) ? $value : floatval(str_replace(',', '.', $value));
    }
}

if (! function_exists('getUserId')) {
    /**
     * Return user id.
     *
     * @param  $guard
     *
     * @return int|null
     */
    function getUserId($guard = null)
    {
        return Auth::guard($guard)->id();
    }
}

if (! function_exists('isLoggedIn')) {
    /**
     * Return if user is loggedin.
     *
     * @param  $guard
     *
     * @return bool
     */
    function isLoggedIn($guard = null)
    {
        return Auth::guard($guard)->check();
    }
}

if (! function_exists('convertPercent')) {
    /**
     * Convert percentage to calculateble float.
     *
     * @param $percent
     *
     * @return float
     */
    function convertPercent($percent)
    {
        return $percent / 100;
    }
}

if (! function_exists('calculateTax')) {
    /**
     * Get amount of tax in amount.
     *
     * @param mixed $amount
     * @param mixed $tax
     * @param bool  $ex
     *
     * @return float
     */
    function calculateTax($amount = 0, $tax = 21, $ex = false)
    {
        $amount = convertFloat($amount);
        $tax = convertFloat($tax);

        if ($amount == 0 or $tax == 0) {
            return 0;
        }

        $tax = convertPercent($tax) + 1;
        $amount = ($ex) ? (($amount * $tax) - $amount) : ($amount - ($amount / $tax));

        return round($amount, 2);
    }
}

if (! function_exists('isEnv')) {
    /**
     * Is current environment given value?
     *
     * @param string $env
     *
     * @return bool
     */
    function isEnv($env)
    {
        return app()->environment($env);
    }
}

if (! function_exists('isProduction')) {
    /**
     * Is current environment production?
     *
     * @return bool
     */
    function isProduction()
    {
        return isEnv('production');
    }
}

if (! function_exists('isDevelopment')) {
    /**
     * Is current environment production?
     *
     * @return bool
     */
    function isDevelopment()
    {
        return ! isProduction();
    }
}

if (! function_exists('getPHPUser')) {
    /**
     * Return user that is running the script.
     *
     * @param bool $lowercase
     *
     * @return string
     */
    function getPHPUser($lowercase = true)
    {
        $user = posix_getpwuid(posix_geteuid())['name'];

        return ($lowercase) ? Str::lower($user) : $user;
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a Carbon object from a string.
     *
     * @param string|null               $time
     * @param \DateTimeZone|string|null $timezone
     *
     * @return \Carbon\Carbon
     */
    function carbon($time = null, $timezone = null)
    {
        return new Carbon($time, $timezone);
    }
}

if (! function_exists('getHost')) {
    /**
     * Get hostname from url.
     *
     * @param string $url
     * @param bool   $subdomain (include subdomain or not)
     *
     * @return string
     * @deprecated
     */
    function getHost($url, $subdomain = true)
    {
        // Fix url
        $url = trim($url);

        if (stripos($url, '://') === false and substr($url, 0, 1) != '/') {
            $url = 'http://' . $url;
        }

        $parsedUrl = parse_url($url);
        $parts = explode('.', $parsedUrl['host']);
        $tld = array_pop($parts);
        $host = array_pop($parts);

        if (strlen($tld) === 2 && strlen($host) <= 3) {
            $tld = $host . '.' . $tld;
            $host = array_pop($parts);
        }

        $info = [
            'protocol'  => $parsedUrl['scheme'],
            'subdomain' => implode('.', $parts),
            'domain'    => $host . '.' . $tld,
            'host'      => $host,
            'tld'       => $tld,
        ];

        return ($subdomain and ! empty($info['subdomain']))
            ? $info['subdomain'] . '.' . $info['domain']
            : $info['domain'];
    }
}

if (! function_exists('randomCode')) {
    /**
     * Generate random code.
     *
     * @return string
     */
    function randomCode()
    {
        return (string) Uuid::uuid4();
    }
}

if (! function_exists('randomFilename')) {
    /**
     * Generate random filename, regenerate if already exists.
     *
     * @param string      $path Path to check
     * @param string      $ext  Extension without dot
     * @param string|null $name Use name as filename (without ext)
     *
     * @return string
     */
    function randomFilename($path, $ext, $name = null)
    {
        $ext = Str::lower(str_replace('.', '', $ext));
        $filename = ($name) ? Str::slug(Str::limit($name, 50), '_') : Str::random(30);
        $filename = Str::lower($filename) . '_' . time();

        // Loop until file does not exists
        while (file_exists($path . '/' . $filename . '.' . $ext)) {
            $filename .= rand(0, 99999);
        }

        return $filename . '.' . $ext;
    }
}

if (! function_exists('ibanMachine')) {
    /**
     * Convert an IBAN to machine format.
     *
     * @param string $iban
     *
     * @return mixed|string
     */
    function ibanMachine($iban)
    {
        // Uppercase and trim spaces from left
        $iban = ltrim(strtoupper($iban));

        // Remove IIBAN or IBAN from start of string, if present
        $iban = preg_replace('/^I?IBAN/', '', $iban);

        // Remove all non basic roman letter / digit characters
        $iban = preg_replace('/[^a-zA-Z0-9]/', '', $iban);

        return $iban;
    }
}

if (! function_exists('ibanHuman')) {
    /**
     * Convert an IBAN to human format.
     *
     * @param string $iban
     *
     * @return string
     */
    function ibanHuman($iban)
    {
        // Add spaces every four characters
        return wordwrap(str_replace(' ', '', $iban), 4, ' ', true);
    }
}

if (! function_exists('selectArray')) {
    /**
     * Create select dropdown with optional first element.
     *
     * @param                   $array
     * @param bool|string|array $withNull
     *
     * @return array
     */
    function selectArray($array, $withNull = false)
    {
        // Create normal array
        if ($array instanceof Collection) {
            $array = $array->toArray();
        }

        if (! $array) {
            return [];
        }

        if ($withNull) {
            return is_array($withNull) ? $withNull + $array : ['' => $withNull] + $array;
        }

        return $array;
    }
}

if (! function_exists('nullOrValue')) {
    /**
     * Return null when string value when is empty
     * Optional, also return null when string value is 0.
     *
     * @param $value
     * @param $skipZero
     *
     * @return null|string
     */
    function nullOrValue($value, $skipZero = true)
    {
        if (is_string($value)) {
            if ($value === '' or ($skipZero and $value === '0')) {
                return null;
            }
        }

        return $value;
    }
}

if (! function_exists('markdown')) {
    /**
     * Convert markdown to html.
     *
     * @param string $text
     * @param bool   $lineBreak
     *
     * @return string
     */
    function markdown($text = '')
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new TableExtension);

        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => false,
        ], $environment);

        return $converter->convertToHtml($text);
    }
}

if (! function_exists('validEmail')) {
    /**
     * Check if email is valid.
     *
     * @param string $email
     *
     * @return bool
     */
    function validEmail($email = '')
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (! function_exists('sanitizeFilename')) {
    /**
     * Sanitize filename (ripped from CodeIgniter).
     *
     * @param string $filename
     * @param string $replace
     *
     * @return string
     */
    function sanitizeFilename($filename = '', $replace = '-')
    {
        $bad = [
            '../',
            '<!--',
            '-->',
            '<',
            '>',
            "'",
            '"',
            '&',
            '$',
            '#',
            '{',
            '}',
            '[',
            ']',
            '=',
            ';',
            '?',
            '%20',
            '%22',
            '%3c',        // <
            '%253c',      // <
            '%3e',        // >
            '%0e',        // >
            '%28',        // (
            '%29',        // )
            '%2528',      // (
            '%26',        // &
            '%24',        // $
            '%3f',        // ?
            '%3b',        // ;
            '%3d',        // =
            './',
            '/',
            '\\',
        ];

        // Remove Invisible Characters
        do {
            $filename = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $filename, -1, $count);
        } while ($count);

        // Remove bad characters
        do {
            $old = $filename;
            $filename = str_replace($bad, $replace, $filename);
        } while ($old !== $filename);

        return stripslashes($filename);
    }
}

if (! function_exists('isValidXML')) {
    /**
     * Validate XML.
     *
     * @param string $xml
     *
     * @return bool
     */
    function isValidXML($xml = '')
    {
        $xml = trim($xml);

        if (empty($xml) or stripos($xml, '<!DOCTYPE html>') !== false) {
            return false;
        }

        libxml_use_internal_errors(true);
        simplexml_load_string($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }
}

if (! function_exists('callBackground')) {
    /**
     * @param      $command
     * @param null $before
     * @param null $after
     *
     * @return mixed
     */
    function callBackground($command, $before = null, $after = null)
    {
        return Background::factory($command, $before, $after)
            ->runInBackground();
    }
}

if (! function_exists('domainName')) {
    /**
     * Parse url and return domainname.
     *
     * @param string $url
     *
     * @return string|null
     */
    function domainName($url = '', $withSubdomain = false)
    {
        $url = 'http://' . str_replace(['http://', 'https://'], '', $url);
        $host = parse_url($url, PHP_URL_HOST);

        $manager = new Manager(new Cache, new CurlHttpClient);
        $rules = $manager->getRules();
        $domain = $rules->resolve($host);

        return ($withSubdomain) ? $domain->getContent() : $domain->getRegistrableDomain();
    }
}

if (! function_exists('custom_range')) {
    /**
     * @param int $start
     * @param int $end
     * @param int $step
     *
     * @return array
     */
    function custom_range($start, $end, $step = 1)
    {
        if ($start === 0) {
            return range($start, $end, $step);
        }

        $range = [];

        for ($i = $start; $i <= $end; $i += $step) {
            $range[$i] = $i;
        }

        return $range;
    }
}

if (! function_exists('cleanLicensePlate')) {
    /**
     * @param $plate
     *
     * @return string
     */
    function cleanLicensePlate($plate)
    {
        return Str::upper(preg_replace('/[^a-zA-Z0-9]/', '', $plate));
    }
}

if (! function_exists('formatLicensePlate')) {
    /**
     * @param $plate
     *
     * @return string
     */
    function formatLicensePlate($plate)
    {
        $plateClean = cleanLicensePlate($plate);
        $licencePlateRegex = [
            '/^([A-Z]{2})(\d{2})(\d{2})$/',       // 1     XX-99-99    (since 1951)
            '/^(\d{2})(\d{2})([A-Z]{2})$/',       // 2     99-99-XX    (since 1965)
            '/^(\d{2})([A-Z]{2})(\d{2})$/',       // 3     99-XX-99    (since 1973)
            '/^([A-Z]{2})(\d{2})([A-Z]{2})$/',    // 4     XX-99-XX    (since 1978)
            '/^([A-Z]{2})([A-Z]{2})(\d{2})$/',    // 5     XX-XX-99    (since 1991)
            '/^(\d{2})([A-Z]{2})([A-Z]{2})$/',    // 6     99-XX-XX    (since 1999)
            '/^(\d{2})([A-Z]{3})(\d{1})$/',       // 7     99-XXX-9    (since 2005)
            '/^(\d{1})([A-Z]{3})(\d{2})$/',       // 8     9-XXX-99    (since 2009)
            '/^([A-Z]{2})(\d{3})([A-Z]{1})$/',    // 9     XX-999-X    (since 2006)
            '/^([A-Z]{1})(\d{3})([A-Z]{2})$/',    // 10    X-999-XX    (since 2008)
            '/^([A-Z]{3})(\d{2})([A-Z]{1})$/',    // 11    XXX-99-X    (since 2015)
            '/^([A-Z]{1})(\d{2})([A-Z]{3})$/',    // 12    X-99-XXX
            '/^(\d{1})([A-Z]{2})(\d{3})$/',       // 13    9-XX-999
            '/^(\d{3})([A-Z]{2})(\d{1})$/',       // 14    999-XX-9

            // likely upcoming plate patterns
            //'^(\d{3})(\d{2})([A-Z]{1})$/',       //       999-99-X
            //'^([A-Z]{3})(\d{2})(\d{1})$/',       //       XXX-99-9
            //'^([A-Z]{3})([A-Z]{2})(\d{1})$/',    //       XXX-XX-9
        ];

        $diplomateLicencePlateRegex = '/^CD[ABFJNST]\d{1,3}$/';

        if (preg_match($diplomateLicencePlateRegex, $plateClean)) {
            return $plateClean;
        }

        foreach ($licencePlateRegex as $regex) {
            if (preg_match($regex, $plateClean)) {
                return preg_replace($regex, '$1-$2-$3', $plateClean);
            }
        }

        return $plate;
    }
}
