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