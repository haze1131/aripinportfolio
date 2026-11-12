<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');

    Route::post('/admin/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/admin/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('/admin/projects/{project}/delete', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::post('/admin/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::post('/admin/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::post('/admin/skills/{skill}/delete', [SkillController::class, 'destroy'])->name('skills.destroy');
});
