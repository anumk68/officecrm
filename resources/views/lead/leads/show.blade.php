@extends('layouts.app')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="leadTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                            type="button" role="tab" aria-controls="details" aria-selected="true">
                            Lead Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity"
                            type="button" role="tab" aria-controls="activity" aria-selected="false">
                            Activity
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="leadTabsContent">
                    <!-- Lead Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <h2>Lead Details</h2>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $lead->id }}</td>
                            </tr>
                            <tr>
                                <th>Lead Title</th>
                                <td>{{ $lead->lead_title }}</td>
                            </tr>
                            <tr>
                                <th>Contact Name</th>
                                <td>{{ $lead->contactPerson->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Contact Email</th>
                                @php
                                    $emails = is_array($lead->contactPerson->emails)
                                        ? $lead->contactPerson->emails
                                        : json_decode($lead->contactPerson->emails, true);
                                @endphp
                                <td>{{ $emails[0] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Project</th>
                                <td>{{ $lead->leadProduct->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $lead->status }}</td>
                            </tr>
                            <tr>
                                <th>Lead Value</th>
                                <td>{{ $lead->lead_value }}</td>
                            </tr>
                            <tr>
                                <th>Source</th>
                                <td>{{ $lead->source }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>{{ $lead->notes ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $lead->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $lead->updated_at }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary mt-3">Back to Leads</a>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                        <h2>Add Activity</h2>
                        <form id="activityForm">
                            @csrf
                            <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control">
                                <span class="text-danger error-text title_error"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"></textarea>
                                <span class="text-danger error-text description_error"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Participant</label>
                                <select name="participant_id" class="form-control">
                                    @foreach ($participants as $person)
                                        <option value="{{ $person->id }}">{{ $person->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text participant_id_error"></span>
                            </div>

                            <!-- ✅ Activity Type -->
                            <div class="mb-3">
                                <label class="form-label">Activity Type *</label>
                                <select name="activity_type" class="form-control">
                                    <option value="call">Call</option>
                                    <option value="meeting">Meeting</option>
                                </select>
                                <span class="text-danger error-text activity_type_error"></span>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Schedule From *</label>
                                    <input type="datetime-local" name="schedule_from" class="form-control">
                                    <span class="text-danger error-text schedule_from_error"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Schedule To *</label>
                                    <input type="datetime-local" name="schedule_to" class="form-control">
                                    <span class="text-danger error-text schedule_to_error"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control">
                                <span class="text-danger error-text location_error"></span>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Activity</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#activityForm').on('submit', function (e) {
                e.preventDefault();

                // Clear old errors
                $('.error-text').text('');

                $.ajax({
                    url: "{{ route('activity.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: response.success
                        });

                        $('#activityForm')[0].reset();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {
                                $("." + key + "_error").text(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection