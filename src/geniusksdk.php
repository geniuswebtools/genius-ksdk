<?php

/**
 * MUST register the autoloader first, so it CAN autoload Traits.
 */
spl_autoload_register('geniusksdk_autoloader');

/**
 * Genius KSDK Library
 * 
 * IMPORTANT! This library is not associated or maintained by Keap, and is an 
 * independent project. Please do not contact Keap for support.
 * 
 * * This library currently supports the Keap REST API V1 and V2 endpoints.
 * * This library DOES NOT use the OAuth2 authentication method.
 * * This library makes requests to the Keap REST API by using a Personal Access 
 *   Token or a Service Account Key.  
 * 
 * See the README file for more information.
 * 
 * @author  Marion Dorsett <marion.dorsett@gmail.com>
 * @copyright (c) 2024 Marion Dorsett
 * @license MIT
 * @version 1.2
 * 
 * Keap Developer Guide
 * https://developer.infusionsoft.com/developer-guide/
 * 
 */
class GeniusKSDK {

    use \GeniusKSDK\Quirk;

    public $restURI = 'https://api.infusionsoft.com/crm/rest',
            /**
             * @deprecated Use the REST API if a comparable request exists
             */
            $xmlURL = 'https://api.infusionsoft.com/crm/xmlrpc/v1';

    /**
     * Expects a Personal Access Token or a Service Account Key.
     * https://developer.infusionsoft.com/pat-and-sak/
     * 
     * @var string $apiKey 
     */
    private $model,
            $apiKey,
            $framework = 'rest';

    public function __construct(array $struct) {
        $this->init($struct);
    }

    public function framework(string $type = null) {
        if ($type !== null) {
            $this->framework = (($type !== 'rest') ? 'xml' : 'rest');
        }
        return $this;
    }

    public function apiKey() {
        return $this->apiKey;
    }

    /**
     * Generic API Access
     * XML-RPC: https://developer.keap.com/docs/xml-rpc/
     * REST V1: https://developer.keap.com/docs/rest/
     * REST V2: https://developer.keap.com/docs/restv2/
     * 
     * @return \GeniusKSDK\[REST|XML]\API 
     */
    public function api($api = 'rest') {
        return $this->model('API', $api);
    }

    /**
     * Account
     */
    public function account() {
        return $this->model('Account', 'rest');
    }

    /**
     * Affiliate
     */
    public function affiliate($api = 'rest') {
        return $this->model('Affiliate', $api);
    }

    /**
     * Affiliate Program
     */
    public function affiliateProgram($api = 'rest') {
        return $this->model('AffiliateProgram', $api);
    }

    /**
     * BusinessProfile
     */
    public function businessprofile() {
        return $this->model('BusinessProfile', 'rest');
    }

    /**
     * Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company
     * 
     * @return \GeniusKSDK\REST\Company
     */
    public function company() {
        return $this->model('Company', 'rest');
    }

    /**
     * Contact
     * https://developer.infusionsoft.com/docs/rest/#tag/Contact
     * https://developer.infusionsoft.com/docs/xml-rpc/#contact
     * 
     * @return \GeniusKSDK\[REST|XML]\Contact 
     */
    public function contact($api = 'rest') {
        return $this->model('Contact', $api);
    }

    /**
     * Data
     * @return \GeniusKSDK\XML\Data
     */
    public function data() {
        return $this->model('Data', 'xml');
    }

    /**
     * Notes
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note
     * 
     * @return \GeniusKSDK\REST\Note
     */
    public function note() {
        return $this->model('Note');
    }

    public function order($api = 'rest') {
        return $this->model('Order', $api);
    }

    /**
     * Product
     * https://developer.infusionsoft.com/docs/rest/#tag/Product
     * https://developer.keap.com/docs/xml-rpc/#product
     * 
     * @return \GeniusKSDK\[REST|XML]\Product
     */
    public function product($api = 'rest') {
        return $this->model('Product', $api);
    }

    /**
     * Tag
     * https://developer.keap.com/docs/restv2/#tag/Tags
     * 
     * @return \GeniusKSDK\[REST|XML]\Tag 
     */
    public function tag($api = 'rest') {
        return $this->model('Tag', $api);
    }

    /**
     * Tag Category
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags
     * 
     * @return \GeniusKSDK\[REST|XML]\TagCategory
     */
    public function tagcategory($api = 'rest') {
        return $this->model('TagCategory', $api);
    }

    /**
     * Shipping
     * https://developer.keap.com/docs/xml-rpc/#shipping
     * 
     * @return \GeniusKSDK\XML\Shipping
     */
    public function shipping() {
        return $this->model('Shipping', 'xml');
    }

    /**
     * Webform
     * https://developer.keap.com/docs/xml-rpc/#webform
     * 
     * @return \GeniusKSDK\XML\Webform
     */
    public function webform() {
        return $this->model('Webform', 'xml');
    }

    /**
     * REST Hooks
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks
     * 
     * @return @return \GeniusKSDK\REST\RESTHook 
     */
    public function resthook() {
        return $this->model('Resthook', 'rest');
    }

