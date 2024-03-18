<?php

namespace App\Console\Commands;

use App\Models\ProjectTeam;
use Carbon\Carbon;
use Illuminate\Console\Command;

class projectExpiredForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project-expired-for-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teams = ProjectTeam::where('end_date', '<', Carbon::today())->get();

        foreach ($teams as $team) {
            $team->update(['isExpire' => 1]);
        }

        $this->info('Expired projects tag updated successfully.');
    }
}
