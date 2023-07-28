<?php

require_once './src/UniClient.php';
require_once './src/UniException.php';
require_once './src/UniResponse.php';
require_once './src/Services/MessageService.php';

use Uni\UniClient;
use Uni\UniException;

// initialization
$client = new UniClient([
  'accessKeyId' => 'your access key id',
  'accessKeySecret' => 'your access key secret'
]);

// send a text message to a single recipient
try {
  $resp = $client->messages->send([
    'to' => 'your phone number', // in E.164 format
    'text' => 'Your verification code is 2048.'
  ]);
  var_dump($resp->data);
} catch (UniException $e) {
  print_r($e);
}
