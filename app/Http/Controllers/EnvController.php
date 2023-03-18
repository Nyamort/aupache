<?php

namespace App\Http\Controllers;

use App\Services\WatcherService;

class EnvController extends Controller
{


    public function __construct(
        private readonly WatcherService $watcherService,
    )
    {
    }

    public function index()
    {
        $envs = $this->watcherService->getAllEnvironments();
        $envs = array_map(function ($env) {
            return [
                "name" => $env,
            ];
        }, $envs);
        return response()->json($envs);
    }
}
