<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadProductController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MailEmailTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RemarkController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WhatsappsentTemplateController;
use App\Http\Controllers\WhatsappTemplateController;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Route;

// Redirect root to login page
Route::get('/', fn() => view('auth.login'))->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'ShowLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/register', [AuthController::class, 'ShowRegistrationForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::get('/profile/approve/{id}', [ProfileController::class, 'approve'])->name('profile.approve');
    Route::get('/profile/reject/{id}', [ProfileController::class, 'reject'])->name('profile.reject');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');



    // Dashboards
    Route::get('/dashboard', [AuthController::class, 'managerDash'])->name('manager.dashboard');
    Route::get('/task-dashboard', [AuthController::class, 'ShowDashboard'])->name('dashboard');
    Route::get('/my-tasks', [AuthController::class, 'ShowteamMember'])->name('teamMember');
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
    Route::post('tasks/{taskId}/remarks', [RemarkController::class, 'updateRemarks'])->name('tasks.updateRemarks');

    Route::delete('/remarks/{id}', [RemarkController::class, 'destroy'])->name('remarks.destroy');

    // Employee routes
    Route::resource('employees', EmployeeController::class);

    Route::get('employees/{employee}/download-invoice', [EmployeeController::class, 'downloadInvoice'])
        ->name('employees.downloadInvoice');
    Route::get('employee', [EmployeeController::class, 'back'])->name('backToEmployee');

    //==================Leave==========================//
    Route::get('leave', [LeaveController::class, 'index'])->name('leaves');
    Route::get('create/leave', [LeaveController::class, 'create'])->name('create.leave');
    Route::post('save/leave', [LeaveController::class, 'save'])->name('save.leave');
    Route::delete('delete/{id}', [LeaveController::class, 'delete'])->name('delete.leave');
    Route::put('/leaves/status/{id}', [LeaveController::class, 'updateStatus'])->name('leave.updateStatus');
    Route::put('/leave/update-tl-status/{id}', [LeaveController::class, 'updateTeamLeaderStatus'])->name('leave.updateTeamLeaderStatus');

    //====================Attendance====================//
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendances');
    Route::get('create/attendance', [AttendanceController::class, 'create'])->name('create.attendance');
    Route::post('save/attendance', [AttendanceController::class, 'save'])->name('save.attendance');
    Route::get('/attendance/events', [AttendanceController::class, 'events'])->name('attendance.events');
    Route::post('update/attandance/status', [AttendanceController::class, 'employeeLogout'])->name('employeeLogout');
    Route::get('/attendance/date-status', [AttendanceController::class, 'dateStatus'])->name('attendance.dateStatus');
    Route::get('/attendance/history', [AttendanceController::class, 'attendanceHistory'])->name('attendance.history');
    Route::get('attendace', [AttendanceController::class, 'forManager'])->name('forManagerAttendance');

    //=====================Projects=========================//
    Route::get('projects', [ProjectsController::class, 'index'])->name('projects');
    Route::get('create/project', [ProjectsController::class, 'create'])->name('create.project');
    Route::post('save/project', [ProjectsController::class, 'save'])->name('save.project');
    Route::put('update/status/{id}', [ProjectsController::class, 'updateProjectStatus'])->name('update.project');
    Route::put('/project/{id}', [ProjectsController::class, 'updateProjectStatus'])->name('projects.update');
    Route::delete('/delete/project/{id}', [ProjectsController::class, 'deleteProject'])->name('delete.project');

    //=====================Contacts =========================//
    Route::get('contacts', [ContactController::class, 'index'])->name('contact.index');
    Route::get('contact-create', [ContactController::class, 'create'])->name('contact.create');
    Route::post('contact-store', [ContactController::class, 'store'])->name('contact.store');
    Route::get('contacts/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    //=====================lead product =========================//
    Route::get('/lead-products', [LeadProductController::class, 'index'])->name('lead-products.index');
    Route::get('/lead-products/create', [LeadProductController::class, 'create'])->name('lead-products.create');
    Route::post('/lead-products', [LeadProductController::class, 'store'])->name('lead-products.store');
    Route::get('lead-products/{id}/edit', [LeadProductController::class, 'edit'])->name('lead-products.edit');
    Route::put('lead-products/{id}/update', [LeadProductController::class, 'update'])->name('lead-products.update');
    Route::delete('lead-products/{id}', [LeadProductController::class, 'destroy'])->name('lead-products.destroy');

    //=====================lead  =========================//
    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('leads/store', [LeadController::class, 'store'])->name('leads.store');
    Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');

    //=====================Quotes=========================//

    Route::controller(QuoteController::class)->prefix('quotes')->group(function () {
        Route::get('', 'index')->name('quotes.index');
        Route::get('create', 'create')->name('quotes.create');
        Route::post('create', 'store')->name('quotes.store');
        Route::get('edit/{id?}', 'edit')->name('quotes.edit');
        Route::put('edit/{id}', 'update')->name('quotes.update');
        Route::get('print/{id?}', 'print')->name('quotes.print');
        Route::get('/{id?}', 'view')->name('quotes.view');
        Route::delete('{id}', 'destroy')->name('quotes.destroy');
        Route::get('send-mail/{id}', 'sendMail')->name('quotes.send.mail');
    });

    Route::get('/mails/inbox', [MailController::class, 'inbox'])->name('mails.inbox');
    Route::get('/mails/drafts', [MailController::class, 'drafts'])->name('mails.drafts');
    Route::get('/mails/sent', [MailController::class, 'sent'])->name('mails.sent');
    Route::get('/mails/trash', [MailController::class, 'trash'])->name('mails.trash');
    Route::post('/mails/store', [MailController::class, 'store'])->name('mails.store');
    Route::get('/mails/{id}', [MailController::class, 'show'])->name('mails.show');
    Route::post('/mails/{id}', [MailController::class, 'destroy'])->name('mails.destroy');
    Route::post('/mails/{id}/restore', [MailController::class, 'restore'])->name('mail.restore');
    Route::post('/mails/{id}/force-delete', [MailController::class, 'forceDelete'])->name('mail.forceDelete');
    Route::post('/mails/{id}/send', [MailController::class, 'sendDraft'])->name('mails.send.draft');

    Route::get('/activites', [ActivityController::class, 'index'])->name('activity.index');
    Route::post('/activity/store', [ActivityController::class, 'store'])->name('activity.store');
    Route::get('activity/{id}/edit', [ActivityController::class, 'edit'])->name('activity.edit');
    Route::post('activity/{id}/update', [ActivityController::class, 'update'])->name('activity.update');
    Route::delete('activity/{id}', [ActivityController::class, 'destroy'])->name('activity.destroy');
    Route::patch('/activities/toggle-done/{id}', [ActivityController::class, 'toggleDone'])->name('activity.toggleDone');

    // bulk delete
    Route::post('/mails/trash/force-bulk-delete', [MailController::class, 'trashforceBulkDelete'])->name('mail.trashforceBulkDelete');

    //profile manage

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.view');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');


    //==============================Information===================================//
    Route::resource('informations', InformationController::class);


    //==============================Holiday===================================//
    Route::get('holiday', [HolidayController::class, 'index'])->name('holiday.index');
    Route::get('holiday/create', [HolidayController::class, 'create'])->name('holiday.create');
    Route::post('holiday/store', [HolidayController::class, 'store'])->name('holiday.store');
    Route::get('holiday/{holiday}/edit', [HolidayController::class, 'edit'])->name('holiday.edit');
    Route::put('holiday/{holiday}', [HolidayController::class, 'update'])->name('holiday.update');
    Route::delete('holiday/{holiday}', [HolidayController::class, 'destroy'])->name('holiday.destroy');

    Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    Route::get('employee-attendance/{id}', [AttendanceController::class, 'view_employees_attendance'])->name('view.employees.attendance');
    Route::get('employee/export', [EmployeeController::class, 'employeeExport'])->name('employee.export');

    Route::get('/add-attendance-remark', [AttendanceController::class, 'addRemark'])->name('addAttendance.remark');
    Route::post('/store-attendance-remark', [AttendanceController::class, 'storeRemark'])->name('storeAttendance.remark');
    Route::put('/update-attendance-remark/{id}', [AttendanceController::class, 'updateRemark'])->name('updateAttendance.remark');
    Route::delete('/delete-attendance-remark/{id}', [AttendanceController::class, 'deleteRemark'])->name('deleteAttendance.remark');
    Route::get('view-remarks', [AttendanceController::class, 'viewRemarks'])->name('viewRemarks');

    Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('templates.index');
    Route::get('/email-templates/create', [EmailTemplateController::class, 'create'])->name('templates.create');
    Route::post('/email-templates', [EmailTemplateController::class, 'store'])->name('templates.store');
    Route::delete('/email-templates/{id}', [EmailTemplateController::class, 'destroy'])->name('templates.destroy');
    Route::get('/email-templates/{id}/edit', [EmailTemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/email-templates/{id}', [EmailTemplateController::class, 'update'])->name('templates.update');
    Route::get('/mail-contact', [MailEmailTemplateController::class, 'mail_contact'])->name('mail.contacts');
    Route::post('/send-template-mail', [MailEmailTemplateController::class, 'sendMail'])->name('mail.send.post');
    Route::get('/get-template/{id}', [MailEmailTemplateController::class, 'getTemplate'])->name('mail.getTemplate');


    //  provide by Kulwant sir //
    Route::get('/whatsapp-templates', [WhatsappTemplateController::class, 'index'])->name('whatapptemplates.index');
    Route::get('/whatsapp-templates/create', [WhatsappTemplateController::class, 'create'])->name('whatapptemplates.create');
    Route::post('/whatsapp-templates', [WhatsappTemplateController::class, 'store'])->name('whatapptemplates.store');
    Route::delete('/whatsapp-templates/{id}', [WhatsappTemplateController::class, 'destroy'])->name('whatapptemplates.destroy');
    Route::get('/whatsapp-templates/{id}/edit', [WhatsappTemplateController::class, 'edit'])->name('whatapptemplates.edit');
    Route::put('/whatsapp-templates/{id}', [WhatsappTemplateController::class, 'update'])->name('whatapptemplates.update');

    Route::get('/whatsapp-contact', [WhatsappsentTemplateController::class, 'whatsapp_contact'])->name('whatsapp.contacts');
    Route::post('/send-template-whatsapp', [WhatsappsentTemplateController::class, 'sendMessage'])->name('whatsapp.send.post');
    Route::get('/get-whatsapptemplate/{id}', [WhatsappsentTemplateController::class, 'getwhatsappTemplate'])->name('whatsapp.getTemplate');

    Route::post('/tasks/bulk-delete', [TaskController::class, 'taskbulkDelete'])->name('tasks.bulk.delete');
    Route::post('/tasks/bulk-restore', [TaskController::class, 'taskbulkRestore'])->name('tasks.bulk.restore');
    Route::post('/tasks/bulk-delete-permanent', [TaskController::class, 'taskbulkDeletePermanent'])->name('tasks.bulk.delete.permanent');
    Route::post('employees/bulk-delete', [EmployeeController::class, 'employeebulkDelete'])->name('employees.bulk.delete');
    Route::post('/leaves/bulk-delete', [LeaveController::class, 'leavebulkDelete'])->name('leaves.bulk.delete');
    Route::delete('/projects/bulk-delete', [ProjectsController::class, 'projectbulkDelete'])->name('projects.bulkDelete');
    Route::post('/contacts/bulk-delete', [ContactController::class, 'contactbulkDelete'])->name('contacts.bulkDelete');
    Route::post('/lead-products/bulk-delete', [LeadProductController::class, 'productsbulkDelete'])->name('lead-products.bulkDelete');
    Route::post('/leads/bulk-delete', [LeadController::class, 'leadsbulkDelete'])->name('leads.bulk-delete');
    Route::post('/quotes/bulk-delete', [QuoteController::class, 'quotebulkDelete'])->name('quotes.bulk-delete');
    Route::post('/activity/bulk-delete', [ActivityController::class, 'activitybulkDelete'])->name('activity.bulk-delete');
    Route::post('/mail/bulk-delete', [MailController::class, 'mailbulkDelete'])->name('mail.bulk-delete');
    Route::post('/template/bulk-delete', [EmailTemplateController::class, 'templatebulkDelete'])->name('template.bulk-delete');
    Route::post('/holiday/bulk-delete', [HolidayController::class, 'holidaybulkDelete'])->name('holiday.bulk-delete');
    Route::post('/informations/bulk-delete', [InformationController::class, 'informationsbulkDelete'])->name('informations.bulk-delete');
    Route::get('/get-states/{countryId}', [ContactController::class, 'getStates']);
    Route::get('/get-cities/{stateId}', [ContactController::class, 'getCities']);

});


