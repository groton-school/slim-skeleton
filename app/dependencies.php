<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use GrotonSchool\Slim\GAE;

return function (ContainerBuilder $containerBuilder) {

    GAE\Dependencies::inject($containerBuilder);

    $containerBuilder->addDefinitions([
        GAE\SettingsInterface::class => DI\get(SettingsInterface::class)
    ]);
};
