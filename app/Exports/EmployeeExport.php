<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmployeeExport implements FromCollection, WithHeadings
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
        try {
            $employees = User::whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->get();

            return $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'unique_id' => $employee->unique_id,
                    'name' => $employee->full_name,
                    'email' => $employee->email,
                    'position' => $employee->position,
                    'role' => $employee->role,
                    'status' => $employee->status,
                    'created_at' => $employee->created_at?->toDateString(),
                    'updated_at' => $employee->updated_at?->toDateString(),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error generating employee collection: ' . $e->getMessage());
            return collect([]);
        }
    }
    public function headings(): array
    {
        return [
            'ID',
            'Employee Id',
            'Name',
            'Email',
            'Position',
            'Role',
            'Status',
            'Created Date',
            'Updated Date',
        ];
    }

}
