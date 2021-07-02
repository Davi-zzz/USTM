<?php

use Carbon\Carbon;
use App\User;

function ukDate($datetime = null, $timestamp = false)
{
    $datetime = $datetime ? $datetime : Carbon::now();
    $format = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
    $timestamp = $timestamp ? 'Y-m-d H:i:s' : 'Y-m-d';
    return Carbon::createFromFormat($format, $datetime)->format($timestamp);
}


function brDate($datetime = null, $timestamp = false)
{
    $datetime = $datetime ? $datetime : Carbon::now();
    $timestamp = $timestamp ? 'd/m/Y H:i' : 'd/m/Y';
    return Carbon::parse($datetime)->format($timestamp);
}

function moeda($get_valor)
{
    if (!$get_valor) return 0;

    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $get_valor);
    return $valor;
}

function money($get_valor)
{
    $valor = number_format($get_valor, 2, ',', '.');
    return $valor;
}

function area($get_valor)
{
    $valor = str_replace(',', '.', $get_valor);

    return $valor;
}

function clean($string)
{
    return preg_replace('/[^A-Za-z0-9]/', '', $string);
}

function days()
{
    $days = array(
        1 => 'Domingo',
        'Segunda-Feira',
        'Terça-Feira',
        'Quarta-Feira',
        'Quinta-Feira',
        'Sexta-Feira',
        'Sábado'
    );
    return $days;
}

function isBase64($base64)
{
    $explode = explode(',', $base64);
    $allow = ['png', 'jpg', 'svg', 'jpeg'];
    $format = str_replace(
        [
            'data:image/',
            ';',
            'base64',
        ],
        [
            '', '', '',
        ],
        $explode[0]
    );

    // check file format
    if (!in_array($format, $allow)) {
        return false;
    }

    // check base64 format
    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
        return false;
    }

    return true;
}


function formatter($value)
{
    $source = array(',', '.');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $value);
    return $valor;
}

function user()
{
    $user_id = auth()->id();
    $user = User::with('people')->find($user_id);
    $firstName = explode(' ', $user->people->name);
    return $firstName[0];
}

function getFormat($type, $image)
{
    $explode = explode(',', $image);
    $format = str_replace(
        [
            'data:' . $type . '/',
            ';',
            'base64',
        ],
        [
            '', '', '',
        ],
        $explode[0]
    );

    return $format;
}

function formatHour($datetime = null, $timestamp = false)
{
    $datetime = $datetime ? $datetime : Carbon::now();
    $timestamp = 'H:i';
    return Carbon::parse($datetime)->format($timestamp);
}

if (!function_exists('carbon')) {
    /**
     * Retornar instância de data.
     *
     * @param mixed $date
     * @return Carbon\Carbon
     */
    function carbon($date = null)
    {
        if (!empty($date)) {
            if ($date instanceof DateTime) {
                return Carbon::instance($date);
            }

            return Carbon::parse(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date))));
        }

        return Carbon::now();
    }
}


function settings($key = null, $default = null)
{
    if ($key === null) {
        return app(App\Settings::class);
    }

    return app(App\Settings::class)->get($key, $default);
}


function code($number)
{
    return sprintf('%08d', $number);
}
