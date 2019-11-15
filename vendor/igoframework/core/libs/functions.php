<?php

function dd($var, $die = true) {
    echo '<pre>' . print_r($var, true) . '</pre>';
    if ($die) die;
}

function d($var, $die = true) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    if ($die) die;
}

function redirect($http = false)
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    }
    header("Location: {$redirect}");
    exit();
}

function hsc($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}