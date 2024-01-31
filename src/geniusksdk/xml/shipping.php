<?php

namespace GeniusKSDK\XML;

class Shipping extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function list(string $type = 'all ') {
        return $this->send('ShippingService.getAllShippingOptions', array());
    }

    public function read(string $type, int $id) {
        $params = array(
            $id,
        );
        switch (strtolower($type)) {
            case 'ups';
                $service = 'ShippingService.getUpsShippingOption';
                break;
            case 'ordertotalranges':
                $service = 'ShippingService.getOrderTotalShippingRanges';
                break;
            case 'orderqty':
                $service = 'ShippingService.getOrderQuantityShippingOption';
                break;
            case 'ordertotal':
                $service = 'ShippingService.getOrderTotalShippingOption';
                break;
            case 'product':
                $service = 'ShippingService.getProductBasedShippingOption';
                break;
            case 'flat':
                $service = 'ShippingService.getFlatRateShippingOption';
                break;
            case 'weight':
                $service = 'ShippingService.getWeightBasedShippingOption';
                break;
            default:
                throw new \Exception('Invalid $type provided.');
                break;
        }

        return $this->send($service, $params);
    }
}
