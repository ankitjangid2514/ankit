<?php

use App\Http\Controllers\gameController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;

require base_path('routes/admin.php');



// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UserController::class, 'download'])->name('download');
Route::get('privacyPolicy', [UserController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register_insert', [UserController::class, 'register_insert'])->name('register_insert');

Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login_insert', [UserController::class, 'login_insert'])->name('login_insert');

Route::middleware('auto_logout')->group(function () {




    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('wallet', [UserController::class, 'wallet'])->name('wallet');
    Route::get('addPoint', [UserController::class, 'addPoint'])->name('addPoint');
    Route::post('deposit', [UserController::class, 'deposit'])->name('deposit');

    Route::get('withdrawPoint', [UserController::class, 'withdrawPoint'])->name('withdrawPoint');
    Route::post('withdrawal_amount', [UserController::class, 'withdrawal_amount'])->name('withdrawal_amount');

    // Route::get('history_win', [UserController::class, 'history_win'])->name('history_win');
    Route::get('history_bid', [UserController::class, 'history_bid'])->name('history_bid');
    Route::POSt('bid_history_list', [UserController::class, 'bid_history_list'])->name('bid_history_list');
    Route::get('winning_history', [UserController::class, 'winning_history'])->name('winning_history');
    Route::post('winning_history_list', [UserController::class, 'winning_history_list'])->name('winning_history_list');


    Route::get('gameRates', [UserController::class, 'gameRates'])->name('gameRates');
    Route::get('contact', [UserController::class, 'contact'])->name('contact');
    Route::get('help', [UserController::class, 'help'])->name('help');
    Route::get('password', [UserController::class, 'password'])->name('password');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    Route::get('resultChart/{result_id}', [UserController::class, 'resultChart'])->name('resultChart');

    Route::get('games/{market_id}', [UserController::class, 'games'])->name('games');
    Route::get('addGame/{gtype_id}/{market_id}', [UserController::class, 'addGame'])->name('addGame');

    Route::get('getBalance', [UserController::class, 'getBalance'])->name('getBalance');

    // Route::post('game_insert', [UserController::class, 'game_insert'])->name('game_insert');
    Route::post('singleDigitGame_insert', [gameController::class, 'singleDigitGame_insert'])->name('singleDigitGame_insert');
    Route::post('singlePannaGame_insert', [gameController::class, 'singlePannaGame_insert'])->name('singlePannaGame_insert');
    Route::post('doublePannaGame_insert', [gameController::class, 'doublePannaGame_insert'])->name('doublePannaGame_insert');
    Route::post('triplePannaGame_insert', [gameController::class, 'triplePannaGame_insert'])->name('triplePannaGame_insert');
    Route::post('halfSangamGame_insert', [gameController::class, 'halfSangamGame_insert'])->name('halfSangamGame_insert');
    Route::post('fullSangamGame_insert', [gameController::class, 'fullSangamGame_insert'])->name('fullSangamGame_insert');
    Route::post('jodiGame_insert', [gameController::class, 'jodiGame_insert'])->name('jodiGame_insert');
    Route::post('spPannaMotorGame_insert ', [gameController::class, 'spPannaMotorGame_insert'])->name('spPannaMotorGame_insert');
    Route::post('game_insert_game', [gameController::class, 'game_insert_game'])->name('game_insert_game');



    Route::get('starline', [UserController::class, 'starline'])->name('starline');
    Route::get('starline_bid_history', [UserController::class, 'starline_bid_history'])->name('starline_bid_history');
    Route::get('starline_winning_history', [UserController::class, 'starline_winning_history'])->name('starline_winning_history');

    Route::get('starline_resultChart/{result_id}', [UserController::class, 'starline_resultChart'])->name('starline_resultChart');

    Route::get('starline_games/{starline_id}', [UserController::class, 'starline_games'])->name('starline_games');
    Route::post('starline_game_insert', [UserController::class, 'starline_game_insert'])->name('starline_game_insert');
    Route::get('starline_addGame/{starline_gtype_id}/{starline_id}', [UserController::class, 'starline_addGame'])->name('starline_addGame');



    // =============================== Desawar Management ================================
    Route::get('galidesawar', [UserController::class, 'galidesawar'])->name('galidesawar');
    Route::post('desawar_game_insert', [UserController::class, 'desawar_game_insert'])->name('desawar_game_insert');

    Route::get('galidesawar_resultChart/{result_id}', [UserController::class, 'galidesawar_resultChart'])->name('galidesawar_resultChart');

    Route::get('galidesawar_games/{desawar_id}', [UserController::class, 'galidesawar_games'])->name('galidesawar_games');
    Route::get('galidesawar_addGame/{desawar_gtype_id}/{desawar_id}', [UserController::class, 'galidesawar_addGame'])->name('galidesawar_addGame');
    Route::get('desawar_bid_history', [UserController::class, 'desawar_bid_history'])->name('desawar_bid_history');
    Route::get('desawar_winning_history', [UserController::class, 'desawar_winning_history'])->name('desawar_winning_history');

    Route::get('wallet_history', [UserController::class, 'wallet_history'])->name('wallet_history');

    Route::post('update_password', [UserController::class, 'update_password'])->name('update_password');
});

// Add Point Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/add-point', [PaymentController::class, 'showAddPoint'])->name('add.point');
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
    Route::get('/payment-status', [PaymentController::class, 'paymentStatus'])->name('payment.status');
});
