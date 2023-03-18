<?php

namespace App\Services;

use App\Models\VHost;

class VHostService
{
    public function generateVHost(VHost $vhost, bool $force = false): void
    {
        $vhostTemplate = file_get_contents(base_path('resources/vhost_template'));
        $vhostTemplate = str_replace('{{document_root}}', $vhost->document_root, $vhostTemplate);
        $vhostTemplate = str_replace('{{server_name}}', $vhost->server_name, $vhostTemplate);
        $vhostTemplate = str_replace('{{php_version}}', $vhost->php_version, $vhostTemplate);

        $path = "/etc/apache2/sites-available/{$vhost->filename}";
        if (!file_exists($path) || $force) {
            file_put_contents($path, $vhostTemplate);
        }
    }
    public function enableVHost(VHost $vhost): void
    {
        $this->executeVHostCommand('a2ensite', $vhost);
    }

    public function disableVHost(VHost $vhost): void
    {
        $this->executeVHostCommand('a2dissite', $vhost);
    }
    public function reloadApache(): void
    {
        exec('systemctl reload apache2');
    }

    public function checkVHostExists(VHost $vhost): bool
    {
        $path = "/etc/apache2/sites-available/{$vhost->filename}";
        return file_exists($path);
    }

    public function getPHPVersion(VHost $vhost): string
    {
        $filename = "/var/www/{$vhost->environment}/{$vhost->name}/php_version";
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        return '8.2';
    }

    public function parseWebSite(string $path): VHost
    {
        $vhost = new VHost();
        $vhost->name = basename($path);
        $vhost->environment = basename(dirname($path));
        $vhost->document_root = file_exists($path . '/public/index.php') ? $path . '/public' : $path;
        $vhost->server_name = $vhost->name . '.' . $vhost->environment . '.local';
        $vhost->php_version = $this->getPHPVersion($vhost);
        return $vhost;
    }

    private function executeVHostCommand(string $commandName, VHost $vhost): void
    {
        $path = "/etc/apache2/sites-available/{$vhost->filename}";
        if (file_exists($path)) {
            $command = "{$commandName} {$vhost->filename}";
            exec($command);
        }
    }

    public function createVHost(string $newDirectoryPath): void
    {
        $vhost = $this->parseWebSite($newDirectoryPath);
        $this->generateVHost($vhost);
        $this->enableVHost($vhost);
        $this->reloadApache();
    }

    public function deleteVHost(string $newDirectoryPath): void
    {
        $vhost = $this->parseWebSite($newDirectoryPath);
        $this->disableVHost($vhost);
        $path = "/etc/apache2/sites-available/{$vhost->filename}";
        if (file_exists($path)) {
            unlink($path);
        }
        $this->reloadApache();
    }

    public function updateVHost(string $directory, string $version): void
    {
        $vhost = $this->parseWebSite($directory);
        $vhost->php_version = $version;
        $this->generateVHost($vhost, true);
        $this->reloadApache();
    }

    public function setPhpVersion(VHost $vhost, string $phpVersion): void
    {
        $filename = "/var/www/{$vhost->environment}/{$vhost->name}/php_version";
        file_put_contents($filename, $phpVersion);
    }


}
