[<img src="https://travis-ci.org/tinify/tinify-php.svg?branch=master" alt="Build Status">](https://travis-ci.org/tinify/tinify-php)

# Tinify API client for PHP

Unofficial PHP client for the Tinify API, used for [TinyPNG](https://tinypng.com) and [TinyJPG](https://tinyjpg.com). Tinify compresses your images intelligently. Read more at [http://tinify.com](http://tinify.com).

## Documentation

[Go to the documentation for the PHP client](https://tinypng.com/developers/reference/php).

⚠️ this client implementation is differents from the documentation.

## Installation

Install the API client with Composer. Add this to your `composer.json`:

```json
{
  "require": {
    "tinify/tinify": "^2.0"
  },
  "repositories": {
    "tinify-php": {
      "type": "vcs",
      "url": "https://github.com/jargoud/tinify-php"
    }
  }
}
```

Then install with:

```
composer install
```

## Usage

```php
Tinify::setKey("YOUR_API_KEY");
Tinify::fromFile("unoptimized.png")->toFile("optimized.png");
```

## Running tests

```
composer install
vendor/bin/phpunit
```

## License

This software is licensed under the MIT License. [View the license](LICENSE).
