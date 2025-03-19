<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class userApiController extends Controller
{

    public function login(Request $request)
    {
        // Validation rules
        $rules = [
            'mobile' => 'required',
            'password' => 'required',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Attempt to log the user in
            // dd($request->all());
            if (Auth::attempt(['number' => $request->mobile, 'password' => $request->password])) {
                // Authentication passed
                $user = Auth::user();
                $userId = Auth::id();
                $blance = DB::table('wallets')
                    ->join('withdrawal', 'wallets.user_id', '=', 'withdrawal.user_id')
                    ->select('wallets.*', 'withdrawal.amount', 'withdrawal.status', 'withdrawal.withdrawal_date')
                    ->where('wallets.user_id', $userId)
                    ->first();
                $setting = DB::table('admin')
                    ->select('mobile_number', 'whatsapp_number', 'telegram_link')
                    ->first();
                return response()->json([
                    'success' => 'User logged in successfully!',
                    'setting' => $setting,
                    'user' => $user,
                    'blance' => $blance
                ], 200);
            } else {
                // Authentication failed
                return response()->json(['error' => 'Invalid details', 'code' => '401'], 401);
            }
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json(['error' => 'Login failed!', 'message' => $e->getMessage()], 500);
        }
    }
    public function user(Request $request)
    {
        try {
            // Attempt to log the user in
            // Authentication passed
            $userId = $request->input('id');
            $user = DB::table('users')->find($userId);

            $blance = DB::table('wallets')
            ->where('user_id', $userId)
            ->select('balance')
            ->first();
        // dd($balance);
        // $blance = $balance ? $balance->balance : 0; // Ensure it's not null
        
        
            $setting = DB::table('admin')
                ->select('mobile_number', 'whatsapp_number', 'telegram_link')
                ->first();
            return response()->json([
                'setting' => $setting,
                'user' => $user,
                'blance' => $blance
            ], 200);
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json(['error' => 'Login failed!', 'message' => $e->getMessage()], 500);
        }
    }

    public function register_insert(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'name' => 'required',
                'mobile' => 'required',
                'password' => 'required',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            // If validation fails, return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Prepare user data
            $data = [
                'name' => $request->name,
                'number' => $request->mobile,
                'password' => Hash::make($request->password),
                'type' => 'user',
                'status' => 'unapproved',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert the user data and get the user ID
            $userId = DB::table('users')->insertGetId($data);

            // Get welcome bonus amount
            $bonus = DB::table('set_amount')->value('welcome_bonus');

            // Check if user insertion was successful
            if ($userId) {
                // Prepare wallet data
                $walletData = [
                    'user_id' => $userId,
                    'balance' => $bonus,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert into the wallet table
                DB::table('wallets')->insert($walletData);

                // Return success response
                return response()->json([
                    'status' => 'success',
                    'message' => 'User registered and wallet credited successfully!',
                ], 201);
            } else {
                // If user insertion failed
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to register user.',
                ], 500);
            }
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function fullSangamGameInsert(Request $request)
    {
        // Debugging: Log the request data
        // Log::debug('Received Request:', $request->all());

        try {
            // $user_id = Auth::id();
            $user_id = $request->input('user_id');
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request
            $validated = $request->validate([
                'gdate' => 'required',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid amount must be at least $min_bid_amount."
                ], 400);
            }

            // Prepare the data for insertion
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => "N/A",
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $open_panna = $request->input('open_panna_full_sangam');
            $close_panna = $request->input('close_panna_full_sangam');
            $data['full_sangam'] = $open_panna && $close_panna ? "$open_panna - $close_panna" : 'N/A';

            DB::beginTransaction();

            // Check wallet balance
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400);
            }

            // Insert the bid and update the wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general errors
            // Log::error('An error occurred while placing a bid:', ['error' => $e->getMessage()]);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }






    public function bidHistoryList(Request $request)
    {
        try {
            $userId = $request->input('user_id');
    
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }
    
            // Validate and normalize the date
            try {
                $request->validate([
                    'date' => 'required',
                ]);
    
                $inputDate = $request->input('date');
                $date = Carbon::createFromFormat('d/m/Y', $inputDate)->format('Y-m-d');
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                    'provided_date' => $request->input('date'),
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format.',
                ], 422);
            }
    
            // Fetch bids from the database
            $bids = DB::table('bid_table')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->where('bid_table.user_id', $userId)
                ->whereDate('bid_table.bid_date', '=', $date)
                ->select(
                    'market.market_name',
                    'bid_table.session',
                    'bid_table.open_digit',
                    'bid_table.close_digit',
                    'bid_table.jodi',
                    'bid_table.open_panna',
                    'bid_table.close_panna',
                    'bid_table.half_sangam_a',
                    'bid_table.half_sangam_b',
                    'bid_table.full_sangam',
                    'bid_table.amount',
                    'gtype.gtype',
                    'bid_table.bid_date'
                )
                ->get();
    
            $totalCount = $bids->count();
    
            if ($bids->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bid history found for the selected date.',
                    'date' => $inputDate,
                ], 404);
            }
    
            // Helper to validate bid values
            $isValidValue = function ($value) {
                return $value !== 'N/A' && $value !== 'Null' && $value !== null && $value !== '' && $value !== '0';
            };
    
            // Process bids into `bidArray`
            $bidArray = $bids->map(function ($bid) use ($isValidValue) {
                $gtype = trim($bid->gtype);
                $bidNumber = null;
    
                if ($isValidValue($bid->open_digit) && $gtype === 'Single Digit' && $bid->session === 'open') {
                    $bidNumber = $bid->open_digit;
                } elseif ($isValidValue($bid->close_digit) && $gtype === 'Single Digit' && $bid->session === 'close') {
                    $bidNumber = $bid->close_digit;
                } elseif ($isValidValue($bid->close_panna) && $gtype === 'Single Panna' && $bid->session === 'close') {
                    $bidNumber = $bid->close_panna;
                } elseif ($isValidValue($bid->open_panna) && $gtype === 'Single Panna' && $bid->session === 'open') {
                    $bidNumber = $bid->open_panna;
                } elseif ($isValidValue($bid->jodi) && $gtype === 'Jodi Digit') {
                    $bidNumber = $bid->jodi;
                } elseif ($isValidValue($bid->half_sangam_a) && $gtype === 'Half Sangam' && $bid->session === 'open') {
                    $bidNumber = $bid->half_sangam_a;
                } elseif ($isValidValue($bid->half_sangam_b) && $gtype === 'Half Sangam' && $bid->session === 'close') {
                    $bidNumber = $bid->half_sangam_b;
                } elseif ($isValidValue($bid->full_sangam) && $gtype === 'Full Sangam') {
                    $bidNumber = $bid->full_sangam;
                } elseif ($isValidValue($bid->open_panna) && $gtype === 'sp moter' && $bid->session === 'open') {
                    $bidNumber = $bid->open_panna;
                } elseif ($isValidValue($bid->close_panna) && $gtype === 'sp moter' && $bid->session === 'close') {
                    $bidNumber = $bid->close_panna;
                }
    
                // Provide fallback value if no valid bidNumber is found
                if ($bidNumber === null) {
                    $bidNumber = 'N/A';
                }
    
                return [
                    'market_name' => $bid->market_name,
                    'session' => $bid->session,
                    'game' => $gtype,
                    'bid_number' => $bidNumber,
                    'amount' => $bid->amount,
                    'date' => Carbon::parse($bid->bid_date)->format('Y-m-d'),
                ];
            })->values()->toArray();
    
            $processedCount = count($bidArray);
    
            return response()->json([
                'success' => true,
                // 'count' => $totalCount,
                // 'length_bidArray' => $processedCount,
                'bidArray' => $bidArray,
                'selected_date' => $date,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the bid history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    





    public function winningHistoryList(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'user_id' => 'required|integer',
                'date' => 'required|date_format:d/m/Y',
            ]);

            // Get user ID and date input
            $userId = $request->input('user_id');
            $inputDate = $request->input('date');

            // Convert input date to database format (Y-m-d)
            $date = Carbon::createFromFormat('d/m/Y', $inputDate)->format('Y-m-d');

            // Query database for winnings
            $winnings = DB::table('winners')
                ->join('market', 'winners.market_id', '=', 'market.id')
                ->join('bid_table', 'winners.bid_id', '=', 'bid_table.id')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->where('winners.user_id', $userId)
                ->whereDate('winners.resultDate', '=', $date)
                ->select(
                    'market.market_name as market_name',
                    'winners.winning_amount as amount',
                    'winners.bid_point',
                    'winners.session',
                    'winners.resultDate as date',
                    'gtype.gtype as gtype',
                    'bid_table.amount as bid_amount'
                )
                ->get();



            // Check if winnings exist
            if ($winnings->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No winning history found for the selected date.',
                    'input_date' => $inputDate,
                    'formatted_date' => $date,
                ], 404);
            }

            // Return success response
            return response()->json([
                'success' => true,
                'winners_table' => $winnings,
                'input_date' => $inputDate,
                'formatted_date' => $date,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the winning history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }





    public function halfSangamGameInsert(Request $request)
    {
        // Debugging: Log the request data
        // Log::debug('Received Half Sangam Game Request:', $request->all());

        try {
            // $user_id = Auth::id();
            // dd($request->all());
            $user_id = $request->input('user_id');
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request
            $validated = $request->validate([
                'gdate' => 'required',
                'timetype' => 'required|in:open,close',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Check bid amount
            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid amount must be at least $min_bid_amount."
                ], 400);
            }

            // Prepare the data for insertion
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add half sangam fields based on session
            if ($data['session'] === 'open') {
                $digit = $request->input('open_digit_half_sangam_a');
                $panna = $request->input('close_panna_half_sangam_a');
                $data['half_sangam_a'] = $digit && $panna ? "$digit - $panna" : 'N/A';
            } elseif ($data['session'] === 'close') {
                $digit = $request->input('close_digit_half_sangam_b');
                $panna = $request->input('open_panna_half_sangam_b');
                $data['half_sangam_b'] = $digit && $panna ? "$digit - $panna" : 'N/A';
            }

            DB::beginTransaction();

            // Check wallet balance
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400);
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::warning('Validation Error in Half Sangam Game Insert:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general errors
            Log::error('An error occurred in Half Sangam Game Insert:', ['error' => $e->getMessage()]);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function singleDigitGameInsert(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            // if user_id not match in users table thes return false and user is not valid user 
            $user = DB::table('users')->where('id', $user_id)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
            $bid_amount = $request->input('amount');

            // Validate the request inputs
            $validated = $request->validate([
                'gdate' => 'required',
                'timetype' => 'required|in:open,close',
                'digit' => 'required|numeric|digits:1',
                'amount' => 'required|numeric',
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Check if the bid amount meets the minimum requirement
            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid amount must be at least $min_bid_amount."
                ], 400);
            }

            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A',
                'close_digit' => 'N/A',
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $digit = $request->input('digit');

            if ($data['session'] === 'open') {
                $data['open_digit'] = $digit ?? 'N/A';
            } elseif ($data['session'] === 'close') {
                $data['close_digit'] = $digit ?? 'N/A';
            }

            // Check wallet balance
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }

            // Perform database operations
            DB::beginTransaction();

            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function singlePannaGameInsert(Request $request)
    {

        try {
            // Get user_id from request
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required.'
                ], 400);
            }

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request inputs
            $validated = $request->validate([
                'gdate' => 'required',
                'timetype' => 'required|in:open,close',
                'single_panna_panna' => 'required|numeric|digits:3',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Check if bid amount meets the minimum requirement
            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid amount must be at least $min_bid_amount."
                ], 400);
            }

            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A',
                'close_digit' => 'N/A',
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Assign digit based on session type
            $single_panna_panna = $request->input('single_panna_panna');
            if ($data['session'] === 'open') {
                $data['open_panna'] = $single_panna_panna;
            } elseif ($data['session'] === 'close') {
                $data['close_panna'] = $single_panna_panna;
            }

            // Begin a database transaction
            DB::beginTransaction();

            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function doublePannaGameInsert(Request $request)
    {
        try {
            // Get user_id from request
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required.'
                ], 400);
            }

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request inputs
            $validated = $request->validate([
                'gdate' => 'required',
                'timetype' => 'required|in:open,close',
                'panna' => 'required|numeric|digits:3',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Check if bid amount meets the minimum requirement
            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid amount must be at least $min_bid_amount."
                ], 400);
            }

            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A',
                'close_digit' => 'N/A',
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Assign digit based on session type
            $double_panna = $request->input('panna');
            if ($data['session'] === 'open') {
                $data['open_panna'] = $double_panna;
            } elseif ($data['session'] === 'close') {
                $data['close_panna'] = $double_panna;
            }

            // Begin a database transaction
            DB::beginTransaction();

            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function triplePannaGameInsert(Request $request)
    {
        try {
            // Get user_id from request
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required.'
                ], 400);
            }

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request inputs
            $validated = $request->validate([
                'gdate' => 'required',
                'timetype' => 'required|in:open,close',
                'panna' => 'required|numeric|digits:3',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Begin a database transaction
            DB::beginTransaction();

            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            // Check wallet balance
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                // Rollback the transaction before returning
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400);
            }

            // Prepare bid data
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                // 'jodi' => $request->input('panna'), // Assuming 'digit' field is passed
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $triple_panna = $request->input('panna');
            if ($data['session'] === 'open') {
                $data['open_panna'] = $triple_panna;
            } elseif ($data['session'] === 'close') {
                $data['close_panna'] = $triple_panna;
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function jodiGameInsert(Request $request)
    {
        try {
            // Get user_id from request input
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required.'
                ], 400);
            }

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
            $bid_amount = $request->input('amount');

            // Validate the request inputs
            $validated = $request->validate([
                'gdate' => 'required',
                'digit' => 'required|numeric|digits:2',
                'amount' => 'required|numeric|min:' . $min_bid_amount,
                'gtype_id' => 'required|integer',
                'market_id' => 'required|integer',
            ]);

            // Begin a database transaction
            DB::beginTransaction();

            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            // Check wallet balance
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400);
            }

            // Prepare bid data
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A',
                'close_digit' => 'N/A',
                'jodi' => $request->input('digit'),
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
            ], 201);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Handle database query errors
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    //

    public function history_bid(Request $request)
    {
        try {
            // $id = Auth::id();

            $id = $request->input('id');
            // Fetch user data
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                Auth::logout(); // Log the user out if data not found
                return response()->json([
                    'success' => false,
                    'message' => 'User not found, please login again.'
                ], 401); // Unauthorized
            }

            // Fetch bid data based on user id and today's date (or a specific date if provided)
            $bidDate = $request->input('bid_date', date('Y-m-d'));
            $data = DB::table('bid_table')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->select('bid_table.*', 'gtype.gtype', 'market.market_name')
                ->where('bid_table.user_id', $id)
                ->where('bid_table.bid_date', '>=', $bidDate)
                ->get();

            return response()->json([
                'success' => true,
                'userData' => $userData,
                'bidData' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error fetching bid history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the bid history.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }




    public function contact(Request $request)
    {
        try {
            //  $id = $request->input('id');
            $id = $request->input('id');

            // Retrieve user data with a join on the wallets table
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                // If no user data is found, log out the user and return a JSON response
                Auth::logout();
                return response()->json(['message' => 'User not found. Please log in again.'], 401);
            }

            // Retrieve admin data
            $admin = DB::table('admin')->first();

            // Check if admin data is found
            if (!$admin) {
                return response()->json(['message' => 'Admin data not found.'], 404);
            }

            // Return a success response with the user data and admin data
            return response()->json([
                'status' => 'success',
                'userData' => $userData,
                'admin' => $admin,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving contact information.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function help(Request $request)
    {
        try {
            $id = $request->input('id');

            // Retrieve user data with a join on the wallets table
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                // If no user data is found, log out the user and return a JSON response
                Auth::logout();
                return response()->json(['message' => 'User not found. Please log in again.'], 401);
            }

            // Return a success response with the user data
            return response()->json([
                'status' => 'success',
                'userData' => $userData
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving help information.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resultChart(Request $request)
    {
        try {
            $id = $request->input('id');
            $result_id = $request->input('result_id');

            // Retrieve user data
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            if (!$userData) {
                // Handle case where user data is not found
                return response()->json([
                    'success' => false,
                    'message' => 'User data not found. Please login again.',
                ], 401);
            }

            // Fetch market results
            $data = DB::table('market_results')->where('market_id', $result_id)->get();

            // Fetch game data
            $game = DB::table('market')->where('id', $result_id)->get();

            return response()->json([
                'success' => true,
                'user' => $userData,
                'market_results' => $data,
                'game' => $game,
            ]);
        } catch (Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the result chart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update_password(Request $request)
    {
        try {
            $id = $request->input('id');

            // Validate the input
            $request->validate([
                'opass' => 'required', // Old Password
                'npass' => 'required|min:', // New Password (
                'cpass' => 'required|same:npass', // Confirm Password (matches New Password)
            ]);

            // Fetch the current user's password from the database
            $user = DB::table('users')->where('id', $id)->first();

            // Check if user data is found
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
            }

            // Check if the entered old password matches the current password
            if (!Hash::check($request->opass, $user->password)) {
                // Return an error if the old password doesn't match
                return response()->json(['status' => 'error', 'message' => 'The old password is incorrect.'], 400);
            }

            // Update the password if the old password is correct
            DB::table('users')->where('id', $id)->update([
                'password' => Hash::make($request->npass),
            ]);

            // Return a success response
            return response()->json(['status' => 'success', 'message' => 'Password updated successfully!'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function wallet_history(Request $request)
    {
        try {
            $id = $request->input('id');

            // Retrieve user data with a join on the wallets table
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                // If no user data is found, log out the user and return a JSON response
                Auth::logout();
                return response()->json(['message' => 'User not found. Please log in again.'], 401);
            }

            // Retrieve wallet withdrawal history data for the authenticated user
            $data = DB::table('withdrawal')
                ->where('withdrawal.user_id', $id)
                ->get();

            // Return a success response with user data and wallet history
            return response()->json([
                'status' => 'success',
                'userData' => $userData,
                'walletHistory' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the wallet history.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function galidesawar_addGame(Request $request, $desawar_gtype_id, $desawar_id)
    {
        try {
            $id = $request->input('id');

            // Retrieve user data with a join on the wallets table
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                // If no user data is found, log out the user and return a JSON response
                Auth::logout();
                return response()->json(['message' => 'User not found. Please log in again.'], 401);
            }

            // Return a success response with user data and desawar game type and ID
            return response()->json([
                'status' => 'success',
                'userData' => $userData,
                'desawar_gtype_id' => $desawar_gtype_id,
                'desawar_id' => $desawar_id,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while loading the galidesawar add game data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   


    // Starline related functions
    public function starline_resultChart(Request $request, $result_id)
    {
        try {
            $id = $request->input('id');
            // Retrieve user data with a join on the wallets table
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Check if user data was retrieved successfully
            if (!$userData) {
                // If no user data is found, log out the user and return a JSON response
                Auth::logout();
                return response()->json(['message' => 'User not found. Please log in again.'], 401);
            }

            // Retrieve starline result data based on the provided result_id
            $data = DB::table('starline_result')->where('starline_id', $result_id)->get();

            // Retrieve starline market details based on the result_id
            $star = DB::table('starline_market')->where('id', $result_id)->get();

            // Check if data and starline market details are found
            if ($data->isEmpty() || $star->isEmpty()) {
                return response()->json(['message' => 'Starline result or market data not found.'], 404);
            }

            // Return a success response with user data, starline result data, and starline market details
            return response()->json([
                'status' => 'success',
                'userData' => $userData,
                'resultData' => $data,
                'marketData' => $star,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the starline result chart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }





    public function dashboard()
    {
        try {
            // $userId = Auth::id();


            // Retrieve active markets ordered by open_time
            $markets = DB::table('market')
                ->where('market_status', 'active')
                ->orderBy('open_time', 'asc')
                ->get();

            // Handle the case where there is no market data
            if ($markets->isEmpty()) {
                return response()->json([
                    'markets' => [],
                    'close_time' => null,
                    'checked' => 0,
                    'admin' => DB::table('admin')->first(),
                    'images' => DB::table('slider_images')->where('status', 'active')->select('image')->get()
                ], 404);
            }


            // Retrieve user data with wallet information
            // $userData = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->select('users.name', 'users.number', 'wallets.balance')
            //     ->where('users.id', $userId)
            //     ->first();

            // Check if user data was retrieved successfully
            // if (!$userData) {
            //     // Handle the case where user data is not found
            //     Auth::logout(); // Log the user out
            //     return response()->json(['error' => 'User data not found, logging out.'], 401);
            // }

            // Collect market IDs for results retrieval
            $marketIds = $markets->pluck('id');

            // Fetch results for all markets grouped by market_id
            $results = DB::table('market_results')
                ->select('market_id', 'open_panna', 'close_panna', 'open_digit', 'close_digit')
                ->whereIn('market_id', $marketIds)
                ->where('result_date', date('Y-m-d'))
                ->get()
                ->groupBy('market_id');

            // Fetch admin data
            // $admin = DB::table('admin')->first();

            // Fetch active slider images
            $images = DB::table('slider_images')
                ->where('status', 'active')
                ->select('image')
                ->get();

            // Return the JSON response
            return response()->json([
                'markets' => $markets,
                // 'user' => $userData,
                'results' => $results,
                // 'admin' => $admin,
                'images' => $images
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function profile(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $data = DB::table('users')->where('id', $id)->first();
            if (!$data) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function wallet(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $data = DB::table('wallets')->where('user_id', $id)->first();
            if (!$data) {
                return response()->json(['error' => 'Wallet not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function addPoint(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $data = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id') // Joining on user_id
                ->select('users.name', 'users.number', 'wallets.balance') // Select specific columns
                ->where('users.id', $id)
                ->first();

            if (!$data) {
                return response()->json(['error' => 'User or wallet data not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function deposit(Request $request)
    {
        try {
            // Log the entire request for debugging
            \Log::info('Received data: ', $request->all());

            // Check each required field separately
            if (empty($request->id)) {
                \Log::warning('User ID is missing or invalid');
                return response()->json([
                    'status' => 'error',
                    'message' => 'User ID is required and cannot be empty',
                ], 400);
            }

            if (empty($request->amount)) {
                \Log::warning('Amount is missing or invalid');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amount is required and cannot be empty',
                ], 400);
            }

            if (empty($request->trnxnId)) {
                \Log::warning('Transaction ID is missing or invalid');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction ID is required and cannot be empty',
                ], 400);
            }

            if (empty($request->marchantId)) {
                \Log::warning('Merchant ID is missing or invalid');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merchant ID is required and cannot be empty',
                ], 400);
            }

            // Prepare data for database insertion
            $data = [
                'user_id' => $request->id,
                'deposit_amount' => $request->amount,
                'marchantId' => $request->marchantId,
                'trnxnId' => $request->trnxnId,
                'deposite_status' => 'approved',
                'deposit_by'=>'user',
                'deposite_date' => now(),
                'updated_at' => now(),
            ];
            //  update the user balance in wallets table and the column name is balance , in balance add $request->amount 
            DB::table('wallets')
                ->where('user_id', $request->id)
                ->increment('balance', $request->amount);

            // Log the prepared data
            \Log::info('Prepared data for insertion: ', $data);

            // Insert the data into the database
            DB::table('deposite_table')->insert($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit request submitted successfully',
            ], 201);
        } catch (\Exception $e) {
            // Log the exception with details
            \Log::error('Error in deposit process: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the deposit request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function MarchantId(Request $request)
    {
        try {
            // Fetch MarchantId from the 'admin' table
            $marchantId = DB::table('admin')->pluck('marchant_id')->first();

            if (!$marchantId) {
                // Return a response if no MarchantId is found
                return response()->json([
                    'success' => false,
                    'message' => 'Marchant ID not found.',
                ], 404);
            }

            // Return the MarchantId in a successful response
            return response()->json([
                'success' => true,
                'marchant_id' => $marchantId,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the Marchant ID.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function miniDeposit(Request $request)
    {
        try {
            // Fetch MarchantId from the 'admin' table
            $marchantId = DB::table('set_amount')->first();

            if (!$marchantId) {
                // Return a response if no MarchantId is found
                return response()->json([
                    'success' => false,
                    'message' => 'Marchant ID not found.',
                ], 404);
            }

            // Return the MarchantId in a successful response
            return response()->json([
                'success' => true,
                'marchant_id' => $marchantId,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the Marchant ID.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function whatsappNo(Request $request)
    {
        try {
            // Fetch WhatsApp number from the 'admin' table
            $whatsAppNumber = DB::table('admin')->pluck('whatsapp_number')->first();
            $mobileNumber = DB::table('admin')->pluck('mobile_number')->first();

            if (!$whatsAppNumber) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp number not found.',
                ], 404);
            }
            if (!$mobileNumber) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'mobile number not found.',
                ], 404);
            }

            // Return the WhatsApp number in a successful response
            return response()->json([
                'success' => true,
                'whatsapp_number' => $whatsAppNumber,
                'mobile_number' => $mobileNumber,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the WhatsApp number.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function walletTransactionHistory(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            //  check the user_id is exist in users table 
            $user = DB::table('users')->find($user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $withdrawal_table = DB::table('withdrawal')
                ->where('user_id', $user_id)
                ->select('amount as value', 'status as type', 'withdrawal_date as date')
                ->get()
                ->map(function ($item) {
                    $item->date = Carbon::parse($item->date)->format('Y-m-d');
                    $item->transaction_type = 'withdrawal'; // Add a transaction type field
                    return $item;
                });

            $deposite_table = DB::table('deposite_table')
                ->where('user_id', $user_id)
                ->select('deposit_amount as value', 'deposite_status as type', 'deposite_date as date')
                ->get()
                ->map(function ($item) {
                    $item->date = Carbon::parse($item->date)->format('Y-m-d');
                    $item->transaction_type = 'deposit'; // Add a transaction type field
                    return $item;
                });

            // Combine the two collections
            $combined_data = $withdrawal_table->merge($deposite_table);

            // Sort the combined data in ascending order by date
            $sorted_data = $combined_data->sortBy('date')->values(); // Use `values()` to reset the keys

            // dd($sorted_data);

            if (!$sorted_data) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'Not Wallet history.',
                ], 404);
            }


            // Return the WhatsApp number in a successful response
            return response()->json([
                'success' => true,
                'wallet_history' => $sorted_data,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the Wallet history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function privacyPolicy(Request $request)
    {
        try {
          

        //    in admin table select the privacyPolicy column value 
            $privacyPolicy = DB::table('admin')->pluck('privacyPolicy')->first();

            if (!$privacyPolicy) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'Privacy Policy not found.',
                ], 404);
            }

            // Return the WhatsApp number in a successful response
            return response()->json([
                'success' => true,
                'privacyPolicy' => $privacyPolicy,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching privacy policy.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function desawarWalletPageText(Request $request)
    {
        try {
          

           // Fetching the data from the Desawar_text table
           $desawar = DB::table('Desawar_text')
           ->select('first', 'second', 'third', DB::raw("CAST(video_link AS CHAR) as video_link"))
           ->first();
            if (!$desawar) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'desawar text updated not found.',
                ], 404);
            }

            // Return the WhatsApp number in a successful response
            return response()->json([
                'success' => true,
                'desawar' => $desawar,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching desawar text .',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function homeText(Request $request)
    {
        try {
            // Fetch WhatsApp number from the 'admin' table
            $home_text = DB::table('admin')->pluck('home_text')->first();

            if (!$home_text) {
                // Return a response if no WhatsApp number is found
                return response()->json([
                    'success' => false,
                    'message' => 'text   not found.',
                ], 404);
            }

            // Return the WhatsApp number in a successful response
            return response()->json([
                'success' => true,
                'home_text' => $home_text,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the WhatsApp number.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }





    public function withdrawPoint(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $data = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id') // Joining on user_id
                ->select('users.name', 'users.number', 'wallets.balance') // Select specific columns
                ->where('users.id', $id)
                ->first();

            if (!$data) {
                return response()->json(['error' => 'User or wallet data not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function withdrawalAmount(Request $request)
    {
        $rules = [
            'id' => 'required',
            'amount' => 'required|numeric|max:255',
            'mode' => 'required',
            'number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 422);
        }
        DB::beginTransaction();

        try {
            $id = $request->input('id');
            $user = DB::table('wallets')->where('user_id', $id)->first();

            if (!$user) {
                return response()->json(['error' => 'Wallet not found'], 404);
            }

            $withdrawalAmount = $request->input('amount');
            if ($user->balance < $withdrawalAmount) {
                return response()->json(['error' => 'Insufficient balance'], 400);
            }

            DB::table('wallets')->where('user_id', $id)->decrement('balance', $withdrawalAmount);

            $date = now();
            $data = [
                'user_id' => $id,
                'amount' => $withdrawalAmount,
                'payout' => $request->input('mode'),
                'number' => $request->input('number'),
                'status' => 'pending',
                'withdrawal_date' => $date,
            ];

            DB::table('withdrawal')->insert($data);

            DB::commit();

            return response()->json(['success' => 'Withdrawal request submitted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to process withdrawal request', 'message' => $e->getMessage()], 500);
        }
    }

    public function historyBid(Request $request)
    {
        try {
            //  $id = $request->input('id');
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $data = DB::table('bid_table')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->select('bid_table.*', 'gtype.gtype', 'market.market_name')
                ->where('bid_table.user_id', $id)
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['error' => 'Bid history not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function gameRates()
    {
        try {
            // Retrieve all game rates as an array
            $data = DB::table('game_rates')->first(); // get() returns an array of records
            // dd($data);
            // Assuming $object is your given object
            $data = (array) $data;
            // dd($data);
            // dd($data['single_digit_win']);
            $single_digit = (object) [
                'rate' => $data['single_digit_bid'],
                'winning' => $data['single_digit_win'],
                'name' => "single_digit"
            ];
            $jodi_digit = (object) [
                'rate' => $data['jodi_digit_bid'],
                'winning' => $data['jodi_digit_win'],
                'name' => "jodi_digit"
            ];
            $single_match = (object) [
                'rate' => $data['single_match_bid'],
                'winning' => $data['single_match_win'],
                'name' => "single_match"
            ];
            $double_match = (object) [
                'rate' => $data['double_match_bid'],
                'winning' => $data['double_match_win'],
                'name' => "double_match"
            ];
            $triple_match = (object) [
                'rate' => $data['triple_match_bid'],
                'winning' => $data['triple_match_win'],
                'name' => "triple_match"
            ];
            $half_sangam = (object) [
                'rate' => $data['half_sangam_bid'],
                'winning' => $data['half_sangam_win'],
                'name' => "half_sangam"
            ];
            $full_sangam = (object) [
                'rate' => $data['full_sangam_bid'],
                'winning' => $data['full_sangam_win'],
                'name' => "full_sangam"
            ];

            $newData = (object) [
                'game_rates' => [
                    $single_digit,
                    $jodi_digit,
                    $single_match,
                    $double_match,
                    $triple_match,
                    $half_sangam,
                    $full_sangam,
                ]
            ];
            // dd($newData);
            // Display the new object
            // dd((object) $newData);

            // Convert the array to a JSON response
            $data = collect($data);

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No game rates found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $newData, // data will be returned as an array
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching game rates',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function withdrawal_amount(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'id' => 'required|integer',
            'amount' => 'required|numeric',
            'mode' => 'required',
            'number' => 'required|numeric|min:10'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            // Fetch the user's current balance
            $user = DB::table('wallets')->where('user_id', $id)->first();

            // Check if user record exists
            if (!$user) {
                return response()->json(['error' => 'User wallet not found.'], 404);
            }

            $withdrawalAmount = $request->input('amount');

            // Check if the user has enough balance to make the withdrawal
            if ($user->balance < $withdrawalAmount) {
                return response()->json(['error' => 'Insufficient balance.'], 400);
            }

            // Decrement the user's balance
            DB::table('wallets')->where('user_id', $id)->decrement('balance', $withdrawalAmount);

            // Insert the withdrawal request into the 'withdrawal' table
            $date = now();
            $data = [
                'user_id' => $id,
                'amount' => $withdrawalAmount,
                'payout' => $request->input('mode'),
                'number' => $request->input('number'),
                'status' => 'pending',
                'withdrawal_date' => $date,
            ];

            DB::table('withdrawal')->insert($data);

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => 'Withdrawal request submitted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to process withdrawal request.', 'message' => $e->getMessage()], 500);
        }
    }


    public function games($market_id)
    {
        try {
            // Store market ID
            $marketid = $market_id;

            // Fetch data from 'gtype' table
            $data = DB::table('gtype')->get();

            // Check if data is retrieved successfully
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No game types found',
                ], 404);
            }

            // Return the data and market ID as a JSON response
            return response()->json([
                'success' => true,
                'market_id' => $marketid,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching game types',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function addGame(Request $request, $gtype_id, $market_id)
    {
        try {
            // Retrieve user ID from request input (passed in JSON payload or URL query)
            $userId = $request->input('id');

            // Retrieve user data along with wallet balance
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $userId)
                ->first();
            // Check if user data was retrieved successfully
            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found. Please log in again.',
                ], 404);
            }

            // Retrieve market open time based on $market_id
            $time = DB::table('market')
                ->where('id', $market_id)
                ->value('open_time');  // Retrieve only the 'open_time' field
            // Check if market data was retrieved successfully
            if (!$time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Market not found.',
                ], 404);
            }

            // Return response with user data, game type, market ID, and time
            return response()->json([
                'success' => true,
                'gtype_id' => $gtype_id,
                'market_id' => $market_id,
                'time' => ['open_time' => $time], // Structure 'time' as an array
                'userData' => $userData,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to load game data.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
            ], 500);
        }
    }



    public function game_insert(Request $request)
    {
        try {
            // Get the authenticated user ID
            // $user_id = Auth::id();
            $user_id = $request->input('id');

            // Define validation rules
            $rules = [
                'gdate' => 'required',
                'digit' => 'required',
                'amount' => 'required',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            // If validation fails, return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Prepare the data for the bid
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => $request->input('digit') ?? 'N/A',
                'close_digit' => $request->input('digit') ?? 'N/A',
                'open_panna' => $request->input('panna') ?? 'N/A',
                'close_panna' => $request->input('panna') ?? 'N/A',
                'amount' => $request->input('amount'),
            ];

            // Insert the bid data into the bid_table
            DB::table('bid_table')->insert($data);

            // Get the user's wallet data
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->first();

            // Check if the user has sufficient balance
            if ($user_wallet && $user_wallet->balance >= $request->amount) {
                // Deduct the amount from the user's wallet
                DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);

                // Return a success response
                return response()->json([
                    'success' => true,
                    'message' => 'Bid placed and wallet updated successfully.',
                ], 200);
            } else {
                // If the user doesn't have enough balance, return an error response
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400);
            }
        } catch (\Exception $e) {
            // Handle any errors that occur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while placing the bid',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function starline()
    {
        try {
            // Fetch the first record from 'starline_game_rates' table
            $data = DB::table('starline_game_rates')->first();

            // Fetch all records from 'starline_market' table
            $starline_market = DB::table('starline_market')->get();

            // Check if data exists
            if (!$data || $starline_market->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Starline data not found',
                ], 404);
            }

            // Return the data as a JSON response
            return response()->json([
                'success' => true,
                'game_rate' => $data,
                'markets' => $starline_market,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching starline data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function starline_bid_history()
    {
        try {
            // Get the authenticated user's ID
            $user_id = Auth::id();

            // Fetch the bid history data for the user
            $data = DB::table('starline_bid_table')
                ->join('starline_gtype', 'starline_bid_table.starline_gtype_id', '=', 'starline_gtype.id')
                ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
                ->select('starline_bid_table.*', 'starline_gtype.gtype', 'starline_market.starline_name')
                ->where('starline_bid_table.user_id', $user_id)
                ->get();

            // Check if the data exists
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bid history found for the user',
                ], 404);
            }

            // Return the bid history data in a JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the bid history',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function starline_winning_history()
    {
        try {
            // Get the authenticated user's ID
            $user_id = Auth::id();

            // Fetch the user's starline winning history data
            $data = DB::table('starline_winners')
                ->join('starline_market', 'starline_winners.starline_id', '=', 'starline_market.id')
                ->select(
                    'starline_market.starline_name',
                    'starline_winners.bid_type',
                    'starline_winners.bid_point',
                    'starline_winners.winning_amount',
                    'starline_winners.created_at'
                )
                ->where('starline_winners.user_id', $user_id)
                ->get();

            // Check if the data exists
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No winning history found for the user',
                ], 404);
            }

            // Return the winning history data in a JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the winning history',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function starline_games($starline_id)
    {
        try {
            // Fetch all game types from 'starline_gtype' table
            $data = DB::table('starline_gtype')->get();

            // Check if data exists
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No game types found for Starline',
                ], 404);
            }

            // Return the data along with the starline ID in a JSON response
            return response()->json([
                'success' => true,
                'starline_id' => $starline_id,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Starline game types',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function starline_addGame(Request $request, $starline_gtype_id, $starline_id)
    {
        try {
            // Prepare the data to return
            $data = [
                'starline_gtype_id' => $starline_gtype_id,
                'starline_id' => $starline_id,
            ];

            // Return the data in a JSON response
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while preparing the game data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function starline_game_insert(Request $request)
    {
        try {
            // Get the authenticated user ID
            $user_id = Auth::id();

            // Define validation rules
            $rules = [
                'gdate' => 'required',
                'digit' => 'integer',
                'amount' => 'required|integer',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            // If validation passes
            if (!$validator->fails()) {
                // Prepare the data for insertion
                $data = [
                    'starline_id' => $request->starline_id,
                    'starline_gtype_id' => $request->starline_gtype_id,
                    'user_id' => $user_id,
                    'starline_bid_date' => now(),
                    'digit' => $request->input('digit') ?? 'N/A',
                    'panna' => $request->input('panna') ?? 'N/A',
                    'amount' => $request->input('amount'),
                ];

                // Insert the bid data into the starline_bid_table
                DB::table('starline_bid_table')->insert($data);

                // Retrieve the user's wallet information
                $user = DB::table('wallets')->where('user_id', $user_id)->first();

                // Check if the user has sufficient balance
                if ($user->balance >= $request->amount) {
                    // Deduct the amount from the user's wallet
                    DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);

                    // Return success response after bid placement and wallet update
                    return response()->json([
                        'success' => true,
                        'message' => 'Bid placed and wallet updated successfully',
                    ], 200);
                } else {
                    // Return error response if the user doesn't have enough balance
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient wallet balance',
                    ], 400);
                }
            } else {
                // Return validation errors
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the bid',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

  
   
    
    public function galidesawar()
    {
        try {
            // Fetch the first record from the galidesawar_game_rates table
            $data = DB::table('galidesawar_game_rates')->first();
    
            // Fetch all records from the galidesawar_market table
            $desawar_market = DB::table('galidesawar_market')->get();
    
            // Check if data exists
            if (!$data || $desawar_market->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for Gali Desawar',
                ], 404);
            }
    
            // Get the current time as Carbon instance
            $currentTime = Carbon::now();
    
            // Modify market data to include `isActive` as a string ("true" or "false")
            $desawar_market = $desawar_market->map(function ($market) use ($currentTime) {
                // Ensure times are properly formatted
                $openTime = Carbon::parse($market->open_time);
                $closeTime = Carbon::parse($market->close_time);
    
                // Handle cases where close time is before open time (crossing midnight)
                if ($closeTime->lt($openTime)) {
                    $closeTime->addDay(); // Move close time to the next day
                }
    
                // Check if the current time is within the open and close time range
                $isActive = ($currentTime->between($openTime, $closeTime)) ? "true" : "false";
    
                // Convert the object to an array and manually add `isActive`
                return [
                    "id" => $market->id,
                    "desawar_name" => $market->desawar_name,
                    "open_time" => $openTime->format('H:i:s'),
                    "close_time" => $closeTime->format('H:i:s'),
                    "market_status" => $market->market_status,
                    "isActive" => $isActive,
                    "today_result_digit" => $market->today_result_digit,
                    "yesterday_result_digit" => $market->yesterday_result_digit,
                ];
            });
    
            // Return the data in a JSON response
            return response()->json([
                'success' => true,
                'game_rates' => $data,
                'markets' => $desawar_market,
                'today_result' => [],
                'yesterday_result' => [],
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the Gali Desawar data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
 

    public function desawar_game_insert(Request $request)
    {
        try {
            $user_id = Auth::id();

            // Validation rules
            $rules = [
                'gdate' => 'required',
                'digit' => 'integer',
                'amount' => 'required|integer',
            ];

            // Validate request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422); // Unprocessable Entity
            }

            // Prepare data for insertion
            $data = [
                'desawar_id' => $request->desawar_id,
                'desawar_gtype_id' => $request->desawar_gtype_id,
                'user_id' => $user_id,
                'desawar_bid_date' => now(),
                'digit' => $request->input('digit'),
                'amount' => $request->input('amount'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert bid data into database
            DB::table('galidesawar_bid_table')->insert($data);

            // Get user wallet
            $userWallet = DB::table('wallets')->where('user_id', $user_id)->first();

            if ($userWallet->balance >= $request->amount) {
                // Deduct the amount from the user's wallet
                DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Bad Request
            }

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Bid placed and wallet updated successfully.',
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while placing the bid.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function desawar_bid_history()
    {
        try {
            $user_id = Auth::id();

            // Fetch bid history for the authenticated user
            $data = DB::table('galidesawar_bid_table')
                ->join('galidesawar_gtype', 'galidesawar_bid_table.desawar_gtype_id', '=', 'galidesawar_gtype.id')
                ->join('galidesawar_market', 'galidesawar_bid_table.desawar_id', '=', 'galidesawar_market.id')
                ->select('galidesawar_bid_table.*', 'galidesawar_gtype.gtype', 'galidesawar_market.desawar_name')
                ->where('galidesawar_bid_table.user_id', $user_id)
                ->get();

            // Return success response with data
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the bid history.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }


   
}
