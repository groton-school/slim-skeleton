<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Middleware\Authenticated;
use App\Application\Settings\SettingsInterface;
use GrotonSchool\Slim\Norms\AbstractAction;
use Packback\Lti1p3\LtiConstants;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

class AppStartAction extends AbstractAction
{
    public function __construct(
        private PhpRenderer $views,
        private SettingsInterface $settings
    ) {}

    protected function invokeHook(ServerRequest $request, Response $response, array $args = []): ResponseInterface
    {
        return $this->views->render($response, 'SPA.php', [
            'title' => $this->settings->getToolName(),
            'user' => $request->getAttribute(Authenticated::USER),
            "consumer_instance_url" => $request->getAttribute(
                Authenticated::LAUNCH_MESSAGE
            )[LtiConstants::LAUNCH_PRESENTATION]['return_url']
        ]);
    }
}
