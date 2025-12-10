@extends('layouts.app')

@section('title', 'Email Templates')
<style>
    #templateBody b {
        color: #0d6efd;
    }
</style>

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Whatsapp Templates</h4>
                                    {{-- <a href="{{ route('whatapptemplates.create') }}" class="btn btn-light btn-primary">
                                        + Create New Template
                                    </a> --}}
                                </div>

                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Template Name</th>
                                                <th>Status</th>
                                                <th>Category</th>
                                                <th>Language(s)</th>
                                                <th>Created By</th>
                                                <th style="width: 120px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($templates as $template)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $template['display_name'] ?? $template['name'] }}</td>
                                                    <td>
                                                        @if ($template['approval_status'] == 'APPROVED')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning">{{ ucfirst(strtolower($template['approval_status'])) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ ucfirst($template['category'] ?? '-') }}</td>
                                                    <td>{{ $template['language'] ?? '-' }}</td>
                                                    <td>{{ $template['created_by'] ?? 'Interakt Admin' }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-warning btn-sm mb-1"
                                                            data-bs-toggle="modal" data-bs-target="#viewTemplateModal"
                                                            data-template-name="{{ $template['display_name'] ?? $template['name'] }}"
                                                            data-template-body="{{ htmlspecialchars($template['body']) }}">
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No approved templates
                                                        found.</td>
                                                </tr>
                                            @endforelse
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

    <div class="modal fade" id="viewTemplateModal" tabindex="-1" aria-labelledby="viewTemplateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTemplateModalLabel">View Template Body</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="templateName"></h6>
                    <div id="templateBody" class="border rounded p-3"
                        style="background: #f8f9fa; max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('button[data-bs-toggle="modal"]');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-template-name');
                    const body = this.getAttribute('data-template-body');
                    document.getElementById('templateName').textContent = name;
                    document.getElementById('templateBody').innerHTML = body
                        .replace(/\n/g, '<br>')
                        .replace(/\{\{(.*?)\}\}/g,
                            '<strong>{$1}</strong>');
                });
            });
        });
    </script>


@endsection
