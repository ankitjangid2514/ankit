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

class apiGaliDesawarController extends Controller
{
    //
    public function galidesawar()
    {
        try {
            // Fetch the first record from the galidesawar_game_rates table
            $data = DB::table('galidesawar_game_rates')
                ->select('left_digit_bid', 'left_digit_win', 'right_digit_bid', 'right_digit_win', 'jodi_digit_bid', 'jodi_digit_win')
                ->first();
    
            // Fetch all records from the galidesawar_market table
            $desawar_market = DB::table('galidesawar_market')
                ->select('id', 'desawar_name', 'open_time', 'close_time', 'market_status')
                ->get();
    
            // Check if data exists
            if (!$data || $desawar_market->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for Gali Desawar',
                ], 404);
            }
    
            $today = Carbon::today(); // Get today's date
            $today_result = DB::table('galidesawar_result')
                ->select('desawar_id', 'digit', 'result_date')
                ->whereDate('result_date', $today)
                ->get();
    
            $yesterday = Carbon::yesterday(); // Get yesterday's date
            $yesterday_result = DB::table('galidesawar_result')
                ->select('desawar_id', 'digit', 'result_date')
                ->whereDate('result_date', $yesterday)
                ->get();
    
            $currentTime = Carbon::now();
    
            // Map markets to include today and yesterday result digits, and isActive status
            $markets = $desawar_market->map(function ($market) use ($today_result, $yesterday_result, $currentTime) {
                // Get today's result digit for this market
                $todayResult = $today_result->firstWhere('desawar_id', $market->id);
                $yesterdayResult = $yesterday_result->firstWhere('desawar_id', $market->id);
    
                // Convert open_time and close_time to Carbon instances
                $openTime = Carbon::createFromFormat('H:i:s', $market->open_time);
                $closeTime = Carbon::createFromFormat('H:i:s.u', $market->close_time);
    
                // Determine if the market is active based on current time
                $market->isActive = $currentTime->between($openTime, $closeTime);
    
                // Add today's and yesterday's result digits to the market object
                $market->today_result_digit = $todayResult->digit ?? null;
                $market->yesterday_result_digit = $yesterdayResult->digit ?? null;
    
                return $market;
            });
    
