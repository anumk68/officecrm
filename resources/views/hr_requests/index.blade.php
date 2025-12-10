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
                                        <i class="fa-solid fa-users-gear"></i> All HR Requests
                                    </h4>
                                    <p class="mb-0 opacity-75">Manage and review all HR-related requests submitted by employees and team leaders.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="card-body" style="margin-left: 20px;">
                            <div class="table-responsive" >
                                <table class="table align-middle table-hover" id="datatable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Type</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $req)
                                            <tr>
                                                <td><strong>{{ $loop->iteration }}</strong></td>
                                                <td>{{ $req->user->full_name ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($req->type) }}</td>
                                                <td>{{ $req->subject ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = match($req->status) {
                                                            'pending' => 'secondary',
                                                            'in_review' => 'warning',
                                                            'resolved' => 'success',
                                                            'rejected' => 'danger',
                                                            default => 'secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $req->created_at->format('d M Y, h:i A') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('hr.requests.show', $req->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-folder-open"></i> Open
                                                    </a>
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
