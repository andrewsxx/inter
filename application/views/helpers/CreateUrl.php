<?php

class Contabilidad_Helper_CreateUrl {
    
    function createUrl(){
        $args = func_get_args();
        $url = "";
        switch ($args[0]){
            case 'account':
                $url = Proxy_Account::getUrl_($args[1]);
                break;
            case 'transaction':
                $url = Proxy_Transaction::getUrl_($args[1]);
                break;
        }
        return $url;
    }
}
