# Twigger

Basic class to init the [Twig](http://twig.sensiolabs.org/) Environment with custom functions.

## Install

```sh
composer require jnav24/twigger dev-master
```

## Usage

Instantiate the twigger class and pass in the html path in the constructor.

```php
$twig = new Twigger\Twigger(__DIR__.'/views');
```