<?php

namespace GeniusKSDK\REST;

/**
 * REST V1 
 * @link https://developer.infusionsoft.com/docs/rest/#tag/Account-Info REST V1 documentation
 * 
 * Bugs Submitted 2024-06-14:
 * Keap Case # 03313290: REST V1 Update Account Profile Bugs
 * 
 */
class Account extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    public function create() {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, __METHOD__));
    }

    /**
     * Retrieve account profile
     * https://developer.infusionsoft.com/docs/rest/#tag/Account-Info/operation/getAccountProfileUsingGET
     * 
     * Retrieves profile/company info for an account.
     * 
     * Bugs Submitted 2024-06-14:
     * Keap Case # 03313290: REST V1 Update Account Profile Bugs
     * 
     * - The returned address.field will ALWAYS be OTHER.
     * - The address.country_code returned as ALPHA-2 
     * 
     * @return stdClass Object 
     */
    public function read() {
        return $this->client->read('/v1/account/profile');
    }

    /**
     * Updates an account profile
     * https://api.infusionsoft.com/crm/rest/v1/account/profile
     * 
     * Updates profile/company info for an account.
     * 
     * Bugs Submitted 2024-06-14:
     * Keap Case # 03313290: REST V1 Update Account Profile Bugs
     * 1. ALWAYS & ONLY use the 'OTHER' address or it will be NULLed on update.
     * 2. Use the ALPHA-3 country_code when setting the address country. ALPHA-2 
     *    will be returned.
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function update(array $struct) {
        return $this->client->update('/v1/account/profile', $struct, 'PUT');
    }

    public function delete(int $id) {
        throw new \Exception(sprintf(self::EX_UNSUPPORTED, __METHOD__));
    }
}
