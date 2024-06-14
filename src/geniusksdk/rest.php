<?php

namespace GeniusKSDK;

abstract class REST {

    use \GeniusKSDK\Quirk;

    protected $client;
    
    CONST EX_UNSUPPORTED = '%s is not supported.';

    public function __construct(\GeniusKSDK $client) {
        $this->client = $client;
    }
}
