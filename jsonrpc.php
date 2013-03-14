<?php

set_include_path(".");
require_once 'public/includes.php';

// AutoLoader not started yet
require_once 'Contabilidad/Initializer.php';

new Initializer('production');

// Instantiate server, etc.
$server = new Zend_Json_Server();
$server->setClass('Contabilidad_Services_Session');
$server->setClass('Contabilidad_Services_Category');

if ('GET' == $_SERVER['REQUEST_METHOD']) {
    // Indicate the URL endpoint, and the JSON-RPC version used:
    $server->setTarget('/api/1.0/jsonrpc.php')
           ->setEnvelope(Zend_Json_Server_Smd::ENV_JSONRPC_2);

    // Grab the SMD
    $smd = $server->getServiceMap();

    // Return the SMD to the client
    header('Content-Type: application/json');
    echo $smd;
    return;
}

$server->handle();