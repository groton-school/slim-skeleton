<?php

declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    public const PROJECT_ID = self::class . '::project_id';
    public const PROJECT_URL = self::class . '::project_url';
    public const LOGGER_NAME = self::class . '::logger_name';

    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getProjectId(): string
    {
        return $this->settings[self::PROJECT_ID];
    }

    public function getProjectUrl(): string
    {
        return $this->settings[self::PROJECT_URL];
    }

    public function getLoggerName(): string
    {
        return $this->settings[self::LOGGER_NAME];
    }

    /**
     * @return mixed
     */
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}
