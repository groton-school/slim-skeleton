<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use Battis\LazySecrets;
use DI\ContainerBuilder;
use GrotonSchool\Slim\CanvasLMS;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigurePassthruAction;
use GrotonSchool\Slim\LTI\Infrastructure;
use GrotonSchool\Slim\LTI\PartitionedSession;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {

    // inject shim dependencies
    LTI\Dependencies::inject($containerBuilder);
    GAE\Dependencies::inject($containerBuilder);
    PartitionedSession\Dependencies::inject(($containerBuilder));
    Infrastructure\GAE\Dependencies::inject($containerBuilder);

    $containerBuilder->addDefinitions([
        // use default partitioned session settings
        PartitionedSession\SettingsInterface::class => DI\get(PartitionedSession\DefaultSettings::class),

        // all other settings interfaces map to the App Settings
        GAE\SettingsInterface::class => DI\get(SettingsInterface::class),
        LTI\SettingsInterface::class => DI\get(SettingsInterface::class),
        Infrastructure\GAE\SettingsInterface::class => DI\get(SettingsInterface::class),

        /*
        * autowire registration configuration passthru (no interactive
        * configuration)
        *
        * to set up interactive configurattion of the registration, implement
        * (and autowire) GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface
        *
        * Interactive configuration ends either by invoking
        * GrotonSchool\Slim\LTI\Action\RegistrationCompleteAction::complete()
        * or by POSTing the complete registration (with the parameter name
        * registration) to an endpoint handled by
        * GrotonSchool\Slim\LTI\Action\RegistrationCompleteAction
        */
        RegistrationConfigureActionInterface::class => DI\autowire(RegistrationConfigurePassthruAction::class),

        PhpRenderer::class => function (ContainerInterface $container) {
            $views = new PhpRenderer(__DIR__ . '/../views', [
                'tool_name' => $container->get(SettingsInterface::class)->get(Settings::TOOL_NAME)
            ]);
            $views->setLayout('layout.php');
            return $views;
        },

        ProviderInterface::class => function (ContainerInterface $container) {
            /** @var SettingsInterface $settings */
            $settings = $container->get(SettingsInterface::class);
            $secrets = new LazySecrets\Cache();
            return new CanvasLMS\APIProxy([
                ...$secrets->get('CANVAS_CREDENTIALS'),
                'purpose' => $settings->getToolName()
            ]);
        }
    ]);
};
