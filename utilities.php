<?php
include "uuid_loading_punks.php";
include "id_background_hex_looney_luca.php";
include "punks_type_attributes.php";
include "looneyluca_type_attributes.php";
include "basealiens_type_attributes.php";

if (!function_exists('is_cli')) {
    function is_cli() {
        if ( defined('STDIN') ) {
            return true;
        }
        if ( php_sapi_name() === 'cli' ) {
            return true;
        }
        if ( array_key_exists('SHELL', $_ENV) ) {
            return true;
        }
        if ( empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        }
        if ( !array_key_exists('REQUEST_METHOD', $_SERVER) ) {
            return true;
        }
        return false;
    }
}

if (!function_exists('camelize')) {
    function camelize(string $string): string
    {
        $separator = '-';
        $result = lcfirst(str_replace($separator, '', ucwords($string, $separator)));

        $separator = '_';
        return lcfirst(str_replace($separator, '', ucwords($result, $separator)));
    }
}

/**
 * return null|string
 */
if (!function_exists('env')) {
    function env (string $key)
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        if (!array_key_exists($key, $_ENV)) {
            return null;
        }

        return $_ENV[$key];
    }
}

if (!function_exists('abort')) {
    function abort(string $url = '', string $message = null) {
        redirect($url, $message, 'danger');
    }
}

if (!function_exists('success')) {
    function success(string $url, string $message = null) {
        redirect($url, $message, 'success');
    }
}

if (!function_exists('warning')) {
    function warning(string $url, string $message = null) {
        redirect($url, $message, 'warning');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, string $message = null, string $type = null) {

        if ($message) {
            $session = new \App\Session();
            $session->setSession('alert', [
                'message' => $message,
                'type' => $type ?? 'success',
            ]);
        }

        header('Location: /' . $url);
        exit;
    }
}

if (!function_exists('generate_token')) {
    function generate_token(int $length = 24) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $charactersLength - 1);
            $token .= $characters[$randomIndex];
        }

        return $token;
    }
}

if (!function_exists('flatten_string')) {
    function flatten_string(string $string): string
    {
        $result = '';

        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];

            if (ctype_upper($char)) {
                $result .= '_' . strtolower($char);
            } else {
                $result .= $char;
            }
        }

        $result = str_replace(' ', '', $result);

        return ltrim($result, '_');
    }
}

if (!function_exists('form_errors')) {
    function form_errors(array $formValidatedValues, array $formErrors)
    {
        $session = new \App\Session();
        $session->setSession('formValidatedValues', $formValidatedValues);
        $session->setSession('formErrors', $formErrors);
    }
}

if (!function_exists('parse_form_errors')) {
    function parse_form_errors(string $formField, array $formErrors = null):? string
    {
        if (!$formErrors) {
            return null;
        }

        if (!isset($formErrors[$formField])) {
            return null;
        }

        $html = '';
        foreach ($formErrors[$formField] as $formError) {
            $html .= '<div class="invalid-feedback d-block">' . $formError . '</div>';
        }

        return $html;
    }
}

if (!function_exists('download_remote_url_and_return_temp_path')) {
    function download_remote_url_and_return_temp_path(string $slug, string $filename): array
    {
        $remoteUrl = get_url_digital_ocean($slug, $filename);

        $path = ROOT . '/tmp/' . $slug . '-' . $filename;
        $image = file_get_contents($remoteUrl);
        file_put_contents($path, $image);
        chmod($path, 0777);

        return [
            'urlTMP' => $path,
            'urlCDN' => $remoteUrl,
        ];
    }
}

if (!function_exists('get_url_digital_ocean')) {
    function get_url_digital_ocean(string $slug, string $filename): string
    {
        return 'https://familynfts.sfo3.cdn.digitaloceanspaces.com/' . $slug . '/' . $filename;
    }
}

if (!function_exists('get_random_number')) {
    function get_random_number(int $min, int $max): int
    {
        return rand($min, $max);
    }
}

if (!function_exists('now')) {
    function now(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now('America/Curacao');
    }
}

if (!function_exists('array_cast_recursive')) {
    function array_cast_recursive($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = array_cast_recursive($value);
                }
                if ($value instanceof stdClass) {
                    $array[$key] = array_cast_recursive((array) $value);
                }
            }
        }

        if ($array instanceof stdClass) {
            return array_cast_recursive((array) $array);
        }

        return $array;
    }
}

if (!function_exists('convert_snakecase_to_camelcase')) {
    function convert_snakecase_to_camelcase(string $string, bool $capitalizeFirstCharacter = false): string
    {
        $string = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $string[0] = strtolower($string[0]);
        }

        return $string;
    }
}

if (!function_exists('adjust_brightness')) {
    function adjust_brightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $colorX) {
            $adjustableLimit = $adjustPercent < 0 ? $colorX : 255 - $colorX;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);
            $colorX = str_pad(dechex((int)$colorX + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }
}
if (!function_exists('darken_color')) {
    function darken_color($hex, $percent) {
        $hex = str_replace('#', '', $hex);

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, $r - round($r * $percent / 100));
        $g = max(0, $g - round($g * $percent / 100));
        $b = max(0, $b - round($b * $percent / 100));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}

if (!function_exists('pick_color')) {
    function pick_color(string $image, int $x, int $y): string
    {
        $im = imagecreatefrompng($image);

        $rgb = imagecolorat($im, $x, $y);
        $colors = imagecolorsforindex($im, $rgb);

        return sprintf("#%02x%02x%02x", $colors['red'], $colors['green'], $colors['blue']);
    }
}
