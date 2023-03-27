<?php

namespace App\Console\Commands;

use App\Jobs\CheckUserShiftJob;
use Illuminate\Console\Command;

class CheckUserShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CheckUserShiftJob::dispatch();
    }
}
