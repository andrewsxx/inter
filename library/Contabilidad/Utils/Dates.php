<?php

class Contabilidad_Utils_Dates {
    
    public function toDate ($timeStamp){
        return $date = date("d/m/Y",$timeStamp);
    }
}
?>
