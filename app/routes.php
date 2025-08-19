<?php

declare(strict_types=1);

use App\Application\Actions\AppStartAction;
use App\Application\Middleware\Authenticated;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\PartitionedSession;
use GrotonSchool\Slim\LTI\PartitionedSession\Middleware\PartitionedSessionMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;

return function (App $app) {

    // define standard routes
    GAE\RouteBuilder::define($app);
    LTI\RouteBuilder::define($app)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);
    PartitionedSession\RouteBuilder::define($app);

    $app->get('/', AppStartAction::class)
        ->add(Authenticated::class)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);
};
