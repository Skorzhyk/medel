<?php

require_once 'Api.php';

$api = Api::getApi();

if (!$_REQUEST['data']) {
    echo request('supply/init', ['status' => 0, 'drone_id' => 1, 'user_id' => 1, 'medicines' => json_encode([1 => 3])]);
} else {

    if (!$q = strpos($_SERVER['REQUEST_URI'], '?')) {
        $q = strlen($_SERVER['REQUEST_URI']);
    }

    $url = explode('/', substr($_SERVER['REQUEST_URI'], 1, $q - 1));

    $api->execute($url[0], $url[1], json_decode($_REQUEST['data'], true));
}

function request($url, $params = []) {
    $myCurl = curl_init();

    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'http://medel/' . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => 'data=' . json_encode($params)
    ));

    $response = curl_exec($myCurl);

    curl_close($myCurl);

    if (isJSON($response)) {
        $response = json_decode($response, true);
    }

    return $response;
}

function isJSON($string) {
    return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
}






