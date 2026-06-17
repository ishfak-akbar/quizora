<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use Illuminate\Console\Command;

class CloseExpiredQuizzes extends Command
{
    protected $signature = 'quizzes:close-expired';
    protected $description = 'Close quizzes past their end date';

    public function handle()
    {
        Quiz::where('status', 'active')
            ->where('ends_at', '<', now())
            ->update(['status' => 'closed']);

        $this->info('Expired quizzes closed.');
    }
}
