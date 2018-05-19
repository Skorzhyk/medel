<?php

class API {
    protected $rules;

    public function executeAPI($action, $params = []) {
        $action = $this->rules[$action];
        $this->$action($params);
    }
}