<?php

namespace GeniusKSDK;

abstract class XML {

    use \GeniusKSDK\Quirk;

    protected $client;

    public function __construct(\GeniusKSDK $client) {
        $this->client = $client;
    }

    public function send(string $service, array $struct) {
        array_unshift($struct, $this->client->apiKey());
        $content = xmlrpc_encode_request($service, $struct, array(
            'output_type' => 'php',
        ));

        $options = array(
            'method' => 'POST',
            'header' => array(
                "Content-type: text/xml",
                "Content-length: " . strlen($content),
            ),
            'content' => $content,
        );
        return $this->client->request($this->client->endpoint(), $options);
    }

    protected function defaultQueryFilter(array $selectedFields) {
        return array(
            'limit' => 1000,
            'page' => 0,
            'queryData' => array('Id' => '%'),
            'selectedFields' => $selectedFields,
            'orderBy' => 'Id',
            'ascending' => true,
        );
    }
}
