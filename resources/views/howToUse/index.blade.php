@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="container mt-4">

            {{-- Title Section --}}
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="mb-4">CRM User Guide</h1>
                    <p class="lead text-muted">Learn how to effectively use the CRM and explore its features.</p>
                </div>
            </div>

            {{-- Dashboard Overview Section --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fa-solid fa-diagram-project"></i> 1. Dashboard Overview</h4>
                </div>
                <div class="card-body">
                    <p>
                        The Dashboard is the central hub of the CRM where you can view essential data and activities.
                        Here you get a real-time overview of your organization's activities.
                    </p>
                    <hr>
                    <h5>Manager Dashboard:</h5>
                    <ul>
                        <li><strong>Total Employees:</strong> The total number of employees registered.</li>
                        <li><strong>Total Team Leaders:</strong> List of all team leaders assigned.</li>
                        <li><strong>Total Projects:</strong> Currently active projects in the system.</li>
                        <li><strong>Completed Projects:</strong> Successfully completed projects count.</li>
                    </ul>
                    <hr>
                    <h5>HR Dashboard:</h5>
                    <ul>
                        <li><strong>Total Employees:</strong> The total number of employees registered.</li>
                        <li><strong>Total Leaves:</strong> Number of leaves taken by employees.</li>
                        <li><strong>Total Presents:</strong> The total employees present today.</li>
                    </ul>
                    <hr>
                    <h5>Team Leaders and Team Members Dashboard:</h5>
                    <ul>
                        <li><strong>Total Presents:</strong> Total presents for the current month.</li>
                        <li><strong>Total Absents:</strong> Total absents for the current month.</li>
                        <li><strong>Remaining Leaves:</strong> Remaining leaves for the month.</li>
                        <li><strong>Pending Projects:</strong> Projects that are yet to be completed.</li>
                    </ul>
                    <p>
                        The dashboard contains graphs, charts, and quick stats to give you a complete understanding of the workflow.
                    </p>
                </div>
            </div>

            {{-- Employee Management Section --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4><i class="fa-solid fa-users"></i> 2. Employee Management</h4>
                </div>
                <div class="card-body">
                    <p>
                        The Employee module allows you to manage employee records, including adding, updating, and deleting employee details.
                    </p>
                    <ul>
                        <li>Name, Email, Phone</li>
                        <li>Role (Employee, Team Leader, Manager, HR)</li>
                        <li>Assigned Team</li>
                        <li>Automatic Attendance Marking upon Login</li>
                        <li>Auto Logout after Shift Completion (9 hours)</li>
                        <li>Attendance & Activity Tracking</li>
                    </ul>
                    <p>Team Leaders have access to monitor attendance and track team members' progress.</p>
                </div>
            </div>

            {{-- Project Management Section --}}
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4><i class="fa-solid fa-project-diagram"></i> 3. Project Management</h4>
                </div>
                <div class="card-body">
                    <p>
                        The Project module is the core part of the CRM where you can create, assign, and manage projects.
                    </p>
                    <h5>Key Features:</h5>
                    <ul>
                        <li>Create New Projects</li>
                        <li>Assign Projects to Team Leaders</li>
                        <li>Add Task Lists</li>
                        <li>Update Project Status (Pending, In Progress, Completed)</li>
                        <li>Manage Project Due Dates</li>
                    </ul>
                    <p>
                        Whenever a project is marked as "Completed", the dashboard will automatically update.
                    </p>
                </div>
            </div>

            {{-- Attendance Management Section --}}
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h4><i class="fa-solid fa-calendar-check"></i> 4. Attendance Management</h4>
                </div>
                <div class="card-body">
                    <p>
                        The Attendance module tracks employee attendance on a daily basis, with FullCalendar integration to provide date-wise data.
                    </p>
                    <h5>You can view:</h5>
                    <ul>
                        <li><strong>Present / Absent Days:</strong> View total presents and absents for employees.</li>
                        <li><strong>Leaves Taken:</strong> View the number of leaves taken by employees, including managers and HR.</li>
                        <li><strong>Remaining Leaves:</strong> Track remaining leave balance.</li>
                        <li><strong>Automatic Attendance Marking:</strong> Attendance is automatically marked when employees log in.</li>
                        <li><strong>Auto Logout:</strong> Automatically logs out employees after 9 hours.</li>
                        <li><strong>Attendance History:</strong> Employees can view their past attendance records.</li>
                        <li><strong>Manual Logout:</strong> Employees need to manually logout if they leave before completing their shift.</li>
                    </ul>
                </div>
            </div>

            {{-- Notification System Section --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h4><i class="fa-solid fa-bell"></i> 5. Notification System</h4>
                </div>
                <div class="card-body">
                    <p>
                        The CRM includes a real-time notification system that sends alerts to users based on certain activities.
                    </p>
                    <ul>
                        <li><strong>Leave Approval / Rejection:</strong> Notifications are sent when a leave request is approved or rejected.</li>
                        <li><strong>New Project Assignment:</strong> Notifications are sent when a new project is assigned to a user.</li>
                        <li><strong>Task Updates:</strong> Notifications are sent when tasks are updated (Pending, In Progress, Completed).</li>
                        <li><strong>General Announcements:</strong> Notifications are sent when the HR or Manager posts general announcements.</li>
                        <li><strong>Holiday Notifications:</strong> Notifications are sent to all users when a new holiday is added by the HR or Manager.</li>
                    </ul>
                </div>
            </div>

            {{-- Role & Permission System Section --}}
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h4><i class="fa-solid fa-shield-alt"></i> 6. Role & Permission Control</h4>
                </div>
                <div class="card-body">
                    <p>
                        The CRM includes three primary user roles with different levels of access:
                    </p>
                    <ul>
                        <li><strong>Admin (Manager & HR):</strong> Full access to all system features, including managing employees, projects, tasks, and viewing all data.</li>
                        <li><strong>Team Leader:</strong> Can manage assigned projects, task statuses, and leave statuses for team members.</li>
                        <li><strong>Employee:</strong> Can view and update their own tasks, mark attendance, and apply for leaves.</li>
                    </ul>
                </div>
            </div>

            {{-- System Workflow Summary Section --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fa-solid fa-sync-alt"></i> 7. System Workflow Summary</h4>
                </div>
                <div class="card-body">
                    <p>
                        The complete workflow of the CRM is as follows:
                    </p>
                    <ol>
                        <li>Admin adds employees and team leaders.</li>
                        <li>Admin creates projects and assigns them to team leaders.</li>
                        <li>Team leaders assign tasks to their team members.</li>
                        <li>Employees update their daily work.</li>
                        <li>Attendance, leaves, and notifications are updated in real-time.</li>
                        <li>The dashboard shows the complete summary of all data.</li>
                    </ol>
                </div>
            </div>

            {{-- Lead Management for Manager (Optional) --}}
            @if (Auth::user()->role === 'manager')
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fa-solid fa-cogs"></i> 8. Lead Management and Marketing</h4>
                    </div>
                    <div class="card-body">
                        <p>
                            The CRM includes lead management and marketing features for the Manager role.
                        </p>
                        <ol>
                            <li><strong>Contact:</strong> Add new client contact details like name, email, phone, and address.</li>
                            <li><strong>Projects:</strong> Add project details including name, price, and description.</li>
                            <li><strong>Lead:</strong> Manage project leads with details of contacts, projects, and lead status.</li>
                            <li><strong>Quotations:</strong> Add quotations related to leads, including billing and shipping addresses, and calculate tax and discount.</li>
                        </ol>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
