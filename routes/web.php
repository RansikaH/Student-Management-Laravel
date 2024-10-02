<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'checkSuspended'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Other protected routes here


        Route::get('/', function () {
            return view('welcome');
        });

        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');
        });

        // Admin Routes
        Route::group(['middleware' => ['auth', 'role:admin']], function () {
            Route::get('/admin/dashboard', function () {
                return view('admin.dashboard');
            })->name('admin.dashboard');
        });

        // Staff Routes
        Route::group(['middleware' => ['auth', 'role:staff']], function () {
            Route::get('/staff/dashboard', function () {
                return view('staff.dashboard');
            })->name('staff.dashboard');
        });

        // Student Routes
        Route::group(['middleware' => ['auth', 'role:student']], function () {
            Route::get('/student/dashboard', function () {
                return view('student.dashboard');
            })->name('student.dashboard');
        });

        // User Routes
        Route::group(['middleware' => ['auth', 'role:user']], function () {
            Route::get('/user/dashboard', function () {
                return view('user.dashboard');
            })->name('user.dashboard');
        });


        Route::group(['middleware' => ['auth', 'role:admin']], function () {
            Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
            Route::post('/admin/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('admin.users.changeRole');
            Route::post('/admin/users/{user}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspendUser'])->name('admin.users.suspend');
            Route::post('/admin/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('admin.users.resetPassword');
        });

        Route::middleware(['auth', 'role:admin,staff'])->group(function () {
            Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
            Route::get('/courses/{course}/add-lesson', [App\Http\Controllers\Admin\LessonController::class, 'create'])->name('courses.addLesson');
            Route::post('/courses/{course}/add-lesson', [App\Http\Controllers\Admin\LessonController::class, 'store'])->name('courses.storeLesson');
            Route::get('/courses/{course}/lessons', [App\Http\Controllers\Admin\LessonController::class, 'index'])->name('courses.showLessons');
            Route::get('/lessons/{lesson}/edit', [App\Http\Controllers\Admin\LessonController::class, 'edit'])->name('lessons.edit');
            Route::delete('/lessons/{lesson}', [App\Http\Controllers\Admin\LessonController::class, 'destroy'])->name('lessons.destroy');
            Route::put('/lessons/{lesson}', [App\Http\Controllers\Admin\LessonController::class, 'update'])->name('lessons.update');
            Route::get('/lessons/trash', [App\Http\Controllers\Admin\LessonController::class, 'trash'])->name('lessons.trash');
            Route::post('/lessons/{lesson}/restore', [App\Http\Controllers\Admin\LessonController::class, 'restore'])->name('lessons.restore');
            Route::get('/{course}', [App\Http\Controllers\Admin\CourseController::class, 'show'])->name('courses.show');
            Route::get('/available', [App\Http\Controllers\Admin\CourseController::class, 'available'])->name('courses.available');

        });
        
       



});

// Route for suspended users
Route::get('/suspended', function () {
    return view('auth.suspended');
})->name('suspended');

