<?php

namespace App\Services;

use Spatie\Watcher\Exceptions\CouldNotStartWatcher;
use Spatie\Watcher\Watch;

class WatcherService
{

    const defaultPath = '/var/www';
    public function __construct(
        private readonly VHostService $vHostService,
    ) { }


    public function getAllEnvironments(): array
    {
        $environments = [];
        $directories = scandir(self::defaultPath);
        foreach ($directories as $directory) {
            if ($directory === '.' || $directory === '..' || $directory === 'html') {
                continue;
            }
            $environments[] = $directory;
        }
        return $environments;
    }

    public function getFoldersByEnvironment(string $environment): array
    {
        $folders = [];
        $base = self::defaultPath . '/' . $environment;
        $directories = scandir($base);
        foreach ($directories as $directory) {
            if (!is_dir($base . '/' . $directory) || str_starts_with($directory, '.')) {
                continue;
            }
            $folders[] = $directory;
        }
        return $folders;
    }

    /**
     * @return void
     * @throws CouldNotStartWatcher
     */
    public function watch(): void
    {
        Watch::path(self::defaultPath)->onDirectoryCreated(function (string $newDirectoryPath) {
            if(dirname($newDirectoryPath, 2) !== self::defaultPath) {
                return;
            }
            $this->vHostService->createVHost($newDirectoryPath);
        })->onDirectoryDeleted(function (string $newDirectoryPath) {
            if(dirname($newDirectoryPath) === self::defaultPath) {
                return;
            }
            $this->vHostService->deleteVHost($newDirectoryPath);
        })->onAnyChange(function (string $type, string $newFilePath) {
            if(dirname($newFilePath, 3) !== self::defaultPath) {
                return;
            }
            $filename = basename($newFilePath);
            $directory = dirname($newFilePath);
            if ($filename === 'php_version') {
                $version = file_get_contents($newFilePath);
                $this->vHostService->updateVHost($directory, $version);
            }
        })->start();
    }
}
