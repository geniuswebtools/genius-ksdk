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
        try {
            $this->checkStruct();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
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
            CURLOPT_TIMEOUT => 1,
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
            $error = curl_error($curl);
        }
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);
        $responsHeader = $this->httpHeader(array_map('trim', (array) explode("\r", trim(substr($response, 0, $headerSize)))));
        $responseBody = $this->httpBody(substr($response, $headerSize), $responsHeader);
        return (object) array(
                    'engine' => 'cURL',
                    'api' => $this->framework,
                    'method' => $method,
                    'error' => ((isset($error)) ? $error : false),
                    'header' => $responsHeader,
                    'content' => $responseBody,
        );
    }

    /**
     * API Access
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
     * REST Hooks
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks
     * 
     * @return @return \GeniusKSDK\REST\RESTHook 
     */
    public function resthook() {
        return $this->model('Resthook', 'rest');
    }

    /**
     * Notes
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note
     */

    /**
     * List Notes
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/listNotesUsingGET_1
     * 
     * Retrieves a list of notes
     * 
     * @param int $contactId
     * @param array $params
     * @return stdClass Object
     */
    public function listNotes(int $contactId, array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->read('/v2/contacts/' . $contactId . '/notes' . $httpQuery);
    }

    /**
     * Create a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/createNoteUsingPOST_1
     * 
     * Creates a new note as the authenticated user. Either a "title" or "body" is required
     * 
     * @param int $contactId
     * @param string $payload
     * @return stdClass Object
     */
    public function createNote(int $contactId, string $payload) {
        return $this->create('https://api.infusionsoft.com/crm/rest/v2/contacts/' . $contactId . '/notes', $payload);
    }

    /**
     * Get a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/getNoteUsingGET_1
     * 
     * Get the specified note
     * 
     * @param int $contactId
     * @param int $noteId
     * @return stdClass Object
     */
    public function getNote(int $contactId, int $noteId) {
        return $this->read('/v2/contacts/' . $contactId, '/notes/' . $noteId);
    }

    /**
     * Update a note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/updateNoteUsingPATCH
     * 
     * Update a note for a contact
     * 
     * @param int $contactId
     * @param int $noteId
     * @param string $payload
     * @return stdClass Object
     */
    public function updateNote(int $contactId, int $noteId, string $payload) {
        return $this->update('/v2/contacts/' . $contactId . '/notes/' . $noteId, $payload);
    }

    /**
     * Delete a Note
     * https://developer.infusionsoft.com/docs/restv2/#tag/Note/operation/deleteNoteUsingDELETE_1
     * 
     * Deletes the specified note
     * 
     * @param int $contactId
     * @param int $noteId
     * @return stdClass Object
     */
    public function deleteNote(int $contactId, int $noteId) {
        return $this->delete('/v2/contacts/' . $contactId . '/notes/' . $noteId);
    }

    /**
     * Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company
     */

    /**
     * List Companies
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/listCompaniesUsingGET_1
     * 
     * Retrieves a list of all Companies
     * 
     * @param string $payload
     * @return stdClass Object
     */
    public function listCompanies(array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->read('/v2/companies' . $httpQuery);
    }

    /**
     * Create a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/createCompanyUsingPOST_1
     * 
     * Creates a new Company.country_code is required if region is specified.
     * 
     * @param string $payload
     * @return stdClass Object
     */
    public function createCompany(string $payload) {
        return $this->create('/v2/companies', $payload);
    }

    /**
     * Retrieve a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/getCompanyUsingGET_1
     * 
     * Retrieves a single Company
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function getCompany(int $id) {
        return $this->read('/v2/companies/' . $id);
    }

    /**
     * Delete a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/deleteCompanyUsingDELETE
     * 
     * Deletes the specified Company
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function deleteCompany(int $id) {
        return $this->delete('' . $id);
    }

    /**
     * Update a Company
     * https://developer.infusionsoft.com/docs/restv2/#tag/Company/operation/patchCompanyUsingPATCH
     * 
     * Updates a Company with the values provided in the request
     * 
     * @param int $id
     * @param string $payload
     * @return stdClass Object
     */
    public function updateCompany(int $id, string $payload) {
        return $this->update('/v2/companies/' . $id, $payload);
    }

    /**
     * List Tagged Companies 
     * https://developer.infusionsoft.com/docs/restv2/#tag/Tags/operation/listCompaniesForTagIdUsingGET_1
     * 
     * Retrieves a list of companies that have the given tag applied To search 
     * for null or empty fields use filter==NONE
     * 
     * @param int $tagId
     * @param array $params
     * @return stdClass Object
     */
    public function listTaggedCompanies(int $tagId, array $params = null) {
        $httpQuery = $this->buildHTTPQuery($params);
        return $this->read('/v2/tags/' . $tagId . '/companies' . $httpQuery);
    }

    /**
     * REST Hooks
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks
     * 
     */
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
    public function update(string $path, array $struct) {
        $payload = json_encode($struct);
        return $this->request($path, array(
                    'method' => 'PATCH',
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
