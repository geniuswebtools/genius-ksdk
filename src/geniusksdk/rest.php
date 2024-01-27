<?php

namespace GeniusKSDK;

abstract class REST {

    use \GeniusKSDK\Quirk;

    protected $client;

    public function __construct(\GeniusKSDK $client) {
        $this->client = $client;
    }
}
