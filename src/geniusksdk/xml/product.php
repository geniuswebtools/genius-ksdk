<?php

namespace GeniusKSDK\XML;

class Product extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function inventory(int $productId, string $action = '', int $quantity = 0) {
        $params = array(
            $productId,
        );
        switch ($action) {
            case 'increase':
                $service = 'ProductService.increaseInventory';
                $prams[] = $quantity;
                break;
            case 'decrease':
                $service = 'ProductService.decreaseInventory';
                $prams[] = $quantity;
                break;
            case 'increment':
                $service = 'ProductService.incrementInventory';
                break;
            case 'decrement':
                $service = 'ProductService.decrementInventory';
                break;
            default:
                $service = 'ProductService.getInventory';
                break;
        }

        return $this->send($service, $params);
    }
}
