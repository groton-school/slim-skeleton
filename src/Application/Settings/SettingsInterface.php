<?php

declare(strict_types=1);

namespace App\Application\Settings;

use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\Infrastructure;
use GrotonSchool\Slim\SPA;;

interface SettingsInterface extends
    GAE\SettingsInterface,
    LTI\SettingsInterface,
    LTI\PartitionedSession\SettingsInterface,
    Infrastructure\GAE\SettingsInterface,
    Spa\OAuth2\Client\SettingsInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');
}
