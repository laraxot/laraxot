<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Caricamento automatico rotte API moduli
Route::middleware('api')->group(function () {
    // Carica rotte API del modulo TechPlanner
    if (file_exists(base_path('Modules/TechPlanner/routes/api.php'))) {
        require base_path('Modules/TechPlanner/routes/api.php');
    }
    // Carica rotte API del modulo Xot
    if (file_exists(base_path('Modules/Xot/routes/api.php'))) {
        require base_path('Modules/Xot/routes/api.php');
    }
});
