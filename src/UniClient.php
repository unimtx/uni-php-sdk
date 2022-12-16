<?php

namespace Uni;

use Uni\UniResponse;
use Uni\UniException;
use Uni\Services\MessageService;

class UniClient {
  const NAME = 'uni-php-sdk';
  const VERSION = '0.2.0';
  const DEFAULT_ENDPOINT = 'https://api.unimtx.com';
  const DEFAULT_SIGNING_ALGORITHM = 'hmac-sha256';
  const USER_AGENT = self::NAME . '/' . self::VERSION;
  const TIMEOUT = 60;

  public $endpoint;
  public $accessKeyId;
  public $signingAlgorithm;
  public $hmacAlgorithm;

  protected $_message;

  private $accessKeySecret;
  private $sslVerify;

  function __construct($config) {
    $this->endpoint = $config['endpoint'] ?? self::DEFAULT_ENDPOINT;
    $this->accessKeyId = $config['accessKeyId'];
    $this->accessKeySecret = $config['accessKeySecret'] ?? null;
    $this->signingAlgorithm = $config['signingAlgorithm'] ?? self::DEFAULT_SIGNING_ALGORITHM;
    $this->hmacAlgorithm = explode('-', $this->signingAlgorithm)[1];
    $this->sslVerify = $config['sslVerify'] ?? true;
  }

  public function __get($name) {
    $method = 'get' . ucfirst($name);
    if (method_exists($this, $method)) {
        return $this->$method();
    }
    throw new UniException('Unknown service ' . $name, -1);
  }

  private function sign($query) {
    if (isset($this->accessKeySecret)) {
      $query['algorithm'] = $this->signingAlgorithm;
      $query['timestamp'] = time();
      $query['nonce'] = bin2hex(random_bytes(12));

      ksort($query);
      $strToSign = http_build_query($query);

      $query['signature'] = hash_hmac($this->hmacAlgorithm, $strToSign, $this->accessKeySecret);
    }

    return $query;
  }

  function request($action, $data) {
    $query = [
      'action' => $action,
      'accessKeyId' => $this->accessKeyId
    ];
    $query = $this->sign($query);
    $body_str = json_encode($data);
    $options = [
      CURLOPT_URL => $this->endpoint . '/?' . http_build_query($query),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => true,
      CURLOPT_HTTPHEADER => [
        'User-Agent: '. self::USER_AGENT,
        'Content-Type: '. 'application/json;charset=utf-8',
        'Accept: '. 'application/json'
      ],
      CURLOPT_TIMEOUT => self::TIMEOUT,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $body_str
    ];

    if (!$this->sslVerify) {
      $options[CURLOPT_SSL_VERIFYPEER] = false;
      $options[CURLOPT_SSL_VERIFYHOST] = false;
    }

    try {
      if (!$curl = curl_init()) {
        throw new UniException('Unable to initialize cURL', -1);
      }

      if (!curl_setopt_array($curl, $options)) {
        throw new UniException(curl_error($curl), -2);
      }

      if (!$response = curl_exec($curl)) {
        throw new UniException(curl_error($curl), -3);
      }

      curl_close($curl);
      return new UniResponse($response);
    } catch (\ErrorException $e) {
      if (isset($curl) && is_resource($curl)) {
        curl_close($curl);
      }
      throw $e;
    }
  }

  protected function getMessages() {
      if (!$this->_message) {
          $this->_message = new MessageService($this);
      }
      return $this->_message;
  }
}
