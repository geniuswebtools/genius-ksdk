<?php

namespace GeniusKSDK\XML;

class AffiliateProgram extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve All Programs 
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-all-programs
     */
    public function list() {
        return $this->send('AffiliateProgramService.getAffiliatePrograms', array());
    }

    /**
     * Retrieve a Program's Affiliates
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-a-program-s-affiliates
     */
    
    
    /**
     * Retrieve an Affiliate's Programs
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-an-affiliate-s-programs
     */
    /**
     * Retrieve Program Resources
     * https://developer.keap.com/docs/xml-rpc/#affiliate-program-retrieve-program-resources
     */
}
