<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use Illuminate\Console\Scheduling\EventMutex;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/agenda', [EventController::class, 'index']);
Route::get('/agenda/create', [EventController::class, 'create']);
Route::post('/agenda/salvar', [EventController::class, 'store']);
Route::delete('/agenda/excluir/{idAgendamento}', [EventController::class, 'destroy']);
Route::put('/agenda/update/{id}', [EventController::class, 'update']);

Route::resource('agenda', EventController::class);
