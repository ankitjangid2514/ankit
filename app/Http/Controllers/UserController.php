<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{


    public function download()
    {
        return view('admin.download');
    }
    public function privacyPolicy()
    {
        return view('user.privacyPolicy');
    }
    public function register()
    {
        return view('user.register');
    }

    public function register_insert(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required',
            'mobile' => 'required',
            'password' => 'required',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // If validation passes
        if (!$validator->fails()) {
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

            $bonus = DB::table('set_amount')->value('welcome_bonus');

            // Check if user insertion was successful
            if ($userId) {
                // Prepare wallet data
                $walletData = [
                    'user_id' => $userId, // Associate the wallet with the new user
                    'balance' => $bonus, // Initial balance of 5 rupees
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert into the wallet table
                DB::table('wallets')->insert($walletData);

                // Redirect to dashboard or return success response
                return redirect()->route('login')->with('success', 'User registered and wallet credited successfully!');
            }
        } else {
            // If validation fails, return error messages
            return response()->json(['errors' => $validator->errors()]);
        }
    }



    public function login()
    {
        $admin = DB::table('admin')->first();

        return view('user.login', compact('admin'));
    }

    public function login_insert(Request $request)
    {
        // Validate the input
        $request->validate([
            'number' => 'required|digits:10',
            'password' => 'required|min:6',
        ]);

        // Check if the user exists
        $user = DB::table('users')->where('number', $request->number)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Check user approval status
            if ($user->status === 'approved') {
                // Login and redirect with success message
                Auth::loginUsingId($user->id);
                return redirect()->intended('dashboard')->with('success', 'Login successful!');
            } else {
                // Redirect back with account approval error
                return redirect()->back()->with('error', 'Your account is not approved yet.');
            }
        }

        // Redirect back with invalid credentials error
        return redirect()->back()->with('error', 'Invalid details.');
    }




    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function dashboard()
    {
        try {
            $userId = Auth::id();

            // Retrieve active markets ordered by open_time
            $markets = DB::table('market')
                ->where('market_status', 'active')
                ->orderBy('open_time', 'asc')
                ->get();

            // Handle the case where there is no market data
            if ($markets->isEmpty()) {
                return view('user.dashboard', [
                    'markets' => null,
                    'close_time' => null,
                    'checked' => 0,
                    'admin' => DB::table('admin')->first(),
                    'images' => DB::table('slider_images')->where('status', 'active')->select('image')->get()
                ]);
            }

            // Retrieve user data
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $userId)
                ->first();

            if (!$userData) {
                Auth::logout();
                return redirect()->route('login');
            }

            // Collect market IDs
            $marketIds = $markets->pluck('id');

            // Fetch results for all markets
            $results = DB::table('market_results')
                ->select('market_id', 'open_panna', 'close_panna', 'open_digit', 'close_digit')
                ->whereIn('market_id', $marketIds)
                ->where('result_date', date('Y-m-d'))
                ->get()
                ->groupBy('market_id');

            // Fetch admin data and images
            $admin = DB::table('admin')->first();
            $images = DB::table('slider_images')->where('status', 'active')->select('image')->get();

            return view('user.dashboard', compact('markets', 'userData', 'results', 'admin', 'images'));
        } catch (\Exception $e) {
            // Catch any exception and log it (optional)
            \Log::error('Error in dashboard: ' . $e->getMessage());

            // Redirect with a generic error message
            return redirect()->route('dashboard')->with('error', 'Something went wrong, please try again later.');
        }
    }



    public function getBalance()
    {
        $userId = Auth::id(); // Get the authenticated user's ID

        if ($userId) {
            $balance = DB::table('wallets')->where('user_id', $userId)->value('balance');
            // dd($balance); 
            return response()->json(['balance' => $balance]);
        }

        return response()->json(['error' => 'User not authenticated'], 401);
    }



    public function profile()
    {
        try {
            $userId = Auth::id();

            // Retrieve user data
            $userData = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.name', 'users.number', 'wallets.balance')
                ->where('users.id', $userId) // Specify the table name here
                ->first();

            // Check if user data was retrieved successfully
            if ($userData) {
                // Access the properties safely
                $name = $userData->name;
                $number = $userData->number;
                $balance = $userData->balance; // This will work only if $userData is not null
            } else {
                // Handle the case where user data is not found
                Auth::logout(); // Log the user out
                return redirect()->route('login'); // Redirect to login page
            }

            return view('user.profile', compact('userData'));
        } catch (\Exception $e) {
            // Catch any exception and log it (optional)
            \Log::error('Error in profile: ' . $e->getMessage());

            // Redirect with a generic error message
            return redirect()->route('profile')->with('error', 'Something went wrong, please try again later.');
        }
    }


    public function wallet()
    {
        $userId = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $userId) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        return view('user.wallet', compact('userData'));
    }

    public function addPoint()
    {
        return view('addPoint', [
            'show_upi' => true,  // এটি UPI অপশন দেখানোর জন্য
            'upi_id' => '6289737464@ybl',
            'min_amount' => 100,
            'max_amount' => 10000
        ]);
    }


    public function deposit(Request $request)
    {
        $id = Auth::id();
        // Minimum Deposit 
        $minimumDeposit = DB::table('set_amount')->value('min_deposite');
        $deposit_amount = $request->input('amount');
        $rules = [
            'upload_qr' => 'required',
            'amount' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($minimumDeposit <= $deposit_amount) {
            $data = [
                'user_id' => $id,
                'deposit_amount' => $request->amount,
                'deposite_status' => 'pending',
                'deposite_date' => now(),
                'updated_at' => now()
            ];

            // Upload QR code image file if present
            if ($request->hasFile('upload_qr')) {
                $qr_code = $request->file('upload_qr');
                $qr_code_name = time() . '.' . $qr_code->getClientOriginalExtension();
                $qr_code->move(public_path('uploads/qr_code'), $qr_code_name);
                $filepath = 'uploads/qr_code/' . $qr_code_name;

                // Add QR code file name to the update data
                $data['upload_qr'] = $filepath;
            }

            DB::table('deposite_table')->insert($data);

            // Success message
            return redirect()->back()->with('success', 'Deposit request successfully submitted.');
        } else {
            // Error message
            return redirect()->back()->with('error', "Minimum deposit amount is ₹$minimumDeposit.");
        }
    }


    public function withdrawPoint()
    {
        $userId = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $userId) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $admin = DB::table('admin')->select('whatsapp_number')->first();
        // minimum thdrawPoins 
        $minWithdraw_amount = DB::table('set_amount')->select('min_withdrawal')->first();

        return view('user.withdrawPoint', compact('userData', 'admin', 'minWithdraw_amount'));
    }


    public function withdrawal_amount(Request $request)
    {
        // Retrieve the minimum withdrawal amount from the database
        $minWithdrawal = DB::table('set_amount')->value('min_withdrawal');

        // Define validation rules
        $rules = [
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($minWithdrawal) {
                    if ($value < $minWithdrawal) {
                        $fail("The $attribute must be at least ₹$minWithdrawal.");
                    }
                },

            ],
            'mode' => 'required|string',
            'number' => 'required|numeric',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Debug validation errors
            // dd($validator->errors()->all());

            // Redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Perform withdrawal logic if validation passes
        $withdrawalAmount = $request->input('amount');
        $id = Auth::id();
        $user = DB::table('wallets')->where('user_id', $id)->first();

        if ($user && $user->balance >= $withdrawalAmount) {
            DB::table('wallets')->where('user_id', $id)->decrement('balance', $withdrawalAmount);
            DB::table('withdrawal')->insert([
                'user_id' => $id,
                'amount' => $withdrawalAmount,
                'payout' => $request->input('mode'),
                'number' => $request->input('number'),
                'status' => 'pending',
                'withdrawal_date' => now(),
            ]);
            return redirect()->back()->with('success', 'Withdrawal request submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }
    }


    public function history_bid(Request $request)
    {
        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }


        return view('user.bid-history', compact('userData'));
    }



    public function bid_history_list(Request $request)
    {
        $userId = auth()->id();

        // Validate the selected date
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;

        // Fetch bids directly from the database based on the selected date and user ID
        $bids = DB::table('bid_table')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id') // Joining game type table
            ->join('market', 'bid_table.market_id', '=', 'market.id') // Joining the market table
            ->whereDate('bid_table.created_at', $date) // Filter by selected date
            ->where('bid_table.user_id', $userId) // Ensure it matches the user ID
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
                'bid_table.created_at'
            )
            ->get();
        // dd($bids);
        // Helper function to check for valid values
        $isValidValue = function ($value) {
            return $value !== 'N/A' && $value !== 'Null' && !empty($value);
        };

        // Process bids into `bid_array`
        $bid_array = $bids->map(function ($bid) use ($isValidValue) {
            $gtype = trim($bid->gtype);

            // Determine the bid type and corresponding bid number
            if ($isValidValue($bid->open_digit) && $gtype === 'Single Digit' && $bid->session === 'open') {
                $bid_number = $bid->open_digit;
            } elseif ($isValidValue($bid->close_digit) && $gtype === 'Single Digit' && $bid->session === 'close') {
                $bid_number = $bid->close_digit;
            } elseif ($isValidValue($bid->close_panna) && $gtype === 'Single Panna' && $bid->session === 'close') {
                $bid_number = $bid->close_panna;
            } elseif ($isValidValue($bid->open_panna) && $gtype === 'Single Panna' && $bid->session === 'open') {
                $bid_number = $bid->open_panna;
            } elseif ($isValidValue($bid->open_panna) && $gtype === 'Double Panna' && $bid->session === 'open') {
                $bid_number = $bid->open_panna;
            } elseif ($isValidValue($bid->close_panna) && $gtype === 'Double Panna' && $bid->session === 'close') {
                $bid_number = $bid->close_panna;
            } elseif ($isValidValue($bid->close_panna) && $gtype === 'Triple Panna' && $bid->session === 'close') {
                $bid_number = $bid->close_panna;
            } elseif ($isValidValue($bid->open_panna) && $gtype === 'Triple Panna' && $bid->session === 'open') {
                $bid_number = $bid->open_panna;
            } elseif ($isValidValue($bid->jodi) && $gtype === 'Jodi Digit') {
                $bid_number = $bid->jodi;
            } elseif ($isValidValue($bid->half_sangam_a) && $gtype === 'Half Sangam' && $bid->session === 'open') {
                $bid_number = $bid->half_sangam_a;
            } elseif ($isValidValue($bid->half_sangam_b) && $gtype === 'Half Sangam' && $bid->session === 'close') {
                $bid_number = $bid->half_sangam_b;
            } elseif ($isValidValue($bid->full_sangam) && $gtype === 'Full Sangam') {
                $bid_number = $bid->full_sangam;
            } elseif ($isValidValue($bid->open_panna) && $gtype === 'sp moter' && $bid->session === 'open') {
                $bid_number = $bid->open_panna;
            } elseif ($isValidValue($bid->close_panna) && $gtype === 'sp moter' && $bid->session === 'close') {
                $bid_number = $bid->close_panna;
            } elseif ($isValidValue($bid->open_panna) && $gtype === 'dp moter' && $bid->session === 'open') {
                $bid_number = $bid->open_panna;
            } elseif ($isValidValue($bid->close_panna) && $gtype === 'dp moter' && $bid->session === 'close') {
                $bid_number = $bid->close_panna;
            } else {
                return null; // Exclude invalid bids
            }

            return [
                'market_name' => $bid->market_name,
                'session' => $bid->session,
                'game' => $gtype,
                'bid_number' => $bid_number,
                'amount' => $bid->amount,
                'date' => Carbon::parse($bid->created_at)->format('Y-m-d'),
            ];
        })->filter(); // Remove null values from the array

        // Return back with bid data
        return back()->with([
            'bids' => $bid_array,
            'selected_date' => $date,
        ]);
    }





    public function history_bidData(Request $request)
    {
        try {
            $userId = Auth::id(); // Get the logged-in user's ID
            $bidDate = $request->input('bid_date', date('Y-m-d')); // Default to today

            // Query to fetch bid data for the given date and user
            $data = DB::table('bid_table')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->select(
                    'market.market_name',
                    'gtype.gtype',
                    'bid_table.session',
                    'bid_table.open_digit',
                    'bid_table.close_digit',
                    'bid_table.open_panna',
                    'bid_table.close_panna',
                    'bid_table.amount',
                    'bid_table.bid_date'
                )
                ->where('bid_table.user_id', $userId)
                ->whereDate('bid_table.bid_date', $bidDate)
                ->get();

            // Return data as JSON for AJAX
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            // Return error message as JSON
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }






    public function wallet_history()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('withdrawal')
            ->where('withdrawal.user_id', $id)
            ->get();

        return view('user.wallet-history', compact('data', 'userData'));
    }

    public function winning_history()
    {
        try {
            $id = Auth::id();

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

            // Retrieve winning history data
            // $data = DB::table('winnings')
            //     ->where('user_id', $id)
            //     ->get();

            return view('user.winning_history', compact('userData'));
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving winning history.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function winning_history_list(Request $request)
    {
        // Retrieve the selected date, if any, and store it in the session
        if ($request->has('date') && $request->input('date')) {
            $selectedDate = $request->input('date');
            $request->session()->put('selectedDate', $selectedDate); // Store selected date in session
        } else {
            // If no date selected, get it from the session or default to today
            $selectedDate = $request->session()->get('selectedDate', now()->toDateString());
        }

        $user_id = auth()->id(); // Get the logged-in user ID

        // Query the database for winnings based on the selected date
        $winnings = DB::table('winners')
            ->join('market', 'winners.market_id', '=', 'market.id')
            ->join('bid_table', 'winners.bid_id', '=', 'bid_table.id')
            ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
            ->whereDate('winners.created_at', $selectedDate)
            ->where('winners.user_id', $user_id)
            ->select(
                'market.market_name as market_name',
                'winners.winning_amount as amount',
                'winners.bid_point',
                'winners.session',
                'winners.created_at as date',
                'gtype.gtype as gtype',
                'bid_table.amount as bid_amount'
            )
            ->get();

        // Use return back() to redirect to the same page with winnings and selected date in session
        return back()->with([
            'winnings' => $winnings,
            'selectedDate' => $selectedDate,
        ]);
    }


    public function gameRates()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('game_rates')->first();

        $starline = DB::table('starline_game_rates')->first();

        return view('user.gameRates', compact('data', 'userData', 'starline'));
    }

    public function contact()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $admin = DB::table('admin')->first();

        return view('user.contact', compact('userData', 'admin'));
    }

    public function help()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        return view('user.help', compact('userData'));
    }

    public function password()
    {
        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        return view('user.password', compact('userData'));
    }

    public function update_password(Request $request)
    {
        // Get the authenticated user's ID
        $id = Auth::id();

        // Validate the input
        $request->validate([
            'opass' => 'required',                  // Old Password
            'npass' => 'required|min:6',           // New Password with minimum length of 6
            'cpass' => 'required|same:npass',      // Confirm Password matching New Password
        ]);

        // Fetch the current user from the database
        $user = DB::table('users')->where('id', $id)->first();

        // Check if the entered old password matches the current password
        if (!Hash::check($request->opass, $user->password)) {
            return back()->withErrors(['opass' => 'The old password is incorrect.']);
        }

        // Check if the new password is the same as the old password
        if (Hash::check($request->npass, $user->password)) {
            return back()->withErrors(['npass' => 'The new password cannot be the same as the old password.']);
        }

        // Update the password in the database
        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make($request->npass),
        ]);

        // Redirect back with a success message
        return back()->with('status', 'Password updated successfully!');
    }

    public function game_insert_game(Request $request)
    {
        // dd($request->all());
        // Decode the panna collection from JSON
        $panna_collection = $request->input('all_bids');
        $panna_collection = json_decode($panna_collection, true);

        $length = count($panna_collection);

        // Calculate the total bid amount
        $total_amount = $request->input('amount') * $length;

        // Get the authenticated user ID
        $user_id = Auth::id();

        // Fetch the minimum bid amount from the 'set_amount' table
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
        $bid_amount = $total_amount;

        // Define validation rules
        $rules = [
            'gdate' => 'required',
            'digit' => 'integer',
            'amount' => 'required|integer',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ensure bid amount is greater than or equal to min_bid_amount
        if ($bid_amount >= $min_bid_amount) {
            // Loop through each panna combination in the collection
            foreach ($panna_collection as $bid) {
                // Prepare the data for insertion
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

                // Get other form inputs
                $digit = $request->input('digit');
                $open_panna = $request->input('open_panna');
                $close_panna = $request->input('close_panna');
                $open_digit = $request->input('open_digit');

                $half_sangam_a_open_digit = $request->input('open_digit_half_a');
                $half_sangam_a_close_panna = $request->input('close_panna_half_a');
                $half_sangam_b_close_digit = $request->input('close_digit_half_sangam_b');
                $half_sangam_b_open_panna = $request->input('open_panna_half_sangam_b');

                $close_panna_full_sangam = $request->input('close_panna_full_sangam');
                $open_panna_full_sangam = $request->input('open_panna_full_sangam');

                // Check the session type and assign digit/panna accordingly
                if ($data['session'] === 'open') {
                    $data['open_digit'] = $digit ?? 'N/A';
                    $data['open_panna'] = $bid['panna_s'] ?? 'N/A';
                    $data['close_panna'] = $close_panna ?? 'N/A';
                    $data['half_sangam_a'] = isset($half_sangam_a_open_digit) && isset($half_sangam_a_close_panna)
                        ? "$half_sangam_a_open_digit - $half_sangam_a_close_panna"
                        : 'N/A';
                } elseif ($data['session'] === 'close') {
                    $data['open_digit'] = $open_digit ?? 'N/A';
                    $data['close_digit'] = $digit ?? 'N/A';
                    $data['close_panna'] = $bid['panna_s'] ?? $close_panna ?? 'N/A';
                    $data['half_sangam_b'] = isset($half_sangam_b_close_digit) && isset($half_sangam_b_open_panna)
                        ? "$half_sangam_b_close_digit - $half_sangam_b_open_panna"
                        : 'N/A';
                } else {
                    $data['full_sangam'] = isset($open_panna_full_sangam) && isset($close_panna_full_sangam)
                        ? "$open_panna_full_sangam - $close_panna_full_sangam"
                        : 'N/A';
                    if (!empty($digit)) {
                        $data['jodi'] = $digit;
                    }
                    $data['open_panna'] = $open_panna ?? 'N/A';
                    $data['close_panna'] = $close_panna ?? 'N/A';
                }

                // Check user's balance and insert the bid for each panna combination
                $user = DB::table('wallets')->where('user_id', $user_id)->first();

                if ($user && $user->balance >= $request->amount) {
                    // Insert bid and deduct balance if conditions are met
                    DB::table('bid_table')->insert($data);
                } else {
                    return redirect()->back()->withErrors(['balance' => 'Insufficient wallet balance.']);
                }
            }

            // Deduct the total amount from the user's balance after all bids have been inserted
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $total_amount);

            return redirect()->back()->with('success', 'Bids placed and wallet updated successfully.');
        } else {
            return redirect()->back()->with('error', "Bid amount must be at least $min_bid_amount.");
        }
    }

    // public function game_insert_game(Request $request)
    // {   
    //     dd($request->all());  // Debugging to inspect the input values

    //     // Decode the panna collection from JSON
    //     // $panna_collection = $request->input('all_bids');
    //     $panna_collection = json_decode($panna_collection, true);

    //     $length = count($panna_collection);

    //     // Calculate the total bid amount
    //     $total_amount = $request->input('amount') * $length;

    //     // Get the authenticated user ID
    //     $user_id = Auth::id();

    //     // Fetch the minimum bid amount from the 'set_amount' table
    //     $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
    //     $bid_amount = $total_amount;

    //     // Define validation rules
    //     $rules = [
    //         'gdate' => 'required',
    //         'digit' => 'integer',
    //         'amount' => 'required|integer',
    //     ];

    //     // Validate the request
    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     // Ensure bid amount is greater than or equal to min_bid_amount
    //     if ($bid_amount >= $min_bid_amount) {
    //         // Loop through each panna combination in the collection
    //         foreach ($panna_collection as $panna) {
    //             // Prepare the data for insertion
    //             $data = [
    //                 'market_id' => $request->market_id,
    //                 'gtype_id' => $request->gtype_id,
    //                 'user_id' => $user_id,
    //                 'bid_date' => now(),
    //                 'session' => $request->input('timetype'),
    //                 'open_digit' => 'N/A',
    //                 'close_digit' => 'N/A',
    //                 'jodi' => 'N/A',
    //                 'open_panna' => 'N/A',
    //                 'close_panna' => 'N/A',
    //                 'amount' => $request->input('amount'),
    //                 'panna_s' => $panna, // Insert the panna combination (panna_s) here
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];

    //             // Get other form inputs
    //             $digit = $request->input('digit');
    //             $open_panna = $request->input('open_panna');
    //             $close_panna = $request->input('close_panna');
    //             $open_digit = $request->input('open_digit');

    //             $half_sangam_a_open_digit = $request->input('open_digit_half_a');
    //             $half_sangam_a_close_panna = $request->input('close_panna_half_a');
    //             $half_sangam_b_close_digit = $request->input('close_digit_half_sangam_b');
    //             $half_sangam_b_open_panna = $request->input('open_panna_half_sangam_b');

    //             $close_panna_full_sangam = $request->input('close_panna_full_sangam');
    //             $open_panna_full_sangam = $request->input('open_panna_full_sangam');

    //             // Check the session type and assign digit/panna accordingly
    //             if ($data['session'] === 'open') {
    //                 $data['open_digit'] = $digit ?? 'N/A';
    //                 $data['open_panna'] = $panna ?? 'N/A';  // Use $panna here directly
    //                 $data['close_panna'] = $close_panna ?? 'N/A';
    //                 $data['half_sangam_a'] = isset($half_sangam_a_open_digit) && isset($half_sangam_a_close_panna) 
    //                     ? "$half_sangam_a_open_digit - $half_sangam_a_close_panna" 
    //                     : 'N/A';
    //             } elseif ($data['session'] === 'close') {
    //                 $data['open_digit'] = $open_digit ?? 'N/A';
    //                 $data['close_digit'] = $digit ?? 'N/A';
    //                 $data['close_panna'] = $panna ?? $close_panna ?? 'N/A';
    //                 $data['half_sangam_b'] = isset($half_sangam_b_close_digit) && isset($half_sangam_b_open_panna) 
    //                     ? "$half_sangam_b_close_digit - $half_sangam_b_open_panna" 
    //                     : 'N/A';
    //             } else {
    //                 $data['full_sangam'] = isset($open_panna_full_sangam) && isset($close_panna_full_sangam) 
    //                     ? "$open_panna_full_sangam - $close_panna_full_sangam" 
    //                     : 'N/A';
    //                 if (!empty($digit)) {
    //                     $data['jodi'] = $digit;
    //                 }
    //                 $data['open_panna'] = $open_panna ?? 'N/A';
    //                 $data['close_panna'] = $close_panna ?? 'N/A';
    //             }

    //             // Check user's balance and insert the bid for each panna combination
    //             $user = DB::table('wallets')->where('user_id', $user_id)->first();

    //             if ($user && $user->balance >= $request->amount) {
    //                 // Insert bid and deduct balance if conditions are met
    //                 DB::table('bid_table')->insert($data);
    //             } else {
    //                 return redirect()->back()->withErrors(['balance' => 'Insufficient wallet balance.']);
    //             }
    //         }

    //         // Deduct the total amount from the user's balance after all bids have been inserted
    //         DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $total_amount);

    //         return redirect()->back()->with('success', 'Bids placed and wallet updated successfully.');
    //     } else {
    //         return redirect()->back()->with('error', "Bid amount must be at least $min_bid_amount.");
    //     }
    // }



    public function resultChart($result_id)
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('market_results')->where('market_id', $result_id)->get();

        $game = DB::table('market')->where('id', $result_id)->get();
        // dd($data);

        return view('user.resultChart', compact('data', 'game', 'userData'));
    }

    public function games($market_id)
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }
        // dd($market_id);
        $marketid = $market_id;

        $time = DB::table('market')->select('open_time')->get();

        $data = DB::table('gtype')->get();
        // dd($data);
        return view('user.games', compact('data', 'marketid', 'time', 'userData'));
    }

    public function addGame(Request $request, $gtype_id, $market_id)
    {
        $userId = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $userId) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }
        // dd($market_id);
        // Now both $gtype_id and $market_id are directly passed
        $market_id = $market_id;
        // $open_time
        $time = DB::table('market')
            ->select('open_time')
            ->where('id', $market_id)
            ->first();

        return view('user.addGame', compact('gtype_id', 'market_id', 'time', 'userData'));
    }


    public function game_insert(Request $request)
    {
        // dd($request->all());
        // Get the authenticated user ID
        $user_id = Auth::id();

        // Fetch the minimum bid amount from the 'set_amount' table
        $min_bid_amount = DB::table('set_amount')->value('min_bid_amount');
        $bid_amount = $request->input('amount');

        // Define validation rules
        $rules = [
            'gdate' => 'required',
            'digit' => 'integer',
            'amount' => 'required|integer',
        ];

        // Validate the request
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);  // Return errors as JSON
        }

        // Ensure bid amount is greater than or equal to min_bid_amount
        if ($bid_amount >= $min_bid_amount) {
            // Prepare the data for insertion
            $data = [
                'market_id' => $request->market_id,
                'gtype_id' => $request->gtype_id,
                'user_id' => $user_id,
                'bid_date' => now(),
                'session' => $request->input('timetype'),
                'open_digit' => 'N/A', // Default value
                'close_digit' => 'N/A', // Default value
                'jodi' => 'N/A', // Default value
                'open_panna' => 'N/A', // Default value
                'close_panna' => 'N/A', // Default value
                'amount' => $request->input('amount'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Get the digit and panna inputs
            $digit = $request->input('digit');
            $panna = $request->input('panna');
            $open_panna = $request->input('open_panna');
            $close_panna = $request->input('close_panna');
            $open_digit = $request->input('open_digit');

            $half_sangam_a_open_digit = $request->input('open_digit_sangam_half_a');
            $half_sangam_a_close_panna = $request->input('close_panna_sangam_half_a');

            $half_sangam_b_close_digit = $request->input('close_digit_half_sangam_b');
            $half_sangam_b_open_panna = $request->input('open_panna_half_sangam_b');

            $close_panna_full_sangam = $request->input('close_panna_full_sangam');
            $open_panna_full_sangam = $request->input('open_panna_full_sangam');

            // Check the session type and assign digit/panna accordingly
            if ($data['session'] === 'open') {
                $data['open_digit'] = $digit ?? 'N/A';
                $data['open_panna'] = $panna ?? 'N/A';
                $data['close_panna'] = $close_panna ?? 'N/A';
                $data['half_sangam_a'] = "$half_sangam_a_open_digit" . " - $half_sangam_a_close_panna" ?? 'N/A';
                // $data['full_sangam'] = $open_panna_full_sangam;

            } elseif ($data['session'] === 'close') {
                $data['open_digit'] = $open_digit ?? 'N/A';
                $data['close_digit'] = $digit ?? 'N/A';
                $data['close_panna'] = $panna ?? 'N/A';
                // $data['half_sangam_b'] =$half_sangam_b_open ?? 'N/A';
                $data['half_sangam_b'] = "$half_sangam_b_close_digit" . " - $half_sangam_b_open_panna" ?? 'N/A';
                // dd($data['half_sangam_a']);

                if (empty($panna)) {
                    $data['close_panna'] = $close_panna ?? 'N/A';
                }
            } else {
                $data['full_sangam'] = "$open_panna_full_sangam" . "- $close_panna_full_sangam";
                // dd($data);
                if (!empty($digit)) {
                    $data['jodi'] = $digit;
                }
                $data['open_panna'] = $open_panna ?? 'N/A';
                $data['close_panna'] = $close_panna ?? 'N/A';
            }

            // Check user's balance and insert the bid
            $user = DB::table('wallets')->where('user_id', $user_id)->first();
            if ($user && $user->balance >= $request->amount) {
                // Insert bid and deduct balance if conditions are met
                DB::table('bid_table')->insert($data);
                DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);

                return redirect()->back()->with('success', 'Bid placed  successfully.');
            } else {
                return redirect()->back()->withErrors(['balance' => 'Insufficient wallet balance.']);
            }
        } else {
            return redirect()->back()->with('error', "Bid amount must be at least $min_bid_amount.");
        }
    }


    // =============================== Starline Management ================================
    public function starline()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('starline_game_rates')->first();

        $starline_market = DB::table('starline_market')->where('market_status', 'active')->orderBy('open_time', 'asc')->get();

        return view('user.starline', compact('data', 'starline_market', 'userData'));
    }


    public function starline_bid_history()
    {
        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('starline_bid_table')
            ->join('starline_gtype', 'starline_bid_table.starline_gtype_id', '=', 'starline_gtype.id')
            ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
            ->select('starline_bid_table.*', 'starline_gtype.gtype', 'starline_market.starline_name')
            ->where('starline_bid_table.user_id', $id)
            ->whereDate('starline_bid_table.starline_bid_date', '=', date('Y-m-d')) // Filter by today's date
            ->get();

        return view('user.starline-bid-history', compact('data', 'userData'));
    }

    public function starline_winning_history()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('starline_winners')
            ->join('starline_market', 'starline_winners.starline_id', '=', 'starline_market.id')
            ->select('starline_market.starline_name', 'starline_winners.bid_type', 'starline_winners.bid_point', 'starline_winners.winning_amount', 'starline_winners.created_at')
            ->where('starline_winners.user_id', $id)
            ->where(function ($query) {
                $query->whereDate('starline_winners.created_at', today())
                    ->orWhereDate('starline_winners.created_at', today()->subDay());
            })
            ->orderByRaw("CASE WHEN DATE(starline_winners.created_at) = ? THEN 0 ELSE 1 END ASC", [today()])
            ->orderBy('starline_winners.created_at', 'asc')
            ->get();

        return view('user.starline_winning_history', compact('data', 'userData'));
    }


    public function starline_games($starline_id)
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        // dd($starline_id);
        $starlineid = $starline_id;

        $data = DB::table('starline_gtype')->get();
        // dd($data);
        return view('user.starline-games', compact('data', 'starlineid', 'userData'));
    }

    public function starline_addGame(Request $request, $starline_gtype_id, $starline_id)
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        // dd($market_id);
        // Now both $gtype_id and $market_id are directly passed
        return view('user.starline-addGame', compact('starline_gtype_id', 'starline_id', 'userData'));
    }

    public function starline_game_insert(Request $request)
    {
        // dd($request->all());
        // $id = $request->id;
        $user_id = Auth::id();

        $rules = [
            'gdate' => 'required',

            'amount' => 'required|integer',

        ];

        $validator = Validator::make(request()->all(), $rules);


        if (!$validator->fails()) {

            $data = [
                'starline_id' => $request->starline_id,
                'starline_gtype_id' => $request->starline_gtype_id,
                'user_id' => $user_id,
                'starline_bid_date' => now(),
                'digit' => $request->input('digit') ?? 'N/A',
                'panna' => $request->input('panna') ?? 'N/A',
                'amount' => $request->input('amount'),


            ];

            // dd($data);

            DB::table('starline_bid_table')->insert($data);

            $user = DB::table('wallets')->where('user_id', $user_id)->first();

            // Check if the user has sufficient balance
            if ($user->balance >= $request->amount) {
                // Deduct the amount from the user's wallet
                DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);
            } else {
                // If user doesn't have enough balance, you might want to return an error response
                return redirect()->back()->withErrors(['balance' => 'Insufficient wallet balance.']);
            }

            // Redirect back after successful insertion and wallet update
            return redirect()->back()->with('success', 'Bid placed and wallet updated successfully.');
        }

        // If validation fails, return back with errors
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function starline_resultChart($result_id)
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('starline_result')->where('starline_id', $result_id)->get();
        // dd($data);
        $star = DB::table('starline_market')->where('id', $result_id)->get();

        return view('user.starline-resultChart', compact('data', 'star', 'userData'));
    }





    // =============================== GaliDesawar Management ================================

    public function galidesawar()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('galidesawar_game_rates')->first();

        $desawar_market = DB::table('galidesawar_market')->get();

        $marketIds = $desawar_market->pluck('id');


        $results = DB::table('galidesawar_result')
            ->select('desawar_id', 'digit')
            ->whereIn('desawar_id', $marketIds)  // Now using `whereIn` for multiple markets
            ->where('result_date', date('Y-m-d'))
            ->get()
            ->groupBy('desawar_id');

        return view('user.galidesawar', compact('data', 'desawar_market', 'userData', 'results'));
    }

    public function galidesawar_games($desawar_id)
    {

        $id = Auth::id();
        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $desawarid = $desawar_id;

        $data = DB::table('galidesawar_gtype')->get();
        return view('user.galidesawar-games', compact('data', 'desawarid', 'userData'));
    }

    public function galidesawar_addGame(Request $request, $desawar_gtype_id, $desawar_id)
    {

        $id = Auth::id();
        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        return view('user.galidesawar-addGame', compact('desawar_gtype_id', 'desawar_id', 'userData'));
    }

    public function desawar_game_insert(Request $request)
    {
        // Get the authenticated user's ID
        $user_id = Auth::id();
        $min_bid_amount = DB::table('set_amount')->select('min_bid_amount')->first()->min_bid_amount; // Fetch min_bid_amount value directly
        $bid_amount = $request->input('amount');

        // Define validation rules
        $rules = [
            'gdate' => 'required',
            'digit' => 'integer',
            'amount' => 'required|integer',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {

            // Prepare the data for insertion
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

            // Check if the bid amount is greater than or equal to the minimum bid amount
            if ($bid_amount >= $min_bid_amount) {
                // Insert bid data
                DB::table('galidesawar_bid_table')->insert($data);

                // Retrieve the user's balance from the wallets table
                $user = DB::table('wallets')->where('user_id', $user_id)->first();

                // Check if the user has sufficient balance
                if ($user->balance >= $bid_amount) {
                    // Deduct the bid amount from the user's balance
                    DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

                    // Redirect back with success message
                    return redirect()->back()->with('success', 'Bid placed and wallet updated successfully.');
                } else {
                    // Insufficient balance error
                    return redirect()->back()->withErrors(['balance' => 'Insufficient wallet balance.']);
                }
            } else {
                // Minimum bid amount error
                return redirect()->back()->withErrors(['amount' => "Minimum bid amount should be at least $min_bid_amount."]);
            }
        }

        // Return validation errors if any
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function desawar_bid_history()
    {
        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('galidesawar_bid_table')
            ->join('galidesawar_gtype', 'galidesawar_bid_table.desawar_gtype_id', '=', 'galidesawar_gtype.id')
            ->join('galidesawar_market', 'galidesawar_bid_table.desawar_id', '=', 'galidesawar_market.id')
            ->select('galidesawar_bid_table.*', 'galidesawar_gtype.gtype', 'galidesawar_market.desawar_name')
            ->where('galidesawar_bid_table.user_id', $id)
            ->where('galidesawar_bid_table.desawar_bid_date', '>=', date('Y-m-d'))
            ->get();

        return view('user.galidesawar-bid-history', compact('data', 'userData'));
    }

    public function desawar_winning_history()
    {

        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('galidesawar_winners')
            ->join('galidesawar_market', 'galidesawar_winners.desawar_id', '=', 'galidesawar_market.id')
            ->select('galidesawar_market.desawar_name', 'galidesawar_winners.bid_type', 'galidesawar_winners.bid_point', 'galidesawar_winners.winning_amount', 'galidesawar_winners.created_at')
            ->where('galidesawar_winners.user_id', $id)
            ->where(function ($query) {
                $query->whereDate('galidesawar_winners.created_at', today())
                    ->orWhereDate('galidesawar_winners.created_at', today()->subDay());
            })
            ->orderByRaw("CASE WHEN DATE(galidesawar_winners.created_at) = ? THEN 0 ELSE 1 END ASC", [today()])
            ->orderBy('galidesawar_winners.created_at', 'asc')
            ->get();

        return view('user.galidesawar-winning-history', compact('data', 'userData'));
    }

    public function galidesawar_resultChart($result_id)
    {
        $id = Auth::id();

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id) // Specify the table name here
            ->first();


        // Check if user data was retrieved successfully
        if ($userData) {
            // Now you can access the properties safely
            $name = $userData->name;
            $number = $userData->number;
            $balance = $userData->balance; // This will work only if $userData is not null
        } else {
            // Handle the case where user data is not found
            Auth::logout(); // Log the user out
            return redirect()->route('login'); // Redirect to login page
        }

        $data = DB::table('galidesawar_result')->where('desawar_id', $result_id)->get();
        // dd($data);
        $star = DB::table('galidesawar_market')->where('id', $result_id)->get();

        return view('user.galidesawar-resultChart', compact('data', 'star', 'userData'));
    }
}
