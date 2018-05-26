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
        $droneId = null;

        $drones = $this->api->request('drone/all');

        foreach ($drones as $drone) {
            if ($drone['status'] == 1) {
                $drone['status'] = 2;
                $droneId = $drone['id'];
                $this->api->request('drone/save', $drone);

                break;
            }
        }

        $data['drone_id'] = $droneId;
        $data['status'] = 0;

        return $this->api->request('supply/save', $data);
    }

    public function done()
    {

    }

    protected function moveDrone($supplyId) {}
}