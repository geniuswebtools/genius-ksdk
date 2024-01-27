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
}
