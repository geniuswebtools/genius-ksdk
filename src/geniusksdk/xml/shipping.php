<?php

namespace GeniusKSDK\XML;

class Shipping extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function list() {
        return $this->send('ShippingService.getAllShippingOptions', array());
    }
}
