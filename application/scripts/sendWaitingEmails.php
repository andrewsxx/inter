<?php

set_include_path(".");

$succesfully = false;
//register shutdown functions
//require_once 'shutdown.inc.php';

require_once realpath(dirname(__FILE__)) . '/../../public/includes.php';


// AutoLoader not started yet
require_once 'Contabilidad/Initializer.php';

// init
new Initializer('production');

$waitings = Proxy_WaitingEmail::getInstance()->fetchAll();
foreach($waitings as $w){
    var_dump($w->id);
    if($w->id_user){
        $user = Proxy_User::getInstance()->findById($w->id_user);
    }
    switch($w->template){
        case "welcome":
            if($user->registered_by == "facebook" || $user->registered_by == "google"){
                Contabilidad_Utils_EmailTemplate::getInstance()->sendWelcomeEmailAndPassword($user, $w->extra);
            } else {
                Contabilidad_Utils_EmailTemplate::getInstance()->sendWelcomeEmail($user);
            }
            break;
        case "recoverPassword":
                Contabilidad_Utils_EmailTemplate::getInstance()->sendRecoverPassword($user);
            break;
        case "feedback":
                Contabilidad_Utils_EmailTemplate::getInstance()->sendFeedback($w->extra);
            break;
    }
    $w->delete();
}
?>
