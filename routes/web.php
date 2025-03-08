<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('home', function () {
//     return view('home');
// });

// use Illuminate\Support\Facades\DB;

// Route::get('/check-database', function () {
//     try {
//         DB::connection()->getPdo();
//         return "Database connection is successful!";
//     } catch (\Exception $e) {
//         return "Could not connect to the database. Error: " . $e->getMessage();
//     }
// });

Route::post('/import-students', [StudentController::class, 'import']);
Route::get('/upload-students', [StudentController::class, 'showUploadForm']);

Route::get('/view-data', [StudentController::class, 'viewData']);
Route::get('/students-data', [StudentController::class, 'getStudents']);
Route::delete('/delete-student/{id}', [StudentController::class, 'deleteStudent']);

