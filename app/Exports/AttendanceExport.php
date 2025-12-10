<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
class AttendanceExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $from;
    protected $to;
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Attendance::with('user')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->from, $this->to])
            ->get()
            ->map(function ($attendance) {
                $login = $attendance->login_time ? Carbon::parse($attendance->login_time) : null;
                $logout = $attendance->logout_time ? Carbon::parse($attendance->logout_time) : null;
                $workingTime = 'N/A';
                if ($login && $logout) {
                    $diffInMinutes = $logout->diffInMinutes($login);
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;
                    $workingTime = sprintf('%02dh:%02dm', $hours, $minutes);
                }

                return [
                    'id' => $attendance->id,
                    'user_name' => $attendance->user?->full_name ?? 'N/A',
                    'role' => $attendance->role,
                    'login_time' => $attendance->login_time,
                    'logout_time' => $attendance->logout_time,
                    'status' => $attendance->status,
                    'working_time' => $workingTime,
                    'created_at' => $attendance->created_at?->toDateString(),
                ];
            });
    }
    public function headings(): array
    {
        return ['Id', 'User Name', 'Role', 'Login Time', 'Logout Time', 'Status', 'Total Working Time', 'Created Date'];
    }

}
