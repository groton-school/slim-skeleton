<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use GrotonSchool\Slim\LTI\Domain\User\UserRepositoryInterface;
use GrotonSchool\Slim\LTI\Infrastructure\GAE\Firestore\FirestoreUserRepository;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider\ProviderRepositoryInterface;
use GrotonSchool\Slim\SPA\OAuth2\Client\Domain\Provider\SingleProvider;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => DI\autowire(FirestoreUserRepository::class),
        ProviderRepositoryInterface::class => DI\autowire(SingleProvider::class)
    ]);
};
