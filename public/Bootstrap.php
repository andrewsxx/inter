<?php

set_include_path(".");
require_once 'includes.php';

// AutoLoader not started yet
require_once dirname(__FILE__) . '/../library/Contabilidad/Initializer.php';

// Prepare the front controller. 
$frontController = Zend_Controller_Front::getInstance();

// Change to 'production' parameter under production environment
$frontController->registerPlugin(new Initializer('production'));

// Dispatch the request using the front controller. 
$frontController->dispatch();
