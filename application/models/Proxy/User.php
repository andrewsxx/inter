<?php
class Proxy_User extends Contabilidad_Proxy
{
    
    protected static $_instance = null;

    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self('user', 'VO_User');
        }
        return (self::$_instance);
    }
    
    public function createNew($params){
        $row = $this->createRow();
        $row->email = trim($params['email']);
        $row->password = Contabilidad_Auth::encryptPassword(trim($params['email']), trim($params['password']));
        $row->created_date = time();
        $row->save();
    }
    
    public function editPassword($user, $password){
        $newPass = Contabilidad_Auth::encryptPassword($user->email, $password);
        $user->password = $newPass;
        $user->token = null;
        $user->save();
        return $user;
    }

    public function findById ($id){
        return $this->getTable()->fetchRow("id = '$id'");
    }
    
    public function findByEmail($email){
        return $this->getTable()->fetchRow("email = '$email'");
    }
    
    public function serialize($user){
        $array = array();
        $array["email"] = $user->email;
        $array["id"] = $user->id;
        return $array;
    }
}