<?php

class Contabilidad_Helper_CurrencyValue {
    
    function currencyValue(){
        $args = func_get_args();
        $value = $args[0];
        switch ($args[1]){
            case '1':
                $value = "$ " . $value;
                break;
            default :
                $value = "USD " . $value;
                
        }
        return $value;
    }
}
