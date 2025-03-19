<?php

use App\Http\Controllers\Api\userCreateContrller;
use App\Http\Controllers\apiController;
use App\Http\Controllers\apiGaliDesawarController;
use App\Http\Controllers\userApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// user routes start 
// new ankit 

Route::post('halfSangamGameInsert', [userApiController::class, 'halfSangamGameInsert'])->name('halfSangamGameInsert');
Route::post('fullSangamGameInsert', [userApiController::class, 'fullSangamGameInsert'])->name('fullSangamGameInsert');
Route::post('singleDigitGameInsert', [userApiController::class, 'singleDigitGameInsert'])->name('singleDigitGameInsert');
Route::post('singlePannaGameInsert', [userApiController::class, 'singlePannaGameInsert'])->name('singlePannaGameInsert');
Route::post('doublePannaGameInsert', [userApiController::class, 'doublePannaGameInsert'])->name('doublePannaGameInsert');
Route::post('triplePannaGameInsert', [userApiController::class, 'triplePannaGameInsert'])->name('triplePannaGameInsert');
Route::post('jodiGameInsert', [userApiController::class, 'jodiGameInsert'])->name('jodiGameInsert');


Route::post('register', [userApiController::class, 'register_insert'])->name('register_insert');
Route::post('user', [userApiController::class, 'user'])->name('user');
Route::post('login', [userApiController::class, 'login'])->name('login');
Route::get('dashboard', [userApiController::class, 'dashboard'])->name('dashboard');
Route::get('profile', [userApiController::class, 'profile'])->name('profile');
Route::get('wallet', [userApiController::class, 'wallet'])->name('wallet');
Route::get('addPoint', [userApiController::class, 'addPoint'])->name('addPoint');
Route::get('withdrawPoint', [userApiController::class, 'withdrawPoint'])->name('withdrawPoint');
Route::post('withdrawalAmount', [userApiController::class, 'withdrawalAmount'])->name('withdrawalAmount');
// Route::post('historyBid', [userApiController::class, 'historyBid'])->name('historyBid');
Route::post('bidHistoryList', [userApiController::class, 'bidHistoryList'])->name('bidHistoryList');
Route::post('winningHistoryList', [userApiController::class, 'winningHistoryList'])->name('winningHistoryList');
Route::get('gameRates', [userApiController::class, 'gameRates'])->name('gameRates');
Route::get('games/{market_id}', [userApiController::class, 'games'])->name('games');
Route::post('addGame/{gtype_id}/{market_id}', [userApiController::class, 'addGame'])->name('addGame');
Route::post('game_insert', [userApiController::class, 'game_insert'])->name('game_insert');
// Route::get('starline', [userApiController::class, 'starline'])->name('starline');
// Route::get('starline_bid_history', [userApiController::class,'starline_bid_history'])->name('starline_bid_history');
// Route::get('starline_winning_history', [userApiController::class,'starline_winning_history'])->name('starline_winning_history');
// Route::get('starline_games/{starline_id}', [userApiController::class, 'starline_games'])->name('starline_games');
// Route::get('starline_addGame/{starline_gtype_id}/{starline_id}', [userApiController::class, 'starline_addGame'])->name('starline_addGame');
// Route::post('starline_game_insert', [userApiController::class, 'starline_game_insert'])->name('starline_game_insert');


                        //  Gali Desawar apis 

Route::get('galidesawar', [apiGaliDesawarController::class, 'galidesawar'])->name('galidesawar');
Route::get('desawar_bid_history', [apiGaliDesawarController::class,'desawar_bid_history'])->name('desawar_bid_history');
Route::get('desawarWinningHistory', [apiGaliDesawarController::class,'desawarWinningHistory'])->name('desawarWinningHistory');
Route::get('galidesawar_resultChart', [apiGaliDesawarController::class, 'galidesawar_resultChart'])->name('galidesawar_resultChart');
Route::get('galidesawar_games', [apiGaliDesawarController::class, 'galidesawar_games'])->name('galidesawar_games');
Route::get('galidesawar_addGame', [apiGaliDesawarController::class, 'galidesawar_addGame'])->name('galidesawar_addGame');
Route::post('desawar_game_insert', [apiGaliDesawarController::class, 'desawar_game_insert'])->name('desawar_game_insert');

                        // starline apis
