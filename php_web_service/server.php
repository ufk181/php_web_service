<?php
error_reporting(0);
require_once 'lib/nusoap.php';
require_once 'Class_DB.php';

$server =  new nusoap_server();
$server->configureWSDL('Android_Login_Web_Service','http://localhost/login_web_service/server.php');
$server->register('Android_DB.storeUser',
    array('k_adi' => 'xsd:string',
          'k_sifre' => 'xsd:integer',
          'k_email' => 'xsd:string'
      ),
      array('return' => 'xsd:string'),
      'http://localhost/login_web_service/server.php',
      'http://localhost/login_web_service/server.php#storeUser',
      'rpc',
      'encoded',
      'Kullanıcı  Servisi'
);
$server->register('Android_DB.testJsontoWebService',
    array('name' => 'xsd:string',
              ),
      array('return' => 'xsd:string'));

@$server->service($HTTP_RAW_POST_DATA);
?>
