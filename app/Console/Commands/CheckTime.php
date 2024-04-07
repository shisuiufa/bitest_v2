<?php

namespace App\Console\Commands;

use App\Enums\TestStatus;
use App\Models\TestUser;
use App\Services\TestService;
use Illuminate\Console\Command;

class CheckTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:check-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(TestService $service)
    {
        $tests = TestUser::where('status', TestStatus::ONGOING)->get();

        foreach ($tests as $test) {
            $service->checkTimeTest($test);
        }

        return 0;
    }
}
