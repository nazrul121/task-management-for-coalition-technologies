<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('task/delete/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('task/show/{task}', [TaskController::class, 'show'])->name('task.show');
Route::get('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