    /**
     * Generic REST CRUD Methods
     * 
     * These methods can be used to make CRUD requests to the Keap REST API when 
     * helper method isn't provided in this library, or when new endpoints are 
     * added to the API.
     * 
     * @method creaete()
     * @method read()
     * @method update()
     * @method delete()
     */

    /**
     * Create an object
     * 
     * @param string $path
     * @param array $struct
     * @return stdClass Object
     */
    public function create(string $path, array $struct) {
        $payload = json_encode($struct);
        return $this->request($path, array(
                    'method' => 'POST',
                    'header' => array('Content-Type: application/json'),
                    'content' => $payload,
        ));
    }

    /**
     * Read an object or Retrieve a list of objects
     * 
     * @param string $path
     * @return stdClass Object
     */
    public function read(string $path) {
        return $this->request($path);
    }

    /**
     * Update an object
     * 
     * @param string $path
     * @param array $struct
     * @return stdClass Object
     */
    public function update(string $path, array $struct, $method = 'PATCH') {
        $payload = json_encode($struct);
        $useMethod = strtoupper($method);
        return $this->request($path, array(
                    'method' => (($useMethod !== 'PATCH') ? $useMethod : 'PATCH'),
                    'header' => array('Content-Type: application/json'),
                    'content' => $payload,
        ));
    }

    /**
     * Delete an object
     * 
     * @param string $path
     * @return stdClass Object
     */
    public function delete(string $path) {
        return $this->request($path, array(
                    'method' => 'DELETE',
        ));
    }

    public function endpoint(string $uri = '') {
        $endpoint = (($this->framework !== 'rest') ? $this->xmlURL : $this->restURI . $uri);
        return ((preg_match('/^https/', $uri)) ? $uri : str_replace('//', '/', $endpoint));
    }

    /**
     * @param string $endpoint
     * @param array $struct
     * @return stdClass Object
     */
    public function request(string $endpoint, array $struct = null) {
        $options = $this->restruct($this->defaultOptions(), $struct);
        $method = $options['method'];
        $header = array_merge((array) $options['header'], array('X-Keap-API-Key: ' . $this->apiKey));
        $content = $options['content'];
        $curlOpts = array(
            CURLOPT_URL => $this->endpoint($endpoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAPATH => __DIR__ . '/cainfusionsoft.pem',
        );
        if (!empty($content)) {
            $curlOpts[CURLOPT_POSTFIELDS] = $content;
        }
        $curl = curl_init();
        curl_setopt_array($curl, $curlOpts);
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            throw new \Exception('cURL Error: ' . curl_error($curl));
        }
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);
        $responsHeader = $this->httpHeader(array_map('trim', (array) explode("\r", trim(substr($response, 0, $headerSize)))));
        $responseBody = $this->httpBody(substr($response, $headerSize), $responsHeader);
        switch ($this->framework) {
            case 'xml':
                if (isset($responseBody['faultString'])) {
                    throw new \Exception('XML-RPC Error(' . $responseBody['faultCode'] . '): ' . $responseBody['faultString']);
                }
                break;
            case 'rest':
                if (isset($responseBody->code)) {
                    throw new \Exception('REST Error(' . $responseBody->code . ' ' . $responseBody->status . '): ' . $responseBody->message);
                }
                break;
            default:
                break;
        }
        return (object) array(
                    'engine' => 'cURL',
                    'api' => $this->framework,
                    'method' => $method,
                    'header' => $responsHeader,
                    'content' => $responseBody,
        );
    }

    private function httpHeader(array $struct) {
        list($protocol, $code) = explode(' ', $struct[0], 2);
        $header = array(
            'protocol' => $protocol,
            'code' => $code,
            'content-type' => '',
        );
        foreach ((array) array_slice((array) $struct, 1) as $keyValuePair) {
            list($key, $value) = array_map('trim', explode(':', $keyValuePair, 2));
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
        if (preg_match('/text\/xml/', $contentType)) {
            return xmlrpc_decode($content);
        }

        return $content;
    }

    private function init(array $struct) {
        $params = $this->restruct($this->defaultStruct(), $struct);
        foreach ((array) $params as $key => $value) {
            $this->{$key} = $value;
        }

        $this->model = array('rest' => array(), 'xml' => array());

        $this->checkStruct();
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
            'api' => 'rest',
        );
    }

    private function defaultOptions() {
        return array(
            'method' => 'GET',
            'header' => null,
            'content' => null
        );
    }

    private function model(string $name, $framework = 'rest') {
        $model = strtolower($name);
        $this->framework($framework);
        if (!isset($this->model[$this->framework][$model])) {
            $className = '\GeniusKSDK\\' . $this->framework . '\\' . $name;
            $this->model[$this->framework][$model] = new $className($this);
        }
        return $this->model[$this->framework][$model];
    }
}

/**
 * Lazy load only the classes required for the request.
 * 
 * @param string $class
 * @return null
 */
function geniusksdk_autoloader($class) {
    $calledClass = strtolower($class);
    if (is_file($file = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $calledClass) . '.php')) {
        include_once $file;
    }
}
