@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- Stats Cards -->
                        @if(auth()->user()->role == 'manager')
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-primary text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Employees</h5>
                                            <h2>{{ $stats['total_employees'] }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-success text-white mb-4">
                                        <div class="card-body">
                                            <h5>Team Leaders</h5>
                                            <h2>{{ $stats['total_team_leaders'] }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-info text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Projects</h5>
                                            <h2>{{ $stats['total_projects'] }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-warning text-white mb-4">
                                        <div class="card-body">
                                            <h5>Completed Projects</h5>
                                            <h2>{{ $stats['completed_projects'] }}</h2>
                                        </div>
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
                                                <canvas id="progressBarChart" ></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        @elseif(auth()->user()->role == 'team_leader')
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-primary text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Presents</h5>
                                            <h2>{{ $stats['total_presents'] }}</h2>
                                            <small>Current Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-danger text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Absents</h5>
                                            <h2>{{ $stats['total_absents'] }}</h2>
                                            <small>Current Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-info text-white mb-4">
                                        <div class="card-body">
                                            <h5>Remaining Leaves</h5>
                                            <h2>{{ $stats['remaining_leaves'] }}</h2>
                                            <small>This Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-warning text-white mb-4">
                                        <div class="card-body">
                                            <h5>Pending Projects</h5>
                                            <h2>{{ $stats['pending_projects'] }}</h2>
                                            <small>Pending</small>
                                        </div>
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
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Project</th>
                                                            <th>Deadline</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($projects as $project)
                                                            <tr>
                                                                <td>{{ $project->project_name }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                                                </td>
                                                                <td><span
                                                                        class="badge bg-{{$project->status == 'Completed' ? 'success' : ($project->status == 'In Progress' ? 'primary' : 'warning')}}">{{ $project->status }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <!-- <a href="#" class="btn btn-sm btn-primary mt-2">View
                                                                                                                                                                                                    All Projects</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- <h4 class="card-title">Quick Actions</h4>
                                                                                                                                              <div class="d-grid gap-2">
                                                                                                                                                  <a href="#" class="btn btn-primary">
                                                                                                                                                      Mark Today's Attendance
                                                                                                                                                  </a>
                                                                                                                                                  <a href="#" class="btn btn-success">
                                                                                                                                                      Apply for Leave
                                                                                                                                                  </a>
                                                                                                                                                  <a href="#" class="btn btn-info">
                                                                                                                                                      Create New Project
                                                                                                                                                  </a>
                                                                                                                                                  <a href="#" class="btn btn-warning">
                                                                                                                                                      View Performance Report
                                                                                                                                                  </a>
                                                                                                                                              </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif(auth()->user()->role == 'team_member')
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-primary text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Presents</h5>
                                            <h2>{{ $stats['total_presents'] }}</h2>
                                            <small>Current Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-danger text-white mb-4">
                                        <div class="card-body">
                                            <h5>Total Absents</h5>
                                            <h2>{{ $stats['total_absents'] }}</h2>
                                            <small>Current Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-info text-white mb-4">
                                        <div class="card-body">
                                            <h5>Remaining Leaves</h5>
                                            <h2>{{ $stats['remaining_leaves'] }}</h2>
                                            <small>This Month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card bg-warning text-white mb-4">
                                        <div class="card-body">
                                            <h5>Pending Projects</h5>
                                            <h2>{{ $stats['pending_projects'] }}</h2>
                                            <small>Pending</small>
                                        </div>
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
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Project</th>
                                                            <th>Deadline</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($projects as $project)
                                                                                                <tr>
                                                                                                    <td>{{ $project->project_name }}</td>
                                                                                                    <td>{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <span
                                                                                                            class="badge bg-{{ 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $project->status == 'Completed' ? 'success' :
                                                            ($project->status == 'In Progress' ? 'primary' : 'warning') }}">
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
                                <!-- Progress Pie Chart -->
                                <!-- <div class="row"> -->
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

                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chartContainer = document.getElementById('chartContainer');
                const ctx = document.getElementById('projectChart');
                ctx.width = chartContainer.offsetWidth;
                ctx.height = chartContainer.offsetHeight * 0.8;
                const chart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [
                            {
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
                window.addEventListener('resize', function () {
                    ctx.width = chartContainer.offsetWidth;
                    ctx.height = chartContainer.offsetHeight * 0.8;
                    chart.update();
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
            document.addEventListener('DOMContentLoaded', function () {
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
                                legend: { position: 'right' },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
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
                                    font: { size: 16 }
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

    </body>
@endsection