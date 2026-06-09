<?php

/**
 * @author Marion Dorsett <marion.dorsett@gmail.com>
 * @link https://github.com/geniuswebtools/genius-ksdk
 */
/**
 * MUST register the autoloader first, so it CAN autoload traits.
 */
spl_autoload_register('geniusksdk_autoloader');

/**
 * Genius KSDK Library
 * 
 * IMPORTANT! This library is not associated or maintained by Keap, and is an 
 * independent project. Please do not contact Keap for support.
 * 
 * * This library only supports Keap REST API V2 endpoints.
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

    protected $restURI = 'https://api.infusionsoft.com/crm/rest';

    /**
     * Expects a Personal Access Token or a Service Account Key.
     * https://developer.infusionsoft.com/pat-and-sak/
     * 
     * @var string $apiKey 
     */
    private $apiKey,
            /**
             * As of 2026 the v2 is the only supported API.
             */
            $apiVersion = 'v2',
            /**
             * A place holder for the loaded API class models.  This prevents
             * reinstantiating a model after it's already been called in the 
             * current request.
             */
            $model = array();

    public function __construct(array $struct) {
        $this->init($struct);
    }

    public function apiKey() {
        return $this->apiKey;
    }

    /**
     * REST V2 is the current active branch
     * REST V1 is deprecated
     * 
     * @param string $branch
     * @return $this
     */
    public function apiVersion(string $branch = null) {
        $this->apiVersion = ($branch !== null) ? strotolower($branch) : 'v2';
        return $this;
    }

    /**
     * Generic model that can be used for basic CRUD operations.
     * REST V2: https://developer.keap.com/docs/restv2/
     * 
     * @return \GeniusKSDK\REST\[V2]\API 
     */
    public function api(string $version = null) {
        $apiVersion = (($version !== null) ? strotolower($version) : $this->apiVersion());
        return $this->model('API', $apiVersion);
    }

    /**
     * Contact API
     */
    public function contact($apiVersion = 'v2') {
        return $this->model('Contact', $apiVersion);
    }

//    /**
//     * Affiliate
//     */
//    public function affiliate($apiVersion = 'v2') {
//        return $this->model('Affiliate', $apiVersion);
//    }
//
//    /**
//     * Affiliate Program
//     */
//    public function affiliateProgram($api = 'rest') {
//        return $this->model('AffiliateProgram', $api);
//    }
//
//    /**
//     * Appointment
//     */
//    public function appointment() {
//        return $this->model('Appointment', 'rest');
//    }
//
//    /**
//     * Automation
//     */
//    public function automation() {
//        return $this->model('Automation', 'rest');
//    }
//
//    /**
//     * Automation Category
//     */
//    public function automationCategory() {
//        return $this->model('AutomationCategory', 'rest');
//    }
//
//    /**
//     * BusinessProfile
//     */
//    public function businessprofile() {
//        return $this->model('BusinessProfile', 'rest');
//    }
//
//    /**
//     * Campaign
//     */
//    public function campaign() {
//        return $this->model('Campaign', 'rest');
//    }
//
//    /**
//     * Company
//     */
//    public function company() {
//        return $this->model('Company', 'rest');
//    }
//
//    /**
//     * Data
//     */
//    public function data() {
//        return $this->model('Data', 'xml');
//    }
//
//    /**
//     * Notes
//     */
//    public function note() {
//        return $this->model('Note');
//    }
//
//    public function order($api = 'rest') {
//        return $this->model('Order', $api);
//    }
//
//    /**
//     * Product
//     */
//    public function product($api = 'rest') {
//        return $this->model('Product', $api);
//    }
//
//    /**
//     * Tag
//     */
//    public function tag($api = 'rest') {
//        return $this->model('Tag', $api);
//    }
//
//    /**
//     * Tag Category
//     */
//    public function tagcategory($api = 'rest') {
//        return $this->model('TagCategory', $api);
//    }
//
//    /**
//     * REST Hooks
//     */
//    public function resthook() {
//        return $this->model('Resthook', 'rest');
//    }
////

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
        try {
            return $this->request($path);
        } catch (\Exception $ex) {
            return $ex;
        }
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
    public function delete(string $path, array $struct = null) {
        $header = (($struct !== null) ? array('Content-Type: application/json') : null);
        $payload = (($struct !== null) ? json_encode($struct) : null);
        return $this->request($path, array(
                    'method' => 'DELETE',
                    'header' => $header,
                    'content' => $payload,
        ));
    }

    public function endpoint(string $uri = '') {
        $endpoint = $this->restURI . $uri;
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
        $header = array_merge((array) $options['header'], array('Authorization: Bearer ' . $this->apiKey));
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
        $responseHeader = $this->httpHeader(array_map('trim', (array) explode("\r", trim(substr($response, 0, $headerSize)))));
        $keapStats = $this->keapStats($responseHeader);
        $responseBody = $this->httpBody(substr($response, $headerSize), $responseHeader);
        return (object) array(
                    'engine' => 'cURL',
                    'api' => 'rest\\' . $this->apiVersion,
                    'method' => $method,
                    'code' => (int) $responseHeader['code'],
                    'keap' => $keapStats,
                    'header' => $responseHeader,
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

    private function keapStats(array $struct) {
        $stats = array();
        foreach ((array) $struct as $key => $value) {
            if (!preg_match('/^x-keap-/', $key)) {
                continue;
            }
            $stat = str_replace('x-keap-', '', $key);
            $stats[$stat] = $value;
        }

        return $stats;
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
            'apiVersion' => 'v2',
        );
    }

    private function defaultOptions() {
        return array(
            'method' => 'GET',
            'header' => null,
            'content' => null
        );
    }

    private function model(string $name, $version = null) {
        $apiVersion = (($version !== null) ? strtolower($version) : $this->apiVersion());
        $className = '\GeniusKSDK\REST\\' . $apiVersion . '\\' . $name;
        $model = strtolower($className);
        $isModel = $this->model[$apiVersion][$model] ?? false;
        if (!$isModel) {
            if (!isset($this->model[$apiVersion])) {
                $this->model[$apiVersion] = array();
            }
            try {
                $this->model[$apiVersion][$model] = new $className($this);
            } catch (\Exception $ex) {
                throw new \Exception(__CLASS__ . ' cannot find the requested model ' . $className);
            }
        }
        return $this->model[$apiVersion][$model];
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
