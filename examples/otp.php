<?php

require_once './src/UniClient.php';
require_once './src/UniException.php';
require_once './src/UniResponse.php';
require_once './src/Services/OtpService.php';

use Uni\UniClient;
use Uni\UniException;

// initialization
$client = new UniClient([
  'accessKeyId' => 'your access key id',
  'accessKeySecret' => 'your access key secret'
]);

// send a verification code to a recipient
try {
  $resp = $client->otp->send([
    'to' => 'your phone number' // in E.164 format
  ]);
  var_dump($resp->data);
} catch (UniException $e) {
  print_r($e);
}

// verify a verification code
try {
  $resp = $client->otp->verify([
    'to' => 'your phone number', // in E.164 format
    'code' => 'the code you received'
  ]);
  var_dump($resp->valid);
} catch (UniException $e) {
  print_r($e);
}
