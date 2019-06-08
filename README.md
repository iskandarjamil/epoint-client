# Epoint System Client Library for PHP

[![Latest Stable Version](https://poser.pugx.org/iskandarjamil/epoint-client/v/stable?format=flat-square)](https://packagist.org/packages/iskandarjamil/epoint-client)
[![License](https://poser.pugx.org/iskandarjamil/epoint-client/license?format=flat-square)](https://packagist.org/packages/iskandarjamil/epoint-client)

The Epoint System API Client Library allowed your to easily integrate [Epoint System](http://epoint.com.sg/) with your application.

## Table of Contents

1. [Installation](#installation)
1. [Example](#example)
1. [Bug](#bug)
1. [License](#license)
1. [Authors](#authors)

## Installation

Assuming you already have Composer installed globally:

```bash
$ composer require iskandarjamil/epoint-client
```

Make sure you have configure **Entry Point**, **Store ID**, **Username**, **Password** of your account.<br>
Please make sure it store been store safely.

Configure via .env

```
EPOINT_ENTRY_POINT=
EPOINT_DB=
EPOINT_STORE_ID=
EPOINT_USERNAME=
EPOINT_PASSWORD=
```

Configure via php

```php
define("EPOINT_ENTRY_POINT", "");
define("EPOINT_DB", "");
define("EPOINT_STORE_ID", "");
define("EPOINT_USERNAME", "");
define("EPOINT_PASSWORD", "");
```

## Example

## Bug

If you have found a bug or have a great idea for improvement, please [open an issue on this repository](https://github.com/iskandarjamil/epoint-client/issues/new).

## License

License can be found [here](https://github.com/iskandarjamil/epoint-client/blob/master/LICENSE).

## Authors

The library was originally created by:

- [Iskandar Jamil](https://github.com/iskandarjamil)

See the list of [contributors](https://github.com/iskandarjamil/epoint-client/graphs/contributors).
