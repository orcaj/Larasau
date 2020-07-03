
# PayUMoney API for Laravel and core PHP

Simple Library/Package for accepting payments via [PayUMoney](https://www.payumoney.com/).

## Installation

To add this library to your project, simply add a dependency on `niranjan94/payumoney` to your project's `composer.json` file. Here is a minimal example of a composer.json file:

    {
        "require": {
            "riazxrazor/payumoney": "1.*"
        }
    }

Or you can run this command from your project directory.

```console
composer require riazxrazor/payumoney
```

## Usage Laravel `(for non laravel usage see below)` 

### Configuration

Open the `config/app.php` and add this line in `providers` section.

```php
Riazxrazor\Payumoney\PayumoneyServiceProvider::class,
```

add this line in the `aliases` section.

```php
'Payumoney' => Riazxrazor\Payumoney\PayumoneyFacade::class

```

get the `config` by running this command.

```console
php artisan vendor:publish --tag=config
```

config option can be found `app/payumoney.php`

```

    'KEY' => '',

    'SALT' => '',

    'TEST_MODE' => TRUE,

    'DEBUG' => FALSE
```

### Basic Usage

You can use the function like this.

```php

// All of these parameters are required!
// Redirects to PayUMoney
\Payumoney::pay([
                       'txnid'       => 'A_UNIQUE_TRANSACTION_ID',
                       'amount'      => 10.50,
                       'productinfo' => 'A book',
                       'firstname'   => 'Peter',
                       'email'       => 'abc@example.com',
                       'phone'       => '1234567890',
                       'surl'        => url('payumoney-test/return'),
                       'furl'        => url('payumoney-test/return'),
                   ])->send();
                               
 
// In the return method of controller
$result = \Payumoney::completePay($_POST);

if ($result->checksumIsValid() AND isSuccess()) {
  print 'Payment was successful.';
} else {
  print 'Payment was not successful.';
}


The `PayumoneyResponse` has a few more methods that might be useful:


$result = \Payumoney::completePay($_POST);

// Returns Complete, Pending, Failed or Tampered
$result->getStatus(); 

// Returns an array of all the parameters of the transaction
$result->getParams();

// Returns the ID of the transaction
$result->getTransactionId();

// Returns true if the checksum is correct
$result->checksumIsValid();

```

## Usage Non Laravel

For non laravel usage

### Completing Payment

```php
<?php
// pay.php

use Riazxrazor\Payumoney;

require 'vendor/autoload.php';

$payumoney = new Payumoney\Payumoney([
    'KEY' => 'YOUR_MERCHANT_KEY',
    'SALT'  => 'YOUR_MERCHANT_SALT',
    'TEST_MODE'   => true, // optional default to true
    'DEBUG' => FALSE // optional default to false
]);

// All of these parameters are required!
$params = [
    'txnid'       => 'A_UNIQUE_TRANSACTION_ID',
    'amount'      => 10.50,
    'productinfo' => 'A book',
    'firstname'   => 'Peter',
    'email'       => 'abc@example.com',
    'phone'       => '1234567890',
    'surl'        => 'http://localhost/payumoney-test/return.php',
    'furl'        => 'http://localhost/payumoney-test/return.php',
];

// Redirects to PayUMoney
$payumoney->pay($params)->send();
```

### Completing Payment

```php
<?php
// return.php

use Riazxrazor\Payumoney;

require 'vendor/autoload.php';

$payumoney = new Payumoney\Payumoney([
    'KEY' => 'YOUR_MERCHANT_KEY',
    'SALT'  => 'YOUR_MERCHANT_SALT',
    'TEST_MODE'   => true, // optional default to true
    'DEBUG' => FALSE // optional default to false
]);

$result = $payumoney->completePay($_POST);

if ($result->checksumIsValid() && $result->isSuccess()) {
  print 'Payment was successful.';
} else {
  print 'Payment was not successful.';
}
```

The `PayumoneyResponse` has a few more methods that might be useful:

```php
$result = $payumoney->completePay($_POST);

// Returns Complete, Pending, Failed or Tampered
$result->getStatus(); 

// Returns an array of all the parameters of the transaction
$result->getParams();

// Returns the ID of the transaction
$result->getTransactionId();

// Returns true if the checksum is correct
$result->checksumIsValid();
```
