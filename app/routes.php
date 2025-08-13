<?php

declare(strict_types=1);

use App\Application\Actions\AppStartAction;
use App\Application\Actions\SpaAction;
use App\Application\Actions\XhrCookieAction;
use App\Application\Middleware\Authenticated;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\PartitionedSession;
use GrotonSchool\Slim\LTI\PartitionedSession\Middleware\PartitionedSessionMiddleware;
use GrotonSchool\Slim\SPA;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;

return function (App $app) {

    // define standard routes
    GAE\RouteBuilder::define($app);
    LTI\RouteBuilder::define($app)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);
    PartitionedSession\RouteBuilder::define($app);
    SPA\OAuth2\Client\RouteBuilder::define($app, 'canvas')
        ->add(PartitionedSessionMiddleware::class);

    $app->get('/', AppStartAction::class)
        ->add(Authenticated::class)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);
};
