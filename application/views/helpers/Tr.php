<?php

class Contabilidad_Helper_Tr {
    
    function tr(){
        $args = func_get_args();
        if(count($args) == 1){
            return $args[0];
        } else {
            $return = $args[0];
            $vars = array_slice($args, 1);
            for($i = 0; $i < count($vars); $i++)
            {
                $return = preg_replace('/%s/', $vars[$i], $return, 1);
            }
            return $return;
        }
    }
}

