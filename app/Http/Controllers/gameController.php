<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class gameController extends Controller
{

    public function singleDigitGame_insert(Request $request)
    {
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
        $bid_amount = $request->input('amount');

        $validated = $request->validate([
            'gdate' => 'required|date',
            'timetype' => 'required|in:open,close',
            'digit' => 'required|numeric|digits:1',
            'amount' => 'required|numeric',
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        if ($bid_amount >= $min_bid_amount) {
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A',
                'close_digit' => 'N/A',
                'amount' => $request->input('amount'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $digit = $request->input('digit');

            if ($data['session'] === 'open') {
                $data['open_digit'] = $digit ?? 'N/A';
            } elseif ($data['session'] === 'close') {
                $data['close_digit'] = $digit ?? 'N/A';
            }

            $user = DB::table('wallets')->where('user_id', $user_id)->first();
            if ($user && $user->balance >= $request->amount) {
                DB::table('bid_table')->insert($data);
                DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);

                // Return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Bid placed successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Bid amount must be at least $min_bid_amount."
            ]);
        }
    }
    public function singlePannaGame_insert(Request $request)
    {
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0; // Default to 0 if null
        $bid_amount = $request->input('amount');

        // Validate the request
        $validated = $request->validate([
            'gdate' => 'required|date',
            'timetype' => 'required|in:open,close',
            'single_panna_panna' => 'required|numeric|digits:3', // Changed to match frontend logic
            'amount' => 'required|numeric|min:' . $min_bid_amount,
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        // Check if bid amount meets the minimum requirement
        if ($bid_amount < $min_bid_amount) {
            return response()->json([
                'success' => false,
                'message' => "Bid amount must be at least $min_bid_amount."
            ]);
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
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ]);
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function doublePannaGame_insert(Request $request)
    {
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0; // Default to 0 if null
        $bid_amount = $request->input('amount');

        // Validate the request
        $validated = $request->validate([
            'gdate' => 'required|date',
            'timetype' => 'required|in:open,close',
            'panna' => 'required|numeric|digits:3', // Changed to match frontend logic
            'amount' => 'required|numeric|min:' . $min_bid_amount,
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        // Check if bid amount meets the minimum requirement
        if ($bid_amount < $min_bid_amount) {
            return response()->json([
                'success' => false,
                'message' => "Bid amount must be at least $min_bid_amount."
            ]);
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
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ]);
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function triplePannaGame_insert(Request $request)
    {
        // dd($request->all())
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0; // Default to 0 if null
        $bid_amount = $request->input('amount');

        // Validate the request
        $validated = $request->validate([
            'gdate' => 'required|date',
            'timetype' => 'required|in:open,close',
            'panna' => 'required|numeric|digits:3', // Matches frontend logic
            'amount' => 'required|numeric|min:' . $min_bid_amount,
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        // Begin a database transaction
        DB::beginTransaction();
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            // Check wallet balance
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                // Rollback the transaction before returning
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Use 400 for client errors
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
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Assign digit based on session type
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
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500); // Use 500 for server errors
        }
    }


    public function halfSangamGame_insert(Request $request)
    {
        // Debugging: log the request data
        Log::debug('Received Request:', $request->all());

        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
        $bid_amount = $request->input('amount');

        // Validate the request
        $validated = $request->validate([
            'gdate' => 'required|date',
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
            ], 400); // Use HTTP 400 for client-side errors
        }

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
            $digit = $request->input('open_digit_sangam_half_a');
            $panna = $request->input('close_panna_sangam_half_a');
            $data['half_sangam_a'] = "$digit - $panna" ?? 'N/A';
        } elseif ($data['session'] === 'close') {
            $digit = $request->input('close_digit_half_sangam_b');
            $panna = $request->input('open_panna_half_sangam_b');
            $data['half_sangam_b'] = "$digit - $panna" ?? 'N/A';
        }

        // Begin a database transaction
        DB::beginTransaction();
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            // Check wallet balance
            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                DB::rollBack(); // Rollback transaction
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Use HTTP 400 for client-side errors
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500); // Use HTTP 500 for server-side errors
        }
    }


    public function fullSangamGame_insert(Request $request)
    {
        // Debugging: log the request data
        Log::debug('Received Request:', $request->all());

        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0;
        $bid_amount = $request->input('amount');

        $validated = $request->validate([
            'gdate' => 'required|date',
            // 'timetype' => 'required|in:open,close',
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

        $open_panna = $request->input('open_panna_full_sangam');
        $close_panna = $request->input('close_panna_full_sangam');
        $data['full_sangam'] = "$open_panna-$close_panna" ?? 'N/A';


        DB::beginTransaction();
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                DB::rollBack(); // Rollback transaction
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Use HTTP 400 for client-side errors
            }


            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ]);
        }
    }




    public function spPannaMotorGame_insert(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'gdate' => 'required|date',
            'timetype' => 'required|string|in:open,close',
            'all_bids' => 'required|json',
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        try {
            $allBids = json_decode($request->input('all_bids'), true);

            // Insert logic for bids into the database
            foreach ($allBids as $bid) {
                DB::table('bids')->insert([
                    'gdate' => $request->input('gdate'),
                    'timetype' => $request->input('timetype'),
                    'panna_s' => $bid['panna_s'],
                    'amount' => $bid['amount'],
                    'gtype_id' => $request->input('gtype_id'),
                    'market_id' => $request->input('market_id'),
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Bids submitted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to submit bids. Please try again.'], 500);
        }
    }

    public function jodiGame_insert(Request $request)
    {
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount') ?? 0; // Default to 0 if null
        $bid_amount = $request->input('amount');

        // Validate the request
        $validated = $request->validate([
            'gdate' => 'required|date',
            'digit' => 'required|numeric|digits:2', // Changed to match frontend logic
            'amount' => 'required|numeric|min:' . $min_bid_amount,
            'gtype_id' => 'required|integer',
            'market_id' => 'required|integer',
        ]);

        // Check if bid amount meets the minimum requirement
        if ($bid_amount < $min_bid_amount) {
            return response()->json([
                'success' => false,
                'message' => "Bid amount must be at least $min_bid_amount."
            ], 400); // Use status 400 for client-side errors
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
        $data['jodi'] = $digit;

        // Begin a database transaction
        DB::beginTransaction();
        try {
            $user_wallet = DB::table('wallets')->where('user_id', $user_id)->lockForUpdate()->first();

            if (!$user_wallet || $user_wallet->balance < $bid_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Return 400 for client-side error
            }

            // Insert bid and update wallet
            DB::table('bid_table')->insert($data);
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully!'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500); // Return 500 for server-side error
        }
    }


    public function game_insert_game(Request $request)
    {
        $panna_collection = json_decode($request->input('all_bids'), true);
        $length = count($panna_collection);
        $total_amount = $request->input('amount') * $length;
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
        $bid_amount = $total_amount;

        $rules = [
            'gdate' => 'required|date',
            'amount' => 'required|integer|min:1',
            'market_id' => 'required|integer',
            'gtype_id' => 'required|integer',
            'timetype' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($bid_amount < $min_bid_amount) {
            return redirect()->back()->with('error', "Bid amount must be at least $min_bid_amount.");
        }

        $user_wallet = DB::table('wallets')->where('user_id', $user_id)->first();

        if (!$user_wallet || $user_wallet->balance < $total_amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        DB::beginTransaction();

        try {
            foreach ($panna_collection as $bid) {
                $data = [
                    'market_id' => $request->market_id,
                    'gtype_id' => $request->gtype_id,
                    'user_id' => $user_id,
                    'bid_date' => now(),
                    'session' => $request->input('timetype'),
                    'open_digit' => 'N/A',
                    'close_digit' => 'N/A',
                    'jodi' => 'N/A',
                    'open_panna' => 'N/A',
                    'close_panna' => 'N/A',
                    'amount' => $request->input('amount'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($data['session'] === 'open') {
                    $data['open_panna'] = $bid['panna_s'] ?? 'N/A';
                } elseif ($data['session'] === 'close') {
                    $data['close_panna'] = $bid['panna_s'] ?? 'N/A';
                }

                DB::table('bid_table')->insert($data);
            }

            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $total_amount);

            DB::commit();

            return redirect()->back()->with('success', 'Bids placed and wallet updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error placing bids', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'An error occurred while placing bids. Please try again.');
        }
    }



    //     public function game_insert_game(Request $request)
    // {
    //     $panna_collection = json_decode($request->input('all_bids'), true);
    //     $length = count($panna_collection);
    //     $total_amount = $request->input('amount') * $length;
    //     $user_id = Auth::id();
    //     $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');

    //     $rules = [
    //         'gdate' => 'required|date',
    //         'amount' => 'required|integer',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     if ($total_amount >= $min_bid_amount) {
    //         foreach ($panna_collection as $bid) {
    //             $data = [
    //                 'market_id' => $request->market_id,
    //                 'gtype_id' => $request->gtype_id,
    //                 'user_id' => $user_id,
    //                 'bid_date' => now(),
    //                 'session' => $request->input('timetype'),
    //                 'amount' => $request->input('amount'),
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];

    //             $user = DB::table('wallets')->where('user_id', $user_id)->first();
    //             if ($user && $user->balance >= $request->amount) {
    //                 DB::table('bid_table')->insert($data);
    //             } else {
    //                 return redirect()->back()->with('error', 'Insufficient wallet balance.');
    //             }
    //         }

    //         DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $total_amount);
    //         return redirect()->back()->with('success', 'Bids placed successfully.');
    //     } else {
    //         return redirect()->back()->with('error', "Bid amount must be at least $min_bid_amount.");
    //     }
    // }

}
