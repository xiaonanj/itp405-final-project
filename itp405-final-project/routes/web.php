<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\ScoreEntryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WorldRankingController;

Route::get('/register', [RegistrationController::class, 'index'])->name('registration.index');
Route::post('/register', [RegistrationController::class, 'register'])->name('registration.create');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [RoundController::class, 'index'])->name('rounds.index');

    Route::get('/rounds/create', [RoundController::class, 'create'])->name('rounds.create');
    Route::post('/rounds', [RoundController::class, 'store'])->name('rounds.store');

    Route::get('/rounds/{round}/scores/create', [ScoreEntryController::class, 'create'])->name('scores.create');
    Route::post('/rounds/{round}/scores', [ScoreEntryController::class, 'store'])->name('scores.store');

    Route::get('/rounds/{round}', [RoundController::class, 'show'])->name('rounds.show');
    
    Route::delete('/rounds/{round}', [RoundController::class, 'destroy'])->name('rounds.destroy');

    Route::get('/stats', action: [StatsController::class, 'index'])->name('stats.index');

    Route::post('/rounds/{round}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/rounds/{round}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/rounds/{round}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/rounds/{round}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    Route::post('/favorites/{round}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

Route::get('/world-rankings', [WorldRankingController::class, 'index'])->name('world-rankings.index');



