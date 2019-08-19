[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/vpre/juliangut/slim-php-di.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-php-di)
[![License](https://img.shields.io/github/license/juliangut/slim-php-di.svg?style=flat-square)](https://github.com/juliangut/slim-php-di/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/juliangut/slim-php-di.svg?style=flat-square)](https://travis-ci.org/juliangut/slim-php-di)
[![Style Check](https://styleci.io/repos/40728455/shield)](https://styleci.io/repos/40728455)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/slim-php-di.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/slim-php-di)
[![Code Coverage](https://img.shields.io/coveralls/juliangut/slim-php-di.svg?style=flat-square)](https://coveralls.io/github/juliangut/slim-php-di)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/slim-php-di.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-php-di/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/slim-php-di.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-php-di/stats)

# Slim Framework PHP-DI container integration

PHP-DI (v6) dependency injection container integration for Slim framework.

## Installation

Best way to install is using [Composer](https://getcomposer.org/):

```
composer require juliangut/slim-php-di
```

Then require_once the autoload file:

```php
require_once './vendor/autoload.php';
```

## Usage

Use `Jgut\Slim\PHPDI\ContainerBuilder` to create PHP-DI container and extract Slim's App from it

```php
use App\ServiceOne;
use Jgut\Slim\PHPDI\Configuration;
use Jgut\Slim\PHPDI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;

$settings = [
    'definitions' => '/path/to/definitions/files',
];
$container = ContainerBuilder::build(new Configuration($settings));

$app = $container->get(App::class);

// Register your services if not provided as definitions
$container->set('service_one', function (ContainerInterface $container) {
    return new ServiceOne($container->get('service_two'));
});

// Set your routes

$app->run();
```

In order to register services in the container it's way better to do it in definition files

### Configuration

```php
use Jgut\Slim\PHPDI\Configuration;

$settings = [
    'ignorePhpDocErrors' => true,
    'compilationPath' => '/path/to/compiled/container',
];
$configuration = new Configuration($settings);

// Can be set after configuration creation
$configuration->setProxiesPath(sys_get_temp_dir());
$configuration->setDefinitions('/path/to/definition/files');

$container = ContainerBuilder::build($configuration);
```

#### PHP-DI settings

* `useAutoWiring`, whether or not to use auto wiring (true by default)
* `useAnnotations`, whether or not to use annotations (false by default)
* `useDefinitionCache`, whether or not to use definition cache (false by default)
* `ignorePhpDocErrors`, whether or not to ignore phpDoc errors on annotations (false by default)
* `wrapContainer`, wrapping container (none by default)
* `proxiesPath`, path where PHP-DI creates its proxy files (none by default)
* `compilationPath`, path to where PHP-DI creates its compiled container (none by default)

Refer to [PHP-DI documentation](http://php-di.org/doc/) to learn more about container configurations

In order for you to use annotations you have to `require doctrine/annotations`. [See here](http://php-di.org/doc/annotations.html)

#### Additional settings

* `definitions`, an array of paths to definition files/directories or arrays of definitions. _Definitions are loaded in order of appearance_
* `containerClass`, container class that will be built. Must implement `\Interop\Container\ContainerInterface`, `\DI\FactoryInterface` and `\DI\InvokerInterface` (`\Jgut\Slim\PHPDI\Container` by default)

## Array value access shorthand

Default `\Jgut\Slim\PHPDI\Container` container allows shorthand array values access by concatenating array keys with dots. If any key in the chain is not defined normal container's `Psr\Container\NotFoundExceptionInterface` is thrown

```php
$container->get('configs')['database']['dsn']; // given configs is an array
$container->get('configs.database.dsn'); // same as above
```

#### Notice

Be careful though not to shadow any array key by using dots in keys itself

```php
$configs = [
    'foo' => [
        'bar' => [
            'baz' => 'shadowed!',
        ],
    ],
    'foo.bar' => 'bang!',
];
$container->set('configs', $configs);

$container->get('configs.foo.bar'); // bang!
$container->get('configs.foo.bar.baz'); // NotFoundExceptionInterface thrown
```

_The easiest way to avoid this from ever happening is by NOT using dots in array keys_

## Invocation strategy

By default slim-php-di sets a custom invocation strategy that employs PHP-DI's Invoker to fulfill callable parameters, it is quite handy and lets you do things like this

```php
$app = $container->get(App::class);

$app->get('/hello/{name}', function (ResponseInterface $response, string $name, CustomDbConnection $connection): ResponseInterface {
    // $name will be injected from request arguments
    // $connection will be injected from the container

    $response->getBody()->write('Hello ' . $name);

    return $response;
});

$app->run();
```

If you prefer default Slim's `Slim\Handlers\Strategies\RequestResponse` strategy or any other of your choosing you only have to set it in a definition file

```php
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Interfaces\InvocationStrategyInterface;

use function DI\create;

return [
    InvocationStrategyInterface::class => create(RequestResponse::class),
];
```

## Migration from 2.x

* Minimum Slim version is now 4.0
* PHP-DI container now provides only the Configuration object used on building the container itself and implementations of the interfaces needed to instantiate an App. Refer to [Slim's documentation](http://www.slimframework.com/docs/v4/)
* You can extract Slim's App directly from container or seed AppFactory from container
* Slim's App is not extended any more
* Service definitions à la Pimple support has been kept but its use is discouraged, use PHP-DI's methods instead

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/slim-php-di/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/slim-php-di/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/slim-php-di/blob/master/LICENSE) included with the source code for a copy of the license terms.
