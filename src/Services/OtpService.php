<?php

namespace Uni\Services;

use Uni\UniClient;

class OtpService {
  private $client;

  function __construct(UniClient $client) {
    $this->client = $client;
  }

  function send($params) {
    return $this->client->request('otp.send', $params);
  }

  function verify($params) {
    $resp = $this->client->request('otp.verify', $params);
    $resp->valid = $resp->data !== null && $resp->data->valid;
    return $resp;
  }
}
