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

use Jgut\ECS\Config\ConfigSet80;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$header = <<<'HEADER'
slim-php-di (https://github.com/juliangut/slim-php-di).
Slim Framework PHP-DI container implementation.

@license BSD-3-Clause
@link https://github.com/juliangut/slim-php-di
@author Julián Gutiérrez <juliangut@gmail.com>
HEADER;

return static function (ECSConfig $ecsConfig) use ($header): void {
    $ecsConfig->paths([
        __FILE__,
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    (new ConfigSet80())
        ->setHeader($header)
        ->enablePhpUnitRules()
        ->configure($ecsConfig);
};
