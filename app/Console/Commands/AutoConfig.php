<?php

namespace App\Console\Commands;

use App\Services\WatcherService;
use Illuminate\Console\Command;
use Spatie\Watcher\Exceptions\CouldNotStartWatcher;
use Spatie\Watcher\Watch;

class AutoConfig extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:autoconfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private readonly WatcherService $watcherService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws CouldNotStartWatcher
     */
    public function handle(): void
    {
        $this->watcherService->watch();
    }
}
