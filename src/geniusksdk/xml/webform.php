<?php

namespace GeniusKSDK\XML;

class Webform extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function list() {
        return $this->send('WebFormService.getMap', array());
    }

    public function read(int $id) {
        $params = array(
            $id,
        );

        return $this->send('WebFormService.getHTML', $params);
    }
}
