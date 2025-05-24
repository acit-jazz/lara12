<?php

use App\Http\Controllers\AuthAdmin\AuthAdminController;
use App\Http\Controllers\Backend\AdministratorController;
use App\Http\Controllers\Backend\MediaController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth.admin']], function () {
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard')->middleware('admin_permission:View Dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index')->middleware('admin_permission:View Dashboard');

    // User
    Route::post('/user/destroy-all', [UserController::class, 'destroyAll'])->name('user.destroy-all')->middleware('admin_permission:Delete User');
    Route::post('/user/{user}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware('admin_permission:Delete User');
    Route::post('/user/{user}/destroy', [UserController::class, 'destroy'])->name('user.forceDelete')->middleware('admin_permission:Delete User');
    Route::post('/user/{user}/restore', [UserController::class, 'restore'])->name('user.restore')->middleware('admin_permission:Delete User');
    Route::get('/user/{user}/password', [UserController::class, 'editPassword'])->name('user.editPassword')->middleware('admin_permission:Edit User');
    Route::put('/user/{user}/password', [UserController::class, 'updatePassword'])->name('user.updatePassword')->middleware('admin_permission:Edit User');
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('admin_permission:View User');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create')->middleware('admin_permission:Create User');
    Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware('admin_permission:Create User');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('admin_permission:Edit User');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('admin_permission:Edit User');

    // Page
    Route::post('/page/destroy-all', [PageController::class, 'destroyAll'])->name('page.destroy-all')->middleware('admin_permission:Delete Page');
    Route::post('/page/{page}/delete', [PageController::class, 'delete'])->name('page.delete')->middleware('admin_permission:Delete Page');
    Route::post('/page/{page}/destroy', [PageController::class, 'destroy'])->name('page.destroy')->middleware('admin_permission:Delete Page');
    Route::post('/page/{page}/restore', [PageController::class, 'restore'])->name('page.restore')->middleware('admin_permission:Delete Page');
    Route::get('/page', [PageController::class, 'index'])->name('page.index')->middleware('admin_permission:View Page');
    Route::get('/page/create', [PageController::class, 'create'])->name('page.create')->middleware('admin_permission:Create Page');
    Route::post('/page', [PageController::class, 'store'])->name('page.store')->middleware('admin_permission:Create Page');
    Route::get('/page/{page}/edit', [PageController::class, 'edit'])->name('page.edit')->middleware('admin_permission:Edit Page');
    Route::patch('/page/{page}', [PageController::class, 'update'])->name('page.update')->middleware('admin_permission:Edit Page');

    // Media
    Route::post('/media/destroy-all', [MediaController::class, 'destroyAll'])->name('media.destroy-all')->middleware('admin_permission:Delete Media');
    Route::post('/media/{media}/delete', [MediaController::class, 'delete'])->name('media.delete')->middleware('admin_permission:Delete Media');
    Route::post('/media/{media}/destroy', [MediaController::class, 'destroy'])->name('media.destroy')->middleware('admin_permission:Delete Media');
    Route::post('/media/{media}/restore', [MediaController::class, 'restore'])->name('media.restore')->middleware('admin_permission:Delete Media');
    Route::get('/media', [MediaController::class, 'index'])->name('media.index')->middleware('admin_permission:View Media');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store')->middleware('admin_permission:Create Media');
    Route::patch('/media/{media}', [MediaController::class, 'update'])->name('page.update')->middleware('admin_permission:Edit Media');


    Route::get('/administrator/{administrator}/role-permissions', [AdministratorController::class, 'rolePermission'])->name('administrator.rolePermission')->middleware('admin_permission:Edit Admin');
    Route::post('/administrator/{administrator}/role-permissions', [AdministratorController::class, 'assignRolePermission'])->name('administrator.assignRolePermission')->middleware('admin_permission:Edit Admin');
    Route::get('/administrator/{administrator}/password', [AdministratorController::class, 'editPassword'])->name('administrator.editPassword')->middleware('admin_permission:Edit Admin');
    Route::put('/administrator/{administrator}/password', [AdministratorController::class, 'updatePassword'])->name('administrator.updatePassword')->middleware('admin_permission:Edit Admin');
    Route::post('/administrator/{administrator}/delete', [AdministratorController::class, 'delete'])->name('administrator.delete')->middleware('admin_permission:Delete Admin');
    Route::post('/administrator/{administrator}/destroy', [AdministratorController::class, 'destroy'])->name('administrator.forceDelete')->middleware('admin_permission:Delete Admin');
    Route::post('/administrator/{administrator}/restore', [AdministratorController::class, 'restore'])->name('administrator.restore')->middleware('admin_permission:Delete Admin');
    Route::get('/administrator', [AdministratorController::class, 'index'])->name('administrator.index')->middleware('admin_permission:View Admin');
    Route::get('/administrator/create', [AdministratorController::class, 'create'])->name('administrator.create')->middleware('admin_permission:Create Admin');
    Route::post('/administrator', [AdministratorController::class, 'store'])->name('administrator.store')->middleware('admin_permission:Create Admin');
    Route::get('/administrator/{administrator}/edit', [AdministratorController::class, 'edit'])->name('administrator.edit')->middleware('admin_permission:Edit Admin');
    Route::patch('/administrator/{administrator}', [AdministratorController::class, 'update'])->name('administrator.update')->middleware('admin_permission:Edit Admin');
});

Route::get('/login', [AuthAdminController::class, 'create'])->name('admin.login.create');
Route::post('/login', [AuthAdminController::class, 'login'])->name('admin.login');
Route::post('logout', [AuthAdminController::class, 'destroy'])->name('admin.logout');

require __DIR__.'/settings.php';