Route::get('starline', [apiGaliDesawarController::class, 'starline'])->name('starline');
Route::get('starline_addGame', [apiGaliDesawarController::class, 'starline_addGame'])->name('starline_addGame');
Route::post('starline_game_insert', [apiGaliDesawarController::class, 'starline_game_insert'])->name('starline_game_insert');
Route::get('starline_resultChart', [apiGaliDesawarController::class, 'starline_resultChart'])->name('starline_resultChart');
Route::post('starline_bid_history', [apiGaliDesawarController::class,'starline_bid_history'])->name('starline_bid_history');
Route::post('starline_winning_history', [apiGaliDesawarController::class,'starline_winning_history'])->name('starline_winning_history');


Route::get('winning_history', [userApiController::class, 'winning_history'])->name('winning_history');
Route::post('contact', [userApiController::class, 'contact'])->name('contact');
Route::get('help', [userApiController::class, 'help'])->name('help');
Route::post('resultChart', [userApiController::class, 'resultChart'])->name('resultChart');
Route::get('starline_resultChart/{result_id}', [userApiController::class, 'starline_resultChart'])->name('starline_resultChart');
Route::get('wallet_history', [userApiController::class,'wallet_history'])->name('wallet_history');
Route::post('update_password', [userApiController::class,'update_password'])->name('update_password');
Route::post('deposit', [userApiController::class, 'deposit'])->name('deposit');
Route::post('withdrawal_amount', [userApiController::class,'withdrawal_amount'])->name('withdrawal_amount'); 
Route::post('withdrawal_amount', [userApiController::class,'withdrawal_amount'])->name('withdrawal_amount'); 
Route::post('MarchantId', [userApiController::class,'MarchantId'])->name('MarchantId'); 
Route::post('miniDeposit', [userApiController::class,'miniDeposit'])->name('miniDeposit'); 
Route::post('whatsappNo', [userApiController::class,'whatsappNo'])->name('whatsappNo'); 
Route::post('homeText', [userApiController::class,'homeText'])->name('homeText'); 

