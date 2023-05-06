<?php

namespace App\Http\Controllers;

use App\Services\PHPService;

class PHPController extends Controller
{
    public function __construct(
        private readonly PHPService $phpService,
    )
    {
    }

    public function index()
    {
        return response()->json([
            'installed' => $this->phpService->listVersionsInstalled(),
            'downloadable' => $this->phpService->listVersionsDownloadable(),
        ]);
    }

    public function extensions(string $version)
    {
        $version = str_replace('php', '', $version);
        return response()->json([
            'installed' => $this->phpService->listExtensionsInstalled($version),
            'downloadable' => $this->phpService->listExtensionsDownloadable($version),
        ]);
    }

    public function installVersion(string $version)
    {
        $version = str_replace('php', '', $version);
        $this->phpService->installVersion($version);
        return response('', 201);
    }

    public function uninstallVersion(string $version)
    {
        $version = str_replace('php', '', $version);
        $this->phpService->uninstallVersionAndExtensions($version);
        return response('', 204);
    }

    public function installExtension(string $version, string $extension)
    {
        $version = str_replace('php', '', $version);
        $this->phpService->installExtension($version, $extension);
        return response('', 201);
    }

    public function uninstallExtension(string $version, string $extension)
    {
        $version = str_replace('php', '', $version);
        $this->phpService->uninstallExtension($version, $extension);
        return response('', 204);
    }
}