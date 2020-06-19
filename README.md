# The Good Till for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/flair/good-till-system.svg?style=flat-square)](https://packagist.org/packages/flair/good-till-system)
[![Build Status](https://img.shields.io/travis/flair/good-till-system/master.svg?style=flat-square)](https://travis-ci.org/flair/good-till-system)
[![Quality Score](https://img.shields.io/scrutinizer/g/flair/good-till-system.svg?style=flat-square)](https://scrutinizer-ci.com/g/flair/good-till-system)
[![Total Downloads](https://img.shields.io/packagist/dt/flair/good-till-system.svg?style=flat-square)](https://packagist.org/packages/flair/good-till-system)


This package is still a work in progress, extended documentation will follow.

## Test Accounts
If you are building an integration and you would like to test the API without affecting your live account, or you are building an integration on behalf of a Goodtill customer, you request a fully-featured test account from dev@thegoodtill.com. 

## Installation

You can install the package via composer:

```bash
composer require flairuk/good-till-system
```

## Basic Usage
Add the environment variables to your .env file and enter the api credentials.
```php
GOOD_TILL_DOAMIN=
GOOD_TILL_USERNAME=
GOOD_TILL_PASSWORD=
```
Use the GoodTillSystem Facade.

``` php
use GoodTillSystem;
```
Once you add the facade, you can now interact with the Good Till System API.


## Good Till System API

### Products

1. Get a list of products.

```php
GoodTillSystem::products()->get();
```

2. Get a product by id.

```php
GoodTillSystem::product($id)->get();
```

3. Create a new product.

```php
GoodTillSystem::product()->create($data);
```

4. Update a product by id.

```php

GoodTillSystem::product($id)->update($data);
```

5. Delete a product by id.

```php
GoodTillSystem::product($id)->delete();
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ijeffrouk@gmail.com instead of using the issue tracker.

## Credits

- [Phil Graham](https://github.com/flair)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
