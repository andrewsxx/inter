<?php
class Contabilidad_Services_Session extends Contabilidad_Services_Abstract {
    
    const USER_NOT_FOUND = "wrong authentication";
    const NOT_ALL_PARAMS = "not all params";
    const EMAIL_ALREADY_REGISTERED = "email already registered";

    public function login($params){
        $resp = array("result" => "failure", "reason" => self::NOT_ALL_PARAMS);
        if($this->reviewParam('email', $params) && $this->reviewParam('password', $params)){
            $params['email'] = trim($params['email']);
            $params['password'] = trim($params['password']);
            $params['password'] = Contabilidad_Auth::encryptPassword($params['email'], $params['password']);
            if(Contabilidad_Auth::getInstance()->login($params)){
                $resp["result"] = "success";
                $resp["reason"] = "OK";
            } else {
                $resp["result"] = "failure";
                $resp["reason"] = self::USER_NOT_FOUND;
            }
        }
        return $resp;
    }
    
//    public function register($params){
//        $puser = Proxy_User::getInstance();
//        $resp = array("result" => "failure", "reason" => self::NOT_ALL_PARAMS);
//        if($this->reviewParam('full_name', $params) && $this->reviewParam('email', $params) 
//           && $this->reviewParam('password', $params) && $this->reviewParam('confirm_password', $params)){
//            $params['full_name'] = trim($params['full_name']);
//            $params['email'] = trim($params['email']);
//            $params['password'] = trim($params['password']);
//            $user = $puser->findByEmail($params['email']);
//            if($user){
//                $resp["result"] = "failure";
//                $resp["reason"] = self::EMAIL_ALREADY_REGISTERED;
//            } else {
//                $user = $puser->createNew($params);
//                $params['password'] = Contabilidad_Auth::encryptPassword($params['email'], $params['password']);
//                Contabilidad_Auth::getInstance()->login($params);
//                $resp["result"] = "success";
//                $resp["reason"] = "OK";
//            }
//        }
//        return $resp;
//    }
    
    public function recoverPassword($params){
        $resp = array("result" => "failure", "reason" => self::NOT_ALL_PARAMS);
        if($this->reviewParam('email', $params)){
            $params['email'] = trim($params['email']);
            $user = Proxy_User::getInstance()->findByEmail($params['email']);
            if($user){
                $user->token = Contabilidad_Utils_String::createRandomString(20);
                $user->save();
                $ar = array("userId" => $user->id, "template" => "recoverPassword");
                Proxy_WaitingEmail::getInstance()->createNew($ar);
                $resp["result"] = "success";
                $resp["reason"] = "OK";
            } else {
                $resp["result"] = "failure";
                $resp["reason"] = self::USER_NOT_FOUND;
            }
        }
        return $resp;
    }
}

