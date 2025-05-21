<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth.admin']], function () {
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index');

    // User
    Route::post('/user/destroy-all', [App\Http\Controllers\Backend\UserController::class, 'destroyAll'])->name('user.destroy-all');
    Route::post('/user/{user}/delete', [App\Http\Controllers\Backend\UserController::class, 'delete'])->name('user.delete');
    Route::post('/user/{user}/destroy', [App\Http\Controllers\Backend\UserController::class, 'destroy'])->name('user.forceDelete');
    Route::post('/user/{user}/restore', [App\Http\Controllers\Backend\UserController::class, 'restore'])->name('user.restore');
    Route::get('/user/{user}/password', [App\Http\Controllers\Backend\UserController::class, 'editPassword'])->name('user.editPassword');
    Route::put('/user/{user}/password', [App\Http\Controllers\Backend\UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::resource('/user', App\Http\Controllers\Backend\UserController::class);

    // Page
    Route::post('/page/destroy-all', [App\Http\Controllers\Backend\PageController::class, 'destroyAll'])->name('page.destroy-all');
    Route::post('/page/{page}/delete', [App\Http\Controllers\Backend\PageController::class, 'delete'])->name('page.delete');
    Route::post('/page/{page}/destroy', [App\Http\Controllers\Backend\PageController::class, 'destroy'])->name('page.forceDelete');
    Route::post('/page/{page}/restore', [App\Http\Controllers\Backend\PageController::class, 'restore'])->name('page.restore');
    Route::resource('/page', App\Http\Controllers\Backend\PageController::class);

    // Media
    Route::post('/media/destroy-all', [App\Http\Controllers\Backend\MediaController::class, 'destroyAll'])->name('media.destroy-all');
    Route::post('/media/{media}/delete', [App\Http\Controllers\Backend\MediaController::class, 'delete'])->name('media.delete');
    Route::post('/media/{media}/destroy', [App\Http\Controllers\Backend\MediaController::class, 'destroy'])->name('media.forceDelete');
    Route::post('/media/{media}/restore', [App\Http\Controllers\Backend\MediaController::class, 'restore'])->name('media.restore');
    Route::apiResource('/media', App\Http\Controllers\Backend\MediaController::class);

    Route::get('/administrator/{administrator}/password', [App\Http\Controllers\Backend\AdminstratorController::class, 'editPassword'])->name('administrator.editPassword');
    Route::put('/administrator/{administrator}/password', [App\Http\Controllers\Backend\AdminstratorController::class, 'updatePassword'])->name('administrator.updatePassword');
    Route::post('/administrator/{administrator}/delete', [App\Http\Controllers\Backend\AdminstratorController::class, 'delete'])->name('administrator.delete');
    Route::post('/administrator/{administrator}/destroy', [App\Http\Controllers\Backend\AdminstratorController::class, 'destroy'])->name('administrator.forceDelete');
    Route::post('/administrator/{administrator}/restore', [App\Http\Controllers\Backend\AdminstratorController::class, 'restore'])->name('administrator.restore');
    Route::resource('/administrator', App\Http\Controllers\Backend\AdminstratorController::class);
});

Route::get('/login', [App\Http\Controllers\AuthAdmin\AuthAdminController::class, 'create'])->name('admin.login.create');
Route::post('/login', [App\Http\Controllers\AuthAdmin\AuthAdminController::class, 'login'])->name('admin.login');
Route::post('logout', [App\Http\Controllers\AuthAdmin\AuthAdminController::class, 'destroy'])->name('admin.logout');

require __DIR__.'/settings.php';