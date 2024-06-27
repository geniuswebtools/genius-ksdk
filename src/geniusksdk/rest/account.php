<?php

namespace GeniusKSDK\REST;

/**
 * REST V1 
 * @link https://developer.infusionsoft.com/docs/rest/#tag/Account-Info REST V1 documentation
 * 
 * Bugs Submitted 2024-06-14:
 * Keap Case # 03313290: REST V1 Update Account Profile Bugs
 * 1) The /v1/account/profile endpoint accepts PUTs with almost no information 
 *    - namely { "address": { "field": null } }, and will overwrite all 
 *    non-specified fields as null with the exception of  time_zone, logo_url, 
 *    currency_code, language_tag, and business_goals.
 * 2) The account profile address always returns "OTHER" regardless of input, 
 *    which is at odds with the example in our documentation that shows "BILLING".
 * 3) As you pointed out, the endpoint only accepts ISO-3/ALPHA-3 input, yet 
 *    only provides ISO-2/ALPHA-2 output.
 * 
 * Resolution:
 * Use the v2 Business Profile endpoints:
 * https://developer.keap.com/docs/restv2/#tag/Business-Profile
 * 
 * @alias BusinessProfile
 * 
 */
class Account extends BusinessProfile {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }
}
