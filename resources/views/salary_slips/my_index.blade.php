@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-header d-flex justify-content-between" style="background-color: #4465DC; color: white">
                        <h5 class="m-0">My Salary Slips</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle" id="datatable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Month</th>
                                        <th>Employee</th>
                                        <th>Designation</th>
                                        <th>Joining Date</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($slips as $index => $slip)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                            {{-- Month Format --}}
                                            <td>{{ \Carbon\Carbon::parse($slip->month)->format('F Y') }}</td>

                                            {{-- Employee Name --}}
                                            <td>{{ $slip->employee_name }}</td>

                                            {{-- Designation --}}
                                            <td>{{ $slip->designation }}</td>

                                            {{-- Joining Date --}}
                                            <td>{{ $slip->joining_date?->format('d M, Y') }}</td>

                                            <td>
                                                <a href="{{ route('salary_slips.my_show', $slip->id) }}"
                                                    class="btn btn-sm btn-info">View</a>

                                                <a href="{{ route('salary_slips.download', $slip->id) }}"
                                                    class="btn btn-sm btn-primary">Download</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No salary slips found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $slips->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
