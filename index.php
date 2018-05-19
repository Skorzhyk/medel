<?php

include_once 'Router.php';

if (!$q = strpos($_SERVER['REQUEST_URI'], '?')) {
    $q = strlen($_SERVER['REQUEST_URI']);
}

$url = explode('/', substr($_SERVER['REQUEST_URI'], 1, $q - 1));

$engine = strtolower($url[0]);
$engine[0] = strtoupper($engine[0]);

echo $engine;
exit;
require_once 'Model/' . $engine . '.php';
$engine = new $engine();

$engine->executeAPI($url[1], json_decode($_REQUEST['data'], true));




