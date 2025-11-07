<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoLogoutUsers extends Command
{
    protected $signature = 'auto:logout';
    protected $description = 'Automatically logout users after 9 hours if not logged out';

    public function handle()
    {
        $now = Carbon::now();

        $attendances = Attendance::where('status', 'Login')
            ->whereNull('logout_time')
            ->get();

        foreach ($attendances as $attendance) {
            $loginTime = Carbon::parse($attendance->created_at);
            $logoutDeadline = $loginTime->copy()->addHours(9);

            if ($now->greaterThanOrEqualTo($logoutDeadline)) {
                $attendance->update([
                    'logout_time' => $logoutDeadline,
                    'status' => 'Logout',
                ]);

                $this->info("✅ User ID {$attendance->user_id} auto logged out. Login time: {$loginTime}, Logout time set to: {$logoutDeadline}");
            } else {
                $this->info("⏳ User ID {$attendance->user_id} still within 9 hours window.");
            }
        }

        return Command::SUCCESS;
    }
}
