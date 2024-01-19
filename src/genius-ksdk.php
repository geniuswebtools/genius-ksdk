<?php

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
     * REST Hooks
     * https://developer.infusionsoft.com/docs/rest/#tag/REST-Hooks
     * 
     */
    public function resthookEvents() {
        return $this->request('/v1/hooks/event_keys');
    }

    public function resthooks() {
        return $this->request('/v1/hooks');
    }

    public function createRestHook() {
        
    }

    /**
     * @param string $endpoint
     * @param array $struct
     * @return object
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
}
