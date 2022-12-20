# Unimatrix PHP SDK

[![Packagist](https://img.shields.io/packagist/v/unimtx/uni-sdk.svg)](https://packagist.org/packages/unimtx/uni-sdk) [![Release](https://img.shields.io/github/release/unimtx/uni-php-sdk.svg)](https://github.com/unimtx/uni-php-sdk/releases/latest) [![GitHub license](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/unimtx/uni-php-sdk/blob/main/LICENSE)

The Unimatrix PHP SDK provides convenient access to integrate communication capabilities into your PHP applications using the Unimatrix HTTP API. The SDK provides support for sending SMS, 2FA verification, and phone number lookup.

## Getting started

Before you begin, you need an [Unimatrix](https://www.unimtx.com/) account. If you don't have one yet, you can [sign up](https://www.unimtx.com/signup?s=php.sdk.gh) for an Unimatrix account and get free credits to get you started.

## Documentation

Check out the documentation at [unimtx.com/docs](https://www.unimtx.com/docs) for a quick overview.

## Installation

Using Composer is the recommended way to install the Unimatrix SDK for PHP, which is available on [Packagist](https://packagist.org/packages/unimtx/uni-sdk).

Run the following command to add `unimtx/uni-sdk` as a dependency to your project:

```bash
composer require unimtx/uni-sdk
```

## Usage

The following example shows how to use the Unimatrix PHP SDK to interact with Unimatrix services.

### Send SMS

Send a text message to a single recipient.

```php

use Uni\UniClient;
use Uni\UniException;

$client = new UniClient([
  'accessKeyId' => 'your access key id',
  'accessKeySecret' => 'your access key secret'
]);

try {
  $resp = $client->messages->send([
    'to' => 'your phone number', // in E.164 format
    'signature' => 'your sender name',
    'content' => 'Your verification code is 2048.'
  ]);
  var_dump($resp->data);
} catch (UniException $e) {
  print_r($e);
}

```

## Reference

### Other Unimatrix SDKs

To find Unimatrix SDKs in other programming languages, check out the list below:

- [Java](https://github.com/unimtx/uni-java-sdk)
- [Go](https://github.com/unimtx/uni-go-sdk)
- [Node.js](https://github.com/unimtx/uni-node-sdk)
- [Python](https://github.com/unimtx/uni-python-sdk)
- [Ruby](https://github.com/unimtx/uni-ruby-sdk)
- [.NET](https://github.com/unimtx/uni-dotnet-sdk)

## License

This library is released under the [MIT License](https://github.com/unimtx/uni-php-sdk/blob/main/LICENSE).
