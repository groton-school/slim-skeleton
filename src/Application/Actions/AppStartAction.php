<?php

namespace App\Application\Actions;

use App\Application\Middleware\Authenticated;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\PhpRenderer;

class AppStartAction
{
    public function __construct(private PhpRenderer $views)
    {
    }

    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        return $this->views->render($response, 'app.php', [
            'launchData' => $request->getAttribute(Authenticated::LAUNCH_MESSAGE)
        ]);
    }
}
