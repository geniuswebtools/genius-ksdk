<?php

namespace GeniusKSDK\XML;

class Affiliate extends \GeniusKSDK\XML {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve Clawbacks
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-clawbacks
     * 
     * Retrieves all clawed back commissions for a particular affiliate. Claw 
     * backs typically occur when an order has been refunded to the customer.
     * 
     * Retrieve Commissions
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-commissions
     * 
     * Retrieves all commissions for a specific affiliate within a date range.
     * 
     * Retrieve Payments
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-payments
     * 
     * Retrieves all payments for a specific affiliate within a date range
     * 
     * Retrieve a Summary of Affiliate Statistics
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-a-summary-of-affiliate-statistics
     * 
     * Retrieves a summary of statistics for a list of affiliates.
     * 
     * @param string $src [clawbacks|commissions|payments|stats]
     * @param int $affiliateId The Id number of the affiliate
     * @param string $filterStartDate dateTime Starting date for the date range
     * @param string $filterEndDate dateTime Ending date for the date range
     * @return stdClass
     */
    public function list(string $src, int $affiliateId, string $filterStartDate, string $filterEndDate) {
        try {
            $service = $this->obj($src);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

        $params = array(
            $affiliateId,
            $filterStartDate,
            $filterEndDate
        );

        return $this->send($service, $params);
    }

    /**
     * Create Affiliate
     * 
     * @param array $struct
     * @return stcClass Object
     */
    public function create(array $struct) {
        $values = restruct($this->defaultStruct(), $struct);
        $params = array(
            'Affiliate',
            $values
        );

        return $this->send('DataService.add', $struct);
    }

    /**
     * Read Affiliate
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function read(int $id, array $struct) {
        $params = array(
            'Affiliate',
            $id,
            $struct
        );
        return $this->send('DataService.load', $params);
    }

    /**
     * Update Affiliate
     * 
     * @param int $id
     * @param array $struct
     * @return stcClass
     */
    public function update(int $id, array $struct) {
        $params = array(
            'Affiliate',
            $id,
            $struct,
        );

        return $this->send('DataService.update', $params);
    }

    /**
     * Delete Affiliate
     * 
     * Experimental: This operation is not provided by default.
     * USE AT YOUR OWN RISK
     * 
     * @param int $id
     * @return stcClass Object
     */
    public function delete(int $id) {
        $params = array(
            'Affiliate',
            $id
        );
        return $this->send('DataService.delete', $params);
    }

    /**
     * Retrieve Redirect Links
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-redirect-links
     * 
     * Retrieves a list of the redirect links for the specified Affiliate.
     * 
     * @param int $affiliateId
     * @return stdClass
     */
    public function links(int $affiliateId) {
        $params = array(
            $affiliateId
        );

        return $this->send('AffiliateService.getRedirectLinksForAffiliate', $params);
    }

    /**
     * Retrieve Running Totals
     * https://developer.infusionsoft.com/docs/xml-rpc/#affiliate-retrieve-running-totals
     * 
     * Retrieves the current balances for Amount Earned, Clawbacks, and Running Balance.
     * 
     * @param int|array $affiliateIds
     * @return stdClass
     */
    public function totals($affiliateIds) {
        if (!is_array($affiliateIds)) {
            $affiliateId = (int) $affiliateIds;
            $affiliateIds = array($affiliateId);
        }
        $params = array(
            $affiliateIds
        );
        return $this->send('APIAffiliateService.affRunningTotals', $params);
    }

    /**
     * LeadAmt - Eg. 23.33 ($23.33)
     * LeadPercent - Eg. 30.5 (30.5%)
     * PayoutType
     *     Receipt of Payment = 5
     *     Up front = 4
     * DefCommissionType
     *     Use Affiliate's Commissions = 2
     *     Use Product Commissions = 3
     * Status
     *     1 = Active
     *     0 = Inactive
     * AffName - Name of Affiliate eg. "John Doe"
     * AffCode - Must be unique
     * NotifyLead
     *     0 = No
     *     1 = Yes
     * NotifySale
     *     0 = No
     *     1 = Yes
     * LeadCookieFor - Number of days to keep lead cookie 
     */
    public function defaultStruct() {
        return array(
            'Id' => 0,
            'ContactId' => 0,
            'ParentId' => 0,
            'AffCode' => '',
            'AffName' => '',
            'DefCommissionType' => 2,
            'LeadAmt' => 0.00,
            'LeadCookieFor' => 0,
            'LeadPercent' => 0.0,
            'NotifyLead' => 0,
            'NotifySale' => 0,
            'Password' => '',
            'PayoutType' => 5,
            'SaleAmt' => 0.0,
            'SalePercent' => 0.0,
            'Status' => 1,
        );
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
            'clawbacks' => 'APIAffiliateService.affClawbacks',
            'commissions' => 'APIAffiliateService.affCommissions',
            'payments' => 'APIAffiliateService.affPayouts',
            'stats' => 'APIAffiliateService.affSummary',
        );
        $service = ( (array_key_exists($candidate, $serviceList)) ? $serviceList[$candidate] : false);
        if ($service === false) {
            throw new \Exception('Invalid $src, must be one of [' . implode(', ', array_keys($serviceList)) . ']');
        }
        return $service;
    }
}
