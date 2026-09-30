<?php

use App\Modules\Events\Presentation\Http\Controllers\EventCategoryController;
use App\Modules\Events\Presentation\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('{id}', [EventController::class, 'show'])->name('show')->whereNumber('id');
    Route::post('/', [EventController::class, 'create'])->name('create');
    Route::put('{id}', [EventController::class, 'update'])->name('update')->whereNumber('id');
    Route::delete('{id}', [EventController::class, 'delete'])->name('delete')->whereNumber('id');
});

Route::prefix('event-categories')->name('event-categories.')->group(function () {
    Route::get('/', [EventCategoryController::class, 'index'])->name('index');
    Route::get('{id}', [EventCategoryController::class, 'show'])->name('show')->whereNumber('id');
    Route::post('/', [EventCategoryController::class, 'create'])->name('create');
    Route::put('{id}', [EventCategoryController::class, 'update'])->name('update')->whereNumber('id');
    Route::delete('{id}', [EventCategoryController::class, 'delete'])->name('delete')->whereNumber('id');
});
