<?php

/*
 * slim-php-di (https://github.com/juliangut/slim-php-di).
 * Slim Framework PHP-DI container implementation.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/slim-php-di
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Jgut\Slim\PHPDI;

use ArrayAccess;
use DI\CompiledContainer as DICompiledContainer;

/**
 * @see \Slim\Container
 *
 * @implements ArrayAccess<string, mixed>
 */
abstract class AbstractCompiledContainer extends DICompiledContainer implements ArrayAccess
{
    /** @phpstan-use ContainerTrait<object> */
    use ContainerTrait;
}
