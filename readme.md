# Unbounce Api Client

## Description

A library to consume the [Unbounce Api](https://developer.unbounce.com/) in your Laravel Application.

## Installation

First require in Composer:

```
composer require campaigningbureau/unbounce-api-client
```

The Service Provider is found via Laravel Autodiscovery.

Then publish the config settings:

```
php artisan vendor:publish
```

## Configuration

After publishing the config file you can edit them in `config/unbounce.php`.

Make sure you configure at least `api_key`.

## Usage

### Subaccounts

Load subaccounts as a Collection of `Subaccount` Objects:
```php
$account_id = 'myAccountId';
$subaccounts = Unbounce::subaccounts($account_id);
```

#### Pages

By default the pages of a subaccount are not loaded. To load them use the following command:

```php
$account_id = 'myAccountId';
$subaccounts = Unbounce::subaccounts($account_id);
$my_subaccount = $subaccounts->first();

$pages = $my_subaccount->getPages();
```

## Still missing

* More Subaccounts informations:
    * Domains
    * Leads
* Other Resource Routes
* OAuth AuthenticationDriver