            // Return the data in a JSON response
            return response()->json([
                'success' => true,
                'game_rates' => $data,
                'markets' => $markets,
                'today_result' => $today_result,
                'yesterday_result' => $yesterday_result,
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
    
    

    public function desawar_bid_history(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $inputDate = $request->input('date');

            // Convert date from DD/MM/YYYY to YYYY-MM-DD
            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $inputDate)->format('Y-m-d');

            // Fetch bid history for the authenticated user
            $data = DB::table('galidesawar_bid_table')
                ->join('galidesawar_gtype', 'galidesawar_bid_table.desawar_gtype_id', '=', 'galidesawar_gtype.id')
                ->join('galidesawar_market', 'galidesawar_bid_table.desawar_id', '=', 'galidesawar_market.id')
                ->where('galidesawar_bid_table.user_id', $user_id)
                ->where('galidesawar_bid_table.desawar_bid_date', $formattedDate)
                ->select(
                    'galidesawar_bid_table.amount',
                    'galidesawar_bid_table.digit',
                    'galidesawar_gtype.gtype',
                    'galidesawar_market.desawar_name',
                    'galidesawar_bid_table.desawar_bid_date'
                )
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


    public function desawarWinningHistory(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $inputDate = $request->input('date');

            // Convert date from DD/MM/YYYY to YYYY-MM-DD
            $formattedDate = Carbon::createFromFormat('d/m/Y', $inputDate)->format('Y-m-d');

            // Fetch winning history for the authenticated user
            $data = DB::table('galidesawar_winners')
                ->join('galidesawar_market', 'galidesawar_winners.desawar_id', '=', 'galidesawar_market.id')
                ->where('galidesawar_winners.user_id', $user_id)
                ->where('galidesawar_winners.created_at', '>=', Carbon::parse($formattedDate)->startOfDay())
                ->select(
                    'galidesawar_market.desawar_name',
                    'galidesawar_winners.bid_type',
                    'galidesawar_winners.bid_point',
                    'galidesawar_winners.winning_amount',
                    'galidesawar_winners.created_at'
                )
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
                'message' => 'An error occurred while fetching the winning history.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function galidesawar_resultChart(Request $request)
    {
        try {
            $id = $request->input('user_id');
            $result_id = $request->input('result_id');

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

            // Retrieve galidesawar result data based on the provided result_id
            $data = DB::table('galidesawar_result')->where('desawar_id', $result_id)->get();

            // Retrieve galidesawar market details based on the result_id
            $market = DB::table('galidesawar_market')->where('id', $result_id)->get();

            // Check if data and galidesawar market details are found
            if ($data->isEmpty() || $market->isEmpty()) {
                return response()->json(['message' => 'Galidesawar result or market data not found.'], 404);
            }

            // Return a success response with user data, galidesawar result data, and market details
            return response()->json([
                'status' => 'success',
                'userData' => $userData,
                'resultData' => $data,
                'marketData' => $market,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the galidesawar result chart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function galidesawar_games(Request $request)
    {
        try {
            $desawar_id = $request->input('desawar_id');
            // Fetch all game types from the galidesawar_gtype table
            $data = DB::table('galidesawar_gtype')->get();

            // Check if data exists
            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No game types found for Gali Desawar',
                ], 404);
            }

            // Return the data in a JSON response
            return response()->json([
                'success' => true,
                'desawar_id' => $desawar_id,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the Gali Desawar game types',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function galidesawar_addGame(Request $request,)
    {
        try {
            $id = $request->input('user_id');
            $desawar_gtype_id = $request->input('desawar_gtype_id');
            $desawar_id = $request->input('desawar_id');

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


    public function desawar_game_insert(Request $request)
    {
        try {
            // Get the authenticated user's ID
            $user_id = $request->input('user_id');

            // Fetch the minimum bid amount
            $min_bid_amount = DB::table('set_amount')->value('min_bid_amount'); // Fetch min_bid_amount directly

            // Get the bid amount from the request
            $bid_amount = $request->input('amount');

            // Define validation rules
            $rules = [
                'gdate' => 'required',
                'digit' => 'required|integer',
                'amount' => 'required|integer',
                'desawar_id' => 'required|integer',
                'desawar_gtype_id' => 'required|integer',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Return validation errors
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422); // Unprocessable Entity
            }

            // Check if the bid amount meets the minimum requirement
            if ($bid_amount < $min_bid_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Minimum bid amount should be at least $min_bid_amount.",
                ], 400); // Bad Request
            }

            // Retrieve the user's wallet balance
            $userWallet = DB::table('wallets')->where('user_id', $user_id)->first();

            if (!$userWallet || $userWallet->balance < $bid_amount) {
                // Insufficient balance error
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance.',
                ], 400); // Bad Request
            }

            // Prepare the data for insertion
            $data = [
                'desawar_id' => $request->input('desawar_id'),
                'desawar_gtype_id' => $request->input('desawar_gtype_id'),
                'user_id' => $user_id,
                'desawar_bid_date' => now(),
                'digit' => $request->input('digit'),
                'amount' => $bid_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert bid data into the database
            DB::table('galidesawar_bid_table')->insert($data);

            // Deduct the bid amount from the user's wallet balance
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $bid_amount);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Bid placed and wallet updated successfully.',
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while placing the bid.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }










public function starline(Request $request)
{
    try {
        $data = DB::table('starline_game_rates')->first();

        $starline_market = DB::table('starline_market')
            ->where('market_status', 'active')
            ->orderBy('open_time', 'asc')
            ->get()
            ->map(function ($market) {
                $market->market_name = $market->starline_name; // Rename field
                unset($market->starline_name); // Remove old field
                return $market;
            });

        return response()->json([
            'success' => true,
            'game_rates' => $data,
            'markets' => $starline_market,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the Starline data',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function starline_addGame(Request $request)
{
    try {

        dd($request->all());

        $id = $request->input('user_id');
        $starline_gtype_id = $request->input('starline_gtype_id');
        $starline_id = $request->input('starline_id');

        $userData = DB::table('users')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->select('users.name', 'users.number', 'wallets.balance')
            ->where('users.id', $id)
            ->first();

        // Check if user data was retrieved successfully
        if (!$userData) {
            Auth::logout(); // Log the user out
            return response()->json([
                'success' => false,
                'message' => 'User not found, please login again',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'game_type_id' => $starline_gtype_id,
            'market_id' => $starline_id,
            'user' => $userData,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while preparing the Starline game',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function starline_game_insert(Request $request)
{
    try {
        $user_id = $request->input('user_id');

        $rules = [
            'gdate' => 'required|date_format:d/m/Y',
            'amount' => 'required|integer',
            'starline_id' => 'required|integer',
            'gtype_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $formattedDate = Carbon::createFromFormat('d/m/Y', $request->input('gdate'))->format('Y-m-d');

        $data = [
            'user_id' => $user_id,
            'starline_id' => $request->input('starline_id'),
            'starline_gtype_id' => $request->input('gtype_id'),
            'starline_bid_date' => $formattedDate,
            'digit' => $request->input('digit') ?? 'N/A',
            'panna' => $request->input('panna') ?? 'N/A',
            'amount' => $request->input('amount'),
        ];

        DB::table('starline_bid_table')->insert($data);

        $user = DB::table('wallets')->where('user_id', $user_id)->first();

        if ($user->balance >= $request->amount) {
            DB::table('wallets')->where('user_id', $user_id)->decrement('balance', $request->amount);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bid placed and wallet updated successfully.',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while placing the bid.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function starline_resultChart(Request $request)
{
    try {
        // $id = Auth::id();

        // $userData = DB::table('users')
        //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
        //     ->select('users.name', 'users.number', 'wallets.balance')
        //     ->where('users.id', $id)
        //     ->first();

        // if (!$userData) {
        //     Auth::logout();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'User not found, please login again',
        //     ], 401);
        // }

        $result_id =$request->input('result_id');
        $data = DB::table('starline_result')->where('starline_id', $result_id)->get();
        $star = DB::table('starline_market')->where('id', $result_id)->get();

        return response()->json([
            'success' => true,
            'results' => $data,
            'market' => $star,
            // 'user' => $userData,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the result chart.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function starline_bid_history(Request $request)
{
    try {
        $id = $request->input('user_id');
        $date = $request->input('date');

        // Convert date from d/m/Y to Y-m-d
        $formattedDate = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        $data = DB::table('starline_bid_table')
            ->join('starline_gtype', 'starline_bid_table.starline_gtype_id', '=', 'starline_gtype.id')
            ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
            ->where('starline_bid_table.user_id', $id)
            ->whereDate('starline_bid_table.starline_bid_date', '=', $formattedDate)
            ->select(
                'starline_bid_table.amount', 
                'starline_bid_table.digit', 
                'starline_bid_table.panna', 
                'starline_gtype.gtype', 
                'starline_market.starline_name', 
                'starline_bid_table.starline_bid_date'
            )
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No bid history found for the selected date.',
                'date' => $formattedDate,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'bid_history' => $data,
            'date' => $formattedDate,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the bid history.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function starline_winning_history(Request $request)
{
    try {
        $id = $request->input('user_id');
        $date = $request->input('date');

     
        // Convert date format from d/m/Y to Y-m-d
        if ($date) {
            $formattedDate = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } else {
            $formattedDate = today()->format('Y-m-d'); // Default to today
        }

        // Fetch winning history for selected date
        $data = DB::table('starline_winners')
            ->join('starline_market', 'starline_winners.starline_id', '=', 'starline_market.id')
            ->select(
                'starline_market.starline_name', 
                'starline_winners.bid_type', 
                'starline_winners.bid_point', 
                'starline_winners.winning_amount', 
                'starline_winners.created_at'
            )
            ->where('starline_winners.user_id', $id)
            ->whereDate('starline_winners.created_at', '=', $formattedDate)
            ->orderBy('starline_winners.created_at', 'asc')
            ->get();

        // Return appropriate response
        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No winning history found for the selected date.',
                'date' => $formattedDate,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'winning_history' => $data,
            'date' => $formattedDate,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the winning history.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
