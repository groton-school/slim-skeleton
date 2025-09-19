<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use GrotonSchool\Slim\OAuth2\APIProxy\GAE\AbstractUserIdentifierMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use GrotonSchool\Slim\LTI\Domain\User\User;
use Packback\Lti1p3\LtiConstants;

class ApiProxyUserIdentifier extends AbstractUserIdentifierMiddleware
{
    protected function getIdentifier(ServerRequestInterface $request): string
    {
        /** @var User $user */
        $user = $request->getAttribute(Authenticated::USER);
        $launch = $request->getAttribute(Authenticated::LAUNCH_MESSAGE);
        return  parse_url($launch[LtiConstants::LAUNCH_PRESENTATION]['return_url'], PHP_URL_HOST) . '::' . $user->getId();
    }
}
