<?php

declare(strict_types=1);

use App\Application\Actions\AppStartAction;
use App\Application\Middleware\Authenticated;
use GrotonSchool\Slim\GAE\Actions\EmptyAction;
use GrotonSchool\Slim\LTI\Actions\JWKSAction;
use GrotonSchool\Slim\LTI\Actions\LaunchAction;
use GrotonSchool\Slim\LTI\Actions\LoginAction;
use GrotonSchool\Slim\LTI\Actions\RegistrationStartAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\FirstPartyLaunchAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\RequestStorageAccessAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\ThirdPartyCookieAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Actions\ValidateSessionAction;
use GrotonSchool\Slim\LTI\PartitionedSession\Middleware\PartitionedSession;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // return an empty string on GAE start/stop requests
    $app->get('/_ah/{action:.*}', EmptyAction::class);

    // standard LTI endpoints
    $app->group('/lti', function (Group $lti) {
        $lti->post('/launch', LaunchAction::class);
        $lti->get('/jwks', JWKSAction::class);
        $lti->get('/register', RegistrationStartAction::class);
        $lti->post('/login', LoginAction::class);
        $lti->get('/third-party-cookies', ThirdPartyCookieAction::class);
        $lti->get('/first-party-launch', FirstPartyLaunchAction::class);
        $lti->get('/request-storage-access', RequestStorageAccessAction::class);
        $lti->get('/validate-session', ValidateSessionAction::class);
    })
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSession::class);

    $app->get('/', AppStartAction::class)
        ->add(Authenticated::class)
        ->add(SessionStartMiddleware::class)
        ->add(PartitionedSession::class);
};
