@extends('layouts.app')

@section('content')

<style>/* Salary Slip Header Responsive Fix */
@media (max-width: 768px) {
    .salary-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px;
    }

    .salary-header div {
        width: 100%;
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    .salary-header div a {
        flex: 1;
        text-align: center;
    }

    .salary-header h4 {
        font-size: 18px !important;
    }
}

@media (max-width: 480px) {
    .salary-header div {
        flex-direction: column;
    }

    .salary-header div a {
        width: 100%;
    }
}
</style>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      {{-- Company Info Card --}}
      <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
          @if($slip->company_logo)
            <img src="{{ asset('storage/app/public/' . $slip->company_logo) }}" 
                 alt="Company Logo" height="70" class="mb-3">
          @endif

          <h3 class="fw-bold">{{ $slip->company_name }}</h3>

          @if($slip->company_tagline)
            <p class="text-muted fst-italic mb-1">{{ $slip->company_tagline }}</p>
          @endif

          <p class="mb-0">{!!   nl2br(e($slip->company_address))!!}</p>
        </div>
      </div>

      {{-- Salary Slip Header with Actions --}}
      <div class="d-flex justify-content-between align-items-center mb-4 salary-header">
        <h4 class="fw-semibold mb-0">Salary Slip — {{ \Carbon\Carbon::parse($slip->month)->format('F, Y') }}</h4>
        <div>
          <a href="{{ route('salary_slips.download', $slip->id) }}" class="btn btn-outline-primary btn-sm me-2">
            <i class="bi bi-download"></i> Download PDF
          </a>
          <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
          </a>
        </div>
      </div>

      {{-- Employee Details Card --}}
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          Employee Details
        </div>
        <div class="card-body p-3">
          <table class="table table-bordered mb-0">
            <tbody>
              <tr>
                <th class="w-25">Employee Name</th>
                <td>{{ $slip->employee_name }}</td>
              </tr>
              <tr>
                <th>Employee Code</th>
                <td>{{ $slip->emp_code }}</td>
              </tr>
              <tr>
                <th>Designation</th>
                <td>{{ $slip->designation }}</td>
              </tr>
              <tr>
                <th>Joining Date</th>
                <td>{{ $slip->joining_date?->format('d M Y') }}</td>
              </tr>
              <tr>
                <th>Father's Name</th>
                <td>{{ $slip->father_name }}</td>
              </tr>
              <tr>
                <th>Date of Birth</th>
                <td>{{ $slip->dob?->format('d M Y') }}</td>
              </tr>
              <tr>
                <th>Mobile</th>
                <td>{{ $slip->mobile }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ $slip->email }}</td>
              </tr>
              <tr>
                <th>Address</th>
                <td>{{ $slip->address }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Attendance Details Card --}}
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white fw-bold">
          Attendance Details
        </div>
        <div class="card-body p-3">
          <table class="table table-bordered mb-0">
            <tbody>
              <tr>
                <th>Working Days</th>
                <td>{{ $slip->working_days }}</td>
              </tr>
              <tr>
                <th>On Duty</th>
                <td>{{ $slip->on_duty }}</td>
              </tr>
              <tr>
                <th>Unpaid Leave Days</th>
                <td>{{ $slip->unpaid_leave_days }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Salary & Deductions Cards side by side --}}
      <div class="row g-4">

        {{-- Salary Details --}}
        <div class="col-lg-6">
          <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
              Salary Details
            </div>
            <div class="card-body p-3">
              @php
                $basic = $slip->basic ?? 0;
                $incentives = $slip->incentives ?? 0;
                $overtime = $slip->overtime ?? 0;
                $total_earnings = $basic + $incentives + $overtime;
              @endphp
              <table class="table table-bordered mb-0">
                <tbody>
                  <tr>
                    <th>Basic Salary</th>
                    <td>₹ {{ number_format($basic, 2) }}</td>
                  </tr>
                  <tr>
                    <th>Incentives</th>
                    <td>₹ {{ number_format($incentives, 2) }}</td>
                  </tr>
                  <tr>
                    <th>Overtime</th>
                    <td>₹ {{ number_format($overtime, 2) }}</td>
                  </tr>
                  <tr class="table-success fw-bold">
                    <th>Total Earnings</th>
                    <td>₹ {{ number_format($total_earnings, 2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- Deductions --}}
        <div class="col-lg-6">
          <div class="card shadow-sm">
            <div class="card-header bg-danger text-white fw-bold">
              Deductions
            </div>
            <div class="card-body p-3">
              @php
                $unpaid_leave_amount = $slip->unpaid_leave_amount ?? 0;
                $late_coming = $slip->late_coming ?? 0;
                $total_deductions = $unpaid_leave_amount + $late_coming;
              @endphp
              <table class="table table-bordered mb-0">
                <tbody>
                  <tr>
                    <th>Unpaid Leave Amount</th>
                    <td>₹ {{ number_format($unpaid_leave_amount, 2) }}</td>
                  </tr>
                  <tr>
                    <th>Late Coming Penalty</th>
                    <td>₹ {{ number_format($late_coming, 2) }}</td>
                  </tr>
                  <tr class="table-danger fw-bold">
                    <th>Total Deductions</th>
                    <td>₹ {{ number_format($total_deductions, 2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      {{-- Net Pay Card --}}
      <div class="card mt-4 shadow-sm">
        <div class="card-body p-3 text-center bg-light">
          @php
            $net_pay = $total_earnings - $total_deductions;
          @endphp
          <h4 class="fw-bold mb-0">Net Pay: <span class="text-success">₹ {{ number_format($net_pay, 2) }}</span></h4>
        </div>
      </div>

      {{-- PDF Preview --}}
      @if($slip->file_path)
        <div class="card mt-4 shadow-sm">
          <div class="card-header fw-bold">
            Salary Slip Preview
          </div>
          <div class="card-body p-0" style="height: 700px;">
            <iframe src="{{ asset('storage/app/public/' . $slip->file_path) }}"
                    style="width: 100%; height: 100%; border: none;"></iframe>
          </div>
        </div>
      @endif

    </div>
  </div>
</div>
@endsection
