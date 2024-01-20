<?php

/**
 * 
 * Keap Developer Guide
 * https://developer.infusionsoft.com/developer-guide/
 */
class GeniusKSDK {

    /**
     * @var string $apiKey Expects a Personal Access Token or a Service Account Key.
     * https://developer.infusionsoft.com/pat-and-sak/
     */
    private $apiKey,
            /**
             * @var string $endpointPrefix
             */
            $endpointPrefix = 'https://api.infusionsoft.com/crm/rest';

    public function __construct(array $struct) {
        $this->init($struct);
        try {
            $this->checkStruct();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Contact
     * https://developer.infusionsoft.com/docs/rest/#tag/Contact
     * 
     */

    /**
     * List Contacts
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/listContactsUsingGET_1
     * 
     * Retrieves a list of contacts
     * https://integration.keap.com/t/rest-v2/85756/3
     * 
     * @param string $params
     * @return stdClass Object
     */
    public function findContacts(array $params = null) {
        $httpQuery = (($params !== null) ? '?' . http_build_query((array) $params, '', '&', PHP_QUERY_RFC3986) : '');
        return $this->request('/v2/contacts' . $httpQuery);
    }

    /**
     * Retrieve Contact Model 
     * https://developer.infusionsoft.com/docs/restv2/#tag/Contact/operation/retrieveContactModelUsingGET_1
     * 
     * Get the custom fields and optional properties for the Contact object
     * 
     * @return stdClass Object
     */
    public function retrieveContactModel() {
        return $this->request('/v2/contacts/model');
    }

    /**
     * Retrieve a Contact
     * https://developer.keap.com/docs/restv2/#tag/Contact/operation/getContactUsingGET_1
     * 
     * $fields = Comma-delimited list of Contact properties to include in the response. 
     * Available fields are: 
     *  score_value, addresses, anniversary, birthday, company, contact_type, 
     *  custom_fields, create_time, email_addresses, fax_numbers, job_title, 
     *  update_date, leadsource_id, middle_name, origin, owner_id, phone_numbers, 
     *  preferred_locale, preferred_name,prefix, relationships, social_accounts, 
     *  source_type, spouse_name, suffix, time_zone,website, tag_ids, utm_parameters
     * 
     * @param int $id
     * @param string $fields
     * @return stdClass Object 
     */
    public function getContact(int $id, string $fields = '') {
        $endpoint = '/v2/contacts/' . $id;
        if (!empty($fields)) {
            $endpoint .= '?fields=' . $fields;
        }
        return $this->request($endpoint);
    }

    /**
     * Update a Contact
     * https://developer.keap.com/docs/restv2/#tag/Contact/operation/patchContactUsingPATCH
     * 
     * @param int $id
     * @param string $payload
     * @return stdClass Object
     */
    public function updateContact(int $id, string $payload) {
        return $this->request('/v2/contacts/' . $id, array(
                    'method' => 'PATCH',
                    'header' => 'Content-Type: application/json',
                    'content' => $payload,
        ));
    }

    /**
     * REST Hooks
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks
     * 
     */

    /**
     * List Hook Event Types
     * List the available types of Events that can be listened to
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/list_hook_event_types
     * 
     * @return stdClass Object
     */
    public function resthookEvents() {
        return $this->request('/v1/hooks/event_keys');
    }

    /**
     * List Stored Hook Subscriptions
     * Lists your hook subscriptions
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/list_stored_hook_subscriptions
     * 
     * @return stdClass Object
     */
    public function resthooks() {
        return $this->request('/v1/hooks');
    }

    /**
     * Create a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/create_a_hook_subscription
     * 
     * Payload: 
     * {
     *   "eventKey": "string",
     *   "hookUrl": "string"
     * }
     * 
     * @param string $payload JSON
     * @param bool $verify Automatically verify the resthook subscription.
     * @return stdClass Object
     */
    public function createRestHook(string $payload, bool $verify = true) {
        if ($verify === true) {
            $this->verifyRestHook();
        }
        return $this->request('/v1/hooks', array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => $payload,
        ));
    }

    /**
     * Retrieve a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/retrieve_a_hook_subscription
     * 
     * Retrieves an existing hook subscription and its status.
     * 
     * If your hook subscription becomes inactive, you may request an activation 
     * attempt via Verify a Hook Subscription.
     * 
     * @param int $key
     * @return stdClass Object
     */
    public function getRestHook(int $key) {
        return $this->request('/v1/hooks/' . $key);
    }

    /**
     * Delete a Hook Subscription
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks/operation/delete_a_hook_subscription
     * 
     * Stop receiving hooks by deleting an existing hook subscription.
     * 
     * @param int $key
     * @return stdClass Object
     */
    public function deleteRestHook(int $key) {
        return $this->request('/v1/hooks/' . $key, array(
                    'method' => 'DELETE',
        ));
    }

    /**
     * Verify a Hook Subscription
     * 
     * @return mixed null|string
     */
    public function verifyRestHook() {
        if (getenv('REQUEST_METHOD') !== 'POST') {
            return;
        }
        $header = $this->requestHeaders();
        $XHookSecret = ((isset($header['X-Hook-Secret'])) ? $header['X-Hook-Secret'] : null);
        if ($XHookSecret === null) {
            return;
        }
        header('X-Hook-Secret: ' . $XHookSecret);
        echo $this->requestBody();
        exit;
    }

    /**
     * @param string $endpoint
     * @param array $struct
     * @return stdClass Object
     */
    protected function request($endpoint, array $struct = null) {
        $options = $this->restruct($this->defaultOptions(), $struct);
        $method = $options['method'];
        $header = array_merge((array) $options['header'], array('X-Keap-API-Key: ' . $this->apiKey));
        $content = $options['content'];
        $curlOpts = array(
            CURLOPT_URL => $this->endpoint($endpoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_ENCODING => '',
        );
        if (!empty($content)) {
            $curlOpts[CURLOPT_POSTFIELDS] = $content;
        }

        $curl = curl_init();
        curl_setopt_array($curl, $curlOpts);
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            $error = curl_error($curl);
        }
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);
        $responsHeader = $this->httpHeader(array_map('trim', (array) explode("\r", trim(substr($response, 0, $headerSize)))));
        $responseBody = $this->httpBody(substr($response, $headerSize), $responsHeader);
        return (object) array(
                    'engine' => 'cURL',
                    'method' => $method,
                    'error' => ((isset($error)) ? $error : false),
                    'header' => $responsHeader,
                    'content' => $responseBody,
        );
    }

