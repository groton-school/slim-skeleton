<?php

declare(strict_types=1);

namespace App\Application\Settings;

use GrotonSchool\Slim\GAE;

interface SettingsInterface extends GAE\SettingsInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');
}
