<?php

namespace App\Http\Controllers;

use App\Models\VHost;
use App\Services\VHostService;
use App\Services\WatcherService;

class SiteController extends Controller
{


    public function __construct(
        private readonly WatcherService $watcherService,
        private readonly VHostService   $vHostService,
    )
    {
    }

    public function index(string $env)
    {
        if ($env === 'html') {
            return response()->json();
        }
        $folders = $this->watcherService->getFoldersByEnvironment($env);
        $folders = array_map(function (string $folder) use ($env) {
            $vhost = new VHost([
                'name' => $folder,
                'environment' => $env,
            ]);
            $hasVhost = $this->vHostService->checkVHostExists($vhost);
            return [
                'name' => $folder,
                'url' => $hasVhost ? $vhost->server_name : null,
                'actif' => $hasVhost,
                'php' => $this->vHostService->getPhpVersion($vhost),
            ];
        }, $folders);

        return response()->json($folders);
    }

    public function patchPhpVersion(string $env, string $site, string $phpVersion)
    {
        $vhost = new VHost([
            'name' => $site,
            'env' => $env,
        ]);
        $this->vHostService->setPhpVersion($vhost, $phpVersion);
        return response()->json();
    }
}
