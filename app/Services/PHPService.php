<?php

namespace App\Services;

class PHPService
{
    public function listVersionsDownloadable(): array
    {
        $output = [];
        exec("apt-cache search --names-only '^php[0-9]+\.[0-9]+$' | awk '{print $1}' | sort -V", $output);
        return $output;
    }

    public function listVersionsInstalled(): array
    {
        $output = [];
        exec("apt list --installed | grep '^php[0-9]\+\.[0-9]\+/' | awk -F '/' '{print $1}' | sort -V", $output);
        return $output;
    }

    public function listExtensionsInstalled(string $version): array
    {
        $output = [];
        exec("apt list --installed | grep '^php$version-' | awk -F '/' '{print $1}' | awk -F '-' '{print $2}' | sort -V", $output);
        return $output;
    }

    public function listExtensionsDownloadable(string $version): array
    {
        $output = [];
        exec("apt-cache search --names-only '^php$version-' | awk '{print $1}' | awk -F '-' '{print $2}' | sort -V", $output);
        return $output;
    }

    public function installVersion(string $version): string|bool
    {
        return exec("apt install -y php$version");
    }

    public function installExtension(string $version, string $extension): string|bool
    {
        return exec("apt install -y php$version-$extension");
    }

    public function uninstallVersion(string $version): string|bool
    {
        return exec("apt remove -y php$version");
    }

    public function uninstallExtension(string $version, string $extension): string|bool
    {
        return exec("apt remove -y php$version-$extension");
    }

    public function uninstallVersionAndExtensions(string $version): void
    {
        $this->uninstallVersion($version);
        $extensions = $this->listExtensionsInstalled($version);
        foreach ($extensions as $extension) {
            $this->uninstallExtension($version, $extension);
        }
    }
}