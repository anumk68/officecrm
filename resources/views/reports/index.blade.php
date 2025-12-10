@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .chart-box {
            flex: 1;
            min-width: 300px;
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .chart-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .chart-title {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 17px;
        }

        /* equal chart height */
        .chart-box canvas {
            max-height: 260px !important;
        }



        th {
            color: black;
        }

        /* Better table UI */
        #task-report-table th,
        #task-report-table td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
            font-size: 14px;
        }

        /* Task column wrapping */
        #task-report-table td:nth-child(2) {
            white-space: normal !important;
            max-width: 300px;
        }

        /* Badge clean design */
        #task-report-table .badge {
            padding: 6px 10px;
            font-size: 12px;
        }

      @media (max-width: 768px) {

    #task-report-table {
        border-collapse: separate !important;
        border-spacing: 0 12px !important;
    }

    /* Hide table header but keep DOM */
    #task-report-table thead {
        display: none !important;
    }

    #task-report-table tr {
        display: block !important;
        background: #fff;
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    #task-report-table td {
        display: flex !important;
        justify-content: space-between !important;
        padding: 10px 8px !important;
        border: none !important;
        border-bottom: 1px dashed #ddd !important;
    }

    #task-report-table td:last-child {
        border-bottom: none !important;
    }

    #task-report-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #333;
        flex: 1;
        text-align: left;
    }

    /* Values */
    #task-report-table td span,
    #task-report-table td button {
        flex: 1;
        text-align: right !important;
    }
}

    </style>


<style>
    /* Improve label spacing for mobile */
    @media (max-width: 768px) {
        #task-report-table td {
            display: flex;
            justify-content: space-between;
        }
        #task-report-table td::before {
            flex: 1;
            font-weight: bold;
            color: #000;
        }
        #task-report-table td span,
        #task-report-table td button {
            flex: 1;
            text-align: right;
        }
    }
