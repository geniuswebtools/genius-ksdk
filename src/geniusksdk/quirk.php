<?php

namespace GeniusKSDK;

trait Quirk {

    public function restruct(array $default, array $struct = null) {
        $reStruct = array_merge($default, (array) $struct);
        return (array) array_intersect_key($reStruct, $default);
    }

    public function buildHTTPQuery(array $params = null) {
        return (($params !== null) ? '?' . http_build_query((array) $params, '', '&', PHP_QUERY_RFC3986) : '');
    }

    /**
     * Load the request headers from the remote host.
     * @return array
     */
    protected function requestHeaders() {
        return ((function_exists('apache_request_headers')) ? apache_request_headers() : $this->buildRequestHeadersFromServer());
    }

    /**
     * https://www.php.net/manual/en/function.apache-request-headers.php#70810
     * 
     * If the apache_request_headers() function doesn't exist, use this function
     * which mimics apache_request_headers() instead.
     * 
     * @author limalopex.eisfux.de
     */
    protected function buildRequestHeadersFromServer() {
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

    protected function requestBody() {
        return file_get_contents('php://input');
    }
}
