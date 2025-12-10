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
                                        <i class="fa-solid fa-file-lines"></i> Company Policies & Documents
                                    </h4>
                                    <p class="mb-0 opacity-75">Uploaded by HR — visible to all employees.</p>
                                </div>

                                @if(auth()->user() && in_array(auth()->user()->role, ['hr','manager']))
                                <div class="col-md-6 text-end">
                                    <a href="{{ route('policies.create') }}" class="btn btn-primary">
                                        <i class="fa fa-upload"></i> Upload
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="card-body" style="margin-left: 20px;">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table align-middle table-hover" id="datatable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Uploaded</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($policies as $policy)
                                            <tr>
                                                <td><strong>{{ $policy->id }}</strong></td>

                                                <td>
                                                    <a href="{{ route('policies.show', $policy->id) }}">
                                                        {{ $policy->title }}
                                                    </a>
                                                </td>

                                                <td>{{ $policy->type ?? '-' }}</td>

                                                <td>{{ Str::limit($policy->description, 80) }}</td>

                                                <td>{{ $policy->created_at->format('d M Y') }}</td>

                                                <td class="text-center">
                                                    <a href="{{ route('policies.download', $policy->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Download">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>

                                                    <a href="{{ route('policies.show', $policy->id) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    @if(auth()->user() && in_array(auth()->user()->role, ['hr','manager']))
                                                        <form action="{{ route('policies.destroy', $policy->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Delete this file?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No policies uploaded yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                {{ $policies->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
