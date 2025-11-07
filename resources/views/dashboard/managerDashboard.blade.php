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
                                    <div class="stats-icon icon-blue">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <p class="stats-title">Total Employees</p>
                                    <p class="stats-value">{{ $totalEmployee }}</p>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <div class="stats-icon icon-green">
                                        <i class="bi bi-person-check-fill"></i>
                                    </div>
                                    <p class="stats-title">Team Leaders</p>
                                    <p class="stats-value">{{ $totalteam_leader }}</p>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <div class="stats-icon icon-orange">
                                        <i class="bi bi-calendar-x-fill"></i>
                                    </div>
                                    <p class="stats-title">Today Leaves</p>
                                    <p class="stats-value">{{ $today_leaves }}</p>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="stats-card">
                                    <div class="stats-icon icon-purple">
                                        <i class="bi bi-person-check-fill"></i>
                                    </div>
                                    <p class="stats-title">Today Presents</p>
                                    <p class="stats-value">{{ $today_persent_user }}</p>
                                </div>
                            </div>
                        </div>
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
