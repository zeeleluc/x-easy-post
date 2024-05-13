<?php

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
    function abort(string $message = null) {
        redirect('', $message, 'danger');
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
    function flatten_string(string $string)
    {
        $string = str_replace(' ', '', $string);
        $string = str_replace('-', '', $string);
        $string = str_replace('_', '', $string);

        return strtolower($string);
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