</style>

    <div class="main-content">
        <div class="page-content">
            <div class="container mt-4">
                <div class="email-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 ">
                            <h4 class="mb-0"><i class="fa-solid fa-sleigh"></i> 📊 Task Performance Reporting </h4>
                            <p class="mb-0 opacity-75"> </p>
                        </div>

                    </div>
                </div>

                {{-- ================= FILTER SECTION ================= --}}
                <div class="chart-card">
                    <div class="row g-3">
                        {{-- USER SELECT --}}
                        <div class="col-md-4">
                            <label class="fw-bold mb-1">Select User</label>
                            <select id="user_id" class="form-control select2" onchange="loadReport()">
                                <option value=""> </option>

                                @if (Auth::user()->role == 'team_member')
                                    <option value="{{ Auth::user()->id }}">
                                        {{ Auth::user()->full_name . ' (' . Auth::user()->position . ')' }}
                                    </option>
                                @else
                                    @foreach (\App\Models\User::where('status', 'active')->orderBy('full_name', 'asc')->get() as $u)
                                        <option value="{{ $u->id }}">
                                            {{ $u->full_name . ' (' . $u->position . ')' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- RANGE FILTER --}}
                        <div class="col-md-4">
                            <label class="fw-bold mb-1">Report Range</label>
                            <select id="range_filter" class="form-control" onchange="onFilterChange()">
                                <option value="daily">Today</option>
                                <option value="weekly">This Week</option>
                                <option value="monthly" selected>This Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>

                        <div class="col-md-4" id="customRangeBox" style="display:none;">
                            <p id="dateError" class="text-danger fw-bold mt-1" style="display:none;"></p>
                            <label class="fw-bold mb-1">Custom Range</label>
                            <div class="d-flex gap-2">
                                <input type="date" id="start_date" class="form-control" onchange="validateDates()">
                                <input type="date" id="end_date" class="form-control" onchange="validateDates()">
                            </div>
                            <button class="btn btn-primary btn-sm mt-2 w-100" onclick="loadReport()">
                                <i class="fa fa-filter"></i> Apply Filter
                            </button>
                        </div>
                    </div>
                </div>

                {{-- LOADER --}}
                <div id="loader" style="display: none; text-align: center; padding: 20px;">
                    <span>Loading report...</span>
                    <div class="spinner-border text-primary" role="status" style="margin-top: 10px;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                {{-- ================= TASK TABLE ================= --}}
                <div class="chart-card">
                    <h4 class="fw-bold mb-3">
                        <i class="fa-solid fa-list-check text-primary"></i> User Task Details
                    </h4>
                    <table id="task-report-table" class="table  table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Deadline</th>
                                <th>Total Time Spent</th>
                                <th>Extra / On-Time</th>
                                <th>View</th>
                            </tr>
                        </thead>

                        <tbody id="taskBody">
                        </tbody>
                    </table>

                </div>

                <div id="chartsWrapper">
                    <div class="chart-row">
                        <div class="chart-box">
                            <h4 class="chart-title"><i class="fa-solid fa-chart-bar"></i> Status Comparison</h4>
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="chart-box">
                            <h4 class="chart-title"><i class="fa-solid fa-chart-pie"></i> Status Overview</h4>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MODAL ================= --}}
        <div class="modal fade" id="taskModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-file-lines"></i> Task Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Task</th>
                                <td id="m_task"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="m_status"></td>
                            </tr>
                            <tr>
                                <th>Assigned At</th>
                                <td id="m_assigned"></td>
                            </tr>
                            <tr>
                                <th>Deadline</th>
                                <td id="m_deadline"></td>
                            </tr>

                            <tr>
                                <th>Total Time Spent</th>
                                <td id="m_spent"></td>
                            </tr>
                            <tr>
                                <th>Extra Time</th>
                                <td id="m_extra"></td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        let pieChart, barChart, lineChart;

        function onFilterChange() {
            const filter = document.getElementById('range_filter').value;
            const customBox = document.getElementById('customRangeBox');

            if (filter === 'custom') {
                customBox.style.display = 'block';
            } else {
                customBox.style.display = 'none';
                loadReport();
            }
        }

        function formatDate(d) {
            if (!d) return '-';
            const date = new Date(d);
            if (isNaN(date.getTime())) return '-';

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            let hours = date.getHours();
            let minutes = String(date.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            hours = String(hours).padStart(2, '0');
            return `${day}-${month}-${year} ${hours}:${minutes} ${ampm}`;
        }

        function hideCharts() {
            document.getElementById('chartsWrapper').style.display = 'none';
            if (pieChart) pieChart.destroy();
            if (barChart) barChart.destroy();
            if (lineChart) lineChart.destroy();
        }

        function showCharts() {
            document.getElementById('chartsWrapper').style.display = 'block';
        }

        function formatExtraStatus(task) {


            if (!task.deadline) return `<span class='text-muted'>-</span>`;

            const [h, m, s] = task.deadline.split(":").map(Number);
            const deadlineSeconds = (h * 3600) + (m * 60) + s;

            const workSeconds = Math.abs(parseInt(task.total_work_seconds ?? 0));

            const extra = workSeconds - deadlineSeconds;

            if (extra > 0) {
                return `<span class="text-danger fw-bold">${formatSecondsToHMS(extra)} extra time</span>`;
            }

            return `<span class="text-success fw-bold">On Time</span>`;
        }


        function formatHMS(timeString) {
            if (!timeString) return "-";

            const parts = timeString.split(":");
            if (parts.length !== 3) return timeString;

            let [h, m, s] = parts;

            return `${h}h : ${m}m : ${s}s`;
        }

        function formatSecondsToHMS(seconds) {
            if (seconds === null || seconds === undefined) return "-";

            // Convert negative → positive
            seconds = Math.abs(seconds);

            let h = Math.floor(seconds / 3600);
            let m = Math.floor((seconds % 3600) / 60);
            let s = seconds % 60;

            // Format 2 digits
            h = h.toString().padStart(2, '0');
            m = m.toString().padStart(2, '0');
            s = s.toString().padStart(2, '0');

            return `${h}h : ${m}m : ${s}s`;
        }

        function loadReport() {
            const user_id = document.getElementById("user_id").value;
            const filter = document.getElementById("range_filter").value;
            const start = document.getElementById("start_date").value;
            const end = document.getElementById("end_date").value;

            if (!user_id) return;

            if (filter === 'custom') {
                if (!start || !end) return;
            }

            const TB = document.getElementById("taskBody");
            TB.innerHTML = "";
            document.getElementById("loader").style.display = "block";

            let url = "{{ route('task.userReport') }}" + `?user_id=${user_id}&filter=${filter}`;
            if (filter === 'custom') {
                url += `&start_date=${start}&end_date=${end}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("loader").style.display = "none";

                    if (!data.tasks || data.tasks.length === 0) {
                        TB.innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center text-danger fw-bold">
                                    ❗ No tasks found for this selection
                                </td>
                            </tr>`;
                        hideCharts();
                        return;
                    }

                    showCharts();

                    data.tasks.forEach((t, i) => {
                        TB.innerHTML += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${t.task}</td>
                                <td>
                                    <span class="badge bg-${t.status_color}">
                                        ${t.status}
                                    </span>
                                </td>
                                <td>${formatDate(t.assigned_at)}</td>
                              <td>${formatHMS(t.deadline)}</td>

                              
                              <td><span class="fw-bold">${formatSecondsToHMS(t.total_work_seconds)}</span></td>

                                <td>${formatExtraStatus(t)}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick='viewTask(${JSON.stringify(t)})'>
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    applyLabelsAfterAjax();

                    loadCharts(data);
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById("loader").style.display = "none";
                    hideCharts();
                    document.getElementById("taskBody").innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-danger fw-bold">
                                Error loading report.
                            </td>
                        </tr>`;
                });
        }

        function loadCharts(data) {
            if (pieChart) pieChart.destroy();
            if (barChart) barChart.destroy();
            if (lineChart) lineChart.destroy();

            const counts = data.counts || {
                Completed: 0,
                Pending: 0,
                "In Progress": 0
            };

            // PIE
            pieChart = new Chart(document.getElementById("pieChart"), {
                type: "pie",
                data: {
                    labels: ["Completed", "Pending", "In Progress"],
                    datasets: [{
                        data: [
                            counts.Completed || 0,
                            counts.Pending || 0,
                            counts["In Progress"] || 0
                        ],
                        backgroundColor: ["#28a745", "#ffc107", "#0dcaf0"]
                    }]
                }
            });

            // BAR
            barChart = new Chart(document.getElementById("barChart"), {
                type: "bar",
                data: {
                    labels: ["Completed", "Pending", "In Progress"],
                    datasets: [{
                        label: "Task Count",
                        data: [
                            counts.Completed || 0,
                            counts.Pending || 0,
                            counts["In Progress"] || 0
                        ],
                        backgroundColor: ["#198754", "#ECB100", "#0d6efd"],
                        borderRadius: 8
                    }]
                }
            });

            // LINE
            lineChart = new Chart(document.getElementById("lineChart"), {
                type: "line",
                data: {
                    labels: data.dates || [],
                    datasets: [{
                        label: "Completed Tasks",
                        data: data.line_counts || [],
                        borderColor: "#198754",
                        borderWidth: 3,
                        tension: 0.4
                    }]
                }
            });
        }

        function viewTask(t) {

            document.getElementById("m_task").innerHTML = t.task;

            document.getElementById("m_status").innerHTML =
                `<span class="badge bg-${t.status_color}">${t.status}</span>`;

            document.getElementById("m_assigned").innerHTML = formatDate(t.assigned_at);
            document.getElementById("m_deadline").innerHTML =
                t.deadline ? formatHMS(t.deadline) : "-";


            document.getElementById("m_spent").innerHTML =
                formatSecondsToHMS(t.total_work_seconds);

            const extraHTML = formatExtraStatus(t);
            document.getElementById("m_extra").innerHTML = extraHTML;

            let modal = new bootstrap.Modal(document.getElementById('taskModal'));
            modal.show();
        }

        function validateDates() {
            let start = document.getElementById('start_date').value;
            let end = document.getElementById('end_date').value;

            if (!start) return;

            document.getElementById('end_date').setAttribute('min', start);
            if (end && end < start) {
                document.getElementById('end_date').value = start;
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                placeholder: "Search employee...",
                allowClear: true,
                width: "100%"

            });
        });
    </script>
<script>
function applyResponsiveLabels() {
    let table = document.getElementById("task-report-table");
    if (!table) return;

    let headers = [];

    table.querySelectorAll("thead th").forEach(function(th, index) {
        headers[index] = th.innerText.trim();
    });

    table.querySelectorAll("tbody tr").forEach(function(row) {
        row.querySelectorAll("td").forEach(function(td, index) {
            td.setAttribute("data-label", headers[index] ?? "");
        });
    });
}

// Run after AJAX loads
function applyLabelsAfterAjax() {
    setTimeout(() => applyResponsiveLabels(), 200);
}

// Detect new rows inserted by AJAX
const taskBody = document.getElementById("taskBody");

const observer = new MutationObserver(() => {
    applyLabelsAfterAjax();
});

observer.observe(taskBody, {
    childList: true,
    subtree: true
});
</script>




@endsection
