<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('admin_login', [AdminController::class, 'admin_login'])->name('admin_login');
Route::post('admin_login_submit', [AdminController::class, 'admin_login_submit'])->name('admin_login_submit');

Route::get('adminLogout', [AdminController::class, 'adminLogout'])->name('adminLogout');

Route::middleware(['auth'])->group(function () {

Route::get('admin_dashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');
Route::get('change_password', [AdminController::class, 'change_password'])->name('change_password');
Route::get('history_bid_user', [AdminController::class, 'history_bid_user'])->name('history_bid_user');

Route::get('declare_result', [AdminController::class, 'declare_result'])->name('declare_result');
Route::post('declare_result_data', [AdminController::class, 'declare_result_data'])->name('declare_result_data');
Route::get('result_history', [AdminController::class, 'result_history'])->name('result_history');

Route::get('winning_prediction', [AdminController::class, 'winning_prediction'])->name('winning_prediction');
Route::get('winning_prediction_list', [AdminController::class, 'winning_prediction_list'])->name('winning_prediction_list');
Route::get('download-apk', [AdminController::class, 'download-apk'])->name('download-apk');

Route::post('admin_password',[AdminController::class, 'admin_password'])->name('admin_password');


//======================= Report Management =======================================
Route::get('user_bid_history', [AdminController::class, 'user_bid_history'])->name('user_bid_history');
Route::get('bid_history', [AdminController::class, 'bid_history'])->name('bid_history');


Route::get('winning_report', [AdminController::class, 'winning_report'])->name('winning_report');
Route::get('winning_report_list', [AdminController::class, 'winning_report_list'])->name('winning_report_list');


Route::get('transfer_point_report', [AdminController::class, 'transfer_point_report'])->name('transfer_point_report');

Route::get('bid_winning_report', [AdminController::class, 'bid_winning_report'])->name('bid_winning_report');

Route::get('withdraw_report', [AdminController::class, 'withdraw_report'])->name('withdraw_report');
Route::get('auto_deposite_history', [AdminController::class, 'auto_deposite_history'])->name('auto_deposite_history');
Route::post('autoDepositReport', [AdminController::class, 'autoDepositReport'])->name('autoDepositReport');
Route::get('get_referral_amount_data', [AdminController::class, 'get_referral_amount_data'])->name('get_referral_amount_data');





Route::get('user_management', [AdminController::class, 'user_management'])->name('user_management');
Route::get('view_user/{user_id}', [AdminController::class, 'view_user'])->name('view_user');
Route::post('getUserList', [AdminController::class, 'getUserList'])->name('getUserList');


//======================= Wallet Management =======================================
Route::get('withdrawal_request', [AdminController::class, 'withdrawal_request'])->name('withdrawal_request');
Route::get('withdrawal_request_list', [AdminController::class, 'withdrawal_request_list'])->name('withdrawal_request_list');
// Route::post('withdrawal_request_submit', [AdminController::class, 'withdrawal_request_submit'])->name('withdrawal_request_submit');
Route::post('withdrawal_request_approve', [AdminController::class, 'withdrawal_request_approve'])->name('withdrawal_request_approve');
Route::post('withdrawal_request_reject', [AdminController::class, 'withdrawal_request_reject'])->name('withdrawal_request_reject');
Route::post('delete_withdrawal_request', [AdminController::class, 'delete_withdrawal_request'])->name('delete_withdrawal_request');
// Route::get('withdrawal_request_history', [AdminController::class, 'withdrawal_request_history'])->name('withdrawal_request_history');
Route::get('withdrawal_request_view/{id}', [AdminController::class, 'withdrawal_request_view'])->name('withdrawal_request_view');
Route::get('withdrawal_request_edit/{id}', [AdminController::class, 'withdrawal_request_edit'])->name('withdrawal_request_edit');

Route::get('fund_request_management', [AdminController::class, 'fund_request_management'])->name('fund_request_management');
Route::get('fund_request_management_list', [AdminController::class, 'fund_request_management_list'])->name('fund_request_management_list');

Route::post('deposite_request_approve', [AdminController::class, 'deposite_request_approve'])->name('deposite_request_approve');
Route::post('deposite_request_reject', [AdminController::class, 'deposite_request_reject'])->name('deposite_request_reject');
Route::post('delete_deposite_request', [AdminController::class, 'delete_deposite_request'])->name('delete_deposite_request');

Route::get('add_fund_user_wallet_management', [AdminController::class, 'add_fund_user_wallet_management'])->name('add_fund_user_wallet_management');
Route::get('bid_revert', [AdminController::class, 'bid_revert'])->name('bid_revert');
Route::post('bid_data', [AdminController::class, 'bid_data'])->name('bid_data');
Route::post('bid_revert_data', [AdminController::class, 'bid_revert_data'])->name('bid_revert_data');


// Route::get('deposit_request', [AdminController::class, 'deposit_request'])->name('deposit_request');
// Route::post('deposit_request_submit', [AdminController::class, 'deposit_request_submit'])->name('deposit_request_submit');
// Route::get('deposit_request_approve/{id}', [AdminController::class, 'deposit_request_approve'])->name('deposit_request_approve');
// Route::get('deposit_request_reject/{id}', [AdminController::class, 'deposit_request_reject'])->name('deposit_request_reject');
// Route::get('deposit_request_history', [AdminController::class, 'deposit_request_history'])->name('deposit_request_history');
// Route::get('deposit_request_view/{id}', [AdminController::class, 'deposit_request_view'])->name('deposit_request_view');

//======================= Game Management =======================================
Route::get('gameNameView', [AdminController::class, 'gameNameView'])->name('gameNameView');
Route::post('gameName_insert', [AdminController::class, 'gameName_insert'])->name('gameName_insert');
Route::post('gameName_search', [AdminController::class, 'gameName_search'])->name('gameName_search');

Route::get('game_rates_show', [AdminController::class, 'game_rates_show'])->name('game_rates_show');
Route::post('game_rates_insert', [AdminController::class, 'game_rates_insert'])->name('game_rates_insert');

//======================= Games & Numbers =======================================
Route::get('single_digit', [AdminController::class, 'single_digit'])->name('single_digit');
Route::get('jodi_digit', [AdminController::class, 'jodi_digit'])->name('jodi_digit');
Route::get('single_pana', [AdminController::class, 'single_pana'])->name('single_pana');
Route::get('double_pana', [AdminController::class, 'double_pana'])->name('double_pana');
Route::get('tripple_pana', [AdminController::class, 'tripple_pana'])->name('tripple_pana');
Route::get('half_sangam', [AdminController::class, 'half_sangam'])->name('half_sangam');
Route::get('full_sangam', [AdminController::class, 'full_sangam'])->name('full_sangam');

//======================= Settings =======================================
Route::get('main_settings', [AdminController::class, 'main_settings'])->name('main_settings');
Route::post('set_amount', [AdminController::class, 'setAmount'])->name('set_amount');
Route::post('updateMarchantId', [AdminController::class, 'updateMarchantId'])->name('updateMarchantId');
Route::post('updateHometext', [AdminController::class, 'updateHometext'])->name('updateHometext');
Route::post('addWalletPageText', [AdminController::class, 'addWalletPageText'])->name('addWalletPageText');
Route::post('UpdateprivacyPolicy', [AdminController::class, 'UpdateprivacyPolicy'])->name('UpdateprivacyPolicy');
Route::get('contact_settings', [AdminController::class, 'contact_settings'])->name('contact_settings');
Route::post('insert_contact', [AdminController::class, 'insert_contact'])->name('insert_contact');
Route::get('clear_data', [AdminController::class, 'clear_data'])->name('clear_data');

Route::get('slider_images_management', [AdminController::class, 'slider_images_management'])->name('slider_images_management');
Route::post('slider_image_insert', [AdminController::class, 'slider_image_insert'])->name(name: 'slider_image_insert');
Route::post('slider_data', [AdminController::class, 'slider_data'])->name('slider_data');
Route::post('slider_image_approve', [AdminController::class, 'slider_image_approve'])->name('slider_image_approve');
Route::post('slider_image_reject', [AdminController::class, 'slider_image_reject'])->name('slider_image_reject');
Route::post('delete_slider_image', [AdminController::class, 'delete_slider_image'])->name('delete_slider_image');
Route::post('delete_user', [AdminController::class, 'delete_user'])->name('delete_user');


Route::get('how_to_play', [AdminController::class, 'how_to_play'])->name('how_to_play');



// =============================== Notice Management ================================
Route::get('notice_management', [AdminController::class, 'notice_management'])->name('notice_management');
Route::post('notice_management_insert', [AdminController::class, 'notice_management_insert'])->name('notice_management_insert');
Route::get('notice_management_data', [AdminController::class, 'notice_management_data'])->name('notice_management_data');

Route::post('delete_notice', [AdminController::class, 'delete_notice'])->name('delete_notice');

Route::get('send_notification', [AdminController::class, 'send_notification'])->name('send_notification');



// =============================== Starline Management ================================
Route::get('starline_game_name', [AdminController::class, 'starline_game_name'])->name('starline_game_name');
Route::get('starline_game_rates', [AdminController::class, 'starline_game_rates'])->name('starline_game_rates');
Route::post('starline_game_rates_insert', [AdminController::class, 'starline_game_rates_insert'])->name('starline_game_rates_insert');
Route::post('starline_market_insert', [AdminController::class, 'starline_market_insert'])->name('starline_market_insert');
Route::post('starline_game_data', [AdminController::class, 'starline_game_data'])->name('starline_game_data');
Route::post('inactive_starline_market', [AdminController::class, 'inactive_starline_market'])->name('inactive_starline_market');
Route::post('active_starline_market', [AdminController::class, 'active_starline_market'])->name('active_starline_market');
Route::post('delete_starline_market', [AdminController::class, 'delete_starline_market'])->name('delete_starline_market');
Route::post('edit_starline_market', [AdminController::class, 'edit_starline_market'])->name('edit_starline_market');


Route::get('starline_user_bid_history', [AdminController::class, 'starline_user_bid_history'])->name('starline_user_bid_history');

Route::get('starline_user_bid_history_list', [AdminController::class, 'starline_user_bid_history_list'])->name('starline_user_bid_history_list');

Route::get('starline_declare_result', [AdminController::class, 'starline_declare_result'])->name('starline_declare_result');
Route::post('starline_result', [AdminController::class, 'starline_result'])->name('starline_result');
Route::post('starline_result_guess', [AdminController::class, 'starline_result_guess'])->name('starline_result_guess');
Route::get('matchResultsWithBids_guess', [AdminController::class, 'matchResultsWithBids_guess'])->name('matchResultsWithBids_guess');
Route::post('starlineBidEdit', [AdminController::class, 'starlineBidEdit'])->name('starlineBidEdit');


Route::get('starline_result_history', [AdminController::class, 'starline_result_history'])->name('starline_result_history');
Route::get('starline_result_history_list', [AdminController::class, 'starline_result_history_list'])->name('starline_result_history_list');


Route::get('starline_winning_report', [AdminController::class, 'starline_winning_report'])->name('starline_winning_report');
Route::get('starline_winning_report_list', [AdminController::class, 'starline_winning_report_list'])->name('starline_winning_report_list');

Route::get('starline_winning_prediction', [AdminController::class, 'starline_winning_prediction'])->name('starline_winning_prediction');
Route::get('starline_winning_prediction_list', [AdminController::class, 'starline_winning_prediction_list'])->name('starline_winning_prediction_list');




// =============================== Desawar Management ================================
Route::get('desawar_game_name', [AdminController::class, 'desawar_game_name'])->name('desawar_game_name');
Route::post('desawar_market_insert', [AdminController::class, 'desawar_market_insert'])->name('desawar_market_insert');
Route::post('desawar_game_data', [AdminController::class, 'desawar_game_data'])->name('desawar_game_data');
Route::post('inactive_desawar_market', [AdminController::class, 'inactive_desawar_market'])->name('inactive_desawar_market');
Route::post('active_desawar_market', [AdminController::class, 'active_desawar_market'])->name('active_desawar_market');
Route::post('delete_desawar_market', [AdminController::class, 'delete_desawar_market'])->name('delete_desawar_market');
Route::post('edit_desawar_market', [AdminController::class, 'edit_desawar_market'])->name('edit_desawar_market');

Route::get('desawar_game_rates', [AdminController::class, 'desawar_game_rates'])->name('desawar_game_rates');
Route::post('desawar_game_rates_insert', [AdminController::class, 'desawar_game_rates_insert'])->name('desawar_game_rates_insert');


Route::get('desawar_user_bid_history', [AdminController::class, 'desawar_user_bid_history'])->name('desawar_user_bid_history');

Route::get('desawar_user_bid_history_list', [AdminController::class, 'desawar_user_bid_history_list'])->name('desawar_user_bid_history_list');

Route::get('desawar_declare_result', [AdminController::class, 'desawar_declare_result'])->name('desawar_declare_result');
Route::post('desawar_result', [AdminController::class, 'desawar_result'])->name('desawar_result');


Route::get('desawar_result_history', [AdminController::class, 'desawar_result_history'])->name('desawar_result_history');
Route::get('desawar_result_history_list', [AdminController::class, 'desawar_result_history_list'])->name('desawar_result_history_list');

Route::get('desawar_sell_report', [AdminController::class, 'desawar_sell_report'])->name('desawar_sell_report');

Route::get('desawar_winning_report', [AdminController::class, 'desawar_winning_report'])->name('desawar_winning_report');
Route::get('desawar_winning_report_list', [AdminController::class, 'desawar_winning_report_list'])->name('desawar_winning_report_list');

Route::get('desawar_winning_prediction', [AdminController::class, 'desawar_winning_prediction'])->name('desawar_winning_prediction');
Route::get('desawar_winning_prediction_list', [AdminController::class, 'desawar_winning_prediction_list'])->name('desawar_winning_prediction_list');


Route::get('game_name', [AdminController::class, 'game_name'])->name('game_name');
Route::post('showDesawarwinner', [AdminController::class, 'showDesawarwinner'])->name('showDesawarwinner');
Route::get('galiResultshow/{game_id}', [AdminController::class, 'galiResultshow'])->name('galiResultshow');
Route::get('showDesawarwinnerEdit', [AdminController::class, 'showDesawarwinnerEdit'])->name('showDesawarwinnerEdit');
// Web Routes (routes/web.php)
Route::post('/updateDesawarwinner', [AdminController::class, 'updateDesawarWinner'])->name('desawarwinner.update');





// Route::get('sub_admin_management', [AdminController::class, 'sub_admin_management'])->name('sub_admin_management');
// Route::post('sub_admin_insert', [AdminController::class, 'sub_admin_insert'])->name('sub_admin_insert');



Route::get('matchResultsWithBids', [AdminController::class, 'matchResultsWithBids'])->name('matchResultsWithBids');

Route::get('matchResult/{game_id}/{marketStatus}', [AdminController::class, 'matchResult'])->name('matchResult');
Route::get('galiResult/{game_id}', [AdminController::class, 'galiResult'])->name('galiResult');



Route::post('/get-market-bid-amount', [AdminController::class, 'getMarketBidAmount']);

Route::get('un_approved_users_list', [AdminController::class, 'un_approved_users_list'])->name('un_approved_users_list');
Route::post('un_approved_users_data', [AdminController::class, 'un_approved_users_data'])->name('un_approved_users_data');
Route::post('unapprove_users_approve', [AdminController::class, 'unapprove_users_approve'])->name('unapprove_users_approve');
Route::post('unapprove_users_unapprove', [AdminController::class, 'unapprove_users_unapprove'])->name('unapprove_users_unapprove');
Route::post('delete_unapprove_users', [AdminController::class, 'delete_unapprove_users'])->name('delete_unapprove_users');







Route::post('edit_market', [AdminController::class, 'edit_market'])->name('edit_market');
Route::post('inactive_market', [AdminController::class, 'inactive_market'])->name('inactive_market');
Route::post('active_market', [AdminController::class, 'active_market'])->name('active_market');
Route::post('delete_market', [AdminController::class, 'delete_market'])->name('delete_market');

Route::post('add_fund', [Admincontroller::class, 'add_fund'])->name('add_fund');
Route::post('withdraw_fund', [Admincontroller::class, 'withdraw_fund'])->name('withdraw_fund');

Route::get('show_winner', [Admincontroller::class, 'show_winner'])->name('show_winner');
Route::get('winner_list_aj', [Admincontroller::class, 'winner_list_aj'])->name('winner_list_aj');

Route::post('edit_bid', [Admincontroller::class, 'edit_bid'])->name('edit_bid');


Route::get('show_winner_close', [Admincontroller::class, 'show_winner_close'])->name('show_winner_close');

 
Route::delete('delete_result', [Admincontroller::class, 'delete_result'])->name('delete_result');


Route::get('approved_transiction', [Admincontroller::class, 'approved_transiction'])->name('approved_transiction');

Route::get('approved_debit', [Admincontroller::class, 'approved_debit'])->name('approved_debit');
Route::get('approved_credit', [Admincontroller::class, 'approved_credit'])->name('approved_credit');

Route::post('close_edit_bid', [Admincontroller::class, 'close_edit_bid'])->name('close_edit_bid');

Route::post('add_fund_user_wallet', [Admincontroller::class, 'add_fund_user_wallet'])->name('add_fund_user_wallet');

Route::get('auto_deposite_history_list', [Admincontroller::class, 'auto_deposite_history_list'])->name('auto_deposite_history_list');

Route::delete('desawar_delete_result', [Admincontroller::class, 'desawar_delete_result'])->name('desawar_delete_result');

Route::delete('starline_delete_result', [Admincontroller::class, 'starline_delete_result'])->name('starline_delete_result');

});