Route::post('walletTransactionHistory', [userApiController::class, 'walletTransactionHistory'])->name('walletTransactionHistory');
Route::post('privacyPolicy', [userApiController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::post('desawarWalletPageText', [userApiController::class, 'desawarWalletPageText'])->name('desawarWalletPageText');

Route::get('history_bid', [userApiController::class, 'history_bid'])->name('history_bid');

// user route end

Route::post('admin_login_submit', [apiController::class, 'admin_login_submit'])->name('admin_login_submit');
Route::get('admin_dashboard', [apiController::class, 'adminDashboard'])->name('adminDashboard');
Route::get('declare_result', [apiController::class, 'declare_result'])->name('declare_result');
Route::post('declare_result_data', [apiController::class, 'declare_result_data'])->name('declare_result_data');
Route::get('winning_prediction', [apiController::class, 'winning_prediction'])->name('winning_prediction');
Route::get('winning_prediction_list', [apiController::class, 'winning_prediction_list'])->name('winning_prediction_list');

//======================= Report Management =======================================
Route::get('user_bid_history', [apiController::class, 'user_bid_history'])->name('user_bid_history');
Route::get('bid_history', [apiController::class, 'bid_history'])->name('bid_history');
Route::get('winning_report', [apiController::class, 'winning_report'])->name('winning_report');
Route::get('winning_report_list', [apiController::class, 'winning_report_list'])->name('winning_report_list');
Route::get('bid_winning_report', [apiController::class, 'bid_winning_report'])->name('bid_winning_report');

Route::get('view_user/{user_id}', [apiController::class, 'view_user'])->name('view_user');

Route::post('getUserList', [apiController::class, 'getUserList'])->name('getUserList');


//======================= Wallet Management =======================================
Route::get('withdrawal_request_list', [apiController::class, 'withdrawal_request_list'])->name('withdrawal_request_list');
Route::post('withdrawal_request_approve', [apiController::class, 'withdrawal_request_approve'])->name('withdrawal_request_approve');
Route::post('withdrawal_request_reject', [apiController::class, 'withdrawal_request_reject'])->name('withdrawal_request_reject');
Route::post('delete_withdrawal_request', [apiController::class, 'delete_withdrawal_request'])->name('delete_withdrawal_request');
// Route::get('withdrawal_request_history', [apiController::class, 'withdrawal_request_history'])->name('withdrawal_request_history');

Route::get('fund_request_management_list', [apiController::class, 'fund_request_management_list'])->name('fund_request_management_list');
Route::post('deposite_request_approve', [apiController::class, 'deposite_request_approve'])->name('deposite_request_approve');
Route::post('deposite_request_reject', [apiController::class, 'deposite_request_reject'])->name('deposite_request_reject');
Route::post('delete_deposite_request', [apiController::class, 'delete_deposite_request'])->name('delete_deposite_request');

Route::get('bid_revert', [apiController::class, 'bid_revert'])->name('bid_revert');


// Route::get('deposit_request', [apiController::class, 'deposit_request'])->name('deposit_request');
// Route::post('deposit_request_submit', [apiController::class, 'deposit_request_submit'])->name('deposit_request_submit');
// Route::get('deposit_request_approve/{id}', [apiController::class, 'deposit_request_approve'])->name('deposit_request_approve');
// Route::get('deposit_request_reject/{id}', [apiController::class, 'deposit_request_reject'])->name('deposit_request_reject');
// Route::get('deposit_request_history', [apiController::class, 'deposit_request_history'])->name('deposit_request_history');
// Route::get('deposit_request_view/{id}', [apiController::class, 'deposit_request_view'])->name('deposit_request_view');

//======================= Game Management =======================================
Route::get('gameNameView', [apiController::class, 'gameNameView'])->name('gameNameView');
Route::post('gameName_insert', [apiController::class, 'gameName_insert'])->name('gameName_insert');
Route::post('gameName_search', [apiController::class, 'gameName_search'])->name('gameName_search');

Route::get('game_rates_show', [apiController::class, 'game_rates_show'])->name('game_rates_show');
Route::post('game_rates_insert', [apiController::class, 'game_rates_insert'])->name('game_rates_insert');


//======================= Settings =======================================
Route::get('contact_settings', [apiController::class, 'contact_settings'])->name('contact_settings');
Route::post('insert_contact', [apiController::class, 'insert_contact'])->name('insert_contact');
Route::post('slider_image_insert', [apiController::class, 'slider_image_insert'])->name(name: 'slider_image_insert');
Route::post('slider_data', [apiController::class, 'slider_data'])->name('slider_data');
Route::post('slider_image_approve', [apiController::class, 'slider_image_approve'])->name('slider_image_approve');
Route::post('slider_image_reject', [apiController::class, 'slider_image_reject'])->name('slider_image_reject');
Route::post('delete_slider_image', [apiController::class, 'delete_slider_image'])->name('delete_slider_image');

// =============================== Notice Management ================================
Route::get('send_notification', [apiController::class, 'send_notification'])->name('send_notification');
Route::post('notice_management_insert', [apiController::class, 'notice_management_insert'])->name('notice_management_insert');
Route::get('notice_management_data', [apiController::class, 'notice_management_data'])->name('notice_management_data');
Route::post('delete_notice', [apiController::class, 'delete_notice'])->name('delete_notice');
Route::get('send_notification', [apiController::class, 'send_notification'])->name('send_notification');



// =============================== Starline Management ================================
Route::get('starline_game_name', [apiController::class, 'starline_game_name'])->name('starline_game_name');
Route::get('starline_game_rates', [apiController::class, 'starline_game_rates'])->name('starline_game_rates');
Route::post('starline_game_rates_insert', [apiController::class, 'starline_game_rates_insert'])->name('starline_game_rates_insert');
// Route::post('starline_game_insert', [apiController::class, 'starline_game_insert'])->name('starline_game_insert');

Route::get('starline_user_bid_history', [apiController::class, 'starline_user_bid_history'])->name('starline_user_bid_history');

Route::get('starline_user_bid_history_list', [apiController::class, 'starline_user_bid_history_list'])->name('starline_user_bid_history_list');

Route::get('starline_declare_result', [apiController::class, 'starline_declare_result'])->name('starline_declare_result');
Route::post('starline_result', [apiController::class, 'starline_result'])->name('starline_result');


Route::get('starline_result_history_list', [apiController::class, 'starline_result_history_list'])->name('starline_result_history_list');

Route::get('starline_sell_report', [apiController::class, 'starline_sell_report'])->name('starline_sell_report');

Route::get('starline_winning_report', [apiController::class, 'starline_winning_report'])->name('starline_winning_report');
Route::get('starline_winning_report_list', [apiController::class, 'starline_winning_report_list'])->name('starline_winning_report_list');

Route::get('starline_winning_prediction_list', [apiController::class, 'starline_winning_prediction_list'])->name('starline_winning_prediction_list');




// =============================== Desawar Management ================================
Route::get('desawar_game_name', [apiController::class, 'desawar_game_name'])->name('desawar_game_name');
// Route::post('desawar_game_insert', [apiController::class, 'desawar_game_insert'])->name('desawar_game_insert');

Route::get('desawar_game_rates', [apiController::class, 'desawar_game_rates'])->name('desawar_game_rates');
Route::post('desawar_game_rates_insert', [apiController::class, 'desawar_game_rates_insert'])->name('desawar_game_rates_insert');



Route::get('desawar_user_bid_history_list', [apiController::class, 'desawar_user_bid_history_list'])->name('desawar_user_bid_history_list');

Route::get('desawar_declare_result', [apiController::class, 'desawar_declare_result'])->name('desawar_declare_result');
Route::post('desawar_result', [apiController::class, 'desawar_result'])->name('desawar_result');


Route::get('desawar_result_history_list', [apiController::class, 'desawar_result_history_list'])->name('desawar_result_history_list');


Route::get('desawar_winning_report_list', [apiController::class, 'desawar_winning_report_list'])->name('desawar_winning_report_list');

Route::get('desawar_winning_prediction', [apiController::class, 'desawar_winning_prediction'])->name('desawar_winning_prediction');
Route::get('desawar_winning_prediction_list', [apiController::class, 'desawar_winning_prediction_list'])->name('desawar_winning_prediction_list');




Route::get('users_query', [apiController::class, 'users_query'])->name('users_query');



Route::get('sub_admin_management', [apiController::class, 'sub_admin_management'])->name('sub_admin_management');
Route::post('sub_admin_insert', [apiController::class, 'sub_admin_insert'])->name('sub_admin_insert');



Route::get('matchResultsWithBids', [apiController::class, 'matchResultsWithBids'])->name('matchResultsWithBids');

Route::get('matchResult', [apiController::class, 'matchResult'])->name('matchResult');
Route::get('galiResult', [apiController::class, 'galiResult'])->name('galiResult');

Route::post('/get-market-bid-amount', [apiController::class, 'getMarketBidAmount']);

Route::post('un_approved_users_data', [apiController::class, 'un_approved_users_data'])->name('un_approved_users_data');
Route::post('unapprove_users_approve', [apiController::class, 'unapprove_users_approve'])->name('unapprove_users_approve');
Route::post('unapprove_users_unapprove', [apiController::class, 'unapprove_users_unapprove'])->name('unapprove_users_unapprove');
Route::post('delete_unapprove_users', [apiController::class, 'delete_unapprove_users'])->name('delete_unapprove_users');
