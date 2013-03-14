<?php
class Contabilidad_Services_Category extends Contabilidad_Services_Abstract {
    
    const USER_NOT_FOUND = "wrong authentication";
    const NOT_ALL_PARAMS = "not all params";
    const NOT_FOUND = "CATEGORY NOT FOUND";
    const EMAIL_ALREADY_REGISTERED = "email already registered";

    public function deleteCategory($id){
        $resp = array("result" => "failure", "reason" => self::NOT_ALL_PARAMS);
        if($id){
            $cat = Proxy_Category::getInstance()->findById($id);
            if($cat){
                $cat->delete();
                $resp["result"] = "success";
                $resp["reason"] = "deleted";
            } else {
                $resp["reason"] = self::NOT_FOUND;
            }
        }
        return $resp;
    }
}

