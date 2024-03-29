<?php

namespace Uni\Services;

use Uni\UniClient;

class MessageService {
  private $client;

  function __construct(UniClient $client) {
    $this->client = $client;
  }

  function send($params) {
    return $this->client->request('sms.message.send', $params);
  }
}
