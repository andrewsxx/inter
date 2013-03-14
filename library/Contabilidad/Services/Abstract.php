<?php
class Contabilidad_Services_Abstract {

    public function reviewParam($name, $params){
        return isset($params[$name]) && strlen($params[$name]);
    }
}

