<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index');

    // Page
    Route::get('/page/trash', [App\Http\Controllers\Backend\PageController::class, 'trash'])->name('page.trash');
    Route::get('/page/trash/count', [App\Http\Controllers\Backend\PageController::class, 'countTrash'])->name('page.countTrash');
    Route::post('/page/destroy-all', [App\Http\Controllers\Backend\PageController::class, 'destroyAll'])->name('page.destroy-all');
    Route::post('/page/{page}/delete', [App\Http\Controllers\Backend\PageController::class, 'delete'])->name('page.delete');
    Route::post('/page/{page}/destroy', [App\Http\Controllers\Backend\PageController::class, 'destroy'])->name('page.forceDelete');
    Route::post('/page/{page}/restore', [App\Http\Controllers\Backend\PageController::class, 'restore'])->name('page.restore');
    Route::resource('/page', App\Http\Controllers\Backend\PageController::class);


    // Media
    Route::get('/media/trash', [App\Http\Controllers\Backend\MediaController::class, 'trash'])->name('media.trash');
    Route::get('/media/trash/count', [App\Http\Controllers\Backend\MediaController::class, 'countTrash'])->name('media.countTrash');
    Route::post('/media/destroy-all', [App\Http\Controllers\Backend\MediaController::class, 'destroyAll'])->name('media.destroy-all');
    Route::post('/media/{media}/delete', [App\Http\Controllers\Backend\MediaController::class, 'delete'])->name('media.delete');
    Route::post('/media/{media}/destroy', [App\Http\Controllers\Backend\MediaController::class, 'destroy'])->name('media.forceDelete');
    Route::post('/media/{media}/restore', [App\Http\Controllers\Backend\MediaController::class, 'restore'])->name('media.restore');
    Route::apiResource('/media', App\Http\Controllers\Backend\MediaController::class);

    Route::get('/change-password', [App\Http\Controllers\Backend\AdminstratorController::class, 'changePassword'])->name('administrator.change.password');
    Route::post('/update-password', [App\Http\Controllers\Backend\AdminstratorController::class, 'updatePassword'])->name('administrator.update.password');
    Route::resource('/administrator', App\Http\Controllers\Backend\AdminstratorController::class);
    Route::post('/administrator/{administrator}/delete', [App\Http\Controllers\Backend\AdminstratorController::class, 'delete'])->name('administrator.delete');
    Route::post('/administrator/{administrator}/destroy', [App\Http\Controllers\Backend\AdminstratorController::class, 'destroy'])->name('administrator.forceDelete');
    Route::post('/administrator/{administrator}/restore', [App\Http\Controllers\Backend\AdminstratorController::class, 'restore'])->name('administrator.restore');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
