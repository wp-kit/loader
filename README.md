# wp-kit/utils

A set of utilities for wp-kit components.

There is nothing on the branch other than a helper file for wp-kit components.

## Installation

From the root of your [```Themosis```](http://framework.themosis.com/) installation or in your [```Composer```](https://getcomposer.org/) driven theme folder, install ```wp-kit/utils``` using the follow command:

```php
composer require "wp-kit/utils"
```

## Commands

### Vendor Publish

To use the vendor publish command in it's simplest form, use the below command:

````wp kit vendor:publish```

Here is a below synopsis:

```
wp kit vendor:publish [--tag=<tag>] [--provider=<provider>] [--force=<force>

OPTIONS

[--tag=<tag>]
  One or many tags that have assets you want to publish.

[--provider=<provider>]
  The service provider that has assets you want to publish.

[--force=<force>]
  Overwrite any existing files.
  ---
  default: false
  ---
```

## Requirements

Wordpress 4+

PHP 5.6+

## License

wp-kit/utils is open-sourced software licensed under the MIT License.
