<?php

require_once 'Api.php';

class Supply
{
    protected $api;

    public function __construct()
    {
        $this->api = Api::getApi();
    }

    public function init($data)
    {
        $droneId = $this->api->request('drone/apply');

        $data['drone_id'] = $droneId;
        $data['status'] = 1;

        return $this->api->request('supply/save', $data);
    }

    public function done()
    {

    }

    protected function moveDrone($supplyId) {}
}