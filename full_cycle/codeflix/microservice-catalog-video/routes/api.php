<?php

use App\Http\Controllers\Api\CastMemberController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:api', 'can:admin-catalog'])->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/genres', GenreController::class);
    Route::apiResource('/cast_members', CastMemberController::class);
    Route::apiResource('/videos', VideoController::class);
// });

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});
