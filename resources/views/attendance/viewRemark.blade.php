@extends('layouts.app')

@section('content')
    <div style="padding-top:100px;">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Existing Remarks for {{ $selectedMonth }}</h5>
                                    <form action="{{ route('viewRemarks') }}" method="GET" class="d-flex">
                                        <input type="month" name="month" value="{{ $selectedMonth }}"
                                            class="form-control" required>
                                        <button type="submit" class="btn btn-primary ms-2">Filter</button>
                                    </form>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('attendances') }}">
                                        <button class="btn btn-info">Back</button>
                                    </a>
                                </div>
                                @if ($existingRemarks->count() > 0)
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Remark</th>
                                                        <th>Added By</th>
                                                        <th>Added On</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($existingRemarks as $remark)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $remark->date->format('l, d M Y') }}</td>
                                                            <td>
                                                                <span
                                                                    id="remark-text-{{ $remark->id }}">{{ $remark->description }}</span>
                                                                <textarea id="remark-edit-{{ $remark->id }}" class="form-control" style="display: none;">{{ $remark->description }}</textarea>
                                                            </td>
                                                            <td>{{ $remark->addedBy->full_name }}</td>
                                                            <td>{{ $remark->created_at->format('d M Y H:i') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="card-body">
                                        <p>No remarks found for this month.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
