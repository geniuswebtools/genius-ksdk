<?php

namespace GeniusKSDK\XML;

class AffiliateProgram extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve All Programs 
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-all-programs
     * 
     * @return stdClass Object
     */
    public function list() {
        return $this->send('AffiliateProgramService.getAffiliatePrograms', array());
    }

    public function create() {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, __METHOD__));
    }

    /**
     * Retrieve a Program's Affiliates
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-a-program-s-affiliates
     * 
     * Retrieve an Affiliate's Programs
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-an-affiliate-s-programs
     * 
     * Retrieve Program Resources
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-program-resources
     * 
     * @param int $id
     * @param string $type [program|affiliate|resources]
     * @return stdClass Object
     * @throws \Exception
     */
    public function read(int $id, string $type = 'program') {
        try {
            $service = $this->obj($type);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
        $params = array(
            $id
        );
        return $this->send($service, $params);
    }

    public function update() {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, __METHOD__));
    }

    public function delete() {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, __METHOD__));
    }

    /**
     * Returns the requested XLM-RPC service
     * 
     * @param string $src
     * @return string
     */
    protected function obj(string $src) {
        $candidate = strtolower($src);
        $serviceList = array(
            'program' => 'AffiliateProgramService.getAffiliatesByProgram',
            'affiliate' => 'AffiliateProgramService.getProgramsForAffiliate',
            'resources' => 'AffiliateProgramService.getResourcesForAffiliateProgram',
        );
        $service = ( (array_key_exists($candidate, $serviceList)) ? $serviceList[$candidate] : false);
        if ($service === false) {
            throw new \Exception('Invalid $src, must be one of [' . implode(', ', array_keys($serviceList)) . ']');
        }
        return $service;
    }
}
