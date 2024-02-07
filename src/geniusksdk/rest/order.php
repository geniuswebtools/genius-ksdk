<?php

namespace GeniusKSDK\REST;

class Order extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function model() {
        return $this->client->read('/v1/orders/model');
    }

    public function list(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->client->read('/v1/orders' . $httpQuery);
    }

    public function create(array $struct) {
        return $this->client->create('/v1/orders', $struct);
    }

    public function read(int $id) {
        return $this->client->read('/v1/orders/' . $id);
    }

    public function delete(int $id) {
        return $this->client->delete('/v1/orders/' . $id);
    }
}
