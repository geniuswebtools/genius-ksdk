<?php

namespace GeniusKSDK\REST;

/**
 * REST V1 
 * @link https://developer.infusionsoft.com/docs/rest/#tag/Campaign REST V1 documentation
 * 
 * Campaigns have been rename Automations.  The remaining campaign endpoints for
 * achieving goals and adding or removing contacts from automation sequences are
 * in the Automation class model.
 * 
 * @uses Automation
 */
class Campaign extends Automation {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }
}
