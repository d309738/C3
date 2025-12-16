<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MatchApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// GET upcoming matches (no score set yet or scheduled in future)
Route::get('matches/upcoming', [MatchApiController::class, 'upcoming']);

// GET played matches (have scores)
Route::get('matches/played', [MatchApiController::class, 'played']);

// POST result for a match (update scores and team points)
Route::post('matches/{match}/result', [MatchApiController::class, 'storeResult']);
