<?php

class Contabilidad_Utils_String {
    
    public static function cleanString ($string){
        
        $string = trim ($string);
        $string = strip_tags($string);
        $string = preg_replace('/(À|Á|Â|Ã|Ä|Å|à|á|â|ã|ä|å|@)/','a',$string);
        $string = preg_replace('/(È|É|Ê|Ë|è|é|ê|ë)/','e',$string);
        $string = preg_replace('/(Ì|Í|Î|Ï|ì|í|î|ï)/','i',$string);
        $string = preg_replace('/(Ò|Ó|Ô|Õ|Ö|Ø|ò|ó|ô|õ|ö|ø)/','o',$string);
        $string = preg_replace('/(Ù|Ú|Û|Ü|ù|ú|û|ü)/','u',$string);
        $string = preg_replace('/(Ç|ç)/','c',$string);
        $string = preg_replace('/(Ñ|ñ)/','n',$string);
        $string = preg_replace('/(ÿ|Ý)/','y',$string);
        $string = preg_replace('/(\~|\^|\!|\#|\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\<|\>|\?|\`|\,|\.|\/|\\|\|)/','',$string);
        $string = strtolower ($string);
        $string = preg_replace('/\s+/',' ', $string);
        $string = preg_replace("[ ]",".",$string);
        
	    return $string;
	}
      
    public static function createRandomString($length = 8){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
}
?>
