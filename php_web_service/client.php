<?php
error_reporting(0);
require_once 'lib/nusoap.php';


$client = new nusoap_client('http://localhost/ws/server.php?wsdl',false);
//$error = $client->getError();
/*
$client->soap_defencoding = 'utf-8';
$client->encode_utf8 = false;
$client->decode_utf8 = false;
*/
$request = $client->call('Android_DB.storeUser',
 array('k_adi' => 'Celal',
       'k_sifre' => '8836412',
       'k_email' => 'den124124e2me@i2nterval.com'
   )
);

if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($request);
    echo "</pre>";
} else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo "<h2>Main</h2>";
       $decode = json_decode($request);
       print $decode->error;
    }
}

echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";






 ?>
