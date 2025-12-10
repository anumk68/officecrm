@php
    /* ========== Helper Formats ========== */

    function secondsToHM($sec)
    {
        $sign = $sec < 0 ? '-' : '';
        $sec = abs($sec);
        $h = floor($sec / 3600);
        $m = floor(($sec % 3600) / 60);
        return $sign . sprintf('%02dh %02dm', $h, $m);
    }

    function hms($sec)
    {
        $h = floor($sec / 3600);
        $m = floor(($sec % 3600) / 60);
        return "{$h}h {$m}m";
    }

    function dayClass($seconds)
    {
        if ($seconds >= 7 * 3600) {
            return 'bg-success text-white';
        }
        if ($seconds < 3 * 3600) {
            return 'bg-danger text-white';
        }
        return 'bg-warning text-dark';
    }
@endphp

<style>
    .accordion-button {
        font-size: 16px;
    }

    .accordion-item {
        border-radius: 8px;
        overflow: hidden;
    }

    .badge {
        font-size: 13px;
    }
</style>

<div class="accordion" id="weekAccordion">

    @foreach ($final as $wkIndex => $week)
        @php
            $weekYear = \Carbon\Carbon::parse($week['week_start'])->format('Y');
            $currentYear = now()->format('Y');
        @endphp

        <div class="accordion-item mb-3 shadow-sm">
            <h2 class="accordion-header" id="weekHeading{{ $wkIndex }}">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#weekCollapse{{ $wkIndex }}">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <div>
                            @if ($weekYear != $currentYear)
                                <span class="text-muted me-2">{{ $weekYear }} -</span>
                            @endif

                            {{ $week['label'] }}
                        </div>

                        <span class="badge bg-primary">
                            Total: {{ hms($week['total_seconds']) }}
                        </span>
                    </div>
                </button>
            </h2>

            <div id="weekCollapse{{ $wkIndex }}" class="accordion-collapse collapse"
                data-bs-parent="#weekAccordion">
                <div class="accordion-body">
                    <div class="accordion" id="dayAccordion{{ $wkIndex }}">
                        @foreach ($week['days'] as $dayIndex => $day)
                            <div class="accordion-item mb-2">

                                <h2 class="accordion-header" id="dayHead{{ $wkIndex }}_{{ $dayIndex }}">
                                    <button class="accordion-button collapsed {{ dayClass($day['total_seconds']) }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#dayCollapse{{ $wkIndex }}_{{ $dayIndex }}">

                                        <div class="w-100 d-flex justify-content-between align-items-center">

                                            <div>
                                                {{ \Carbon\Carbon::parse($day['date'])->format('l, d M') }}
                                            </div>

                                            <span class="badge bg-dark">
                                                {{ hms($day['total_seconds']) }}
                                            </span>

                                        </div>

                                    </button>

                                </h2>

                                <div id="dayCollapse{{ $wkIndex }}_{{ $dayIndex }}"
                                    class="accordion-collapse collapse">
                                    <div class="accordion-body">

                                        <table id="datatable" class="table table-bordered table-striped table-sm shadow-sm">
                                            <thead class="table-primary">
                                                <tr class="text-center">
                                                    <th>Project</th>
                                                    <th>Task</th>
                                                    <th>Start</th>
                                                    <th>End</th>
                                                    <th>Deadline</th>
                                                    <th>Duration</th>
                                                    <th>Extra Time</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($day['logs'] as $log)
                                                    @php
                                                        /* Duration */
                                                        $duration =
                                                            strtotime($log->end_time ?? now()) -
                                                            strtotime($log->start_time);

                                                        /* Remaining (deadline) from DB */
                                                        $rem = (int) $log->start_remaining_seconds;

                                                        /* ========== EXTRA TIME FINAL LOGIC ========== */
                                                        if ($rem < 0) {
                                                            // already overtime → entire duration is extra
                                                            $extraSec = $duration;
                                                        } else {
                                                            // normal → duration - remaining
                                                            $extraSec = $duration - $rem;
                                                        }

                                                        $extra = $extraSec > 0 ? secondsToHM($extraSec) : '-';
                                                    @endphp

                                                    <tr class="text-center align-middle">

                                                        <td class="fw-semibold">{{ $log->project_name }}</td>

                                                        <td>{{ $log->task_title }}</td>

                                                        <td>{{ \Carbon\Carbon::parse($log->start_time)->format('d M h:i A') }}
                                                        </td>

                                                        <td>
                                                            {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('d M h:i A') : '-' }}
                                                        </td>

                                                        {{-- Deadline (converted seconds) --}}
                                                        <td>
                                                            @php
                                                                $rem = (int) $log->start_remaining_seconds;
                                                                $isNegative = $rem < 0;
                                                            @endphp

                                                            <span
                                                                class="badge {{ $isNegative ? 'bg-danger' : 'bg-info' }}">
                                                                {{ secondsToHM($rem) }}
                                                            </span>
                                                        </td>


                                                        {{-- Duration --}}
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                {{ hms($duration) }}
                                                            </span>
                                                        </td>

                                                        {{-- Extra Time --}}
                                                        {{-- Extra Time --}}
                                                        <td>
                                                            @if ($extra !== '-')
                                                                <span
                                                                    class="badge bg-danger">{{ $extra }}</span>

                                                                {{-- STATUS --}}
                                                                @php
                                                                    $status = $log->extra_time_status ?? 'Pending';
                                                                @endphp
                                                                @if ($log->extra_time_reason == null)
                                                                    {{-- ADD / EDIT REASON BUTTON --}}
                                                                    <button
                                                                        class="btn btn-sm btn-primary ms-2 add-reason-btn"
                                                                        data-log="{{ $log->id }}"
                                                                        data-extra="{{ $extra }}"
                                                                        data-reason="{{ $log->extra_time_reason }}"
                                                                        data-remarks="{{ $log->remarks }}">
                                                                        {{ $log->extra_time_reason ? 'Edit Reason' : 'Add Reason' }}
                                                                    </button>
                                                                @else
                                                                    <span
                                                                        class="badge 
                                                                        @if ($status == 'Approved') bg-success 
                                                                        @elseif($status == 'Rejected') bg-dark 
                                                                        @else bg-warning @endif">
                                                                        {{ $status }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-success">No Extra</span>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
    @endforeach

</div>
<!-- Extra Time Reason Modal -->
<div class="modal fade" id="extraReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="extraReasonForm">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Extra Time Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="log_id" id="reason_log_id">
                    <input type="hidden" name="extra_time" id="reason_extra">

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" name="extra_time_reason" id="reason_text" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" id="reason_remarks" class="form-control" rows="3"></textarea>
                    </div>

                    <input type="hidden" name="extra_time_status" value="Pending">

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // OPEN MODAL
        document.querySelectorAll(".add-reason-btn").forEach(btn => {
            btn.addEventListener("click", function() {

                document.getElementById("reason_log_id").value = this.dataset.log;
                document.getElementById("reason_extra").value = this.dataset.extra;

                document.getElementById("reason_text").value = this.dataset.reason ?? "";
                document.getElementById("reason_remarks").value = this.dataset.remarks ?? "";

                var modal = new bootstrap.Modal(document.getElementById("extraReasonModal"));
                modal.show();
            });
        });

        // SAVE REASON
        document.getElementById("extraReasonForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let form = new FormData(this);

            fetch("{{ route('extra.reason.save') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: form
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "ok") {
                        alert("Reason Saved!");
                        location.reload();
                    } else {
                        alert("Error saving reason");
                    }
                })
                .catch(() => alert("Server error"));
        });

    });
</script>

{{-- <script>
    let isFiltering = false;

    /* Auto Refresh (Optional) */
    setInterval(() => {
        if (!isFiltering) {
            $.get("{{ url('/get-task-logs') }}", res => {
                $("#task-log-table").html(res.html);
            });
        }
    }, 200);
</script> --}}