    protected function endpoint($uri) {
        return ((preg_match('/^https/', $uri)) ? $uri : str_replace('//', '/', $this->endpointPrefix . $uri));
    }

    protected function restruct(array $default, array $struct = null) {
        $reStruct = array_merge($default, (array) $struct);
        return (array) array_intersect_key($reStruct, $default);
    }

    private function httpHeader(array $struct) {
        list($protocol, $code) = explode(' ', $struct[0], 2);
        $header = array(
            'protocol' => $protocol,
            'code' => $code,
            'content-type' => '',
        );
        foreach ((array) array_slice((array) $struct, 1) as $keyValuePair) {
            list($key, $value) = explode(':', $keyValuePair, 2);
            $header[strtolower($key)] = $value;
        }
        return $header;
    }

    private function httpBody(string $content, array $header) {
        $contentType = ((isset($header['content-type'])) ? $header['content-type'] : '');
        if (preg_match('/application\/json/', $contentType)) {
            $json = json_decode($content);
            if ($json !== null) {
                return $json;
            }
        }

        return $content;
    }

    private function init(array $struct) {
        $params = $this->restruct($this->defaultStruct(), $struct);
        foreach ((array) $params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    private function checkStruct() {
        if (!empty($this->apiKey)) {
            return true;
        }

        throw new \Exception('Keap API Key cannot be empty!');
    }

    private function defaultStruct() {
        return array(
            'apiKey' => '',
        );
    }

    private function defaultOptions() {
        return array(
            'method' => 'GET',
            'header' => null,
            'content' => null
        );
    }

    /**
     * Load the request headers from the remote host.
     * @return array
     */
    private function requestHeaders() {
        return ((function_exists('apache_request_headers')) ? apache_request_headers() : $this->buildRequestHeadersFromServer());
    }

    /**
     * https://www.php.net/manual/en/function.apache-request-headers.php#70810
     * 
     * If the apache_request_headers() function doesn't exist, use this function
     * which mimics apache_request_headers() instead. - limalopex.eisfux.de
     */
    private function buildRequestHeadersFromServer() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val)
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        $arh['Function'] = true;
        return( $arh );
    }

    private function requestBody() {
        return file_get_contents('php://input');
    }
}
