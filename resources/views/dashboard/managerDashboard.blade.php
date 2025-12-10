@extends('layouts.app')

@section('content')
    <style>
        .stats-card p {
            color: #212529;
        }
    </style>
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Stats Cards -->
                    @if (auth()->user()->role == 'manager')
                        <div class="row g-3">
                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('employees.index') }}">
                                        <div class="stats-icon icon-blue">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Employees</p>
                                        <p class="stats-value">{{ $stats['total_employees'] }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('employees.index') }}">
                                        <div class="stats-icon icon-green">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Team Leaders</p>
                                        <p class="stats-value">{{ $stats['total_team_leaders'] }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('projects') }}">
                                        <div class="stats-icon icon-purple">
                                            <i class="bi bi-kanban-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Projects</p>
                                        <p class="stats-value">{{ $stats['total_projects'] }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('projects') }}">
                                        <div class="stats-icon icon-orange">
                                            <i class="bi bi-check2-circle"></i>
                                        </div>
                                        <p class="stats-title">Completed Projects</p>
                                        <p class="stats-value">{{ $stats['completed_projects'] }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Project Chart -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Monthly Project Progress</h4>
                                        <div id="chartContainer" style="width: 100%; height: 60vh; min-height: 400px;">
                                            <canvas id="projectChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Projects Progress (Line Chart)</h4>
                                        <div style="width: 100%; height: 60vh; min-height: 400px;">
                                            <canvas id="progressBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->role == 'team_leader')
                        <div class="row g-3">
                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('view.employees.attendance', Auth::user()->id) }}">
                                        <div class="stats-icon icon-blue">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Presents</p>
                                        <p class="stats-value">{{ $stats['total_present'] }}</p>
                                        <p class="stats-sub">Current Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('view.employees.attendance', Auth::user()->id) }}">
                                        <div class="stats-icon icon-red">
                                            <i class="bi bi-person-x-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Absents</p>
                                        <p class="stats-value">{{ $stats['total_absent'] }}</p>
                                        <p class="stats-sub">Current Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('leaves') }}">
                                        <div class="stats-icon icon-green">
                                            <i class="bi bi-calendar-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Remaining Leaves</p>
                                        <p class="stats-value">{{ $stats['remaining_leaves'] }}</p>
                                        <p class="stats-sub">This Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('projects') }}">
                                        <div class="stats-icon icon-orange">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <p class="stats-title">Pending Projects</p>
                                        <p class="stats-value">{{ $stats['pending_projects'] }}</p>
                                        <p class="stats-sub">Pending</p>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <!-- Projects Section -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recent Projects</h4>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Project</th>
                                                        <th>Deadline</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($projects as $project)
                                                        <tr>
                                                            <td>{{ $project->project_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : 'warning') }}">{{ $project->status }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Project Progress</h4>
                                        <div style="width: 100%; height: 50vh; min-height: 400px;">
                                            <canvas id="memberProgressPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->role == 'team_member')
                        <div class="row g-3">
                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('view.employees.attendance', Auth::user()->id) }}">
                                        <div class="stats-icon icon-blue">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Presents</p>
                                        <p class="stats-value">{{ $stats['total_present'] }}</p>
                                        <p class="stats-sub">Current Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('view.employees.attendance', Auth::user()->id) }}">
                                        <div class="stats-icon icon-red">
                                            <i class="bi bi-person-x-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Absents</p>
                                        <p class="stats-value">{{ $stats['total_absent'] }}</p>
                                        <p class="stats-sub">Current Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('leaves') }}">
                                        <div class="stats-icon icon-green">
                                            <i class="bi bi-calendar-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Remaining Leaves</p>
                                        <p class="stats-value">{{ $stats['remaining_leaves'] }}</p>
                                        <p class="stats-sub">This Month</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('projects') }}">
                                        <div class="stats-icon icon-orange">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <p class="stats-title">Pending Projects</p>
                                        <p class="stats-value">{{ $stats['pending_projects'] }}</p>
                                        <p class="stats-sub">Pending</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Projects -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recent Projects</h4>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Project</th>
                                                        <th>Deadline</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($projects as $project)
                                                        <tr>
                                                            <td>{{ $project->project_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : 'warning') }}">
                                                                    {{ $project->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Project Progress</h4>
                                        <div style="width: 100%; height: 50vh; min-height: 400px;">
                                            <canvas id="memberProgressPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                    @endif

                    @if (auth()->user()->role == 'hr')
                        <div class="row g-3">
                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('employees.index') }}">
                                        <div class="stats-icon icon-blue">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <p class="stats-title">Total Employees</p>
                                        <p class="stats-value">{{ $totalEmployee }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('employees.index') }}">
                                        <div class="stats-icon icon-green">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Team Leaders</p>
                                        <p class="stats-value">{{ $totalteam_leader }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('leaves') }}">
                                        <div class="stats-icon icon-orange">
                                            <i class="bi bi-calendar-x-fill"></i>
                                        </div>
                                        <p class="stats-title">Today Leaves</p>
                                        <p class="stats-value">{{ $today_leaves }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <a href="{{ route('forManagerAttendance') }}">
                                        <div class="stats-icon icon-purple">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <p class="stats-title">Today Presents</p>
                                        <p class="stats-value">{{ $today_persent_user }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <style>

</style>
                       <div class="card mt-4 attendance-chart">

    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center attendance-header">
        <h5 class="mb-0">📊 Daily Attendance Overview</h5>

        <div class="d-flex gap-2 attendance-filters">
            <select id="selectMonth" class="form-select" style="width: 120px;">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select id="selectYear" class="form-select" style="width: 120px;">
                @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <div class="card-body ">
        <canvas id="dailyAttendanceChart" height="130"></canvas>
    </div>
</div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <script>
                            let dailyChart;

                            function loadDailyChart(year, month) {
                                fetch(`{{ route('hr.daily.attendance') }}?year=${year}&month=${month}`)
                                    .then(res => res.json())
                                    .then(data => {

                                        let ctx = document.getElementById("dailyAttendanceChart").getContext("2d");

                                        if (dailyChart) dailyChart.destroy();

                                        dailyChart = new Chart(ctx, {
                                            type: "line",
                                            data: {
                                                labels: data.days,
                                                month: data.month, // store for tooltip
                                                year: data.year,
                                                totalEmployees: data.totalEmployees, // store total
                                                datasets: [{
                                                    label: `Daily Attendance`,
                                                    data: data.data,
                                                    borderColor: "#0d6efd",
                                                    backgroundColor: "rgba(13,110,253,0.2)",
                                                    borderWidth: 3,
                                                    pointRadius: 4,
                                                    fill: true,
                                                    tension: 0.3
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        display: true
                                                    },

                                                    tooltip: {
                                                        callbacks: {

                                                            // TOP TITLE -> full date
                                                            title: function(context) {
                                                                let day = context[0].label;
                                                                let m = context[0].chart.data.month;
                                                                let y = context[0].chart.data.year;

                                                                day = String(day).padStart(2, '0');
                                                                m = String(m).padStart(2, '0');

                                                                return `Date: ${day}-${m}-${y}`;
                                                            },

                                                            // VALUE LINE -> Present + Absent
                                                            label: function(context) {

                                                                let present = context.parsed.y;
                                                                let total = context.chart.data.totalEmployees;
                                                                let absent = total - present;

                                                                return [
                                                                    `Present: ${present} Employees`,
                                                                    `Absent: ${absent} Employees`
                                                                ];
                                                            }
                                                        }
                                                    }
                                                },

                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        max: data.totalEmployees,
                                                        title: {
                                                            display: true,
                                                            text: "Total Employees  ", // LEFT SIDE LABEL
                                                            font: {
                                                                size: 14,
                                                                weight: "bold"
                                                            }
                                                        },
                                                        ticks: {
                                                            stepSize: 1,
                                                            autoSkip: false
                                                        }
                                                    },
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: "Days of Month", // BOTTOM LABEL
                                                            font: {
                                                                size: 14,
                                                                weight: "bold"
                                                            }
                                                        }
                                                    }
                                                }


                                            }
                                        });
                                    });
                            }

                            // Load current month by default
                            loadDailyChart(new Date().getFullYear(), new Date().getMonth() + 1);

                            // On month change → reload
                            document.getElementById("selectMonth").addEventListener("change", function() {
                                loadDailyChart(
                                    document.getElementById("selectYear").value,
                                    this.value
                                );
                            });
                        </script>
                    @endif


                    @if (Auth::user()->role == 'sales')
                        <style>
                            .dash-box {
                                border-radius: 18px;
                                padding: 18px 22px;
                                transition: 0.2s ease-in-out;
                                min-height: 120px;
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                            }

                            .dash-box:hover {
                                transform: translateY(-4px);
                                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
                            }

                            .dash-icon {
                                font-size: 26px;
                                opacity: 0.9;
                            }

                            .dash-title {
                                font-size: 14px;
                                font-weight: 600;
                                margin-top: 5px;
                            }

                            .dash-value {
                                font-size: 32px;
                                font-weight: 700;
                                margin-top: 5px;
                            }

                            /* Cards responsive */
                            @media (max-width: 768px) {
                                .col-md-2.col-8 {
                                    width: 100% !important;
                                }
                            }

                            /* Chart container responsive */
                            .chart-box {
                                width: 100%;
                                height: auto;
                            }

                            canvas {
                                width: 100% !important;
                                height: auto !important;
                            }
                        </style>

                        <div class="row g-3">
                            <div class="col-xl col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="dash-box" style="background:#ffffff; border:1px solid #ddd;">
                                    <i class="fa-solid fa-clock dash-icon text-dark"></i>
                                    <div class="dash-title text-dark">Follow-Up</div>
                                    <div class="dash-value text-dark">{{ $leadCounts['followup'] }}</div>
                                </div>
                            </div>

                            <div class="col-xl col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="dash-box text-white" style="background:#343a40;">
                                    <i class="fa-solid fa-user-xmark dash-icon"></i>
                                    <div class="dash-title">Fake Leads</div>
                                    <div class="dash-value">{{ $leadCounts['fake'] }}</div>
                                </div>
                            </div>

                            <div class="col-xl col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="dash-box text-white" style="background:#dc3545;">
                                    <i class="fa-solid fa-ban dash-icon"></i>
                                    <div class="dash-title">Blacklisted</div>
                                    <div class="dash-value">{{ $leadCounts['blacklist'] }}</div>
                                </div>
                            </div>

                            <div class="col-xl col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="dash-box text-white" style="background:#fd7e14;">
                                    <i class="fa-solid fa-handshake dash-icon"></i>
                                    <div class="dash-title">Interested</div>
                                    <div class="dash-value">{{ $leadCounts['interested'] }}</div>
                                </div>
                            </div>

                            <div class="col-xl col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="dash-box text-white" style="background:#28a745;">
                                    <i class="fa-solid fa-check-circle dash-icon"></i>
                                    <div class="dash-title">Converted</div>
                                    <div class="dash-value">{{ $leadCounts['converted'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-7 col-12 mb-4">
                                <h6>Lead Trend Overview (Premium Bar Chart)</h6>
                                <div class="chart-box">
                                    <canvas id="chartLeadBar"></canvas>
                                </div>
                            </div>

                            <div class="col-md-5 col-12 mb-4">
                                <h6>Overall Lead Distribution</h6>
                                <div class="chart-box">
                                    <canvas id="chartLeadPie"></canvas>
                                </div>
                            </div>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

                        <script>
                            Chart.defaults.font.size = 14;
                            Chart.defaults.responsive = true;
                            Chart.defaults.maintainAspectRatio = false;

                            const fake = {{ $leadCounts['fake'] }};
                            const black = {{ $leadCounts['blacklist'] }};
                            const interested = {{ $leadCounts['interested'] }};
                            const converted = {{ $leadCounts['converted'] }};
                            const followUp = {{ $leadCounts['followup'] }};

                            const total = fake + black + interested + converted + followUp;
                            const percent = (v) => ((v / total) * 100).toFixed(1);

                            // ================= PIE CHART ==================
                            new Chart(document.getElementById("chartLeadPie"), {
                                type: "pie",
                                data: {
                                    labels: ["Fake", "Blacklisted", "Interested", "Converted", "Follow-Up"],
                                    datasets: [{
                                        data: [fake, black, interested, converted, followUp],

                                        backgroundColor: [
                                            "#343a40", // fake
                                            "#dc3545", // blacklisted
                                            "#fd7e14", // interested
                                            "#28a745", // converted
                                            "#ffffff" // white followup
                                        ],

                                        borderColor: [
                                            "#343a40",
                                            "#dc3545",
                                            "#fd7e14",
                                            "#28a745",
                                            "#000" // white → black border
                                        ],
                                        borderWidth: 2
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            position: "bottom"
                                        }
                                    }
                                }
                            });

                            // ================= BAR CHART ==================
                            new Chart(document.getElementById("chartLeadBar"), {
                                type: "bar",
                                plugins: [ChartDataLabels],
                                data: {
                                    labels: ["Fake", "Blacklisted", "Interested", "Converted", "Follow-Up"],
                                    datasets: [{
                                        data: [
                                            percent(fake),
                                            percent(black),
                                            percent(interested),
                                            percent(converted),
                                            percent(followUp)
                                        ],

                                        backgroundColor: [
                                            "rgba(52,58,64,0.9)", // fake
                                            "rgba(220,53,69,0.9)", // blacklisted
                                            "rgba(253,126,20,0.9)", // interested
                                            "rgba(40,167,69,0.9)", // converted
                                            "rgba(255,255,255,0.9)" // follow-up (white)
                                        ],

                                        borderColor: [
                                            "#343a40", // fake
                                            "#dc3545", // blacklisted
                                            "#fd7e14", // interested
                                            "#28a745", // converted
                                            "#000" // follow-up (white → black border)
                                        ],

                                        borderWidth: 2,
                                        borderRadius: 20
                                    }]
                                },

                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 100,
                                            ticks: {
                                                callback: v => v + "%"
                                            }
                                        }
                                    },

                                    plugins: {
                                        legend: {
                                            display: false
                                        },

                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => {
                                                    const counts = [fake, black, interested, converted, followUp];
                                                    return `${counts[ctx.dataIndex]} Leads (${ctx.formattedValue}%)`;
                                                }
                                            }
                                        },

                                        datalabels: {
                                            anchor: "end",
                                            align: "end",
                                            color: "#000",
                                            formatter: (value) => value + "%",
                                            font: {
                                                weight: "bold"
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    @endif

                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartContainer = document.getElementById('chartContainer');
                const ctx = document.getElementById('projectChart');
                ctx.width = chartContainer.offsetWidth;
                ctx.height = chartContainer.offsetHeight * 0.8;
                const chart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                                label: 'Projects Created',
                                data: @json($chartData['created']),
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                maxBarThickness: 150
                            },
                            {
                                label: 'Projects Completed',
                                data: @json($chartData['completed']),
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                maxBarThickness: 150
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Projects'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                ccategoryPercentage: 0.6,
                                barPercentage: 0.5,
                                title: {
                                    display: true,
                                    text: 'Month'
                                },
                                ticks: {
                                    autoSkip: true,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        animation: {
                            duration: 2000
                        }
                    }
                });
                window.addEventListener('resize', function() {
                    ctx.width = chartContainer.offsetWidth;
                    ctx.height = chartContainer.offsetHeight * 0.8;
                    chart.update();
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('progressBarChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // Standard bar type
                    data: {
                        labels: @json($progressChart['labels']),
                        datasets: [{
                            label: 'Progress (%)',
                            data: @json($progressChart['progress']),
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Makes bars horizontal (left-to-right)
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: (context) => `${context.label}: ${context.raw}%`,
                                },
                            },
                            legend: {
                                display: false,
                            },
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Progress (%)',
                                },
                                ticks: {
                                    stepSize: 20,
                                },
                            },
                            y: {
                                grid: {
                                    display: false, // Hide horizontal grid lines
                                },
                                ticks: {
                                    autoSkip: false, // Ensure all labels are shown
                                },
                            },
                        },
                        barPercentage: 0.5, // Reduce bar width (0.5 = 50% of available space)
                        categoryPercentage: 0.8, // Adjust spacing between categories
                    },
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const memberCtx = document.getElementById('memberProgressPieChart');
                if (memberCtx) {
                    const memberChart = new Chart(memberCtx.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: Object.keys(@json($progressData)),
                            datasets: [{
                                data: Object.values(@json($progressData)),
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(255, 99, 132, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(255, 99, 132, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Your Project Progress',
                                    font: {
                                        size: 16
                                    }
                                }
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            }
                        }
                    });
                }
            });
        </script>

    @endsection
