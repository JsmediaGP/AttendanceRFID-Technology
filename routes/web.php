<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RFIDController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AttendanceController;

use App\Http\Controllers\LectureHallController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ClassScheduleController;




Route::get('/', function () {
    return view('auth.login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/lecturehalls', [AdminController::class, 'lectureHalls'])->name('admin.lecturehalls');
    Route::get('/admin/courses', [AdminController::class, 'courses'])->name('admin.courses');
    Route::get('/admin/schedules', [AdminController::class, 'schedules'])->name('admin.schedules');


    

    //new lecturehall
    Route::get('/admin/lecturehalls', [LectureHallController::class, 'index'])->name('admin.lecturehalls.index');
    Route::post('/admin/lecturehalls', [LectureHallController::class, 'store'])->name('admin.lecturehalls.store');
    Route::put('/admin/lecturehalls/{id}', [LectureHallController::class, 'update'])->name('admin.lecturehalls.update');
    Route::delete('/admin/lecturehalls/{id}', [LectureHallController::class, 'destroy'])->name('admin.lecturehalls.destroy');

    //course
    Route::get('/admin/courses', [CourseController::class, 'index'])->name('admin.courses');
    Route::post('/admin/courses/store', [CourseController::class, 'store'])->name('admin.courses.store');
    // Route::put('/admin/courses/{course}/update', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::put('/admin/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/admin/courses/{course}/delete', [CourseController::class, 'destroy'])->name('admin.courses.delete');

    //class schedule 
    // Route::resource('schedules', ClassScheduleController::class); 
    Route::get('/admin/schedules', [ClassScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/admin/schedules', [ClassScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::put('/admin/schedules/{id}', [ClassScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/admin/schedules/{id}', [ClassScheduleController::class, 'destroy'])->name('admin.schedules.destroy');

    //users 
    Route::get('/admin/users', [RegistrationController::class, 'index'])->name('users.index');
    Route::post('/admin/users', [RegistrationController::class, 'store'])->name('users.store');
    Route::put('/admin/users/{id}', [RegistrationController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{id}', [RegistrationController::class, 'destroy'])->name('users.destroy');



});


//lecturer routes
Route::middleware(['auth'])->group(function () {
    Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');
    Route::post('/lecturer/filter-schedule', [LecturerController::class, 'filterSchedules'])->name('lecturer.filter.schedule');
    Route::post('/lecturer/update-schedule/{id}', [LecturerController::class, 'updateClassStatus'])->name('lecturer.update.schedule');
    Route::get('/lecturer/attendance/{course}', [LecturerController::class, 'viewAttendance'])->name('lecturer.view.attendance');
    // Route::post('/lecturer/filter-attendance', [LecturerController::class, 'filterAttendance'])->name('lecturer.filter.attendance');
    // Route::get('/lecturer/attendance/export/{course}', [LecturerController::class, 'exportAttendanceToCsv'])->name('lecturer.attendance.export');
    // Route::get('/lecturer/attendance/export/{course}', [LecturerController::class, 'exportAttendanceToCsv'])->name('lecturer.attendance.export');  
    Route::get('/lecturer/attendance/export/details/{course}', [LecturerController::class, 'exportDetailedAttendanceToCsv'])->name('lecturer.attendance.export.details');  
    // Summary attendance route  
    Route::get('/lecturer/attendance/summary/{course}', [LecturerController::class, 'viewAttendanceSummary'])->name('lecturer.view.attendance.summary');  
    Route::get('/lecturer/attendance/export/summary/{course}', [LecturerController::class, 'exportAttendanceSummaryToCsv'])->name('lecturer.attendance.export.summary');  




});


// Student Dashboard  
Route::middleware(['auth'])->group(function () {  
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');  
    
    // Course Registration  
    Route::get('/student/course-registration', [StudentController::class, 'courseRegistration'])->name('student.course.registration');  
    
    // View Attendance  
    // Route::get('/student/view-attendance', [StudentController::class, 'viewAttendance'])->name('student.attendance.view');  
    
    
});  




// php artisan serve --host=192.168.200.52 --port=8000
//  php artisan serve --host=192.168.42.52 --port=8000