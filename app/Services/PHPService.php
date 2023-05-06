<?php

namespace App\Services;

class PHPService
{


    public function __construct(
        private readonly CommandService $commandService,
    )
    {
    }

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
        exec("apt list --installed | grep '^php$version-' | awk -F '/' '{print $1}' | awk -F '-' '{print $2}' | sort -V | uniq", $output);
        return $output;
    }

    public function listExtensionsDownloadable(string $version): array
    {
        $output = [];
        exec("apt-cache search --names-only '^php$version-' | awk '{print $1}' | awk -F '-' '{print $2}' | sort -V | uniq", $output);
        return $output;
    }

    public function installVersion(string $version): void
    {
        $this->commandService->run("apt install -y php$version");
    }

    public function installExtension(string $version, string $extension): void
    {
        $this->commandService->run("dpkg --configure -a");
        $this->commandService->run("apt install -y php$version-$extension");
        $this->commandService->run("dpkg --configure -a");
    }

    public function uninstallVersion(string $version): void
    {
        $this->commandService->run("dpkg --configure -a");
        $this->commandService->run("apt remove -y php$version");
        $this->commandService->run("dpkg --configure -a");
    }

    public function uninstallExtension(string $version, string $extension): void
    {
        $this->commandService->run("dpkg --configure -a");
        $this->commandService->run("apt remove -y php$version-$extension");
        $this->commandService->run("dpkg --configure -a");
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