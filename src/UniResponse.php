<?php

namespace Uni;

use Uni\UniException;

class UniResponse {
  const REQUEST_ID_HEADER_KEY = 'x-uni-request-id';

  public $headers;
  public $code;
  public $data;
  public $raw;
  public $requestId;
  public $valid;

  function __construct($resp) {
    list($raw_headers, $raw_body) = explode("\r\n\r\n", $resp, 2);
    $this->headers = $this->parse_headers($raw_headers);
    $this->requestId = $this->headers[self::REQUEST_ID_HEADER_KEY] ?? null;

    $data = json_decode($raw_body);
    $code = $data->code;

    if ($code != 0) {
      throw new UniException($data->message, $code, $this->requestId);
    }

    $this->code = $code;
    $this->data = $data->data;
    $this->raw = $resp;
  }

  private function parse_headers($raw_headers) {
    $headers = [];

    foreach (explode("\n", $raw_headers) as $i => $h) {
      $h = explode(':', $h, 2);

      if (isset($h[1])) {
        $k = strtolower($h[0]);
        $v = $h[1];

        if(!isset($headers[$k])) {
          $headers[$k] = trim($v);
        } else if(is_array($headers[$k])) {
          $tmp = array_merge($headers[$k],array(trim($v)));
          $headers[$k] = $tmp;
        } else {
          $tmp = array_merge(array($headers[$k]),array(trim($v)));
          $headers[$k] = $tmp;
        }
      }
    }

    return $headers;
  }
}
