<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\RemarkController;

// Redirect root to login page
Route::get('/', fn() => view('auth.login'))->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'ShowLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'ShowRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    // Dashboards
    Route::get('/managerLeaderMemberDashboard',[AuthController::class,'managerDash'])->name('manager.dashboard');
    Route::get('/dashboard', [AuthController::class, 'ShowDashboard'])->name('dashboard');
    Route::get('/team-member', [AuthController::class, 'ShowteamMember'])->name('teamMember');
    Route::get('/team-leader', [AuthController::class, 'TeamLeader'])->name('teamLeader');

    // Team leader specific views
    Route::get('/tasks/assigned-me', [TaskController::class, 'assignedMe'])->name('tasks.assignedMe');
    Route::get('/tasks/assigned-other', [TaskController::class, 'assignedOther'])->name('tasks.assignedOther');

    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // ✅ Correct task status update route

    Route::get('/tasks/trashed', [TaskController::class, 'trashed'])->name('tasks.trashed');
    Route::get('/task-history', [TaskController::class, 'taskHistory'])->name('tasks.history');
    Route::get('/tasks/view/{task}', [TaskController::class, 'ViewUpdatedTask'])->name('tasks.view');
    Route::post('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::post('/tasks/{taskId}/remarks', [RemarkController::class, 'updateRemarks'])->name('tasks.updateRemarks');

    Route::delete('/remarks/{id}', [RemarkController::class, 'destroy'])->name('remarks.destroy');

    // Employee routes
    Route::resource('employees', EmployeeController::class);

    //==================Leave==========================//
    Route::get('leave', [LeaveController::class, 'index'])->name('leaves');
    Route::get('create/leave', [LeaveController::class, 'create'])->name('create.leave');
    Route::post('save/leave', [LeaveController::class, 'save'])->name('save.leave');
    Route::delete('delete/{id}', [LeaveController::class, 'delete'])->name('delete.leave');
    Route::put('/leaves/status/{id}', [LeaveController::class, 'updateStatus'])->name('leave.updateStatus');

    //====================Attendance====================//
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendances');
    Route::get('create/attendance', [AttendanceController::class, 'create'])->name('create.attendance');
    Route::post('save/attendance', [AttendanceController::class, 'save'])->name('save.attendance');
    Route::get('/attendance/events', [AttendanceController::class, 'events'])->name('attendance.events');
    Route::post('update/attandance/status', [AttendanceController::class, 'employeeLogout'])->name('employeeLogout');
    Route::get('/attendance/date-status', [AttendanceController::class, 'dateStatus'])->name('attendance.dateStatus');
    Route::get('/attendance/history', [AttendanceController::class, 'attendanceHistory'])->name('attendance.history');
    Route::get('attendace', [AttendanceController::class,'forManager'])->name('forManagerAttendance');

    //=====================Projects=========================//
    Route::get('project', [ProjectsController::class, 'index'])->name('projects');
    Route::get('create/project', [ProjectsController::class, 'create'])->name('create.project');
    Route::post('save/project', [ProjectsController::class, 'save'])->name('save.project');
    Route::put('update/status/{id}', [ProjectsController::class, 'updateProjectStatus'])->name('update.project');
    Route::put('/project/{id}', [ProjectsController::class, 'updateProjectStatus'])->name('projects.update');
    Route::delete('/delete/project/{id}',[ProjectsController::class,'deleteProject'])->name('delete.project');
});
