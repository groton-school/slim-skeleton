<?php

declare(strict_types=1);

use App\Application\Actions\AppStartAction;
use App\Application\Middleware\Authenticated;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\FirstPartyLaunchAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\RequestStorageAccessAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\ThirdPartyCookieAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\ValidateSessionAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Middleware\PartitionedSessionMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    GAE\RouteBuilder::define($app);
    LTI\RouteBuilder::define($app)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);

    $app->group('/lti', function (Group $lti) {
        $lti->get('/third-party-cookies', ThirdPartyCookieAction::class);
        $lti->get('/first-party-launch', FirstPartyLaunchAction::class);
        $lti->get('/request-storage-access', RequestStorageAccessAction::class);
        $lti->get('/validate-session', ValidateSessionAction::class);
    })
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);

    $app->get('/', AppStartAction::class)
        ->add(Authenticated::class)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSessionMiddleware::class);
};
