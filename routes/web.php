<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'ShowLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'ShowRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'ShowDashboard'])->name('dashboard');
    Route::get('/teamMember', [AuthController::class, 'ShowteamMember'])->name('teamMember');
    Route::get('/teamLeader', [AuthController::class, 'TeamLeader'])->name('teamLeader');

    Route::get('team_member_list', [TaskController::class, 'getList'])->name('getTeamMemberList');

    Route::post('/create-description', [TaskController::class, 'storeDescription'])->name('create-description');
    Route::put('/edit-description', [TaskController::class, 'updateDescription'])->name('edit-description');

    Route::get('employees',[EmployeeController::class, 'index'])->name('employees');
    Route::get('createEmployee/{id?}', [EmployeeController::class, 'create'])->name('create-employee');
    Route::post('saveEmployee', [EmployeeController::class, 'save'])->name('save-employee');
    Route::put('updateEmployee/{id}', [EmployeeController::class, 'save'])->name('update-employee');
    Route::delete('statusUpate/{id}', [EmployeeController::class, 'statusUpdate'])->name('status-update');

});
