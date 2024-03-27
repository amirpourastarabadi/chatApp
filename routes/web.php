<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Users\Index as UserIndex;
use App\Livewire\Chat\Index as ChatIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Chat\Chat;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/chats', ChatIndex::class)->name('chats.index');
Route::get('/chats/{chat}', Chat::class)->name('chats.show');

Route::get('/users', UserIndex::class)->name('users.index');