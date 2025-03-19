<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Match;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    public function admin_login()
    {
        return view('admin.login');
    }


    public function admin_login_submit(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user type is 'admin'
            if ($user->type === 'admin') {
                return redirect()->intended('admin_dashboard'); // Redirect to dashboard after login
            }

            // Log out the user if not admin
            Auth::logout();
            return back()->withErrors([
                'number' => 'Unauthorized access. You must be an admin.',
            ]);
        }

        return back()->withErrors([
            'number' => 'Invalid credentials.',
        ]);
    }

    public function download()
    {
        // Define the file path. This assumes the file is stored in the 'public' disk.
        $filePath = public_path('downloads/kk-kalyan.apk'); // Adjust the path accordingly

        // Check if the file exists
        if (file_exists($filePath)) {
            // Send the file for download
            return response()->download($filePath);
        } else {
            // Return a 404 error if the file doesn't exist
            abort(404, 'File not found.');
        }
    }


    public function adminLogout()
    {
        Auth::logout();
        return redirect()->route('admin_login');
    }


    public function adminDashboard()
    {
        $data = DB::table('users')->where('type', 'user')->count();
        // dd($data);
        $market = DB::table('market')->count();
        $market_data = DB::table('market')->get();
        // dd($market_data);
        $starline = DB::table('starline_market')->count();
        $desawar = DB::table('galidesawar_market')->count();
        $total = $market + $starline + $desawar;

        // $amount = DB::table('bid_table')->sum('amount')->where('bid_date', );
        $bid_amount = DB::table('bid_table')
            ->whereDate('bid_date', DB::raw('CURDATE()'))
            ->sum('amount');

        $tottal_winning_amount = DB::table('winners')->sum('winning_amount');
        $totta_withdrawal_amount = DB::table('withdrawal')->where('status', 'approved')->sum('amount');
        $tottal_deposite_amount = DB::table('deposite_table')->where('deposite_status', 'approved')->sum('deposit_amount');

        $approved = DB::table('users')->where('status', 'approved')->count();

        $unapproved = DB::table('users')->where('status', 'unapproved')->count();

        // dd($amount);
        return view('admin.dashboard', compact(
            'data',
            'total',
            'market_data',
            'bid_amount',
            'approved',
            'unapproved',
            'tottal_winning_amount',
            'totta_withdrawal_amount',
            'tottal_deposite_amount'
        ));
    }


    public function getMarketBidAmount(Request $request)
    {
        $marketId = $request->input('market_id');

        // Ensure market ID is valid
        if (is_null($marketId) || $marketId == '0') {
            return response()->json(['total_bid_amount' => 'N/A']);
        }

        // Get today's date
        $today = now()->format('Y-m-d');

        // Query the total bid amount for today from the database
        $totalBidAmount = DB::table('bid_table')
            ->where('market_id', $marketId)
            ->whereDate('bid_date', $today) // Assuming you have a bid_date column
            ->sum('amount');

        // Return the total bid amount or 'N/A' if no bids exist
        return response()->json([
            'total_bid_amount' => $totalBidAmount > 0 ? $totalBidAmount : 'N/A'
        ]);
    }



    public function change_password()
    {
        return view('admin.change-password');
    }

    public function admin_password(Request $request)
    {
        $id = Auth::id();

        // dd($id);

        // Validate the input
        $request->validate([
            'oldpass' => 'required', // Old Password
            'newpass' => 'required', // New Password (min length of 8)
            'retypepass' => 'required|same:newpass', // Confirm Password (matches New Password)
        ]);

        // Fetch the current user's password from the database
        $user = DB::table('users')->where('id', $id)->first();

        // Check if the entered old password matches the current password
        if (!Hash::check($request->oldpass, $user->password)) {
            // Return an error if the old password doesn't match

            return back()->withErrors(['oldpass' => 'The old password is incorrect.']);
        }


        // Update the password if the old password is correct
        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make($request->newpass),
            'duplicate_password' => $request->input('newpass'),
        ]);

        // dd($request->newpass);

        // Redirect or return a success message
        return back()->with('status', 'Password updated successfully!');
    }


    public function declare_result()
    {

        $data = DB::table('market')->get();

        return view('admin.declare-result', compact('data'));
    }

    public function declare_result_data(Request $request)
    {
        // dd($request->all());
        $game_id = $request->input('game_id');
        // dd($game_id);
        $openNumber = $request->input('open_number');
        $closeNumber = $request->input('close_number');
        $openResult = $request->input('open_result');
        $closeResult = $request->input('close_result');
        // $open_digit_half_a = $request->input('open_digit_half_a');
        // $close_panna_half_a = $request->input('close_panna_half_a');
        $rules = [
            'open_number' => 'string|nullable', // Nullable for open results
            'close_number' => 'string|nullable', // Nullable for close results
            'open_result' => 'integer|nullable', // Nullable for open results
            'close_result' => 'integer|nullable', // Nullable for close results
            'game_id' => 'required|integer|not_in:0',
            'result_dec_date' => 'required|date',
            'market_status' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $date = date('Y-m-d H:i:s');
        $marketStatus = $request->input('market_status');
        //  dd($marketStatus);
        $gameId = $request->input('game_id');
        $resultDecDate = $request->input('result_dec_date');

        // Check if a record already exists for the specified game_id and result_dec_date
        $existingResult = DB::table('market_results')
            ->where('market_id', $gameId)
            ->where('result_date', $resultDecDate)
            ->first();

        // Prepare data for insertion or update
        $data = [
            'market_id' => $gameId,
            'result_date' => $resultDecDate,
            'created_at' => $date,
        ];

        if ($marketStatus == '1') { // Open result
            $data['open_panna'] = $request->input('open_number');
            $data['open_digit'] = $request->input('open_result');
            $data['jodi'] = $request->input('open_result');
            $data['half_sangam_a'] = "$openResult";
            $data['half_sangam_b'] = "$openNumber";
            $data['full_sangam'] = "$openNumber";
        } elseif ($marketStatus == '2') { // Close result
            $data['close_panna'] = $request->input('close_number');
            $data['close_digit'] = $request->input('close_result');
            // $data['hald_sangam_a'];

            $full_sangam = DB::table('market_results')
                ->where('market_id', $gameId)
                ->orWhere('result_date', $resultDecDate)
                ->orderBy('id', 'desc')  // Or order by your timestamp column like 'created_at' if it's available
                ->value('full_sangam');

            if ($full_sangam) {
                $data['full_sangam'] = "$full_sangam-$closeNumber";
            }

            $jodiData = DB::table('market_results')
                ->where('market_id', $gameId)
                ->orWhere('result_date', $resultDecDate)
                ->orderBy('id', 'desc')  // Or order by your timestamp column like 'created_at' if it's available
                ->value('jodi');
            if ($jodiData) {
                $data['jodi'] = $jodiData . $request->input('close_result');
            }

            $half_sangam_a = DB::table('market_results')
                ->where('market_id', $gameId)
                ->orWhere('result_date', $resultDecDate)
                ->orderBy('id', 'desc')
                ->value('half_sangam_a');

            if ($half_sangam_a) {
                $data['half_sangam_a'] = "$half_sangam_a" . " - $closeNumber";
            }

            $half_sangam_b = DB::table('market_results')
                ->where('market_id', $gameId)
                ->orWhere('result_date', $resultDecDate)
                ->orderBy('id', 'desc')
                ->value('half_sangam_b');

            if ($half_sangam_b) {
                $data['half_sangam_b'] = "$closeResult" . " - $half_sangam_b";
            }
            // dd($data);
        } else {
            return redirect()->back()->with('error', 'Invalid market status.');
        }

        // Insert new or update existing record
        if ($existingResult) {
            // Update the existing record
            DB::table('market_results')
                ->where('id', $existingResult->id)
                ->update($data);
            return redirect()->route('matchResult', ['game_id' => $game_id, 'marketStatus' => $marketStatus]);

            // return redirect()->back()->with('success', 'Game result has been updated successfully.');
        } else {
            // Insert new record
            DB::table('market_results')->insert($data);
            // return redirect()->route('matchResult');
            // dd("market_id = $game_id"."marketStatus = $marketStatus");
            return redirect()->route('matchResult', ['game_id' => $game_id, 'marketStatus' => $marketStatus]);
        }
    }

    public function result_history(Request $request)
    {
        // Build the base query with a join between market_results and market tables
        $query = DB::table('market_results')
            ->join('market', 'market_results.market_id', '=', 'market.id')
            ->select('market_results.*', 'market.market_name');

        // Apply search filter (if any)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('market.market_name', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Apply date filter (if result_pik_date is provided)
        if ($request->has('result_pik_date') && !empty($request->result_pik_date)) {
            $query->whereDate('market_results.result_date', $request->result_pik_date);
        }

        // Handle sorting (if any)
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (before filtering)
        $totalRecords = DB::table('market_results')
            ->join('market', 'market_results.market_id', '=', 'market.id')
            ->count(); // Total records before pagination and filtering

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count filtered records (after search and filters)
        $filteredRecords = $query->count();

        // Return the JSON response with the data
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    // =============================== Match Result ================================
    public function matchResult(Request $request, $game_id, $marketStatus)
    {
        // dd($game_id, $marketStatus);
        $game_id = $game_id;
        $bids = DB::table('bid_table')
            ->where('bid_date', today()->toDateString())
            ->select(
                'id',
                'user_id',
                'market_id',
                'gtype_id',
                'bid_date',
                'session',
                'open_digit',
                'close_digit',
                'jodi',
                'open_panna',
                'close_panna',
                'half_sangam_a',
                'half_sangam_b',
                'full_sangam',
                'amount'
            )
            ->get();
        // dd($bids);

        $result = DB::table('market_results')
            ->where('result_date', today()->toDateString())
            ->where('market_id', $game_id)
            ->select(
                'market_id',
                'result_date',
                'open_digit',
                'close_digit',
                'jodi',
                'open_panna',
                'close_panna',
                'half_sangam_a',
                'half_sangam_b',
                'full_sangam'
            )
            ->first();



        // Check if result is found for today
        if (!$result) {
            return; // No result for today, so no match
        }

        // $open_digit_result = empty($result->open_digit);
        // dd($open_digit_result);


        $winners = []; // Array to hold winning bids
        $processedBids = []; // Array to track processed bids

        // Get game rates once, as we need them for calculations

        $rates = DB::table('game_rates')->first();


        // dd("some went wrong");

        foreach ($bids as $bid) {
            // Basic conditions: market_id and bid_date must match
            $basicMatch = (
                $bid->market_id == $result->market_id &&
                $bid->bid_date == $result->result_date

            );

            if ($basicMatch && !in_array($bid->id, $processedBids)) {

                // dd("hi");   

                // Check if open_panna matches
                // dd(($bid->open_panna !== 'N/A' && $bid->open_panna == $result->open_panna));
                // dd($bid);
                if ($bid->open_panna !== 'N/A' && $bid->open_panna == $result->open_panna && $marketStatus == 1) {
                    $pannaMatchType = $this->isPannaMatch($bid->open_panna, $result->open_panna);
                    // Initialize winning amount and bid type
                    $winning_amount = 0;
                    $bid_type = '';

                    // Calculate winning amount based on panna match type
                    switch ($pannaMatchType) {
                        case 'single':
                            $winning_amount = $bid->amount / $rates->single_match_bid * $rates->single_match_win;
                            $bid_type = 'single panna';
                            break;
                        case 'double':
                            $winning_amount = $bid->amount / $rates->double_match_bid * $rates->double_match_win;
                            $bid_type = 'double panna';
                            break;
                        case 'triple':
                            $winning_amount = $bid->amount / $rates->triple_match_bid * $rates->triple_match_win;
                            $bid_type = 'triple panna';
                            break;
                    }
                    // dd($winning_amount);
                    // Check if winning amount is greater than zero and store winner details
                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Correct format
                            'market_id' => $bid->market_id,
                            'bid_type' => $bid_type,
                            'session' => $bid->session,
                            'bid_point' => $bid->open_panna,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        // dd("$winners");

                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                } elseif ($bid->open_digit !== 'N/A' && $bid->open_digit == $result->open_digit && $marketStatus == 1) {
                    $winning_amount = $bid->amount / $rates->single_digit_bid * $rates->single_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()
                            // Use the Carbon instance returned by now()
                            'market_id' => $bid->market_id,
                            'bid_type' => 'digit',
                            'session' => $bid->session,
                            'bid_point' => $bid->open_digit,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                } elseif ($bid->jodi == $result->jodi) {
                    // dd("jodi is matched");

                    $winning_amount = $bid->amount / $rates->jodi_digit_bid * $rates->jodi_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'market_id' => $bid->market_id,
                            'bid_type' => 'jodi',
                            'bid_point' => $bid->jodi,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'session' => $bid->session,  // Add the session field here
                        ];

                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                } elseif ($bid->close_panna !== 'N/A' && $bid->close_panna == $result->close_panna && $marketStatus == 2) {
                    $pannaMatchType = $this->isPannaMatch($bid->close_panna, $result->close_panna);

                    // Initialize winning amount and bid type
                    $winning_amount = 0;
                    $bid_type = '';

                    // Calculate winning amount based on panna match type
                    switch ($pannaMatchType) {
                        case 'single':
                            $winning_amount = $bid->amount / $rates->single_match_bid * $rates->single_match_win;
                            $bid_type = 'single panna';
                            break;
                        case 'double':
                            $winning_amount = $bid->amount / $rates->double_match_bid * $rates->double_match_win;
                            $bid_type = 'double panna';
                            break;
                        case 'triple':
                            $winning_amount = $bid->amount / $rates->triple_match_bid * $rates->triple_match_win;
                            $bid_type = 'triple panna';
                            break;
                    }

                    // Check if winning amount is greater than zero and store winner details
                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'market_id' => $bid->market_id,
                            'bid_type' => $bid_type,
                            'session' => $bid->session,
                            'bid_point' => $bid->close_panna,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }
                // Optional: Check close_digit match here if needed
                elseif ($bid->close_digit !== 'N/A' && $bid->close_digit == $result->close_digit && $marketStatus == 2) {
                    $winning_amount = $bid->amount / $rates->single_digit_bid * $rates->single_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'market_id' => $bid->market_id,
                            'bid_type' => 'digit',
                            'session' => $bid->session,
                            'bid_point' => $bid->close_digit,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }

                // check if half_sangam_a matches
                elseif ($bid->half_sangam_a !== 'N/A' && $bid->half_sangam_a == $result->half_sangam_a && $marketStatus == 1) {
                    // dd("half sangam is matched");
                    $winning_amount = $bid->amount / $rates->half_sangam_bid * $rates->half_sangam_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'bid_type' => 'half_sangam_a',
                            'session' => $bid->session,
                            'bid_point' => $bid->half_sangam_a,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }
                // check if half_sangam_b is matched 
                elseif ($bid->half_sangam_b !== 'N/A' && $bid->half_sangam_b == $result->half_sangam_b && $marketStatus == 2) {
                    // dd("half sangam b is matched");
                    $winning_amount = $bid->amount / $rates->half_sangam_bid * $rates->half_sangam_win;
                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'bid_type' => 'half_sangam_b',
                            'session' => $bid->session,
                            'bid_point' => $bid->half_sangam_b,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }
                // check if full_sangam is matched 

                elseif ($bid->full_sangam !== 'N/A' && $bid->full_sangam == $result->full_sangam) {
                    // dd("half sangam b is matched");
                    $winning_amount = $bid->amount / $rates->full_sangam_bid * $rates->full_sangam_win;
                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'resultDate' => now()->format('Y-m-d'), // Use the Carbon instance returned by now()

                            'market_id' => $bid->market_id,
                            'bid_type' => 'full_sangam',
                            'session' => $bid->session,
                            'bid_point' => $bid->full_sangam,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }
            }
        }


        // dd($winners);

        // dd(" result match successfully");
        // Update wallet balances and store winners in the database
        if (count($winners) > 0) {
            foreach ($winners as $winner) {
                // Update user wallet balance
                $user_balance = DB::table('wallets')->where('user_id', $winner['user_id'])->value('balance');
                $user_wallet = $user_balance + $winner['winning_amount'];
                // dd($user_wallet,$winner);
                DB::table('wallets')->where('user_id', $winner['user_id'])->update(['balance' => $user_wallet]);
            }
            // dd($winner);

            DB::table('winners')->insert($winners);

            return redirect()->route('winning_prediction'); // Return the winners if matches found


        } else {
            // Redirect to winning_prediction route if no winners found
            return redirect()->route('declare_result')->with('message', 'No matches found for today\'s result.');
        }
    }

    public function winning_prediction()
    {

        $data = DB::table('market')->select('id', 'market_name')->get();

        $totalbid = DB::table('bid_table')->sum('amount');

        $totalwin = DB::table('winners')->sum('winning_amount');

        // dd($totalbid);
        // dd($data);
        return view('admin.winning-prediction', compact('data', 'totalbid', 'totalwin'));
    }

    public function winning_prediction_list(Request $request)
    {
        // Build the base query with join and select statements
        $query = DB::table('winners')
            ->join('users', 'winners.user_id', '=', 'users.id')
            ->select('winners.*', 'users.name'); // Select necessary columns, including user name

        // Apply filters if present in the request
        if ($request->has('result_date') && !empty($request->result_date)) {
            $query->whereDate('winners.created_at', '=', $request->result_date);
        }

        if ($request->has('win_game_name') && !empty($request->win_game_name)) {
            $query->where('winners.market_id', '=', $request->win_game_name);
        }

        if ($request->has('win_market_status') && !empty($request->win_market_status)) {
            $query->where('winners.market_status', '=', $request->win_market_status);
        }

        if ($request->has('winning_ank') && !empty($request->winning_ank)) {
            $query->where('winners.winning_ank', '=', $request->winning_ank);
        }

        if ($request->has('close_number') && !empty($request->close_number)) {
            $query->where('winners.close_number', '=', $request->close_number);
        }

        // Handle search functionality across multiple columns
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('winners.bid_id', 'like', "%$search%"); // Search by bid_id or name
            });
        }

        // Apply sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count the total number of records (before filtering)
        $totalRecords = $query->count();

        // Pagination: Apply offset and limit
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering
        $filteredRecords = $query->count(); // Filtered records count after search and filter

        // Return the JSON response for DataTable
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }




    //======================= Report Management =======================================
    public function user_bid_history()
    {
        $data = DB::table('market')->select('id', 'market_name')->get();
        // dd($data);

        $gtype = DB::table('gtype')->select('id', 'gtype')->get();
        // dd($gtype);
        return view('admin.user-bid-history', compact('data', 'gtype'));
    }

    public function bid_history(Request $request)
    {
        // Build the base query
        $query = DB::table('bid_table')
            ->join('market', 'bid_table.market_id', '=', 'market.id')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
            ->select(
                'users.name',
                'market.market_name',
                'bid_table.amount',
                'bid_table.bid_date',
                'bid_table.session',
                'gtype.gtype',
                'bid_table.open_digit',
                'bid_table.close_digit',
                'bid_table.jodi',
                'bid_table.open_panna',
                'bid_table.close_panna',
                'bid_table.half_sangam_a',
                'bid_table.half_sangam_b',
                'bid_table.full_sangam',
                'bid_table.id as bid_id'
            );

        // Apply search filter (if any)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('market.market_name', 'like', "%$search%")
                    ->orWhere('users.name', 'like', "%$search%");
            });
        }

        // Apply date filter (if bid_date is provided)
        if ($request->has('bid_date') && !empty($request->bid_date)) {
            $query->whereDate('bid_table.bid_date', $request->bid_date);
        }

        // Apply game name filter (if provided)
        if ($request->has('game_name') && !empty($request->game_name)) {
            $query->where('bid_table.market_id', $request->game_name);
        }

        // Apply game type filter (if provided)
        if ($request->has('game_type') && !empty($request->game_type) && $request->game_type !== 'all') {
            $query->where('bid_table.gtype_id', $request->game_type);
        }

        // Handle sorting (if any)
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (before filtering)
        $totalRecords = DB::table('bid_table')->count();

        // Step 1: Count total records (before pagination)
        $filteredRecords = $query->count();

        // Step 2: Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $paginatedData = $query->offset($start)->limit($length)->get();

        // Format data
        $formatted_data = $paginatedData->map(function ($query) {
            $bid_points = '';

            if ($query->close_digit !== 'N/A') {
                $bid_points = $query->close_digit;
            } elseif ($query->open_digit !== 'N/A') {
                $bid_points = $query->open_digit;
            } elseif ($query->close_panna !== 'N/A') {
                $bid_points = $query->close_panna;
            } elseif ($query->open_panna !== 'N/A') {
                $bid_points = $query->open_panna;
            } elseif ($query->half_sangam_a !== 'N/A') {
                $bid_points = $query->half_sangam_a;
            } elseif ($query->half_sangam_b !== 'N/A') {
                $bid_points = $query->half_sangam_b;
            } elseif ($query->full_sangam !== 'N/A') {
                $bid_points = $query->full_sangam;
            } elseif ($query->jodi !== 'N/A') {
                $bid_points = $query->jodi;
            }

            return [
                'name' => $query->name,
                'market_name' => $query->market_name,
                'amount' => $query->amount,
                'bid_point' => $bid_points,
                'bid_date' => $query->bid_date,
                'session' => $query->session,
                'game_type' => $query->gtype,
                'bid_id' => $query->bid_id,
            ];
        });

        // Return the JSON response with the data
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formatted_data,
        ]);
    }




    public function winning_report()
    {

        $data = DB::table('market')->select('id', 'market_name')->get();

        return view('admin.winning-report', compact('data'));
    }

    public function winning_report_list(Request $request)
    {
        // Build the base query
        $query = DB::table('winners')
            ->join('users', 'winners.user_id', '=', 'users.id')
            ->join('market', 'winners.market_id', '=', 'market.id')
            ->select('users.name', 'market.market_name', 'winners.*');

        // Apply filters based on form inputs

        // Filter by user_id (this will ensure we are only fetching data for the logged-in user or a specific user)
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('winners.user_id', $request->user_id);
        }

        // Filter by result_date
        if ($request->has('result_date') && !empty($request->result_date)) {
            $query->whereDate('winners.created_at', '=', $request->result_date);
        }

        // Filter by game name (market_id)
        if ($request->has('win_game_name') && !empty($request->win_game_name) && $request->win_game_name != 0) {
            $query->where('winners.market_id', '=', $request->win_game_name);
        }

        // Filter by market status (session)
        if ($request->has('win_market_status') && !empty($request->win_market_status) && $request->win_market_status != 0) {
            $query->where('winners.session', '=', $request->win_market_status);
        }

        // Handle searching (searching by user name or email)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other searchable columns if necessary
            });
        }

        // Handle sorting (order by selected column)
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Step 1: Count total records (before filtering)
        $totalRecords = DB::table('winners')->count(); // Total records without filters
        $filteredRecords = $query->count(); // Total records after filtering

        // Step 2: Apply pagination
        $start = $request->input('start', 0); // Start offset for pagination
        $length = $request->input('length', 10); // Number of records per page
        $data = $query->offset($start)->limit($length)->get(); // Get the paginated result

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }




    public function transfer_point_report()
    {
        return view('admin.transfer-point-report');
    }

    public function bid_winning_report()
    {
        $data = DB::table('market')->select('id', 'market_name')->get();

        return view('admin.bid-winning-report', compact('data'));
    }



    public function withdraw_report()
    {
        return view('admin.withdraw-report');
    }

    public function auto_deposite_history()
    {
        return view('admin.auto-deposite-history');
    }

    public function autoDepositReport(Request $request)
    {
        $validated = $request->validate([
            'bid_revert_date' => 'required|date',
        ]);

        $bidRevertDate = $validated['bid_revert_date'];

        try {
            // Fetch data from the `deposite_table`
            $data = DB::table('deposite_table')
                ->whereDate('deposite_date', $bidRevertDate)
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for the selected date.',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            // Return detailed error message for debugging
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function get_referral_amount_data()
    {
        return view('admin.get-referral-amount-data');
    }












    public function user_management()
    {

        return view('admin.user-management');
    }

    public function game_name()
    {

        return view('admin.game-name');
    }

    public function getUserList(Request $request)
    {
        // Initial query without pagination
        $query = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.*', 'wallets.balance');

        // Count total records before filtering
        $totalRecords = $query->count();

        // Apply search filter
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.number', 'like', "%$search%");
            });
        }

        // Count records after filtering
        $filteredRecords = $query->count();

        // Apply sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function view_user($user_id)
    {
        $id = $user_id;
        // dd($id);
        // $withdrawal = DB::table('withdrawal')->where('user_id', $id)->get();
        $total_withdrawal = DB::table('withdrawal')
            ->where('user_id', $id)
            ->where('status', 'approved')
            ->sum('amount');

        $total_winning = DB::table('winners')
            ->where('user_id', $id)
            ->sum('winning_amount');
        // dd($total_winning);

        $user_data = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            // ->join('bid_table', 'users.id', '=', 'bid_table.user_id')
            ->select('users.*', 'wallets.balance','duplicate_password')
            ->where('users.id', $id)
            ->first();

        // Controller
        $data = DB::table('bid_table')
            ->join('market', 'bid_table.market_id', '=', 'market.id')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
            ->select('bid_table.id', 'bid_table.bid_date', 'bid_table.session', 'bid_table.open_digit', 'bid_table.close_digit', 'bid_table.jodi', 'bid_table.amount', 'market.market_name', 'users.name', 'gtype.gtype')
            ->where('users.id', $id)
            ->paginate(5); // Paginate 10 records per page

        // Pass to the view


        // dd($data);
        $winning = DB::table('winners')
            ->join('market', 'winners.market_id', '=', 'market.id')
            ->join('bid_table', 'winners.bid_id', '=', 'bid_table.id')
            ->select('winners.winning_amount', 'market.market_name', 'bid_table.id', 'bid_table.bid_date')
            ->where('winners.user_id', $id)->get();

        $query = $data;

        return view('admin.view-user.572', compact('user_data', 'total_withdrawal', 'total_winning', 'query', 'winning', 'id'));
    }



    //======================= Game Management =======================================
    public function gameNameView()
    {

        $data = DB::table('market')->get();

        return view('admin.game-name', compact('data'));
    }

    public function gameName_insert(Request $request)
    {
        // dd($request->all());
        $rules = [
            'game_name' => 'required|string|max:255|unique:market,market_name',
            // 'game_name_hindi' =>'required|string|max:255|unique:market,market_name_hindi',
            'open_time' => 'required|date_format:H:i',   // Open time must be in HH:MM format
            'close_time' => 'required|date_format:H:i|after:open_time' // Close time must be after open time
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $date = date('Y-m-d H:i:s');
            $data = [
                'market_name' => $request->input('game_name'),
                // 'market_name_hindi' => $request->input('game_name_hindi'),
                'open_time' => $request->input('open_time'),
                'close_time' => $request->input('close_time'),
                'market_status' => 'active',
                'created_at' => $date,
            ];
            // dd($data);
            $result = DB::table('market')->insert($data);
            return redirect()->back();
        } else {
            echo "some went wrong";
        }

        //return redirect('/admin/dashboard')->with('message', 'Game Name Inserted Successfully');
    }

    public function inactive_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('market')->where('id', $id)->update(['market_status' => 'inactive', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Inactive successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Inactiving the Market.']);
        }
    }

    public function active_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('market')->where('id', $id)->update(['market_status' => 'active', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Active successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Activing the Market.']);
        }
    }

    public function delete_market(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('market')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function edit_market(Request $request)
    {
        // dd($request->all());
        $rules = [
            'game_id' => 'required', // Ensure the ID exists
            'game_name' => 'required', // Allow current name
            // 'game_name_hindi' => 'required', // Allow current name in Hindi
            'open_time' => 'required', // Open time must be in HH:MM format
            'close_time' => 'required|after:open_time' // Close time must be after open time
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Redirect back with errors
        }

        $id = $request->input('game_id');
        // dd($id); // Get the market ID

        $data = [
            'market_name' => $request->input('game_name'),
            // 'market_name_hindi' => $request->input('game_name_hindi'),
            'open_time' => $request->input('open_time'),
            'close_time' => $request->input('close_time'),
            'updated_at' => now(), // Update the updated_at timestamp
        ];

        // Perform the update
        $result = DB::table('market')->where('id', $id)->update($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Market updated successfully!');
    }


    public function gameName_search(Request $request)
    {
        $query = DB::table('market');

        // Count total records before filtering
        $totalRecords = $query->count();

        // Handle searching (if search value is provided)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where('market_name', 'like', "%$search%");
        }

        // Count total records after filtering
        $filteredRecords = $query->count();

        // Handle sorting
        if ($request->has('order')) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumn, $orderDir);
        }

        // Handle pagination
        $start = $request->start;
        $length = $request->length;
        $data = $query->offset($start)->limit($length)->get();

        // Return response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }



    public function game_rates_show()
    {

        $data = DB::table('game_rates')->first();

        return view('admin.game-rates', compact('data'));
    }

    public function game_rates_insert(Request $request)
    {
        // dd($request->all());
        $rules = [
            'single_digit_bid' => 'required|numeric',
            'single_digit_win' => 'required|numeric',
            'jodi_digit_bid' => 'required|numeric',
            'jodi_digit_win' => 'required|numeric',
            'single_match_bid' => 'required|numeric',
            'single_match_win' => 'required|numeric',
            'double_match_bid' => 'required|numeric',
            'double_match_win' => 'required|numeric',
            'triple_match_bid' => 'required|numeric',
            'triple_match_win' => 'required|numeric',
            'half_sangam_bid' => 'required|numeric',
            'half_sangam_win' => 'required|numeric',
            'full_sangam_bid' => 'required|numeric',
            'full_sangam_win' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $date = date('Y-m-d H:i:s');
            $data = [
                'single_digit_bid' => $request->input('single_digit_bid'),
                'single_digit_win' => $request->input('single_digit_win'),
                'jodi_digit_bid' => $request->input('jodi_digit_bid'),
                'jodi_digit_win' => $request->input('jodi_digit_win'),
                'single_match_bid' => $request->input('single_match_bid'),
                'single_match_win' => $request->input('single_match_win'),
                'double_match_bid' => $request->input('double_match_bid'),
                'double_match_win' => $request->input('double_match_win'),
                'triple_match_bid' => $request->input('triple_match_bid'),
                'triple_match_win' => $request->input('triple_match_win'),
                'half_sangam_bid' => $request->input('half_sangam_bid'),
                'half_sangam_win' => $request->input('half_sangam_win'),
                'full_sangam_bid' => $request->input('full_sangam_bid'),
                'full_sangam_win' => $request->input('full_sangam_win'),
                'updated_at' => $date
            ];
            $existingRates = DB::table('game_rates')->first(); // Fetch the first entry

            if ($existingRates) {
                // Update the existing record
                DB::table('game_rates')->where('id', $existingRates->id)->update($data);
            } else {
                // Insert a new record if no existing record is found
                $data['created_at'] = now();
                DB::table('game_rates')->insert($data);
            }

            // Redirect back after operation
            return redirect()->back()->with('success', 'Game rates have been updated successfully.');
        } else {
            // Handle validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }



    //======================= Wallet Management =======================================
    public function withdrawal_request()
    {


        return view('admin.withdraw-request-management');
    }

    public function withdrawal_request_list(Request $request)
    {
        // Build the base query
        $query = DB::table('users')
            ->join('withdrawal', 'users.id', '=', 'withdrawal.user_id')
            ->select('users.name', 'withdrawal.*');

        // Filter by user_id if provided
        if ($request->has('user_id') && !empty($request->user_id)) {
            $userId = $request->user_id;
            $query->where('withdrawal.user_id', '=', $userId);
        }

        // Filter by withdraw_date if provided
        if ($request->has('withdraw_date') && !empty($request->withdraw_date)) {
            $withdrawDate = $request->withdraw_date;
            $query->whereDate('withdrawal.withdrawal_date', '=', $withdrawDate);
        }

        // Handle searching
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%");
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records
        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering
        $filteredRecords = $query->count();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }



    public function withdrawal_request_approve(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to approve the withdrawal request
            $updated = DB::table('withdrawal')->where('id', $id)->update(['status' => 'approved']);

            if ($updated) {
                // Fetch the user_id and the withdrawal amount from the withdrawal record
                $withdrawal = DB::table('withdrawal')->where('id', $id)->first(['user_id', 'amount']);
                if (!$withdrawal) {
                    return response()->json(['success' => false, 'message' => 'Withdrawal request not found.']);
                }

                // Fetch the user's current wallet balance
                $user = DB::table('wallets')->where('user_id', $withdrawal->user_id)->value('balance');

                // Check if the user has enough balance for the withdrawal
                if ($user >= $withdrawal->amount) {
                    $new_balance = $user - $withdrawal->amount;

                    // Update the user's wallet balance in a transaction
                    DB::transaction(function () use ($withdrawal, $new_balance) {
                        DB::table('wallets')->where('user_id', $withdrawal->user_id)->update(['balance' => $new_balance]);
                    });

                    return response()->json(['success' => true, 'message' => 'Record approved successfully.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Insufficient balance.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while approving the record.']);
        }
    }



    public function withdrawal_request_reject(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to reject the withdrawal request
            $updated = DB::table('withdrawal')->where('id', $id)->update(['status' => 'rejected']);

            if ($updated) {
                // Fetch the user_id and the withdrawal amount from the withdrawal record
                $withdrawal = DB::table('withdrawal')->where('id', $id)->first(['user_id', 'amount']);
                if (!$withdrawal) {
                    return response()->json(['success' => false, 'message' => 'Withdrawal request not found.']);
                }

                // Fetch the user's current wallet balance
                $user = DB::table('wallets')->where('user_id', $withdrawal->user_id)->value('balance');

                // Add the amount back to the user's balance since the request was rejected
                $new_balance = $user + $withdrawal->amount;

                // Update the user's wallet balance
                DB::table('wallets')->where('user_id', $withdrawal->user_id)->update(['balance' => $new_balance]);

                return response()->json(['success' => true, 'message' => 'Withdrawal request rejected successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while rejecting the record.']);
        }
    }


    public function delete_withdrawal_request(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('withdrawal')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function fund_request_management()
    {
        return view('admin.fund-request-management');
    }

    public function fund_request_management_list(Request $request)
    {
        // Initialize the query
        $query = DB::table('deposite_table')
            ->join('users', 'deposite_table.user_id', '=', 'users.id')
            ->select('users.name', 'deposite_table.*');

        // Filter data by user_id if provided
        if ($request->has('user_id') && !empty($request->user_id)) {
            $userId = $request->user_id;
            $query->where('deposite_table.user_id', '=', $userId); // Filter based on user_id
        }

        // Handle searching
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records
        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering
        $filteredRecords = $query->count();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }


    public function deposite_request_approve(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to approve the withdrawal request
            $updated = DB::table('deposite_table')->where('id', $id)->update(['deposite_status' => 'approved']);

            if ($updated) {
                // Fetch the user_id and the withdrawal amount from the withdrawal record
                $deposite = DB::table('deposite_table')->where('id', $id)->first(['user_id', 'deposit_amount']);
                if (!$deposite) {
                    return response()->json(['success' => false, 'message' => 'Deposite request not found.']);
                }

                // Fetch the user's current wallet balance
                $user = DB::table('wallets')->where('user_id', $deposite->user_id)->value('balance');

                // Check if the user has enough balance for the withdrawal

                $new_balance = $user + $deposite->deposit_amount;

                // Update the user's wallet balance in a transaction
                DB::transaction(function () use ($deposite, $new_balance) {
                    DB::table('wallets')->where('user_id', $deposite->user_id)->update(['balance' => $new_balance]);
                });

                return response()->json(['success' => true, 'message' => 'Record approved successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while approving the record.']);
        }
    }



    public function deposite_request_reject(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to reject the Deposite request
            $updated = DB::table('deposite_table')->where('id', $id)->update(['deposite_status' => 'rejected']);

            if ($updated) {
                // Fetch the user_id and the Deposite amount from the Deposite record
                $deposite = DB::table('deposite_table')->where('id', $id)->first(['user_id', 'amount']);
                if (!$deposite) {
                    return response()->json(['success' => false, 'message' => 'Deposite request not found.']);
                }

                return response()->json(['success' => true, 'message' => 'Deposite request rejected successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while rejecting the record.']);
        }
    }


    public function delete_deposite_request(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('deposite_table')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }



    public function add_fund_user_wallet_management()
    {

        $data = DB::table('users')->select('id', 'name', 'number')->where('type', 'user')->get();

        return view('admin.add-fund-user-wallet-management', compact('data'));
    }

    public function bid_revert()
    {

        $data = DB::table('market')->get();
        return view('admin.bid-revert', compact('data'));
    }

    public function bid_data(Request $request)
    {
        $bid_revert_date = $request->input('bid_revert_date');
        $win_game_name = $request->input('win_game_name');
        // dd($request->all());
        $query = DB::table('bid_table')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
            ->select('bid_table.id', 'users.name', 'bid_table.amount', 'gtype.gtype');

        // Handle searching
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records
        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering
        $filteredRecords = $query->count();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function bid_revert_data(Request $request)
    {
        $bid_revert_date = $request->input('bid_revert_date');
        $market_id = $request->input('win_game_name');

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Fetch data from `market_results` for verification
            $marker_result_data = DB::table('market_results')
                ->where('market_id', $market_id)
                ->where('result_date', $bid_revert_date)
                ->get();

            // Fetch data from `winners` table
            $winners_data = DB::table('winners')
                ->where('market_id', $market_id)
                ->get();

            // Fetch unique user IDs from `winners`
            $winners_user_ids = DB::table('winners')
                ->where('market_id', $market_id)
                ->distinct()
                ->pluck('user_id');

            // Calculate total winning amounts for each user
            $totalWinningAmounts = DB::table('winners')
                ->select('user_id', DB::raw('SUM(winning_amount) as total_winning_amount'))
                ->where('market_id', $market_id) // Filter by market_id
                ->groupBy('user_id')
                ->get();

            // Update wallets by subtracting total winning amounts
            foreach ($totalWinningAmounts as $winning) {
                DB::table('wallets')
                    ->where('user_id', $winning->user_id)
                    ->decrement('balance', $winning->total_winning_amount);
            }

            // Delete records from `market_results`
            DB::table('market_results')
                ->where('market_id', $market_id)
                ->where('result_date', $bid_revert_date)
                ->delete();

            // Delete records from `winners`
            DB::table('winners')
                ->where('market_id', $market_id)
                ->delete();

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'bid  reverted successfully!',
                'affected_users' => $winners_user_ids // Optionally include affected users
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function bid_revert_data(Request $request)

    // {
    //     $bid_revert_date = $request->input('bid_revert_date');
    //     $market_id  = $request->input('win_game_name');
    //     // dd($market_id);



    //     // Perform necessary logic
    //     try {
    //         // Example logic for reverting bids
    //         // DB::table('market_result')->delete();
    //         // DB::table('winners')->delete();

    //         $marker_result_data = DB::table('market_results')
    //             ->where('market_id', $market_id)
    //             ->where('result_date', $bid_revert_date)
    //             ->get();

    //         $winners_data = DB::table('winners')
    //             ->where('market_id', $market_id)
    //             ->get();
    //         //  dd($winners_data);

    //         $winners_user_ids = DB::table('winners')
    //             ->where('market_id', $market_id)
    //             ->distinct()
    //             ->pluck('user_id');

    //         //  dd($winners_user_ids);
    //         // $totle_winning_amount = DB::table('winners')
    //         // ->where('market_id', $market_id)
    //         // ->sum('winning_amount');
    //         // dd($totle_winning_amount);

    //         $totalWinningAmounts = DB::table('winners')
    //             ->select('user_id', DB::raw('SUM(winning_amount) as total_winning_amount'))
    //             ->groupBy('user_id')
    //             ->get();
    //         //  dd($totalWinningAmounts);

    //         DB::beginTransaction();

    //         foreach ($totalWinningAmounts as $winning) {
    //             DB::table('wallets')
    //                 ->where('user_id', $winning->user_id)
    //                 ->decrement('balance', $winning->total_winning_amount);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data reverted successfully!'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }







    //======================= Games & Numbers =======================================
    public function single_digit()
    {

        return view('admin.single-digit');
    }

    public function jodi_digit()
    {

        return view('admin.jodi-digit');
    }

    public function single_pana()
    {
        return view('admin.single-pana');
    }

    public function double_pana()
    {
        return view('admin.double-pana');
    }

    public function tripple_pana()
    {
        return view('admin.tripple-pana');
    }

    public function half_sangam()
    {
        return view('admin.half-sangam');
    }

    public function full_sangam()
    {
        return view('admin.full-sangam');
    }

    // ====================================== Settings Function ======================================
    public function main_settings()
    {
        $set_amount = DB::table('set_amount')->first();
        $admin = DB::table('admin')->first();
        $desawar_text = DB::table('Desawar_text')->first();
        return view('admin.main-settings', compact('set_amount', 'admin', 'desawar_text'));
    }

    public function updateMarchantId(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'marchant_Id' => 'required|string|max:255', // Ensure 'marchant_Id' is a valid string
            ]);

            // Update the Marchant ID in the admin table
            DB::table('admin') // Table name is "admin"
                ->update(['marchant_Id' => $request->marchant_Id]); // Update the "marchant_Id" column

            // Redirect with success message
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Marchant ID updated successfully',
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            Log::error('Error updating Marchant ID: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'An error occurred while updating the Marchant ID',
            ]);
        }
    }
    public function updateHometext(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'home_text' => 'required', // Ensure 'marchant_Id' is a valid string
            ]);

            // Update the Marchant ID in the admin table
            DB::table('admin') // Table name is "admin"
                ->update(['home_text' => $request->home_text]); // Update the "marchant_Id" column

            // Redirect with success message
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Home text updated successfully',
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            Log::error('Error updating Home text: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'An error occurred while updating the Home text',
            ]);
        }
    }
    public function UpdateprivacyPolicy(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'privacyPolicy' => 'required', // Ensure 'marchant_Id' is a valid string
            ]);

            // Update the Marchant ID in the admin table
            DB::table('admin') // Table name is "admin"
                ->update(['privacyPolicy' => $request->privacyPolicy]); // Update the "marchant_Id" column

            // Redirect with success message
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'privacy policy updated successfully',
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            Log::error('Error updating privacy policy: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'An error occurred while updating the privacy policy',
            ]);
        }
    }
    public function addWalletPageText(Request $request)
    {
        try {
            // Validate the request data
            // dd($request->all());

            $request->validate([
                'textFirst' => 'required',
                'textSecond' => 'required',
                'textThird' => 'required',
            ]);

            $data = [
                'first' => $request->input('textFirst'),
                'second' => $request->input('textSecond'),
                'third' => $request->input('textThird'),
                'video_link' => $request->input('video_link'),
            ];
            // dd($data);
            // Update a specific row (assuming id = 1, modify as needed)
            $updated = DB::table('Desawar_text')->where('id', 1)->update($data);

            // dd($updated);

            if ($updated) {
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => 'Desawar text updated successfully.',
                ]);
            } else {
                return redirect()->back()->with([
                    'status' => 'warning',
                    'message' => 'No changes were made or record not found.',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating Desawar text: ' . $e->getMessage());

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'An error occurred while updating the Desawar text.',
            ]);
        }
    }




    public function setAmount(Request $request)
    {
        // Validation rules for the inputs
        $rules = [
            'min_deposite'    => 'required|numeric',
            'max_deposite'    => 'required|numeric',
            'min_withdrawal'  => 'required|numeric',
            'max_withdrawal'  => 'required|numeric',
            'min_bid_amt'     => 'required|numeric',
            'max_bid_amt'     => 'required|numeric',
            'welcome_bonus'   => 'required|numeric',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // If validation fails, redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Data to insert or update
        $data = [
            'min_deposite'    => $request->input('min_deposite'),
            'max_deposite'    => $request->input('max_deposite'),
            'min_withdrawal'  => $request->input('min_withdrawal'),
            'max_withdrawal'  => $request->input('max_withdrawal'),
            'min_bid_amount'     => $request->input('min_bid_amt'),
            'max_bid_amount'     => $request->input('max_bid_amt'),
            'welcome_bonus'   => $request->input('welcome_bonus'),
        ];

        // Update the record where id = 1
        DB::table('set_amount')->where('id', 1)->update($data);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }


    public function contact_settings()
    {
        $data = DB::table('admin')->first();

        return view('admin.contact-settings', compact('data'));
    }

    public function insert_contact(Request $request)
    {

        $rules = [
            'mobile_1' => 'required',
            'mobile_2' => 'required',
            'whats_mobile' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $whats_mobile = '91' . $request->whats_mobile;

        $data = [
            'mobile_number' => $request->mobile_1,
            'telegram_link' => $request->mobile_2,
            'whatsapp_number' => $whats_mobile,
            'updated_at' => now()
        ];

        // Upload QR code image file if present
        if ($request->hasFile('qr_code')) {
            $qr_code = $request->file('qr_code');
            $qr_code_name = time() . '.' . $qr_code->getClientOriginalExtension();
            $qr_code->move(public_path('uploads/qr_code'), $qr_code_name);
            $filepath = 'uploads/qr_code/' . $qr_code_name;

            // Add QR code file name to the update data
            $data['qr_code'] = $filepath;
        }

        // Assuming you are updating a specific admin record, add a condition
        DB::table('admin')->where('id', 1)->update($data); // Replace 1 with the relevant admin ID

        return redirect()->route('contact_settings')->with('success', 'Contact settings updated successfully.');
    }

    public function clear_data()
    {
        return view('admin.clear-data');
    }

    public function slider_images_management()
    {
        return view('admin.slider-images-management');
    }

    public function slider_image_insert(Request $request)
    {
        // Validation rules
        $rules = [
            'image' => 'required', // Image validation
            'display_order' => 'required', // Ensure display_order is an integer
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // dd('fail');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->hasFile('image')) {

            // Get the uploaded file
            $image = $request->file('image');

            // Generate a unique name for the image using the current timestamp and the file extension
            $imagename = time() . '.' . $image->getClientOriginalExtension();

            // Move the file to the 'uploads' directory in the public folder
            $image->move(public_path('slider/img'), $imagename);

            // Generate the path to the uploaded image
            $imagePath = 'slider/img/' . $imagename;

            // Prepare data for insertion
            $data = [
                'image' => $imagePath,
                'display_order' => $request->input('display_order'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert data into the database
            try {
                DB::table('slider_images')->insert($data);
                return redirect()->route('slider-images-management')->with('success', 'Slider image added successfully.');
            } catch (\Exception $e) {
                // Handle any database errors
                return redirect()->back()->with('error', 'Failed to add slider image. Please try again.');
            }
        }

        return redirect()->back()->with('error', 'No file was uploaded.');
    }

    public function slider_data(Request $request)
    {
        // Build the base query
        $query = DB::table('slider_images');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
                //   ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function slider_image_approve(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('slider_images')->where('id', $id)->update(['status' => 'active']);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Record rejected successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while rejecting the record.']);
        }
    }
    public function slider_image_reject(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('slider_images')->where('id', $id)->update(['status' => 'inactive']);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Record rejected successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while rejecting the record.']);
        }
    }

    public function delete_slider_image(Request $request,)
    {

        $id = $request->id;
        // dd($id); 

        try {
            // Perform the deletion
            $deleted = DB::table('slider_images')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }
    public function delete_user(Request $request,)
    {

        $id = $request->id;
        // dd($id); 

        try {
            // Perform the deletion
            $deleted = DB::table('users')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }




    public function how_to_play()
    {
        return view('admin.how-to-play');
    }

    // =============================== Notice Management ================================
    public function notice_management()
    {
        return view('admin.notice-management');
    }

    public function notice_management_insert(Request $request)
    {
        // Define validation rules
        $rules = [
            'notice_title' => 'required',
            'notice_date' => 'required|date',
            'description' => 'required',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data for insertion
        $data = [
            'notice_title' => $request->input('notice_title'),
            'notice_date' => $request->input('notice_date'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'created_at' => now(),  // assuming you want to track when it was created
            'updated_at' => now(),  // assuming you want to track updates
        ];

        // Insert data into the database
        DB::table('notice_management')->insert($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Notice added successfully!');
    }

    public function notice_management_data(Request $request)
    {
        // Build the base query
        $query = DB::table('notice_management');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
                //   ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function delete_notice(Request $request,)
    {

        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('notice_management')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function send_notification()
    {
        $data = DB::table('users')->where('type', 'user')->get();

        return view('admin.send-notification', compact('data'));
    }


    // =============================== Starline Management ================================
    public function starline_game_name()
    {

        $data = DB::table('starline_market')->get();

        return view('admin.starline-game-name', compact('data'));
    }

    public function starline_game_rates()
    {

        $data = DB::table('starline_game_rates')->first();

        return view('admin.starline-game-rates', compact('data'));
    }

    public function starline_game_rates_insert(Request $request)
    {
        // dd($request->all());
        $rules = [
            'single_digit_bid' => 'required|numeric',
            'single_digit_win' => 'required|numeric',
            'single_match_bid' => 'required|numeric',
            'single_match_win' => 'required|numeric',
            'double_match_bid' => 'required|numeric',
            'double_match_win' => 'required|numeric',
            'triple_match_bid' => 'required|numeric',
            'triple_match_win' => 'required|numeric',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $date = date('Y-m-d H:i:s');
            $data = [
                'single_digit_bid' => $request->input('single_digit_bid'),
                'single_digit_win' => $request->input('single_digit_win'),
                'single_match_bid' => $request->input('single_match_bid'),
                'single_match_win' => $request->input('single_match_win'),
                'double_match_bid' => $request->input('double_match_bid'),
                'double_match_win' => $request->input('double_match_win'),
                'triple_match_bid' => $request->input('triple_match_bid'),
                'triple_match_win' => $request->input('triple_match_win'),

                'updated_at' => $date
            ];
            $existingRates = DB::table('starline_game_rates')->first(); // Fetch the first entry

            if ($existingRates) {
                // Update the existing record
                DB::table('starline_game_rates')->where('id', $existingRates->id)->update($data);
            } else {
                // Insert a new record if no existing record is found
                $data['created_at'] = now();
                DB::table('starline_game_rates')->insert($data);
            }

            // Redirect back after operation
            return redirect()->back()->with('success', 'Game rates have been updated successfully.');
        } else {
            // Handle validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function starline_market_insert(Request $request)
    {
        $rules = [
            'game_name' => 'required|string|max:255|unique:starline_market,starline_name',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'starline_name' => $request->input('game_name'),
            'open_time' => $request->input('open_time'),
            'close_time' => $request->input('close_time'),
            'market_status' => 'active',
            'created_at' => now(),
        ];

        $result = DB::table('starline_market')->insert($data);

        if ($result) {
            return redirect()->back()->with('success', 'Game added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add the game.');
        }
    }


    public function starline_game_data(Request $request)
    {

        $query = DB::table('starline_market');


        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('starline_name', 'like', "%$search%"); // Add other columns to search if needed
            });
        }
        // Count total records
        $totalRecords = $query->count();

        // Handle sorting
        if ($request->has('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumn]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Handle pagination
        $start = $request->start;
        $length = $request->length;
        $data = $query->offset($start)->limit($length)->get();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $query->count(), // Count after filtering
            'data' => $data
        ]);
    }

    public function inactive_starline_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('starline_market')->where('id', $id)->update(['market_status' => 'inactive', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Inactive successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Inactiving the Market.']);
        }
    }

    public function active_starline_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('starline_market')->where('id', $id)->update(['market_status' => 'active', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Active successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Activing the Market.']);
        }
    }

    public function delete_starline_market(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('starline_market')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function edit_starline_market(Request $request)
    {
        // dd($request->all());
        $rules = [
            'game_id' => 'required', // Ensure the ID exists
            'game_name' => 'required', // Allow current name
            // 'game_name_hindi' => 'required', // Allow current name in Hindi
            'open_time' => 'required', // Open time must be in HH:MM format
            'close_time' => 'required|after:open_time' // Close time must be after open time
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Redirect back with errors
        }

        $id = $request->input('game_id');
        // dd($id); // Get the market ID

        $data = [
            'starline_name' => $request->input('game_name'),
            'starline_name_hindi' => $request->input('game_name_hindi'),
            'open_time' => $request->input('open_time'),
            'close_time' => $request->input('close_time'),
            'updated_at' => now(), // Update the updated_at timestamp
        ];

        // Perform the update
        $result = DB::table('starline_market')->where('id', $id)->update($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Market updated successfully!');
    }

    public function starline_user_bid_history()
    {
        $data = DB::table('starline_market')->get();
        return view('admin.starline-user-bid-history', compact('data'));
    }
    public function history_bid_user(Request $request)
    {
        // Get the user ID from the request or fallback to the authenticated user
        $user_id = $request->input('user_id');

        // Ensure the user ID exists
        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Start building the query with joins
        $query = DB::table('bid_table')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
            ->join('market', 'bid_table.market_id', '=', 'market.id')
            ->where('bid_table.user_id', $user_id)
            ->select(
                'market.market_name',
                'gtype.gtype',
                'bid_table.session',
                'bid_table.amount',
                'bid_table.bid_date',
                'bid_table.open_digit',
                'bid_table.close_digit',
                'bid_table.jodi',
                'bid_table.open_panna',
                'bid_table.close_panna',
                'bid_table.half_sangam_a',
                'bid_table.half_sangam_b',
                'bid_table.full_sangam'
            );

        // Apply the search functionality (Game Name or Game Type)
        if ($request->filled('search_value')) {
            $searchTerm = $request->input('search_value');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('market.market_name', 'like', "%$searchTerm%")
                    ->orWhere('gtype.gtype', 'like', "%$searchTerm%");
            });
        }

        // Debug the query to check the results
        $queryResults = $query->get(); // Get all the records before pagination
        // dd($queryResults, $user_id); // Inspect the query results and the user_id

        // Get the total number of records before pagination
        $totalRecords = $queryResults->count();

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        // Get the filtered records with pagination
        $records = $query->offset($start)->limit($length)->get();

        // Format the data for DataTables
        $data = $records->map(function ($record) {
            $bid_points = null;

            if ($record->close_digit !== null && $record->close_digit !== '' && $record->close_digit !== 'N/A') {
                $bid_points = $record->close_digit;
            } elseif ($record->open_digit !== null && $record->open_digit !== '' && $record->open_digit !== 'N/A') {
                $bid_points = $record->open_digit;
            } elseif ($record->close_panna !== null && $record->close_panna !== '' && $record->close_panna !== 'N/A') {
                $bid_points = $record->close_panna;
            } elseif ($record->open_panna !== null && $record->open_panna !== '' && $record->open_panna !== 'N/A') {
                $bid_points = $record->open_panna;
            } elseif ($record->half_sangam_a !== null && $record->half_sangam_a !== '' && $record->half_sangam_a !== 'N/A') {
                $bid_points = $record->half_sangam_a;
            } elseif ($record->half_sangam_b !== null && $record->half_sangam_b !== '' && $record->half_sangam_b !== 'N/A') {
                $bid_points = $record->half_sangam_b;
            } elseif ($record->full_sangam !== null && $record->full_sangam !== '' && $record->full_sangam !== 'N/A') {
                $bid_points = $record->full_sangam;
            } elseif ($record->jodi !== null && $record->jodi !== '' && $record->jodi !== 'N/A') {
                $bid_points = $record->jodi;
            }

            if (!$bid_points) {
                $bid_points = 'N/A'; // Default value if no valid points were found
            }


            return [
                'market_name' => $record->market_name,
                'gtype' => $record->gtype,
                'session' => $record->session,
                'bid_point' => $bid_points,
                'amount' => $record->amount,
                'bid_date' => $record->bid_date,
            ];
        });
        // dd($data);
        // Return JSON response for DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }



    public function starline_user_bid_history_list(Request $request)
    {
        // Build the base query
        $query = DB::table('starline_bid_table')
            ->join('users', 'starline_bid_table.user_id', '=', 'users.id')
            ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
            ->join('starline_gtype', 'starline_bid_table.starline_gtype_id', '=', 'starline_gtype.id')
            ->select('users.name', 'starline_market.starline_name', 'starline_gtype.gtype', 'starline_bid_table.*');

        // Apply date filter (if bid_date is provided)
        if ($request->has('bid_date') && !empty($request->bid_date)) {
            $query->whereDate('starline_bid_table.starline_bid_date', $request->bid_date);
        } else {
        }

        // Apply game name filter (if provided)
        if ($request->has('game_name') && !empty($request->game_name)) {
            $query->where('starline_bid_table.starline_id', $request->game_name);
        }

        // Apply game type filter (if provided)
        if ($request->has('game_type') && !empty($request->game_type) && $request->game_type !== 'all') {
            $query->where('starline_bid_table.starline_gtype_id', $request->game_type);
        }

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
                //   ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function starline_declare_result()
    {
        $data = DB::table('starline_market')->get();
        return view('admin.starline-declare-result', compact('data'));
    }



    public function starline_result_history()
    {
        return view('admin.starline-result-history');
    }

    public function starline_result_history_list(Request $request)
    {
        // Build the base query
        $query = DB::table('starline_result')
            ->join('starline_market', 'starline_result.starline_id', '=', 'starline_market.id')
            ->select('starline_market.starline_name', 'starline_result.*');

        // Apply search filter (if any)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('starline_market.starline_name', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Apply date filter (if result_pik_date is provided)
        if ($request->has('result_star_pik_date') && !empty($request->result_star_pik_date)) {
            $query->whereDate('starline_result.result_date', $request->result_star_pik_date);
        }

        // Handle sorting (if any)
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (before filtering)
        $totalRecords = DB::table('starline_result')
            ->join('starline_market', 'starline_result.starline_id', '=', 'starline_market.id')
            ->count(); // Total records before pagination and filtering

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count filtered records (after search and filters)
        $filteredRecords = $query->count();

        // Return the JSON response with the data
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function starline_winning_report()
    {
        $data = DB::table('starline_market')->select('id', 'starline_name')->get();
        return view('admin.starline-winning-report', compact('data'));
    }

    public function starline_winning_report_list(Request $request)
    {

        // Build the base query
        $query = DB::table('starline_winners')
            ->join('users', 'starline_winners.user_id', '=', 'users.id')
            ->join('starline_market', 'starline_winners.starline_id', '=', 'starline_market.id')
            ->select('users.name', 'starline_market.starline_name', 'starline_winners.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function starline_winning_prediction()
    {
        return view('admin.starline-winning-prediction');
    }

    public function starline_winning_prediction_list(Request $request)
    {

        // Build the base query
        $query = DB::table('starline_winners')
            ->join('users', 'starline_winners.user_id', '=', 'users.id')
            ->select('users.name', 'starline_winners.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    // =============================== Match Starline Result ================================


    public function starline_result_guess(Request $request)
    {
        $rules = [
            'open_number' => 'required',
            'open_result' => 'required',
            'game_id' => 'required|integer|not_in:0',
            'result_dec_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $guess_winners = $this->matchResultsWithBids_guess(
            $request->input('game_id'),
            $request->input('open_number'),
            $request->input('open_result'),
            $request->input('result_dec_date')
        );
        // dd($guess_winners);
        return response()->json(['guess_winners' => $guess_winners]);
    }


    private function matchResultsWithBids_guess($game_id, $panna, $digit, $result_dec_date)
    {
        $bids = DB::table('starline_bid_table')
            ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
            ->join('users', 'users.id', '=', 'starline_bid_table.user_id')
            ->where('starline_bid_date', $result_dec_date)
            ->where('starline_bid_table.starline_id', $game_id)
            ->select(
                'starline_bid_table.id as bid_id',
                'starline_bid_table.user_id',
                'starline_bid_table.starline_id',
                'starline_bid_table.starline_gtype_id',
                'starline_bid_table.starline_bid_date',
                'starline_bid_table.panna',
                'starline_bid_table.digit',
                'starline_bid_table.amount',
                'starline_market.starline_name',
                'users.name as user_name'
            )
            ->get();
        if ($bids->isEmpty()) {
            return [];
        }

        $winners = [];
        $processedBids = [];

        $rates = DB::table('starline_game_rates')->first();

        foreach ($bids as $bid) {
            if ($bid->starline_id != $game_id || $bid->starline_bid_date != $result_dec_date) {
                continue;
            }
            if ($bid->panna !== 'N/A' && $bid->panna == $panna && !isset($processedBids[$bid->bid_id])) {
                $winning_amount = ($bid->amount / $rates->single_match_bid) * $rates->single_match_win;
                $winners[] = [
                    'user_id' => $bid->user_id,
                    'user_name' => $bid->user_name,
                    'market_name' => $bid->starline_name,
                    'bid_id' => $bid->bid_id,
                    'gtype' => $bid->starline_gtype_id,
                    'starline_id' => $bid->starline_id,
                    'bid_type' => 'panna',
                    'bid_point' => $bid->panna,
                    'winning_amount' => $winning_amount,
                ];
                $processedBids[$bid->bid_id] = true;
            }

            if ($bid->digit !== 'N/A' && $bid->digit == $digit && !isset($processedBids[$bid->bid_id])) {
                $winning_amount = ($bid->amount / $rates->single_digit_bid) * $rates->single_digit_win;
                $winners[] = [
                    'user_id' => $bid->user_id,
                    'user_name' => $bid->user_name,
                    'market_name' => $bid->starline_name,
                    'bid_id' => $bid->bid_id,
                    'gtype' => $bid->starline_gtype_id,
                    'starline_id' => $bid->starline_id,
                    'bid_type' => 'digit',
                    'bid_point' => $bid->digit,
                    'winning_amount' => $winning_amount,
                ];
                $processedBids[$bid->bid_id] = true;
            }
        }
        return $winners;
    }

    public function starlineBidEdit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'bid_id' => 'required|integer',
            'starline_gtype_id' => 'required|integer',
            'bid_point' => 'required|integer'
        ]);
        if ($request->gtype == 1) {
            $updated = DB::table('starline_bid_table')
                ->where('id', $request->bid_id)
                ->where('starline_gtype_id', $request->starline_gtype_id)
                ->update(['digit' => $request->bid_point]);
        }
        $updated = DB::table('starline_bid_table')
            ->where('id', $request->bid_id)
            ->where('starline_gtype_id', $request->starline_gtype_id)
            ->update(['panna' => $request->bid_point]);

        if ($updated) {
            // dd($updated);
            return response()->json(['success' => true, 'message' => 'Bid updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update bid.']);
        }
    }


    public function starline_result(Request $request)
    {
        // dd($request->all());
        $rules = [
            'open_number' => 'required|string',
            'open_result' => 'required|integer',
            'game_id' => 'required|integer|not_in:0', // Validate game_id is not 0
            'result_dec_date' => 'required', // Validate result_dec_date
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $date = date('Y-m-d H:i:s');
            $data = [
                'starline_id' => $request->input('game_id'), // Save game_id
                'panna' => $request->input('open_number'),
                'digit' => $request->input('open_result'),
                'result_date' => $request->input('result_dec_date'), // Save result_dec_date
                'created_at' => $date,
            ];
            $result = DB::table('starline_result')->insert($data);
            // return redirect()->back()->with('success', 'Game result has been declared successfully.');
            return redirect()->route('matchResultsWithBids');
        } else {
        }
    }



    public function matchResultsWithBids()
    {
        // Get today's bids
        $bids = DB::table('starline_bid_table')
            ->where('starline_bid_date', today()->toDateString())
            ->select('id', 'user_id', 'starline_id', 'starline_gtype_id', 'starline_bid_date', 'panna', 'digit', 'amount')
            ->get();

        // Get today's result
        $result = DB::table('starline_result')
            ->where('result_date', today()->toDateString())
            ->select('starline_id', 'result_date', 'panna', 'digit')
            ->first();

        if (!$result) {
            return redirect()->route('starline_declare_result')->with('message', 'No results declared yet.');
        }

        // Convert panna to string for proper matching
        $result->panna = (string) $result->panna;

        $winners = [];
        $processedBids = [];

        // Get game rates for calculations
        $rates = DB::table('starline_game_rates')->first();

        foreach ($bids as $bid) {
            // Basic conditions: starline_id and bid_date must match the result
            if ($bid->starline_id != $result->starline_id || $bid->starline_bid_date != $result->result_date) {
                continue; // Skip if the game ID or date doesn't match
            }

            // Check for Panna match
            if ($bid->panna !== 'N/A' && $bid->panna == $result->panna && !in_array($bid->id, $processedBids)) {
                $winning_amount = ($bid->amount / $rates->single_match_bid) * $rates->single_match_win;

                $winners[] = [
                    'user_id' => $bid->user_id,
                    'bid_id' => $bid->id,
                    'starline_id' => $bid->starline_id,
                    'bid_type' => 'panna',
                    'bid_point' => $bid->panna,
                    // 'bid_amount' => $bid->amount, // Store bid amount
                    'winning_amount' => $winning_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $processedBids[] = $bid->id;
            }

            // Check for Digit match
            if ($bid->digit !== 'N/A' && $bid->digit == $result->digit && !in_array($bid->id, $processedBids)) {
                $winning_amount = ($bid->amount / $rates->single_digit_bid) * $rates->single_digit_win;

                $winners[] = [
                    'user_id' => $bid->user_id,
                    'bid_id' => $bid->id,
                    'starline_id' => $bid->starline_id,
                    'bid_type' => 'digit',
                    'bid_point' => $bid->digit,
                    // 'bid_amount' => $bid->amount, // Store bid amount
                    'winning_amount' => $winning_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $processedBids[] = $bid->id;
            }
        }

        // Store winners and update wallet balances
        if (!empty($winners)) {
            foreach ($winners as $winner) {
                // Update wallet balance
                DB::table('wallets')->where('user_id', $winner['user_id'])->increment('balance', $winner['winning_amount']);
            }

            // Insert all winners at once
            // dd($winners);
            DB::table('starline_winners')->insert($winners);

            return redirect()->route('starline_winning_prediction')->with('message', 'Winners have been declared.');
        }

        return redirect()->route('starline_declare_result')->with('message', 'No winners found for today.');
    }


    // Method to check if the panna matches the winning panna
    private function isPannaMatch($bidPanna, $resultPanna)
    {
        // Single panna: 3 distinct digits (e.g., 102)
        if (
            strlen($bidPanna) === 3 &&
            $bidPanna[0] !== $bidPanna[1] &&
            $bidPanna[1] !== $bidPanna[2] &&
            $bidPanna[0] !== $bidPanna[2]
        ) {
            return $bidPanna === (string)$resultPanna ? 'single' : false;
        }

        // Double panna: 2 identical digits (e.g., 121, 122)
        elseif (strlen($bidPanna) == 3 && (
            ($bidPanna[0] == $bidPanna[1] && $bidPanna[1] !== $bidPanna[2]) ||
            ($bidPanna[1] == $bidPanna[2] && $bidPanna[0] !== $bidPanna[1]) ||
            ($bidPanna[0] == $bidPanna[2] && $bidPanna[0] !== $bidPanna[1])
        )) {
            return substr($bidPanna, 0, 2) === substr($resultPanna, 0, 2) ? 'double' : false; // Match first two digits
        }
        // Triple panna: All digits are identical (e.g., 111)
        elseif (strlen($bidPanna) == 3 && $bidPanna[0] == $bidPanna[1] && $bidPanna[1] == $bidPanna[2]) {
            return $bidPanna === $resultPanna ? 'triple' : false; // Exact match for triple panna
        }

        return false; // No match
    }




    // =============================== Desawar Management ================================
    public function desawar_game_name()
    {

        $data = DB::table('galidesawar_market')->get();

        return view('admin.galidesawar-game-name', compact('data'));
    }

    public function desawar_market_insert(Request $request)
    {
        // dd($request->all());
        $rules = [
            'game_name' => 'required|unique:galidesawar_market,desawar_name',
            'open_time' => 'required',
            'close_time' => 'required',   // Open time must be in HH:MM format
        ];

        $validator = Validator::make($request->all(), $rules);
        // dd('jdkjk');
        if (!$validator->fails()) {

            $date = date('Y-m-d H:i:s');
            $data = [
                'desawar_name' => $request->input('game_name'),
                // 'desawar_name_hindi' => $request->input('game_name_hindi'),
                'open_time' => $request->input('open_time'),
                'close_time' => $request->input('close_time'),
                "market_status" => "active",
                'created_at' => $date,
            ];
            // dd($data);
            $result = DB::table('galidesawar_market')->insert($data);
            return redirect()->back();
        } else {
            echo "some went wrong";
        }
    }

    public function desawar_game_data(Request $request)
    {

        $query = DB::table('galidesawar_market');


        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('starline_name', 'like', "%$search%"); // Add other columns to search if needed
            });
        }
        // Count total records
        $totalRecords = $query->count();

        // Handle sorting
        if ($request->has('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumn]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Handle pagination
        $start = $request->start;
        $length = $request->length;
        $data = $query->offset($start)->limit($length)->get();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $query->count(), // Count after filtering
            'data' => $data
        ]);
    }

    public function inactive_desawar_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('galidesawar_market')->where('id', $id)->update(['market_status' => 'inactive', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Inactive successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Inactiving the Market.']);
        }
    }

    public function active_desawar_market(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('galidesawar_market')->where('id', $id)->update(['market_status' => 'active', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Market Active successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while Activing the Market.']);
        }
    }

    public function delete_desawar_market(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('galidesawar_market')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function edit_desawar_market(Request $request)
    {
        // dd($request->all());
        $rules = [
            'game_id' => 'required', // Ensure the ID exists
            'game_name' => 'required', // Allow current name
            'open_time' => 'required', // Open time must be in HH:MM format
            'close_time' => 'required|after:open_time' // Close time must be after open time
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Redirect back with errors
        }

        $id = $request->input('game_id');
        // dd($id); // Get the market ID

        $data = [
            'desawar_name' => $request->input('game_name'),
            'open_time' => $request->input('open_time'),
            'close_time' => $request->input('close_time'),
            'updated_at' => now(), // Update the updated_at timestamp
        ];

        // Perform the update
        $result = DB::table('galidesawar_market')->where('id', $id)->update($data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Market updated successfully!');
    }


    public function desawar_game_rates()
    {

        $data = DB::table('galidesawar_game_rates')->first();

        return view('admin.galidesawar-game-rates', compact('data'));
    }

    public function desawar_game_rates_insert(Request $request)
    {
        // dd($request->all());
        $rules = [
            'left_digit_bid' => 'required|numeric',
            'left_digit_win' => 'required|numeric',
            'right_digit_bid' => 'required|numeric',
            'right_digit_win' => 'required|numeric',
            'jodi_digit_bid' => 'required|numeric',
            'jodi_digit_win' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $date = date('Y-m-d H:i:s');
            $data = [
                'left_digit_bid' => $request->input('left_digit_bid'),
                'left_digit_win' => $request->input('left_digit_win'),
                'right_digit_bid' => $request->input('right_digit_bid'),
                'right_digit_win' => $request->input('right_digit_win'),
                'jodi_digit_bid' => $request->input('jodi_digit_bid'),
                'jodi_digit_win' => $request->input('jodi_digit_win'),

                'updated_at' => $date
            ];
            $existingRates = DB::table('galidesawar_game_rates')->first(); // Fetch the first entry

            if ($existingRates) {
                // Update the existing record
                DB::table('galidesawar_game_rates')->where('id', $existingRates->id)->update($data);
            } else {
                // Insert a new record if no existing record is found
                $data['created_at'] = now();
                DB::table('galidesawar_game_rates')->insert($data);
            }

            // Redirect back after operation
            return redirect()->back()->with('success', 'Game rates have been updated successfully.');
        } else {
            // Handle validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function desawar_user_bid_history()
    {
        return view('admin.galidesawar-user-bid-history');
    }

    public function desawar_user_bid_history_list(Request $request)
    {
        // Build the base query
        $query = DB::table('galidesawar_bid_table')
            ->join('users', 'galidesawar_bid_table.user_id', '=', 'users.id')
            ->join('galidesawar_market', 'galidesawar_bid_table.desawar_id', '=', 'galidesawar_market.id')
            ->join('galidesawar_gtype', 'galidesawar_bid_table.desawar_gtype_id', '=', 'galidesawar_gtype.id')
            ->select('users.name', 'galidesawar_market.desawar_name', 'galidesawar_gtype.gtype', 'galidesawar_bid_table.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
                //   ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function desawar_declare_result()
    {

        $data = DB::table('galidesawar_market')->get();

        return view('admin.galidesawar-declare-result', compact('data'));
    }



    public function desawar_result_history()
    {
        return view('admin.galidesawar-result-history');
    }

    public function desawar_result_history_list(Request $request)
    {

        // dd($request->all());
        // Build the base query
        $query = DB::table('galidesawar_result')
            ->join('galidesawar_market', 'galidesawar_result.desawar_id', '=', 'galidesawar_market.id')
            ->select('galidesawar_market.desawar_name', 'galidesawar_result.*');

        // Apply date filter (if result_star_pik_date is provided)
        if ($request->has('result_star_pik_date') && !empty($request->result_star_pik_date)) {
            $query->whereDate('galidesawar_result.result_date', $request->result_star_pik_date);
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    // public function desawar_sell_report() {
    //     return view('admin.galidesawar-sell-report');
    // }

    public function desawar_winning_report()
    {
        $data = DB::table('galidesawar_market')->select('id', 'desawar_name')->get();

        return view('admin.galidesawar-winning-report',compact('data'));
    }

    public function desawar_winning_report_list(Request $request)
    {

        // Build the base query
        $query = DB::table('galidesawar_winners')
            ->join('users', 'galidesawar_winners.user_id', '=', 'users.id')
            ->join('galidesawar_market', 'galidesawar_winners.desawar_id', '=', 'galidesawar_market.id')
            ->select('users.name', 'galidesawar_market.desawar_name', 'galidesawar_winners.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function desawar_winning_prediction()
    {

        $data = DB::table('galidesawar_market')->select('id', 'desawar_name')->get();

        $totalbid = DB::table('galidesawar_bid_table')->sum('amount');

        $totalwin = DB::table('galidesawar_winners')->sum('winning_amount');

        return view('admin.galidesawar-winning-prediction', compact('data', 'totalbid', 'totalwin'));
    }

    public function desawar_winning_prediction_list(Request $request)
    {

        // Build the base query
        $query = DB::table('galidesawar_winners')
            ->join('users', 'galidesawar_winners.user_id', '=', 'users.id')
            ->select('users.name', 'galidesawar_winners.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }




    // public function sub_admin_management() {
    //     return view('admin.sub-admin-management');
    // }

    // public function sub_admin_insert(Request $request) {
    //     // dd($request->all());
    //     $rules = [
    //         'sub_admin_name' =>'required|string|max:255',
    //         'email' =>'required|integer|email|max:10|unique:users',
    //         'password' => 'nullable|string|min:8|confirmed',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     if (!$validator->fails()) {

    //         $data = [
    //             'name' => $request->input('sub_admin_name'),
    //             'number' => $request->input('email'),
    //             'password' => Hash::make($request->input('password')),
    //             'role' => 'Sub-Admin',
    //         ];
    //         DB::table('users')->insert($data);

    //         // Redirect back after operation
    //         return redirect()->back()->with('success', 'Sub-Admin has been added successfully.');
    //     }
    // }


    public function un_approved_users_list()
    {
        return view('admin.un-approved-users-list');
    }

    public function un_approved_users_data(Request $request)
    {
        // Build the base query
        $query = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.*', 'wallets.balance')
            ->where('users.status', 'unapproved');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (filtered and unfiltered)
        $totalRecords = $query->count(); // Total records before pagination

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // dd($data);

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count(); // Filtered records after applying search and sorting

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function unapprove_users_unapprove(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('users')->where('id', $id)->update(['status' => 'unapproved', 'updated_at' => now()]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Record unapproved successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while unapproving the record.']);
        }
    }

    public function unapprove_users_approve(Request $request)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('users')->where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);

            // dd($updated);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Record approved successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while approving the record.']);
        }
    }

    public function delete_unapprove_users(Request $request,)
    {
        // Valid

        // Retrieve the ID from the request
        $id = $request->id;
        // dd($id);

        try {
            // Perform the deletion
            $deleted = DB::table('users')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.']);
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.']);
        }
    }

    public function add_fund(Request $request)
    {
        // Validation rules
        $rules = [
            'user_amount' => 'required|numeric|min:1', // Ensure it's a number and positive
            'user_id' => 'required|exists:wallets,user_id' // Ensure user_id is provided and exists in the wallets table
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Fetch the current balance
        $wallet = DB::table('wallets')->where('user_id', $request->user_id)->first();

        // Ensure the wallet record exists
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }

        // Calculate the new balance
        $newBalance = $wallet->balance + $request->user_amount;

        $deposit_table_data = [
            'user_id' => $request->user_id,
            'deposit_amount' => $request->user_amount,
            'deposit_by' => "admin",
            'deposite_status' => 'approved',
            'deposite_date' => now(),
            'updated_at' => now(),
        ];

        // Insert the data into the deposite_table
        DB::table('deposite_table')->insert($deposit_table_data);

        // Update the balance in the database
        DB::table('wallets')->where('user_id', $request->user_id)->update([
            'balance' => $newBalance
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Funds added successfully.');
    }

    public function withdraw_fund(Request $request)
    {

        // dd($request->all());
        // Validation rules
        $rules = [
            'amount' => 'required|numeric|min:1', // Ensure it's a number and positive
            'user_id' => 'required|exists:wallets,user_id' // Ensure user_id is provided and exists in the wallets table
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Fetch the current balance
        $wallet = DB::table('wallets')->where('user_id', $request->user_id)->first();

        // Ensure the wallet record exists
        if (!$wallet) {
            return redirect()->back()->with('error', 'Wallet not found.');
        }


        // Calculate the new balance
        $newBalance = $wallet->balance - $request->amount;

        // Update the balance in the database
        DB::table('wallets')->where('user_id', $request->user_id)->update([
            'balance' => $newBalance
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Funds added successfully.');
    }


    public function winner_list_aj(Request $request)
    {
        return view('aj');
    }



    private function fetchWinners($gameId, $date, $filter, $column)
    {
        return DB::table('bid_table')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->where('market_id', $gameId)
            ->where('bid_date', $date)
            ->where($column, $filter)
            ->select('users.name', 'bid_table.user_id', 'bid_table.amount', $column)
            ->get()
            ->toArray();
    }


    public function show_winner_close(Request $request)
    {
        // Get data from the request
        $game_id = $request->input('game_id');
        $market_status = 'close';
        $result_dec_date = $request->input('result_dec_date');
        $close_panna = $request->input('close_number');
        $close_digit = $request->input('close_result');

        // Fetch market results
        $marketResults = DB::table('market_results')
            ->where('market_id', $game_id)
            ->where('result_date', $result_dec_date)
            ->first();

        $fullJodi = (int) ("{$marketResults->jodi}{$close_digit}");
        // dd($fullJodi);
        $halfSangamA = "{$marketResults->half_sangam_a} - {$close_panna}";
        $halfSangamB = "{$close_digit} - {$marketResults->half_sangam_b}";
        $fullSangam = "{$marketResults->full_sangam}-{$close_panna}";

        // Fetch winners
        $close_digit_win = $this->getWinners($game_id, $result_dec_date, $market_status, 'close_digit', $close_digit);
        $close_panna_win = $this->getWinners($game_id, $result_dec_date, $market_status, 'close_panna', $close_panna);
        $jodiwinners = $this->getWinners($game_id, $result_dec_date, null, 'jodi', $fullJodi);
        $halfSangamA_win = $this->getWinners($game_id, $result_dec_date, null, 'half_sangam_a', $halfSangamA);
        $halfSangamB_win = $this->getWinners($game_id, $result_dec_date, $market_status, 'half_sangam_b', $halfSangamB);
        $fullSangam_win = $this->getWinners($game_id, $result_dec_date, null, 'full_sangam', $fullSangam);

        // Merge all winners into a single array
        $all_winners = array_merge(
            $close_digit_win,
            $close_panna_win,
            $jodiwinners,
            $halfSangamA_win,
            $halfSangamB_win,
            $fullSangam_win
        );
        // dd( $all_winners);
        // Transform data for DataTables
        $formatted_data = [];
        foreach ($all_winners as $winner) {
            $bid_type = '';
            if (isset($winner->close_digit)) {
                $bid_type = "Close Digit: {$winner->close_digit}";
            } elseif (isset($winner->close_panna)) {
                $bid_type = "Close Panna: {$winner->close_panna}";
            } elseif (isset($winner->half_sangam_a)) {
                $bid_type = "Half Sangam open: {$winner->half_sangam_a}";
            } elseif (isset($winner->half_sangam_b)) {
                $bid_type = "Half Sangam close: {$winner->half_sangam_b}";
            } elseif (isset($winner->full_sangam)) {
                $bid_type = "Full Sangam: {$winner->full_sangam}";
            } elseif (isset($winner->jodi)) {
                $bid_type = "jodi: {$winner->jodi}";
            }

            $formatted_data[] = [
                'name' => $winner->name,
                'type' => $bid_type,
                'amount' => $winner->amount,
                'user_id' => $winner->user_id,
                'bid_id' => $winner->id,
            ];
        }
        // dd($close_panna_win,$jodiwinners,$all_winners,$formatted_data);


        return response()->json([
            'draw' => intval($request->input('draw')), // Pass draw value from DataTables
            'recordsTotal' => count($all_winners),
            'recordsFiltered' => count($all_winners),
            'data' => $formatted_data,
        ]);
    }


    /**
     * Fetch winners based on specific conditions.
     *
     * @param int $game_id
     * @param string $result_dec_date
     * @param string|null $market_status
     * @param string $column
     * @param string $value
     * @return array
     */
    private function getWinners($game_id, $result_dec_date, $market_status, $column, $value)
    {
        $query = DB::table('bid_table')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->where('market_id', $game_id)
            ->where('bid_date', $result_dec_date)
            ->where(function ($q) use ($column, $value) {
                $q->where($column, $value);
            });

        if ($market_status) {
            $query->where('session', $market_status);
        }

        return $query->select(
            'users.name',      // User name
            'bid_table.user_id', // User ID
            'bid_table.id', // bid  ID
            'bid_table.amount',  // Bid amount
            "bid_table.$column"  // Winning column
        )->get()->toArray();
    }






    public function show_winner(Request $request)
    {

        // dd($request->all());
        // Get the data from the request
        $game_id = $request->input('game_id');
        // $market_status = $request->input('market_status');
        $market_status = 'open';
        $result_dec_date = $request->input('result_dec_date');
        $open_number = $request->input('open_number');
        $open_result = $request->input('open_result');


        $query = DB::table('bid_table')
            ->join('users', 'bid_table.user_id', '=', 'users.id')
            ->where('market_id', $request->input('game_id'))
            ->where('session', $market_status)
            ->where('bid_date', $request->input('result_dec_date'))
            ->where(function ($query) use ($open_number, $open_result) {
                $query->where('open_panna', $open_number)
                    ->orWhere('open_digit', $open_result);
            })
            ->select('bid_table.*', 'users.name', 'users.number'); // Adjust this to select only what's necessary

        // dd($query->get());



        // Handle sorting (if any)
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        // $data = $query->offset($start)->limit($length)->get();
        $data = $query->offset($start)->limit($length)->get()->map(function ($row, $index) use ($start) {
            $row->DT_RowIndex = $start + $index + 1; // Add a sequence number
            return $row;
        });

        // Count filtered records (after search and filters)
        $filteredRecords = $query->count();

        // Return the JSON response with the data
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }




    public function editBid(Request $request)
    {
        // Validate the request
        $request->validate([
            'bid_id' => 'required|exists:bid_table,id'
        ]);



        return response()->json(['message' => 'Bid updated successfully']);
    }

    public function edit_bid(Request $request)
    {
        // Get the bid ID and new points
        $bidId = $request->input('bid_id');
        $newPoints = $request->input('points');

        // Trim and convert the points to an integer
        $newPoints = trim($newPoints);
        $newPoints = (int) $newPoints;

        // Check if the bid exists in the database
        $bid = DB::table('bid_table')->where('id', $bidId)->first();
        if (!$bid) {
            return response()->json(['message' => 'Bid not found'], 404);
        }

        // Update the appropriate field based on the length of the points
        if (strlen($newPoints) == 1) {
            // Update open_digit field
            $updated = DB::table('bid_table')
                ->where('id', $bidId)
                ->update(['open_digit' => $newPoints]);
        } elseif (strlen($newPoints) == 3) {
            // Update open_panna field
            $updated = DB::table('bid_table')
                ->where('id', $bidId)
                ->update(['open_panna' => $newPoints]);
        } else {
            return response()->json(['message' => 'Invalid points'], 400);
        }

        // Check if the update was successful
        if ($updated) {
            return response()->json(['message' => 'Bid updated successfully']);
        } else {
            return response()->json(['message' => 'Failed to update bid'], 500);
        }
    }





    public function close_edit_bid(Request $request)
    {
        // dd($request->all());
        $bidId = $request->input('bid_id');
        $newPoints = $request->input('points');

        // Retrieve the bid details
        $bid = DB::table('bid_table')
            ->where('id', $bidId)
            ->where(function ($query) {
                $query->whereNotNull('close_digit')
                    ->orWhereNotNull('close_panna');
            })
            ->first();

        // Check if the bid exists
        if (!$bid) {
            return response()->json(['message' => 'Bid not found or invalid bid'], 404);
        }

        // Update based on existing data
        $updateData = [];
        if ($bid->close_digit === 'N/A' || $bid->close_digit === null) {
            $updateData['close_panna'] = $newPoints;
        } else {
            $updateData['close_digit'] = $newPoints;
        }

        $updated = DB::table('bid_table')
            ->where('id', $bidId)
            ->update($updateData);

        if ($updated) {
            return response()->json(['message' => 'Bid updated successfully']);
        } else {
            return response()->json(['message' => 'Failed to update bid'], 500);
        }
    }


    public function delete_result(Request $request)
    {
        // Begin a transaction
        DB::beginTransaction();

        try {
            // Delete the result from the market_results table
            $deleted = DB::table('market_results')->where('id', $request->id)->delete();

            if ($deleted) {
                // Retrieve all winners associated with this result using the market_id
                $winners = DB::table('winners')
                    ->join('market_results', 'market_results.market_id', '=', 'winners.market_id')
                    ->where('market_results.id', $request->id)
                    ->select('winners.*')
                    ->get();

                if ($winners->isNotEmpty()) {
                    // Revert the winning amounts in users' wallets
                    foreach ($winners as $winner) {
                        DB::table('wallets')
                            ->where('user_id', $winner->user_id)
                            ->decrement('balance', $winner->winning_amount);
                    }

                    // Delete all winners associated with the result using the correct market_id
                    DB::table('winners')
                        ->where('market_id', $winners->first()->market_id)
                        ->delete();

                    // Commit the transaction if everything is successful
                    DB::commit();
                    return response()->json(['message' => 'Result and winnings reverted successfully.']);
                } else {
                    // If no winners are found, only delete the result and commit
                    DB::commit();
                    return response()->json(['message' => 'No winners found for this result.']);
                }
            } else {
                // Rollback if the result was not found or couldn't be deleted
                DB::rollBack();
                return response()->json(['message' => 'Result not found.'], 404);
            }
        } catch (\Exception $e) {
            // Rollback if there’s any error
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while deleting the result and reverting winnings.'], 500);
        }
    }


    public function approved_transiction(Request $request)
    {
        $user_id = $request->input('user_id');
    
        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }
    
        // Fetch approved deposits with a 'type' field
        $deposits = DB::table('deposite_table')
            ->where('user_id', $user_id)
            ->where('deposite_status', 'approved')
            ->select('id', 'deposit_amount as amount', 'deposite_date as date', DB::raw('"deposite" as type'))
            ->get();
    
        // Fetch approved withdrawals with a 'type' field
        $withdrawals = DB::table('withdrawal')
            ->where('user_id', $user_id)
            ->where('status', 'approved')
            ->select('id', 'amount as amount', 'withdrawal_date as date', DB::raw('"withdrawal" as type'))
            ->get();
    
        // Merge data from both tables
        $mergedData = $deposits->merge($withdrawals);
    
        // Total records before filtering
        $totalRecords = $mergedData->count();
    
        // **Fix Sorting Issue**
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
    
            // Ensure column exists to prevent Undefined Property error
            $validColumns = ['id', 'amount', 'date', 'type'];
    
            if (in_array($orderColumnName, $validColumns)) {
                $mergedData = $mergedData->sortBy(function ($item) use ($orderColumnName) {
                    return $item->$orderColumnName ?? null; // Avoid accessing undefined properties
                }, SORT_REGULAR, $orderDir === 'desc')->values();
            }
        }
    
        // **Fix Search Filtering**
        if ($request->has('search') && $searchValue = $request->search['value']) {
            $mergedData = $mergedData->filter(function ($item) use ($searchValue) {
                return stripos((string) $item->amount, $searchValue) !== false ||
                    stripos((string) $item->type, $searchValue) !== false ||
                    stripos((string) $item->date, $searchValue) !== false;
            })->values();
        }
    
        // Count records after filtering
        $filteredRecords = $mergedData->count();
    
        // **Fix Pagination**
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $pagedData = $mergedData->slice($start, $length)->values();
    
        // Return the JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $pagedData
        ]);
    }
    

    public function approved_debit(Request $request)
    {
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $query = DB::table('withdrawal')
            ->where('user_id', $user_id)
            ->where('status', 'approved');

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];

            // Ensure the column is valid and exists in the database table
            if (in_array($orderColumnName, ['amount', 'withdrawal_date', 'id'])) {
                $query->orderBy($orderColumnName, $orderDir);
            }
        }

        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Add sequence numbers to the data items
        foreach ($data as $index => $record) {
            $record->sequence_number = $start + $index + 1;
        }

        $filteredRecords = $totalRecords;

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }


    public function approved_credit(Request $request)
    {
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $query = DB::table('deposite_table')
            ->where('user_id', $user_id)
            ->where('deposite_status', 'approved');

        // Apply sorting securely
        if ($request->has('order') && isset($request->order[0]['column'], $request->columns)) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'] ?? 'asc';

            if (isset($request->columns[$orderColumnIndex]['data'])) {
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['amount', 'withdrawal_date', 'id'])) {
                    $query->orderBy($orderColumnName, $orderDir);
                }
            }
        }

        // Count total records before pagination
        $totalRecords = (clone $query)->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Add sequence numbers to the data items
        foreach ($data as $index => $record) {
            $record->sequence_number = $start + $index + 1;
        }

        // Filtered records count (after search, if added)
        $filteredRecords = $totalRecords;

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }


    public function add_fund_user_wallet(Request $request)
    {
        // Validate the form input
        $request->validate([
            'user' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Retrieve and update the user's wallet balance without using User::find
        $userId = $request->user;
        $amount = $request->amount;

        // dd($userId, $amount);


        // Use DB query builder to update the user's wallet balance
        $updated = DB::table('wallets')
            ->where('user_id', $userId)
            ->increment('balance', $amount);

        if ($updated) {

            // Return a success message if the update was successful and redirect back
            return redirect()->back()->with('success', 'Funds added to user wallet successfully.');
        }

        // Return an error if the update fails
        return redirect()->back()->with('error', 'Failed to add funds to user wallet.');
    }

    public function auto_deposite_history_list(Request $request)
    {
        // Build the base query
        $query = DB::table('deposite_table')
            ->join('users', 'deposite_table.user_id', '=', 'users.id')
            ->select('users.name', 'deposite_table.*')
            ->where('deposite_table.deposite_status', '!=', 'pending');

        // Apply date filter if a date is provided
        if ($request->filled('bid_revert_date')) {
            $query->whereDate('deposite_table.deposite_date', '=', $request->bid_revert_date);
        }

        // Handle searching
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records
        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering
        $filteredRecords = $query->count();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function desawar_delete_result(Request $request)
    {
        // Begin a transaction
        DB::beginTransaction();

        try {
            // Delete the result from the market_results table
            $deleted = DB::table('galidesawar_result')->where('id', $request->id)->delete();

            if ($deleted) {
                // Retrieve all winners associated with this result using the market_id
                $winners = DB::table(table: 'galidesawar_winners')
                    ->join('galidesawar_result', 'galidesawar_result.desawar_id', '=', 'galidesawar_winners.desawar_id')
                    ->where('galidesawar_result.id', $request->id)
                    ->select('galidesawar_winners.*')
                    ->get();

                if ($winners->isNotEmpty()) {
                    // Revert the winning amounts in users' wallets
                    foreach ($winners as $winner) {
                        DB::table('wallets')
                            ->where('user_id', $winner->user_id)
                            ->decrement('balance', $winner->winning_amount);
                    }

                    // Delete all winners associated with the result using the correct market_id
                    DB::table('galidesawar_winners')
                        ->where('desawar_id', $winners->first()->market_id)
                        ->delete();

                    // Commit the transaction if everything is successful
                    DB::commit();
                    return response()->json(['message' => 'Result and winnings reverted successfully.']);
                } else {
                    // If no winners are found, only delete the result and commit
                    DB::commit();
                    return response()->json(['message' => 'No winners found for this result.']);
                }
            } else {
                // Rollback if the result was not found or couldn't be deleted
                DB::rollBack();
                return response()->json(['message' => 'Result not found.'], 404);
            }
        } catch (\Exception $e) {
            // Rollback if there’s any error
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while deleting the result and reverting winnings.'], 500);
        }
    }

    public function desawar_result(Request $request)
    {
        // dd($request->all());
        $rules = [
            'open_number' => 'required',
            'game_id' => 'required|integer|gt:0',
            'result_dec_date' => 'required|date',
        ];

        $game_id = $request->input('game_id');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = [
                'desawar_id' => $request->input('game_id'),
                'digit' => $request->input('open_number'),
                'result_date' => $request->input('result_dec_date'),
                'created_at' => now(),
            ];

            // dd($data);


            $kk = DB::table('galidesawar_result')->insert($data);
            // return redirect()->route('galiResult')->with('success', 'Game result has been declared successfully.');
            // dd($kk);
            if ($kk) {

                return redirect()->route('galiResult', ['game_id' => $request->input('game_id')])
                    ->with('success', 'Game result has been declared successfully.');
            }
            // dd('hi');
            // return redirect()->route('galiResult')->with([
            //     'success' => 'Game result has been declared successfully.',
            //     'game_id' => $game_id,
            // ]);

        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Failed to declare the result. Please try again.');
        }
    }



    public function galiResult(Request $request, $game_id)
    {
        // dd($game_id);
        $result = DB::table('galidesawar_result')
            ->where('result_date', today()->toDateString())
            ->where('desawar_id', $game_id)
            ->select('desawar_id', 'result_date', 'digit')
            ->first();
        // dd($result);
        if (!$result) {
            return redirect()->route('desawar_declare_result')->with('message', 'No result found for today.');
        }

        $rates = DB::table('galidesawar_game_rates')->first();

        if (!$rates) {
            return redirect()->route('desawar_declare_result')->with('error', 'Game rates not found.');
        }

        $bids = $this->getBids($result);
        // dd($bids);
        $winners = $this->processBids($bids, $result, $rates);
        // $winners = $this->filterWinners($winners, $result, $rates);
        // dd($winners);

        if (count($winners) > 0) {
            $this->storeWinners($winners);
            return redirect()->route('desawar_winning_prediction');
        }

        return redirect()->route('desawar_declare_result')->with('message', 'No matches found for today\'s result.');
    }

    private function getBids($result)
    {
        if ((int)$result->desawar_id === 30) {
            $today = today()->toDateString();
            $yesterday = today()->subDay()->toDateString();

            $bidsToday = DB::table('galidesawar_bid_table')
                ->where('desawar_id', 30)
                ->where('desawar_bid_date', $today)
                ->get();

            $bidsYesterday = DB::table('galidesawar_bid_table')
                ->where('desawar_id', 30)
                ->where('desawar_bid_date', $yesterday)
                ->get();

            return $bidsToday->merge($bidsYesterday);
        }

        return DB::table('galidesawar_bid_table')
            ->where('desawar_bid_date', today()->toDateString())
            ->where('desawar_id', $result->desawar_id)
            ->get();
    }

    private function processBids($bids, $result, $rates)
    {
        $winners = [];
        $processedBids = [];

        foreach ($bids as $bid) {
            if (in_array($bid->id, $processedBids)) {
                continue;
            }

            $winning_amount = 0;
            $bid_type = '';

            if ($bid->desawar_gtype_id == 1 && $bid->digit == substr($result->digit, 0, 1)) {
                $winning_amount = $bid->amount / $rates->left_digit_bid * $rates->left_digit_win;
                $bid_type = 'left digit';
            } elseif ($bid->desawar_gtype_id == 2 && $bid->digit == substr($result->digit, 1, 1)) {
                $winning_amount = $bid->amount / $rates->right_digit_bid * $rates->right_digit_win;
                $bid_type = 'right digit';
            } elseif ($bid->desawar_gtype_id == 3 && $bid->digit == $result->digit) {
                $winning_amount = $bid->amount / $rates->jodi_digit_bid * $rates->jodi_digit_win;
                $bid_type = 'jodi';
            }

            if ($winning_amount > 0) {
                $winners[] = [
                    'user_id' => $bid->user_id,
                    'bid_id' => $bid->id,
                    'desawar_id' => $bid->desawar_id,
                    'bid_type' => $bid_type,
                    'bid_point' => $bid->digit,
                    'winning_amount' => $winning_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $processedBids[] = $bid->id;
            }
        }

        return $winners;
    }

    private function storeWinners($winners)
    {
        // Fetch existing bid IDs from the database
        $existingBidIds = DB::table('galidesawar_winners')
            ->whereIn('bid_id', array_column($winners, 'bid_id'))
            ->pluck('bid_id')
            ->toArray();

        // Filter out winners with duplicate bid IDs
        $filteredWinners = array_filter($winners, function ($winner) use ($existingBidIds) {
            return !in_array($winner['bid_id'], $existingBidIds);
        });

        // Update wallet balances and insert winners into the database
        foreach ($filteredWinners as $winner) {
            // Update user wallet balance
            DB::table('wallets')
                ->where('user_id', $winner['user_id'])
                ->increment('balance', $winner['winning_amount']);
        }

        // Insert only unique winners
        if (!empty($filteredWinners)) {
            DB::table('galidesawar_winners')->insert($filteredWinners);
        }
    }



    public function showDesawarWinner(Request $request)
    {
        // dd($request->all());
        $rules = [
            'open_number' => 'required',
            'game_id' => 'required|integer|gt:0',
            'result_dec_date' => 'required|date',
        ];

        // Validate the incoming request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $openNumber = $request->input('open_number');
            $gameId = $request->input('game_id');
            $resultDecDate = $request->input('result_dec_date');

            // Fetch game rates from database
            $gameRates = DB::table('galidesawar_game_rates')->first();

            if (!$gameRates) {
                return redirect()->route('desawar_declare_result')->with('error', 'Game rates not found.');
            }

            // Fetch bids for the game
            $bids = $this->getBidsForGame($gameId, $openNumber, $resultDecDate);

            // Process the bids to determine winners
            $winners = $this->processWinningBids($bids, $gameRates, $openNumber);
            // dd($winners);

            // If winners are found, return success with data
            if (count($winners) > 0) {
                return response()->json([
                    'status' => 'success',
                    'data' => $winners, // Send the winners data
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No winners found.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again.',
            ]);
        }
    }

    // Method to fetch bids for the given game
    private function getBidsForGame($gameId, $openNumber, $resultDecDate)
    {
        $today = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();

        // Query bids for the given game and date
        if ((int)$gameId === 30) {
            // Get today's and yesterday's bids for game ID 30
            $bidsToday = $this->getBidsByDate($gameId, $today);
            $bidsYesterday = $this->getBidsByDate($gameId, $yesterday);

            return $bidsToday->merge($bidsYesterday);
        }

        // Get bids for other game IDs
        return $this->getBidsByDate($gameId, $today);
    }

    // Helper method to query bids by date
    private function getBidsByDate($gameId, $date)
    {
        return DB::table('galidesawar_bid_table')
            ->join('users', 'galidesawar_bid_table.user_id', '=', 'users.id')
            ->join('galidesawar_market', 'galidesawar_market.id', '=', 'galidesawar_bid_table.desawar_id')
            ->select('galidesawar_bid_table.*', 'users.name', 'users.number', 'galidesawar_market.desawar_name')
            ->where('desawar_id', $gameId)
            ->where('desawar_bid_date', $date)
            ->get();
    }

    // Method to process the bids and determine winners
    private function processWinningBids($bids, $gameRates, $openNumber)
    {
        $winners = [];
        $processedBids = [];

        foreach ($bids as $bid) {
            // Skip already processed bids
            if (in_array($bid->id, $processedBids)) {
                continue;
            }

            $winningAmount = 0;
            $bidType = '';

            // Calculate winning amount based on the game type
            switch ($bid->desawar_gtype_id) {
                case 1: // Left digit match
                    if ($bid->digit == substr($openNumber, 0, 1)) {
                        $winningAmount = $bid->amount / $gameRates->left_digit_bid * $gameRates->left_digit_win;
                        $bidType = 'left digit';
                    }
                    break;

                case 2: // Right digit match
                    if ($bid->digit == substr($openNumber, 1, 1)) {
                        $winningAmount = $bid->amount / $gameRates->right_digit_bid * $gameRates->right_digit_win;
                        $bidType = 'right digit';
                    }
                    break;

                case 3: // Jodi digit match
                    if ($bid->digit == $openNumber) {
                        $winningAmount = $bid->amount / $gameRates->jodi_digit_bid * $gameRates->jodi_digit_win;
                        $bidType = 'jodi';
                    }
                    break;
            }

            // If the bid wins, add to the winners list
            if ($winningAmount > 0) {
                $winners[] = [
                    'user_name' => $bid->name,
                    'market_name' => $bid->desawar_name,
                    'bid_id' => $bid->id,
                    'bid_type' => $bidType,
                    'bid_point' => $bid->digit,
                    'winning_amount' => $winningAmount,
                ];
                $processedBids[] = $bid->id; // Mark this bid as processed
            }
        }

        return $winners;
    }


    public function showDesawarwinnerEdit(Request $request)
    {
        try {
            // Retrieve the bid record based on the bid_id
            $bid = DB::table('galidesawar_bid_table')
                ->join('users', 'galidesawar_bid_table.user_id', '=', 'users.id')
                ->join('galidesawar_market', 'galidesawar_market.id', '=', 'galidesawar_bid_table.desawar_id')
                ->select('galidesawar_bid_table.*', 'users.name', 'users.number', 'galidesawar_market.desawar_name')
                ->where('galidesawar_bid_table.id', $request->bid_id)
                ->first();

            if ($bid) {
                return response()->json([
                    'status' => 'success',
                    'data' => $bid,  // Return the bid data (including bid_id)
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bid not found.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching bid details.',
            ]);
        }
    }


    public function updateDesawarWinner(Request $request)
    {
        // dd($request->all());
        try {
            // Validate the incoming data (ensure bid_id and digit are provided)
            $request->validate([
                'bid_id' => 'required|integer|exists:galidesawar_bid_table,id',  // Ensure the bid_id exists
                'digit' => 'required|integer',  // Ensure the new bid point (digit) is valid
            ]);

            // Retrieve the bid record based on the bid_id
            $bid = DB::table('galidesawar_bid_table')
                ->where('id', $request->bid_id)
                ->first();

            if (!$bid) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bid not found.',
                ]);
            }

            // Update the digit (bid point)
            DB::table('galidesawar_bid_table')
                ->where('id', $request->bid_id)
                ->update(['digit' => $request->digit]);  // Update the digit column

            return response()->json([
                'status' => 'success',
                'message' => 'Bid point updated successfully.',
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the bid point.',
            ]);
        }
    }





    public function starline_delete_result(Request $request)
    {
        // Begin a transaction
        DB::beginTransaction();

        try {
            // Delete the result from the market_results table
            $deleted = DB::table('starline_result')->where('id', $request->id)->delete();

            if ($deleted) {
                // Retrieve all winners associated with this result using the market_id
                $winners = DB::table(table: 'starline_winners')
                    ->join('starline_result', 'starline_result.starline_id', '=', 'starline_winners.starline_id')
                    ->where('starline_result.id', $request->id)
                    ->select('starline_winners.*')
                    ->get();

                if ($winners->isNotEmpty()) {
                    // Revert the winning amounts in users' wallets
                    foreach ($winners as $winner) {
                        DB::table('wallets')
                            ->where('user_id', $winner->user_id)
                            ->decrement('balance', $winner->winning_amount);
                    }

                    // Delete all winners associated with the result using the correct market_id
                    DB::table('starline_winners')
                        ->where('starline_id', $winners->first()->market_id)
                        ->delete();

                    // Commit the transaction if everything is successful
                    DB::commit();
                    return response()->json(['message' => 'Result and winnings reverted successfully.']);
                } else {
                    // If no winners are found, only delete the result and commit
                    DB::commit();
                    return response()->json(['message' => 'No winners found for this result.']);
                }
            } else {
                // Rollback if the result was not found or couldn't be deleted
                DB::rollBack();
                return response()->json(['message' => 'Result not found.'], 404);
            }
        } catch (\Exception $e) {
            // Rollback if there’s any error
            DB::rollBack();
            return response()->json(['message' => 'An error occurred while deleting the result and reverting winnings.'], 500);
        }
    }
}
