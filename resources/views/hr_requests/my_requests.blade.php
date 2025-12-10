@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            {{-- Header --}}
                            <div class="email-header">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">
                                            <i class="fa-solid fa-list-check"></i> My HR Requests
                                        </h4>
                                        <p class="mb-0 opacity-75">Below is a list of your submitted HR requests and their
                                            current status.</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="{{ route('hr.requests.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus"></i> New Request
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Table --}}
                            <div class="card-body" style="margin-left: 20px;">
                                <div class="table-responsive">
                                    <table class="table align-middle table-hover" id="datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Submitted</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($requests as $req)
                                                <tr>
                                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                                    <td>{{ ucfirst($req->type) }}</td>
                                                    <td>{{ $req->subject ?? '-' }}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = match ($req->status) {
                                                                'pending' => 'secondary',
                                                                'in_review' => 'warning',
                                                                'resolved' => 'success',
                                                                'rejected' => 'danger',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge bg-{{ $statusClass }}">
                                                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $req->created_at->format('d M Y, h:i A') }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('hr.requests.show', $req->id) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fa-solid fa-eye"></i> View
                                                        </a>
                                                        @if ($req->status === 'pending')
                                                            <form action="{{ route('hr.requests.destroy', $req->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this request?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger">
                                                                    <i class="fa-solid fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                              
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
