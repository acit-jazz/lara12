<?php

use App\Http\Controllers\AuthAdmin\AuthAdminController;
use App\Http\Controllers\Backend\AdministratorController;
use App\Http\Controllers\Backend\MediaController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth.admin']], function () {
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard')->middleware('role_or_permission_admin:Master|Editor');
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index')->middleware('role_or_permission_admin:Master|Editor');

    // User
    Route::post('/user/destroy-all', [UserController::class, 'destroyAll'])->name('user.destroy-all')->middleware('role_or_permission_admin:Master|Delete User');
    Route::post('/user/{user}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware('role_or_permission_admin:Master|Delete User');
    Route::post('/user/{user}/destroy', [UserController::class, 'destroy'])->name('user.forceDelete')->middleware('role_or_permission_admin:Master|Delete User');
    Route::post('/user/{user}/restore', [UserController::class, 'restore'])->name('user.restore')->middleware('role_or_permission_admin:Master|Delete User');
    Route::get('/user/{user}/password', [UserController::class, 'editPassword'])->name('user.editPassword')->middleware('role_or_permission_admin:Master|Edit User');
    Route::put('/user/{user}/password', [UserController::class, 'updatePassword'])->name('user.updatePassword')->middleware('role_or_permission_admin:Master|Edit User');
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('role_or_permission_admin:Master|View User');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create')->middleware('role_or_permission_admin:Master|Create User');
    Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware('role_or_permission_admin:Master|Create User');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('role_or_permission_admin:Master|Edit User');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('role_or_permission_admin:Master|Edit User');

    // Page
    Route::post('/page/destroy-all', [PageController::class, 'destroyAll'])->name('page.destroy-all')->middleware('role_or_permission_admin:Master|Editor|Delete Page');
    Route::post('/page/{page}/delete', [PageController::class, 'delete'])->name('page.delete')->middleware('role_or_permission_admin:Master|Editor|Delete Page');
    Route::post('/page/{page}/destroy', [PageController::class, 'destroy'])->name('page.destroy')->middleware('role_or_permission_admin:Master|Editor|Delete Page');
    Route::post('/page/{page}/restore', [PageController::class, 'restore'])->name('page.restore')->middleware('role_or_permission_admin:Master|Editor|Delete Page');
    Route::get('/page', [PageController::class, 'index'])->name('page.index')->middleware('role_or_permission_admin:Master|Editor|View Page');
    Route::get('/page/create', [PageController::class, 'create'])->name('page.create')->middleware('role_or_permission_admin:Master|Editor|Create Page');
    Route::post('/page', [PageController::class, 'store'])->name('page.store')->middleware('role_or_permission_admin:Master|Editor|Create Page');
    Route::get('/page/{page}/edit', [PageController::class, 'edit'])->name('page.edit')->middleware('role_or_permission_admin:Master|Editor|Edit Page');
    Route::patch('/page/{page}', [PageController::class, 'update'])->name('page.update')->middleware('role_or_permission_admin:Master|Editor|Edit Page');

    // Media
    Route::post('/media/destroy-all', [MediaController::class, 'destroyAll'])->name('media.destroy-all')->middleware('role_or_permission_admin:Master|Delete Media');
    Route::post('/media/{media}/delete', [MediaController::class, 'delete'])->name('media.delete')->middleware('role_or_permission_admin:Master|Delete Media');
    Route::post('/media/{media}/destroy', [MediaController::class, 'destroy'])->name('media.destroy')->middleware('role_or_permission_admin:Master|Delete Media');
    Route::post('/media/{media}/restore', [MediaController::class, 'restore'])->name('media.restore')->middleware('role_or_permission_admin:Master|Delete Media');
    Route::get('/media', [MediaController::class, 'index'])->name('media.index')->middleware('role_or_permission_admin:Master|View Media');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store')->middleware('role_or_permission_admin:Master|Create Media');
    Route::patch('/media/{media}', [MediaController::class, 'update'])->name('page.update')->middleware('role_or_permission_admin:Master|Edit Media');


    Route::get('/administrator/{administrator}/password', [AdministratorController::class, 'editPassword'])->name('administrator.editPassword')->middleware('role_or_permission_admin:Master|Edit Admin');
    Route::put('/administrator/{administrator}/password', [AdministratorController::class, 'updatePassword'])->name('administrator.updatePassword')->middleware('role_or_permission_admin:Master|Edit Admin');
    Route::post('/administrator/{administrator}/delete', [AdministratorController::class, 'delete'])->name('administrator.delete')->middleware('role_or_permission_admin:Master|Delete Admin');
    Route::post('/administrator/{administrator}/destroy', [AdministratorController::class, 'destroy'])->name('administrator.forceDelete')->middleware('role_or_permission_admin:Master|Delete Admin');
    Route::post('/administrator/{administrator}/restore', [AdministratorController::class, 'restore'])->name('administrator.restore')->middleware('role_or_permission_admin:Master|Delete Admin');
    Route::get('/administrator', [AdministratorController::class, 'index'])->name('administrator.index')->middleware('role_or_permission_admin:Master|View Admin');
    Route::get('/administrator/create', [AdministratorController::class, 'create'])->name('administrator.create')->middleware('role_or_permission_admin:Master|Create Admin');
    Route::post('/administrator', [AdministratorController::class, 'store'])->name('administrator.store')->middleware('role_or_permission_admin:Master|Create Admin');
    Route::get('/administrator/{administrator}/edit', [AdministratorController::class, 'edit'])->name('administrator.edit')->middleware('role_or_permission_admin:Master|Edit Admin');
    Route::patch('/administrator/{administrator}', [AdministratorController::class, 'update'])->name('administrator.update')->middleware('role_or_permission_admin:Master|Edit Admin');
});

Route::get('/login', [AuthAdminController::class, 'create'])->name('admin.login.create');
Route::post('/login', [AuthAdminController::class, 'login'])->name('admin.login');
Route::post('logout', [AuthAdminController::class, 'destroy'])->name('admin.logout');

require __DIR__.'/settings.php';