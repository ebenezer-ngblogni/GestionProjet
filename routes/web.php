<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Routes protégées par l'authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/project/{project}/stats', [DashboardController::class, 'getProjectStats'])
         ->name('dashboard.project.stats');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::controller(ProjectController::class)->group(function () {
        Route::post('projects/{project}/invite', 'invite')->name('projects.invite');
        Route::post('projects/{project}/update-status', 'updateStatus')->name('projects.update-status');
        Route::get('projects/{project}/members', 'members')->name('projects.members');
        Route::delete('projects/{project}/members/{user}', 'removeMember')->name('projects.members.remove');
    });

    // Tasks
    Route::resource('projects.tasks', TaskController::class);
    Route::controller(TaskController::class)->group(function () {
        Route::post('tasks/{task}/complete', 'complete')->name('tasks.complete');
        Route::post('tasks/{task}/assign', 'assign')->name('tasks.assign');
        Route::post('tasks/{task}/status', 'updateStatus')->name('tasks.update-status');
    });

    // Files
    Route::controller(FileController::class)->group(function () {
        Route::post('tasks/{task}/files', 'store')->name('files.store');
        Route::get('files/{file}/download', 'download')->name('files.download');
        Route::delete('files/{file}', 'destroy')->name('files.destroy');
    });

    // User Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::get('/test-mail', function () {
        Mail::raw('Test email', function($message) {
            $message->to('votre-email@example.com')
                    ->subject('Test Email');
        });

        return 'Email envoyé !';
    });
});
