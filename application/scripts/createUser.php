<?php

set_include_path(".");

require_once realpath(dirname(__FILE__)) . '/../../public/includes.php';

// AutoLoader not started yet
require_once 'Contabilidad/Initializer.php';

// init
new Initializer('production');
$params = array("email" => "ayn_nian@hotmail.com", "password" => 123456);
$user = Proxy_User::getInstance()->createNew($params);

?>
