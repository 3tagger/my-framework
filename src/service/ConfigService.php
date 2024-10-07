<?php

namespace ThreeTagger\MyFramework\Service;

use League\Flysystem\FilesystemOperator;

class ConfigService
{
    protected array $config;

    public function __construct(FilesystemOperator $fs)
    {
        $this->config = json_decode($fs->read('config.json'), true);
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}