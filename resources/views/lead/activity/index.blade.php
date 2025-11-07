@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list"
                            type="button" role="tab" aria-controls="list" aria-selected="true">
                            <i class="fas fa-list"></i> Activity List
                        </button>

                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendarTab"
                            type="button" role="tab" aria-controls="calendarTab" aria-selected="false">
                            <i class="fas fa-calendar-alt"></i> Calendar View
                        </button>
                    </li>
                </ul>

                <!-- List Tab -->
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active " id="list" role="tabpanel" aria-labelledby="list-tab">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div>
                                            <h4>Activity List</h4>
                                            <button type="button" id="bulkDeleteBtn"
                                                class="btn btn-danger btn-sm float-end mb-2" style="display:none;">
                                                <i class="fas fa-trash"></i> Delete Selected
                                            </button>
                                        </div class="d-flex justify-content-between align-items-center mb-3">
                                        <table id="datatable"
                                            class="table table-striped table-bordered table-striped dt-responsive nowrap w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><input type="checkbox" id="selectAll"></th>
                                                    <th>ID / Title</th>
                                                    <th>Is Done</th>
                                                    <th>Lead / Type</th>
                                                    <th>Schedule From / Schedule To</th>
                                                    <th>Location</th>
                                                    <th>Description</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($activities as $activity)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="selectItem" name="ids[]"
                                                                value="{{ $activity->id }}">
                                                        </td>
                                                        <td>
                                                            <div><strong>{{ $activity->id }}</strong></div>
                                                            <div>{{ $activity->title }}</div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="toggle-status"
                                                                data-id="{{ $activity->id }}"
                                                                {{ $activity->is_done ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <div>{{ $activity->lead->lead_title ?? 'N/A' }}</div>
                                                            <span
                                                                class="badge bg-primary">{{ ucfirst($activity->activity_type) }}</span>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                {{ \Carbon\Carbon::parse($activity->schedule_from)->format('d M Y h:i A') }}
                                                            </div>
                                                            <div>
                                                                {{ \Carbon\Carbon::parse($activity->schedule_to)->format('d M Y h:i A') }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                {{ $activity->location }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $words = explode(' ', $activity->description);
                                                                $shortDesc =
                                                                    count($words) > 3
                                                                        ? implode(' ', array_slice($words, 0, 3)) .
                                                                            '...'
                                                                        : $activity->description;
                                                            @endphp

                                                            <span class="description" data-bs-toggle="tooltip"
                                                                title="{{ $activity->description }}">
                                                                {{ $shortDesc }}
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <div>
                                                                {{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y h:i A') }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('activity.edit', $activity->id) }}"
                                                                class="btn btn-sm btn-outline-primary me-1">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('activity.destroy', $activity->id) }}"
                                                                method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger delete-btn">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
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


                    <!-- Calendar Tab -->
                    <div class="tab-pane fade  active" id="calendarTab" role="tabpanel" aria-labelledby="calendar-tab">
                        <div class="card mt-3">
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bulk Delete Logic -->
    <script>
        (function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.selectItem');
            const bulkBtn = document.getElementById('bulkDeleteBtn');

            function toggleBulkBtn() {
                const checked = document.querySelectorAll('.selectItem:checked').length;
                bulkBtn.style.display = checked > 0 ? 'inline-block' : 'none';
            }

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('selectItem')) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                    if (!e.target.checked) selectAll.checked = false;
                    toggleBulkBtn();
                }
            });

            bulkBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');
                if (!selected.length) {
                    Swal.fire('Oops!', 'Select at least one activity.', 'warning');
                    return;
                }

                const result = await Swal.fire({
                    title: 'Delete Activities?',
                    text: `Delete ${selected.length} selected activit${selected.length > 1 ? 'ies' : 'y'}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('activity.bulk-delete') }}";
                form.style.display = 'none';

                const token = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: '_token',
                    value: token
                }));

                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                }));

                selected.forEach(cb => {
                    form.appendChild(Object.assign(document.createElement('input'), {
                        type: 'hidden',
                        name: 'ids[]',
                        value: cb.value
                    }));
                });

                document.body.appendChild(form);
                form.submit();
            });

            toggleBulkBtn();
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        let activities = @json($activities);
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
            let events = activities.map(function(activity) {
                return {
                    id: activity.id,
                    title: activity.title,
                    start: activity.schedule_from,
                    end: activity.schedule_to,
                    color: activity.is_done ? '#4CAF50' : '#f39c12',
                    url: "{{ url('/activity') }}/" + activity.id + "/edit"
                };
            });

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                height: 650,
                events: events,
                editable: false,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();
        });
    </script>

    <script>
        $(document).on('change', '.toggle-status', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('activity.toggleDone', ':id') }}".replace(':id', id),
                type: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        Toastify({
                            text: "✅ Activity updated successfully.",
                            duration: 3000,
                            close: true,
                            gravity: "bottom",
                            position: "center",
                            backgroundColor: "#4CAF50",
                            stopOnFocus: true,
                            className: "custom-toast"
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "❌ Something went wrong!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "center",
                        backgroundColor: "#f44336",
                        stopOnFocus: true,
                        className: "custom-toast"
                    }).showToast();
                }
            });
        });
    </script>
@endsection
