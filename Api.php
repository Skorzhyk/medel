<?php

class Api
{
    private static $api = null;

    public static function getApi()
    {
        if (self::$api === null) {
            self::$api = new self();
        }

        return self::$api;
    }

    public function execute($engineCode, $action, $data)
    {
        $engineName = strtolower($engineCode);
        $engineName[0] = strtoupper($engineName[0]);

        require_once 'Model/' . $engineName . '.php';

        $engine = new $engineName();

        $response = $engine->$action($data);

        if (is_array($response)) {
            echo json_encode($response);
        } else {
            echo $response;
        }
    }

    public function request($url, $params = []) {
        $myCurl = curl_init();

        curl_setopt_array($myCurl, array(
            CURLOPT_URL => 'http://db.medel/' . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'data=' . json_encode($params)
        ));

        $response = curl_exec($myCurl);

        curl_close($myCurl);

        if ($this->isJSON($response)) {
            $response = json_decode($response, true);
        }

        return $response;
    }

    protected function isJSON($string) {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }
}