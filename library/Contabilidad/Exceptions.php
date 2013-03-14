<?php
class Contabilidad_Exceptions {
    
    public static function showException (){
try {
        throw new Exception(' Upps !!');
    } catch (ErrorException $e) {
        // este bloque no se ejecuta, no coincide el tipo de excepción
        echo 'ErrorException' . $e->getMessage();
    } catch (Exception $e) {
        // este bloque captura la excepción
        echo 'Exception' . $e->getMessage();
    }    }
}
?>
