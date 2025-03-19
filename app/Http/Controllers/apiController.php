<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class apiController extends Controller
{



    // new ajay and ankit


    public function admin_login_submit(Request $request)
    {
        try {
            // Get the credentials from the request
            $credentials = $request->only('name', 'password');

            // Attempt to authenticate the user
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Check if the authenticated user is an admin
                if ($user->type === 'admin') {
                    // Return success response with user details
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Login successful.',
                        'user' => $user,
                        'redirect_url' => url('admin_dashboard'),
                    ], 200); // OK
                }

                // Logout the user if not admin
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. You must be an admin.',
                ], 403); // Forbidden
            }

            // Invalid credentials response
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401); // Unauthorized

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function adminDashboard()
    {
        try {
            // Count the total number of regular users
            $userCount = DB::table('users')->where('type', 'user')->count();

            // Count the total number of markets and fetch market data
            $marketCount = DB::table('market')->count();
            $marketData = DB::table('market')->get();

            // Count the number of starline and desawar markets
            $starlineCount = DB::table('starline_market')->count();
            $desawarCount = DB::table('galidesawar_market')->count();

            // Calculate the total number of markets
            $totalMarketCount = $marketCount + $starlineCount + $desawarCount;

            // Sum the total amount from the bid table
            $totalBidAmount = DB::table('bid_table')->sum('amount');

            // Count the number of approved and unapproved users
            $approvedUsers = DB::table('users')->where('status', 'approved')->count();
            $unapprovedUsers = DB::table('users')->where('status', 'unapproved')->count();

            // Return success response with all the collected data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_count' => $userCount,
                    'total_market_count' => $totalMarketCount,
                    'market_data' => $marketData,
                    'total_bid_amount' => $totalBidAmount,
                    'approved_users' => $approvedUsers,
                    'unapproved_users' => $unapprovedUsers,
                ],
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the dashboard data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function declare_result()
    {
        try {
            // Fetch market data
            $data = DB::table('market')->get();

            // Return success response with market data
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the market data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function declare_result_data(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'open_number' => 'string|nullable', // Nullable for open results
                'close_number' => 'string|nullable', // Nullable for close results
                'open_result' => 'integer|nullable', // Nullable for open results
                'close_result' => 'integer|nullable', // Nullable for close results
                'game_id' => 'required|integer|not_in:0',
                'result_dec_date' => 'required|date',
                'market_status' => 'required|integer'
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422); // Unprocessable Entity
            }

            $date = now();
            $marketStatus = $request->input('market_status');
            $gameId = $request->input('game_id');
            $resultDecDate = $request->input('result_dec_date');

            // Check if a result record already exists for the given game_id and result_dec_date
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
            } elseif ($marketStatus == '2') { // Close result
                $data['close_panna'] = $request->input('close_number');
                $data['close_digit'] = $request->input('close_result');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid market status.',
                ], 400); // Bad Request
            }

            // Insert new or update existing result
            if ($existingResult) {
                DB::table('market_results')
                    ->where('id', $existingResult->id)
                    ->update($data);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Game result has been updated successfully.',
                ], 200); // OK
            } else {
                DB::table('market_results')->insert($data);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Game result has been declared successfully.',
                ], 201); // Created
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while declaring the result.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function winning_prediction()
    {
        try {
            // Fetch market data
            $data = DB::table('market')->select('id', 'market_name')->get();

            // Sum total bid amount
            $totalbid = DB::table('bid_table')->sum('amount');

            // Sum total winning amount
            $totalwin = DB::table('winners')->sum('winning_amount');

            // Return success response with data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'market_data' => $data,
                    'total_bid' => $totalbid,
                    'total_win' => $totalwin
                ]
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the winning prediction data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function winning_prediction_list(Request $request)
    {
        try {
            // Build the base query with a join between winners and users tables
            $query = DB::table('winners')
                ->join('users', 'winners.user_id', '=', 'users.id')
                ->select('users.name', 'winners.*');

            // Handle search functionality
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%$search%")
                        ->orWhere('users.email', 'like', "%$search%");
                    // Add other columns to search if needed
                });
            }

            // Handle sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];
                $query->orderBy($orderColumnName, $orderDir);
            }

            // Count total records (before filtering)
            $totalRecords = DB::table('winners')
                ->join('users', 'winners.user_id', '=', 'users.id')
                ->count();

            // Handle pagination
            $start = $request->input('start', 0); // Default to 0 if not provided
            $length = $request->input('length', 10); // Default to 10 if not provided
            $data = $query->offset($start)->limit($length)->get();

            // Count filtered records (after applying search and filters)
            $filteredRecords = $query->count();

            // Return the JSON response with the data
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the winning prediction list.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function user_bid_history()
    {
        try {
            // Fetch market data
            $data = DB::table('market')->select('id', 'market_name')->get();

            // Fetch game type data
            $gtype = DB::table('gtype')->select('id', 'gtype')->get();

            // Return success response with the data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'market_data' => $data,
                    'game_types' => $gtype
                ]
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching user bid history data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function bid_history(Request $request)
    {
        try {
            // Build the base query with a join between bid_table and related tables
            $query = DB::table('bid_table')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->join('users', 'bid_table.user_id', '=', 'users.id')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->select('bid_table.*', 'market.market_name', 'users.name', 'gtype.gtype');

            // Apply search filter (if any)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('market.market_name', 'like', "%$search%")
                        ->orWhere('users.name', 'like', "%$search%");
                });
            }

            // Apply date filter (if bid_date is provided)
            // Uncomment and implement if needed
            // if ($request->has('bid_date') && !empty($request->bid_date)) {
            //     $query->whereDate('bid_table.bid_date', $request->bid_date);
            // }

            // // Apply game name filter (if provided)
            // if ($request->has('game_name') && !empty($request->game_name)) {
            //     $query->where('bid_table.market_id', $request->game_name);
            // }

            // // Apply game type filter (if provided)
            // if ($request->has('game_type') && !empty($request->game_type) && $request->game_type !== 'all') {
            //     $query->where('bid_table.gtype_id', $request->game_type);
            // }

            // Handle sorting (if any)
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];
                $query->orderBy($orderColumnName, $orderDir);
            }

            // Count total records (before filtering)
            $totalRecords = DB::table('bid_table')->count();

            // Handle pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->offset($start)->limit($length)->get();

            // Count filtered records (after search and filters)
            $filteredRecords = $query->count();

            // Return the JSON response with the data
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching bid history data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function winning_report()
    {
        try {
            // Fetch data from the market table
            $data = DB::table('market')->select('id', 'market_name')->get();

            // Return the data as a JSON response
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the winning report data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function winning_report_list(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('winners')
                ->join('users', 'winners.user_id', '=', 'users.id')
                ->join('market', 'winners.market_id', '=', 'market.id')
                ->select('users.name', 'market.market_name', 'winners.*');

            // Handle searching (for multiple columns if needed)
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

            // Count total records (before pagination)
            $totalRecords = $query->count();

            // Handle pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->offset($start)->limit($length)->get();

            // Count filtered records (after applying search and sorting)
            $filteredRecords = $query->count();

            // Return JSON response
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the winning report list.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function bid_winning_report()
    {
        try {
            // Fetch the data from the market table
            $data = DB::table('market')->select('id', 'market_name')->get();

            // Return a successful JSON response
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the bid winning report.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function view_user($user_id)
    {
        try {
            $id = $user_id;

            // Fetch withdrawal data for the user
            $withdrawal = DB::table('withdrawal')->where('user_id', $id)->get();

            // Fetch user data along with wallet balance
            $user_data = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.*', 'wallets.balance')
                ->where('users.id', $id)
                ->first();

            // Fetch bid history for the user
            $query = DB::table('bid_table')
                ->join('market', 'bid_table.market_id', '=', 'market.id')
                ->join('users', 'bid_table.user_id', '=', 'users.id')
                ->join('gtype', 'bid_table.gtype_id', '=', 'gtype.id')
                ->select('bid_table.*', 'market.market_name', 'users.name', 'gtype.gtype')
                ->where('users.id', $id)
                ->get();

            // Fetch winning history for the user
            $winning = DB::table('winners')
                ->join('market', 'winners.market_id', '=', 'market.id')
                ->join('bid_table', 'winners.bid_id', '=', 'bid_table.id')
                ->select('winners.winning_amount', 'market.market_name', 'bid_table.id', 'bid_table.bid_date')
                ->where('winners.user_id', $id)
                ->get();

            // Return a successful JSON response with the user data and associated records
            return response()->json([
                'status' => 'success',
                'user_data' => $user_data,
                'withdrawal' => $withdrawal,
                'bid_history' => $query,
                'winning_history' => $winning,
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching user data.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function getUserList(Request $request)
    {
        try {
            $query = DB::table('users')
                ->join('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.*', 'wallets.balance')
                ->where('users.status', 'approved');

            // Apply search filter (if any)
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

            // Count total records (before filtering)
            $totalRecords = DB::table('users')->where('status', 'approved')->count(); // Total records before pagination

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
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the user list.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function withdrawal_request_list(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('users')
                ->join('withdrawal', 'users.id', '=', 'withdrawal.user_id')
                ->select('users.name', 'withdrawal.*');

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

            // Count total records (before filtering)
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
            ], 200); // OK

        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the withdrawal request list.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    public function withdrawal_request_approve(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id' => 'required|integer|exists:withdrawal,id',
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to approve the withdrawal request
            $updated = DB::table('withdrawal')->where('id', $id)->update(['status' => 'approved']);

            if ($updated) {
                // Fetch the user_id and the withdrawal amount from the withdrawal record
                $withdrawal = DB::table('withdrawal')->where('id', $id)->first(['user_id', 'amount']);
                if (!$withdrawal) {
                    return response()->json(['success' => false, 'message' => 'Withdrawal request not found.'], 404); // Not Found
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

                    return response()->json(['success' => true, 'message' => 'Record approved successfully.'], 200); // OK
                } else {
                    return response()->json(['success' => false, 'message' => 'Insufficient balance.'], 400); // Bad Request
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404); // Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while approving the record.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function withdrawal_request_reject(Request $request)
    {
        // Validate the request to ensure an ID is provided
        $request->validate([
            'id' => 'required|integer|exists:withdrawal,id', // Ensure ID exists in the withdrawal table
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to reject the withdrawal request
            $updated = DB::table('withdrawal')->where('id', $id)->update(['status' => 'rejected']);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Record rejected successfully.'], 200); // 200 OK
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404); // 404 Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rejecting the record.',
                'error' => $e->getMessage() // Optionally log this error for debugging
            ], 500); // 500 Internal Server Error
        }
    }

    public function delete_withdrawal_request(Request $request)
    {
        // Validate the request to ensure an ID is provided
        $request->validate([
            'id' => 'required|integer|exists:withdrawal,id', // Ensure ID exists in the withdrawal table
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the deletion
            $deleted = DB::table('withdrawal')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.'], 200); // 200 OK
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.'], 404); // 404 Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the record.',
                'error' => $e->getMessage() // Optionally log this error for debugging
            ], 500); // 500 Internal Server Error
        }
    }

    public function fund_request_management_list(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('deposite_table')
                ->join('users', 'deposite_table.user_id', '=', 'users.id')
                ->select('users.name', 'deposite_table.*');

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
            ], 200); // OK
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the fund request management list.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function deposite_request_approve(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to approve the deposit request
            $updated = DB::table('deposite_table')->where('id', $id)->update(['deposite_status' => 'approved']);

            if ($updated) {
                // Fetch the user_id and the deposit amount from the deposit record
                $deposite = DB::table('deposite_table')->where('id', $id)->first(['user_id', 'deposit_amount']);
                if (!$deposite) {
                    return response()->json(['success' => false, 'message' => 'Deposit request not found.'], 404); // Not Found
                }

                // Fetch the user's current wallet balance
                $user = DB::table('wallets')->where('user_id', $deposite->user_id)->value('balance');

                // Calculate the new balance after deposit
                $new_balance = $user + $deposite->deposit_amount;

                // Update the user's wallet balance in a transaction
                DB::transaction(function () use ($deposite, $new_balance) {
                    DB::table('wallets')->where('user_id', $deposite->user_id)->update(['balance' => $new_balance]);
                });

                return response()->json(['success' => true, 'message' => 'Record approved successfully.'], 200); // OK
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404); // Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while approving the record.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function deposite_request_reject(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update to reject the deposit request
            $updated = DB::table('deposite_table')->where('id', $id)->update(['deposite_status' => 'rejected']);

            if ($updated) {
                // Fetch the user_id from the deposit record
                $deposite = DB::table('deposite_table')->where('id', $id)->first(['user_id', 'deposit_amount']);
                if (!$deposite) {
                    return response()->json(['success' => false, 'message' => 'Deposit request not found.'], 404); // Not Found
                }

                return response()->json(['success' => true, 'message' => 'Deposit request rejected successfully.'], 200); // OK
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404); // Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while rejecting the record.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function delete_deposite_request(Request $request)
    {
        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the deletion
            $deleted = DB::table('deposite_table')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.'], 200); // OK
            } else {
                return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.'], 404); // Not Found
            }
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function bid_revert()
    {
        try {
            // Retrieve data from the 'market' table
            $data = DB::table('market')->get();

            // Return a JSON response with the retrieved data
            return response()->json(['success' => true, 'data' => $data], 200); // OK
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while retrieving the data.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function gameNameView()
    {
        try {
            // Retrieve all data from the market table
            $data = DB::table('market')->get();

            // Return a successful JSON response with the data
            return response()->json(['success' => true, 'data' => $data], 200); // 200 OK
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving game names.',
                'error' => $e->getMessage() // Optionally log this error for debugging
            ], 500); // 500 Internal Server Error
        }
    }

    public function gameName_insert(Request $request)
    {
        // Define validation rules
        $rules = [
            'game_name' => 'required|string|max:255|unique:market,market_name',
            'game_name_hindi' => 'required|string|max:255|unique:market,market_name_hindi',
            'open_time' => 'required|date_format:H:i',   // Open time must be in HH:MM format
            'close_time' => 'required|date_format:H:i|after:open_time' // Close time must be after open time
        ];

        try {
            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // Return validation errors
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors()
                ], 422); // 422 Unprocessable Entity
            }

            // Prepare data for insertion
            $date = now(); // Use Laravel's helper to get the current timestamp
            $data = [
                'market_name' => $request->input('game_name'),
                'market_name_hindi' => $request->input('game_name_hindi'),
                'open_time' => $request->input('open_time'),
                'close_time' => $request->input('close_time'),
                'created_at' => $date,
            ];

            // Insert data into the market table
            DB::table('market')->insert($data);

            // Return success response
            return response()->json(['success' => true, 'message' => 'Game name inserted successfully.'], 201); // 201 Created
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while inserting the game name.',
                'error' => $e->getMessage() // Optionally log this error for debugging
            ], 500); // 500 Internal Server Error
        }
    }

    public function gameName_search(Request $request)
    {
        try {
            $query = DB::table('market');

            // Handle searching (for multiple columns if needed)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%"); // Add other columns to search if needed
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
            $start = $request->start ?? 0; // Default to 0 if not provided
            $length = $request->length ?? 10; // Default to 10 if not provided
            $data = $query->offset($start)->limit($length)->get();

            // Return JSON response
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $query->count(), // Count after filtering
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while searching for game names.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function game_rates_show()
    {
        try {
            // Retrieve the first record from the game_rates table
            $data = DB::table('game_rates')->first();

            // Check if data is found
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No game rates found.',
                    'redirect' => route('admin.game_rates_show'), // Replace with the appropriate route
                ], 404); // 404 Not Found
            }

            // Return a successful JSON response with the data
            return response()->json([
                'success' => true,
                'data' => $data,
                'redirect' => route('admin.game-rates'), // Optional redirect URL
            ], 200); // 200 OK
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving game rates.',
                'error' => $e->getMessage(), // Optionally log this error for debugging
                'redirect' => route('admin.game_rates_show'), // Optional redirect URL
            ], 500); // 500 Internal Server Error
        }
    }

    public function game_rates_insert(Request $request)
    {
        // Define validation rules
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

        try {
            // Validate request data
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422); // Unprocessable Entity
            }

            $date = now(); // Current date and time
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

            // Return success response
            return response()->json(['success' => true, 'message' => 'Game rates have been updated successfully.']);
        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred while updating game rates.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    public function contactSettings(Request $request)
    {
        try {
            $data = DB::table('admin')->first();

            // Check if data exists
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No contact settings found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching contact settings.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function insertContact(Request $request)
    {
        // Define validation rules
        $rules = [
            'mobile_1' => 'required',
            'mobile_2' => 'required',
            'whats_mobile' => 'required',
            'qr_code' => 'nullable|image' // Validate QR code image if present
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Prepare data for update
            $data = [
                'mobile_number' => $request->mobile_1,
                'telegram_link' => $request->mobile_2,
                'whatsapp_number' => $request->whats_mobile,
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

            return response()->json([
                'success' => true,
                'message' => 'Contact settings updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating contact settings.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sliderImageInsert(Request $request)
    {
        // Define validation rules
        $rules = [
            'image' => 'required|image', // Image validation
            'display_order' => 'required|integer', // Ensure display_order is an integer
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
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
                DB::table('slider_images')->insert($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Slider image added successfully.',
                ], 201); // 201 Created
            }

            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded.',
            ], 400); // 400 Bad Request

        } catch (\Exception $e) {
            // Handle any database errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to add slider image. Please try again.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function sliderData(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('slider_images');

            // Handle searching (for multiple columns if needed)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('slider_images.name', 'like', "%$search%"); // Assuming 'name' is a column in 'slider_images'
                    // ->orWhere('slider_images.some_other_column', 'like', "%$search%"); // Add more columns to search if needed
                });
            }

            // Handle sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];
                $query->orderBy($orderColumnName, $orderDir);
            }

            // Count total records before filtering (unfiltered)
            $totalRecords = DB::table('slider_images')->count();

            // Count records after filtering (filtered)
            $filteredRecords = $query->count();

            // Handle pagination
            $start = $request->input('start', 0); // Default to 0 if not provided
            $length = $request->input('length', 10); // Default to 10 if not provided
            $data = $query->offset($start)->limit($length)->get();

            // Return JSON response
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ], 200); // 200 OK

        } catch (\Exception $e) {
            // Handle any exceptions and return a JSON response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching slider data.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function slider_image_approve(Request $request)
    {
        // Validate request
        $request->validate([
            'id' => 'required|integer|exists:slider_images,id',
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('slider_images')->where('id', $id)->update(['status' => 'active']);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record approved successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or could not be updated.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error approving slider image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function slider_image_reject(Request $request)
    {
        // Validate request
        $request->validate([
            'id' => 'required|integer|exists:slider_images,id',
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the update
            $updated = DB::table('slider_images')->where('id', $id)->update(['status' => 'inactive']);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record rejected successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or could not be updated.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error rejecting slider image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rejecting the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete_slider_image(Request $request)
    {
        // Validate request
        $request->validate([
            'id' => 'required|integer|exists:slider_images,id',
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the deletion
            $deleted = DB::table('slider_images')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or could not be deleted.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error deleting slider image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function notice_management_insert(Request $request)
    {
        // Define validation rules
        $rules = [
            'notice_title' => 'required|string|max:255',
            'notice_date' => 'required|date',
            'description' => 'required|string',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        try {
            // Prepare data for insertion
            $data = [
                'notice_title' => $request->input('notice_title'),
                'notice_date' => $request->input('notice_date'),
                'description' => $request->input('description'),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert data into the database
            DB::table('notice_management')->insert($data);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Notice added successfully!'
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error inserting notice: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the notice.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function notice_management_data(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('notice_management');

            // Handle searching (for multiple columns if needed)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('notice_title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
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
            $totalRecords = DB::table('notice_management')->count(); // Total records before pagination

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
            ], 200);
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error fetching notice data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the data.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function delete_notice(Request $request)
    {
        // Validate request
        $request->validate([
            'id' => 'required|integer|exists:notice_management,id',
        ]);

        // Retrieve the ID from the request
        $id = $request->id;

        try {
            // Perform the deletion
            $deleted = DB::table('notice_management')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found or could not be deleted.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error deleting notice: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function send_notification()
    {
        try {
            // Fetch users with type 'user'
            $data = DB::table('users')->where('type', 'user')->get();

            // Return data in JSON response
            return response()->json([
                'success' => true,
                'message' => 'Users fetched successfully.',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error fetching users: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching users.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function starline_game_name()
    {
        try {
            // Fetch data from starline_market table
            $data = DB::table('starline_market')->get();

            // Return data in JSON response
            return response()->json([
                'success' => true,
                'message' => 'Starline game names fetched successfully.',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error fetching starline game names: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching starline game names.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function starline_game_rates()
    {
        try {
            // Fetch the first record from starline_game_rates table
            $data = DB::table('starline_game_rates')->first();

            // Return data in JSON response
            return response()->json([
                'success' => true,
                'message' => 'Starline game rates fetched successfully.',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Error fetching starline game rates: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching starline game rates.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function starline_game_rates_insert(Request $request)
    {
        try {
            // Define validation rules
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

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            // If validation fails, return a JSON response with errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422); // 422 Unprocessable Entity
            }

            // Prepare the data for insertion or update
            $data = [
                'single_digit_bid' => $request->input('single_digit_bid'),
                'single_digit_win' => $request->input('single_digit_win'),
                'single_match_bid' => $request->input('single_match_bid'),
                'single_match_win' => $request->input('single_match_win'),
                'double_match_bid' => $request->input('double_match_bid'),
                'double_match_win' => $request->input('double_match_win'),
                'triple_match_bid' => $request->input('triple_match_bid'),
                'triple_match_win' => $request->input('triple_match_win'),
                'updated_at' => now(), // Use Laravel's `now()` helper
            ];

            // Check if an existing record exists
            $existingRates = DB::table('starline_game_rates')->first();

            if ($existingRates) {
                // Update the existing record
                DB::table('starline_game_rates')->where('id', $existingRates->id)->update($data);
            } else {
                // Insert a new record if no existing record is found
                $data['created_at'] = now();
                DB::table('starline_game_rates')->insert($data);
            }

            // Return success response after operation
            return response()->json([
                'status' => 'success',
                'message' => 'Game rates have been updated successfully.',
            ], 200); // 200 OK

        } catch (\Exception $e) {
            // Catch any exceptions and return a JSON error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the game rates.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function starline_game_insert(Request $request)
    {
        try {
            $rules = [
                'game_name' => 'required|string|max:255|unique:starline_market,starline_name',
                'game_name_hindi' => 'required|string|max:255|unique:starline_market,starline_name_hindi',
                'open_time' => 'required',   // Open time must be in HH:MM format
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $date = date('Y-m-d H:i:s');
            $data = [
                'starline_name' => $request->input('game_name'),
                'starline_name_hindi' => $request->input('game_name_hindi'),
                'open_time' => $request->input('open_time'),
                'created_at' => $date,
            ];

            $result = DB::table('starline_market')->insert($data);

            return response()->json(['message' => 'Game inserted successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
        }
    }


    public function starline_user_bid_history()
    {
        try {
            // Fetch data required for the 'starline-user-bid-history' view
            $bidHistory = DB::table('starline_bids')->get();

            // Return the data as JSON
            return response()->json(['bid_history' => $bidHistory], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
        }
    }

    public function starline_user_bid_history_list(Request $request)
    {
        try {
            // Build the base query
            $query = DB::table('starline_bid_table')
                ->join('users', 'starline_bid_table.user_id', '=', 'users.id')
                ->join('starline_market', 'starline_bid_table.starline_id', '=', 'starline_market.id')
                ->join('starline_gtype', 'starline_bid_table.starline_gtype_id', '=', 'starline_gtype.id')
                ->select('users.name', 'starline_market.starline_name', 'starline_gtype.gtype', 'starline_bid_table.*');

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

            // Count total records before pagination
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
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'details' => $e->getMessage()], 500);
        }
    }

    public function starline_declare_result()
    {
        try {
            // Add any logic you want to execute before returning the response
            $data = [
                // Example data to return
                'message' => 'Result declared successfully',
                // Add more data as necessary
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            return response()->json([
                'error' => 'An error occurred while declaring the result.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function starline_result(Request $request)
    {
        // Define validation rules
        $rules = [
            'open_number' => 'required|string',
            'open_result' => 'required|integer',
            'game_id' => 'required|integer|not_in:0', // Validate game_id is not 0
            'result_dec_date' => 'required|date', // Ensure result_dec_date is a valid date
        ];

        try {
            // Validate request data
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed.',
                    'messages' => $validator->errors()
                ], 422); // Unprocessable Entity
            }

            // Prepare data for insertion
            $date = now(); // Current date and time
            $data = [
                'starline_id' => $request->input('game_id'),
                'panna' => $request->input('open_number'),
                'digit' => $request->input('open_result'),
                'result_date' => $request->input('result_dec_date'),
                'created_at' => $date,
            ];

            // Insert data into database
            DB::table('starline_result')->insert($data);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Game result has been declared successfully.'
            ], 201); // Created status
        } catch (\Exception $e) {
            // Log the exception if needed
            return response()->json([
                'error' => 'An error occurred while declaring the result.',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    public function starline_result_history_list(Request $request)
{
    try {
        // Build the base query
        $query = DB::table('starline_result')
            ->join('starline_market', 'starline_result.starline_id', '=', 'starline_market.id')
            ->select('starline_market.starline_name', 'starline_result.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Adjust the columns if needed
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
        ], 200); // Success response with status code 200

    } catch (\Exception $e) {
        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch starline result history list',
            'error' => $e->getMessage()
        ], 500); // Error response with status code 500
    }
}

public function starline_winning_report()
{
    try {
        // Fetch data from the database
        $data = DB::table('starline_market')->select('id', 'starline_name')->get();

        // Return the data as a JSON response
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200); // Success response with status code 200

    } catch (\Exception $e) {
        // Return error response in case of exception
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch starline winning report',
            'error' => $e->getMessage()
        ], 500); // Error response with status code 500
    }
}

public function starline_winning_report_list(Request $request)
{
    try {
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
        ], 200); // Success response with status code 200

    } catch (\Exception $e) {
        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch starline winning report list',
            'error' => $e->getMessage()
        ], 500); // Error response with status code 500
    }
}

public function starline_winning_prediction_list(Request $request)
{
    try {
        // Build the base query
        $query = DB::table('starline_winners')
            ->join('users', 'starline_winners.user_id', '=', 'users.id')
            ->select('users.name', 'starline_winners.*');

        // Handle searching (for multiple columns if needed)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%"); // Adjust the columns if needed
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
        ], 200); // Success response with status code 200

    } catch (\Exception $e) {
        // Return error response in case of exception
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch starline winning prediction list',
            'error' => $e->getMessage()
        ], 500); // Error response with status code 500
    }
}

public function desawar_game_name()
{
    try {
        // Fetch data from the 'galidesawar_market' table
        $data = DB::table('galidesawar_market')->get();

        // Return a successful JSON response
        return response()->json([
            'success' => true,
            'message' => 'Desawar game names fetched successfully',
            'data' => $data
        ], 200);
    } catch (\Exception $e) {
        // Handle any potential errors
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch Desawar game names',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_game_insert(Request $request)
{
    try {
        // Validation rules
        $rules = [
            'game_name' => 'required|unique:galidesawar_market,desawar_name',
            'game_name_hindi' => 'required|unique:galidesawar_market,desawar_name_hindi',
            'close_time' => 'required', // Open time must be in HH:MM format
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare data for insertion
        $date = date('Y-m-d H:i:s');
        $data = [
            'desawar_name' => $request->input('game_name'),
            'desawar_name_hindi' => $request->input('game_name_hindi'),
            'close_time' => $request->input('close_time'),
            'created_at' => $date,
        ];

        // Insert data into the database
        DB::table('galidesawar_market')->insert($data);

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Desawar game inserted successfully'
        ], 201);
        
    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while inserting Desawar game',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_game_rates()
{
    try {
        // Fetch the first record from the galidesawar_game_rates table
        $data = DB::table('galidesawar_game_rates')->first();

        // Check if data is found
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Desawar game rates retrieved successfully',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No game rates found'
            ], 404);
        }
        
    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching Desawar game rates',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_game_rates_insert(Request $request)
{
    try {
        // Validation rules
        $rules = [
            'left_digit_bid' => 'required|numeric',
            'left_digit_win' => 'required|numeric',
            'right_digit_bid' => 'required|numeric',
            'right_digit_win' => 'required|numeric',
            'jodi_digit_bid' => 'required|numeric',
            'jodi_digit_win' => 'required|numeric',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare data for insertion or update
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

        // Check if there is an existing record
        $existingRates = DB::table('galidesawar_game_rates')->first();

        if ($existingRates) {
            // Update the existing record
            DB::table('galidesawar_game_rates')->where('id', $existingRates->id)->update($data);
            $message = 'Game rates have been updated successfully.';
        } else {
            // Insert a new record if no existing one is found
            $data['created_at'] = now();
            DB::table('galidesawar_game_rates')->insert($data);
            $message = 'Game rates have been inserted successfully.';
        }

        // Return success response
        return response()->json([
            'status' => true,
            'message' => $message
        ], 200);

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while processing game rates',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_user_bid_history_list(Request $request)
{
    try {
        // Build the base query
        $query = DB::table('galidesawar_bid_table')
            ->join('users', 'galidesawar_bid_table.user_id', '=', 'users.id')
            ->join('galidesawar_market', 'galidesawar_bid_table.desawar_id', '=', 'galidesawar_market.id')
            ->join('galidesawar_gtype', 'galidesawar_bid_table.desawar_gtype_id', '=', 'galidesawar_gtype.id')
            ->select('users.name', 'galidesawar_market.desawar_name', 'galidesawar_gtype.gtype', 'galidesawar_bid_table.*');

        // Handle searching
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
                // Add other columns to search if needed
            });
        }

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        // Count total records (unfiltered)
        $totalRecords = $query->count();

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count filtered records
        $filteredRecords = $query->count();

        // Return success response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ], 200);

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching user bid history',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_declare_result()
{
    try {
        // Fetch the declare results
        $data = DB::table('galidesawar_market')->get();

        // Return success response
        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching declare results',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function desawar_result(Request $request)
{
    try {
        // Validation rules
        $rules = [
            'open_number' => 'required',
            'game_id' => 'required|integer', // Validate game_id is not 0
            'result_dec_date' => 'required', // Validate result_dec_date
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        // Prepare the data for insertion
        $date = now(); // Use Laravel's now() helper for the current date
        $data = [
            'desawar_id' => $request->input('game_id'), // Save game_id
            'digit' => $request->input('open_number'),
            'result_date' => $request->input('result_dec_date'), // Save result_dec_date
            'created_at' => $date,
        ];

        // Insert the data into the database
        DB::table('galidesawar_result')->insert($data);

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Game result has been declared successfully.'
        ], 201); // Created

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while declaring the result',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}

public function desawar_result_history_list(Request $request)
{
    try {
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
        ], 200); // OK status

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching the result history',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}

public function desawar_winning_report_list(Request $request)
{
    try {
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
        ], 200); // OK status

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching the winning report list',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}

public function desawar_winning_prediction()
{
    try {
        // Fetch the market data
        $data = DB::table('galidesawar_market')->select('id', 'desawar_name')->get();

        // Calculate total bid amount
        $totalbid = DB::table('galidesawar_bid_table')->sum('amount');

        // Calculate total winning amount
        $totalwin = DB::table('galidesawar_winners')->sum('winning_amount');

        // Return JSON response with the data
        return response()->json([
            'status' => true,
            'data' => $data,
            'total_bid' => $totalbid,
            'total_win' => $totalwin
        ], 200); // OK status

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching winning prediction data',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}

public function desawar_winning_prediction_list(Request $request)
{
    try {
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
        ], 200); // OK status

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching the winning prediction list',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}

public function matchResultsWithBids()
{
    try {
        // Get today's bids and result
    $bids = DB::table('starline_bid_table')
        ->where('starline_bid_date', today()->toDateString())
        ->select('id', 'user_id', 'starline_id', 'starline_gtype_id', 'starline_bid_date', 'panna', 'digit', 'amount')
        ->get();

    $result = DB::table('starline_result')
        ->where('result_date', today()->toDateString())
        ->select('starline_id', 'result_date', 'panna', 'digit')
        ->first();

    // Check if result is found for today
    if (!$result) {
        return; // No result for today, so no match
    }

    $winners = []; // Array to hold winning bids
    $processedBids = []; // Array to track processed bids

    // Get game rates once, as we need them for calculations
    $rates = DB::table('starline_game_rates')->first();

    // Iterate through the bids and check if any match with today's result
    foreach ($bids as $bid) {
        // Basic conditions: starline_id and starline_bid_date must match
        $basicMatch = (
            $bid->starline_id == $result->starline_id &&
            $bid->starline_bid_date == $result->result_date
        );

        // dd($bid);

        // Check for panna matches with filtering
        if ($basicMatch && !in_array($bid->id, $processedBids)) {
            if ($bid->panna !== 'N/A') {
                $pannaMatchType = $this->isPannaMatch($bid->panna, $result->panna);

                // Calculate winning amount based on panna match type
                $winning_amount = 0;
                $bid_type = '';

                if ($pannaMatchType === 'single') {
                    $winning_amount = $bid->amount / $rates->single_match_bid * $rates->single_match_win;
                    $bid_type = 'single panna';
                } elseif ($pannaMatchType === 'double') {
                    $winning_amount = $bid->amount / $rates->double_match_bid * $rates->double_match_win;
                    $bid_type = 'double panna';
                } elseif ($pannaMatchType === 'triple') {
                    $winning_amount = $bid->amount / $rates->triple_match_bid * $rates->triple_match_win;
                    $bid_type = 'triple panna';
                }

                if ($winning_amount > 0) {
                    $winners[] = [
                        'user_id' => $bid->user_id,
                        'bid_id' => $bid->id,
                        'starline_id' => $bid->starline_id,
                        'bid_type' => $bid_type,
                        'bid_point' => $bid->panna,
                        'winning_amount' => $winning_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $processedBids[] = $bid->id; // Mark this bid as processed
                }
            }
        }

        // Check for digit matches
        elseif ($basicMatch && $bid->digit == $result->digit && !in_array($bid->id, $processedBids)) {
            $winning_amount = $bid->amount / $rates->single_digit_bid * $rates->single_digit_win;

            if ($winning_amount > 0) {
                $winners[] = [
                    'user_id' => $bid->user_id,
                    'bid_id' => $bid->id,
                    'starline_id' => $bid->starline_id,
                    'bid_type' => 'digit',
                    'bid_point' => $bid->digit,
                    'winning_amount' => $winning_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $processedBids[] = $bid->id; // Mark this bid as processed
            }
        }
    }

    // Update wallet balances and store winners in the database
    if (count($winners) > 0) {
        foreach ($winners as $winner) {
            // Update user wallet balance
            $user_balance = DB::table('wallets')->where('user_id', $winner['user_id'])->value('balance');
            $user_wallet = $user_balance + $winner['winning_amount'];
            DB::table('wallets')->where('user_id', $winner['user_id'])->update(['balance' => $user_wallet]);
        }

        DB::table('starline_winners')->insert($winners);
        
        // Return a JSON response with a redirect URL if no winners found
        return response()->json([
            'message' => 'Today winner result.',
            'redirect_url' => route('starline_winning_prediction')
        ]);
    } else {
        // Return a JSON response with a redirect URL if no winners found
        return response()->json([
            'message' => 'No matches found for today\'s result.',
            'redirect_url' => route('starline_declare_result')
        ]);
    }

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while processing results.',
            'error' => $e->getMessage()
        ], 500); // Internal Server Error
    }
}


public function matchResult()
{
    try {
        // Get today's bids
         $bids = DB::table('bid_table')
        ->where('bid_date', today()->toDateString())
        ->select('id', 'user_id', 'market_id', 'gtype_id', 'bid_date', 'session', 'open_digit', 'close_digit', 'open_panna','close_panna', 'amount')
        ->get();


        $result = DB::table('market_results')
            ->where('result_date', today()->toDateString())
            ->select('market_id', 'result_date','open_digit','close_digit', 'open_panna', 'close_panna')
            ->first();


        // Check if result is found for today
        if (!$result) {
            return; // No result for today, so no match
        }

        $winners = []; // Array to hold winning bids
        $processedBids = []; // Array to track processed bids

        // Get game rates once, as we need them for calculations
        $rates = DB::table('game_rates')->first();

        // Iterate through the bids and check if any match with today's result
        foreach ($bids as $bid) {
            // Basic conditions: market_id and bid_date must match
            $basicMatch = (
                $bid->market_id == $result->market_id &&
                $bid->bid_date == $result->result_date
            );

            // Check for panna matches with filtering
            if ($basicMatch && !in_array($bid->id, $processedBids)) {

                // Check if jodi matches
                if($bid->open_digit == $result->open_digit && $bid->close_digit == $result->close_digit) {

                    $winning_amount = $bid->amount / $rates->jodi_digit_bid * $rates->jodi_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'bid_type' => 'jodi',
                            'bid_point' => $bid->open_digit.$bid->close_digit,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }

                // Check if full sangam matches
                elseif($bid->open_panna == $result->open_panna && $bid->close_panna == $result->close_panna) {

                    $winning_amount = $bid->amount / $rates->full_sangam_bid * $rates->full_sangam_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'bid_type' => 'full sangam',
                            // 'session' => $bid->session,
                            'bid_point' => $bid->open_panna - $bid->close_panna,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }

                // Check if open_panna matches
                elseif ($bid->open_panna !== 'N/A' && $bid->open_panna == $result->open_panna) {
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

                    // Check if winning amount is greater than zero and store winner details
                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'bid_type' => $bid_type,
                            'session' => $bid->session,
                            'bid_point' => $bid->open_panna,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }

                // Optional: Check close_panna match here if needed
                elseif ($bid->close_panna !== 'N/A' && $bid->close_panna == $result->close_panna) {
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

                elseif($bid->open_digit !== 'N/A' && $bid->open_digit == $result->open_digit) {
                    $winning_amount = $bid->amount / $rates->single_digit_bid * $rates->single_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
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
                }

                // Optional: Check close_digit match here if needed
                elseif($bid->close_digit !== 'N/A' && $bid->close_digit == $result->close_digit) {
                    $winning_amount = $bid->amount / $rates->single_digit_bid * $rates->single_digit_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
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

                // Check if half sangam matches
                elseif($bid->open_digit == $result->open_digit && $bid->close_panna == $result->close_panna) {

                    $winning_amount = $bid->amount / $rates->half_sangam_bid * $rates->half_sangam_win;

                    if ($winning_amount > 0) {
                        $winners[] = [
                            'user_id' => $bid->user_id,
                            'bid_id' => $bid->id,
                            'market_id' => $bid->market_id,
                            'bid_type' => 'half sangam',
                            'session' => $bid->session,
                            'bid_point' => $bid->open_digit - $bid->close_panna,
                            'winning_amount' => $winning_amount,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $processedBids[] = $bid->id; // Mark this bid as processed
                    }
                }

            }

        }

        // Update wallet balances and store winners in the database
        if (count($winners) > 0) {
            foreach ($winners as $winner) {
                // Update user wallet balance
                $user_balance = DB::table('wallets')->where('user_id', $winner['user_id'])->value('balance');
                $user_wallet = $user_balance + $winner['winning_amount'];
                DB::table('wallets')->where('user_id', $winner['user_id'])->update(['balance' => $user_wallet]);
            }

            DB::table('winners')->insert($winners);
            
            return response()->json([
                'message' => 'Winners processed successfully.',
                'redirect_url' => route('winning_prediction')
            ]);
            return redirect()->route('winning_prediction'); // Return the winners if matches found


        }  else {
            // Return a JSON response with a redirect URL if no winners found
            return response()->json([
                'message' => 'No matches found for today\'s result.',
                'redirect_url' => route('declare_result')
            ]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred while processing results: ' . $e->getMessage()], 500);
    }
}

/**
 * Calculate the winning amount based on match type.
 */
private function calculateWinningAmount($pannaMatchType, $bidAmount, $rates, $matchPoint)
{
    switch ($pannaMatchType) {
        case 'single':
            return $bidAmount / $rates->single_match_bid * $rates->single_match_win;
        case 'double':
            return $bidAmount / $rates->double_match_bid * $rates->double_match_win;
        case 'triple':
            return $bidAmount / $rates->triple_match_bid * $rates->triple_match_win;
        default:
            return 0;
    }
}

/**
 * Create a winner entry for the database.
 */
private function createWinnerEntry($bid, $bid_type, $winning_amount, $bid_point)
{
    return [
        'user_id' => $bid->user_id,
        'bid_id' => $bid->id,
        'market_id' => $bid->market_id,
        'bid_type' => $bid_type,
        'session' => $bid->session,
        'bid_point' => $bid_point,
        'winning_amount' => $winning_amount,
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

public function galiResult()
{
    try {
         // Get today's bids and result
    $bids = DB::table('galidesawar_bid_table')
        ->where('desawar_bid_date', today()->toDateString())
        ->select('id', 'user_id', 'desawar_id', 'desawar_gtype_id', 'desawar_bid_date', 'digit', 'amount')
        ->get();

    $result = DB::table('galidesawar_result')
        ->where('result_date', today()->toDateString())
        ->select('desawar_id', 'result_date', 'digit')
        ->first();

    // Check if result is found for today
    if (!$result) {
        return; // No result for today, so no match
    }

    $winners = []; // Array to hold winning bids
    $processedBids = []; // Array to track processed bids

    // Get game rates once, as we need them for calculations
    $rates = DB::table('galidesawar_game_rates')->first();

    foreach ($bids as $bid) {

        $basicMatch = (
            $bid->desawar_id == $result->desawar_id &&
            $bid->desawar_bid_date == $result->result_date
        );

        // Check for digit matches
        if ($basicMatch && !in_array($bid->id, $processedBids)) {
            
            // Check the length of the Left digit
            if($bid->desawar_gtype_id == 1 && $bid->digit == substr($result->digit, 0, 1)) {


                $winning_amount = $bid->amount / $rates->left_digit_bid * $rates->left_digit_win;

                if ($winning_amount > 0) {
                    $winners[] = [
                        'user_id' => $bid->user_id,
                        'bid_id' => $bid->id,
                        'desawar_id' => $bid->desawar_id,
                        'bid_type' => 'left digit',
                        'bid_point' => $bid->digit,
                        'winning_amount' => $winning_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $processedBids[] = $bid->id; // Mark this bid as processed
                }
                
            }

            // Check the length of the Right digit
            elseif($bid->desawar_gtype_id == 2 && $bid->digit == substr($result->digit, 1, 1)) {

                $winning_amount = $bid->amount / $rates->right_digit_bid * $rates->right_digit_win;

                if ($winning_amount > 0) {
                    $winners[] = [
                        'user_id' => $bid->user_id,
                        'bid_id' => $bid->id,
                        'desawar_id' => $bid->desawar_id,
                        'bid_type' => 'right digit',
                        'bid_point' => $bid->digit,
                        'winning_amount' => $winning_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $processedBids[] = $bid->id; // Mark this bid as processed
                }
            

            }

            // Check the length of the Jodi digit
            elseif($bid->desawar_gtype_id == 3 && $bid->digit == $result->digit) {

                $winning_amount = $bid->amount / $rates->jodi_digit_bid * $rates->jodi_digit_win;

                if ($winning_amount > 0) {
                    $winners[] = [
                        'user_id' => $bid->user_id,
                        'bid_id' => $bid->id,
                        'desawar_id' => $bid->desawar_id,
                        'bid_type' => 'jodi',
                        'bid_point' => $bid->digit,
                        'winning_amount' => $winning_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $processedBids[] = $bid->id; // Mark this bid as processed
                }

            }
            
            
        }

    }

    // Update wallet balances and store winners in the database
    if (count($winners) > 0) {
        foreach ($winners as $winner) {
            // Update user wallet balance
            $user_balance = DB::table('wallets')->where('user_id', $winner['user_id'])->value('balance');
            $user_wallet = $user_balance + $winner['winning_amount'];
            DB::table('wallets')->where('user_id', $winner['user_id'])->update(['balance' => $user_wallet]);
        }

        DB::table('galidesawar_winners')->insert($winners);
        
        return response()->json([
            'message' => 'Winners processed successfully.',
            'redirect_url' => route('desawar_winning_prediction')
        ]);
    } else {
        // Return a JSON response with a redirect URL if no winners found
        return response()->json([
            'message' => 'No matches found for today\'s result.',
            'redirect_url' => route('desawar_declare_result')
        ]);
    }
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred while processing bids', 'details' => $e->getMessage()], 500);
    }
}

public function getMarketBidAmount(Request $request)
{
    try {
        $marketId = $request->input('market_id');

        // Ensure market ID is valid
        if (is_null($marketId) || $marketId == '0') {
            return response()->json(['total_bid_amount' => 'N/A'], 400); // Return 400 Bad Request
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
        ], 200);

    } catch (\Exception $e) {
        // Handle exceptions and return a 500 Internal Server Error
        return response()->json(['error' => 'An error occurred while fetching the bid amount', 'details' => $e->getMessage()], 500);
    }
}


public function un_approved_users_data(Request $request)
{
    try {
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

        // Count total records before pagination
        $totalRecords = DB::table('users')
            ->where('status', 'unapproved')
            ->count();

        // Handle pagination
        $start = $request->input('start', 0); // Default to 0 if not provided
        $length = $request->input('length', 10); // Default to 10 if not provided
        $data = $query->offset($start)->limit($length)->get();

        // Count records after filtering (for the current query)
        $filteredRecords = $query->count();

        // Return JSON response
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ], 200);

    } catch (\Exception $e) {
        // Handle exceptions and return a 500 Internal Server Error
        return response()->json(['error' => 'An error occurred while fetching unapproved users data', 'details' => $e->getMessage()], 500);
    }
}

public function unapprove_users_approve(Request $request)
{
    // Validate the request input
    $request->validate([
        'id' => 'required|integer|exists:users,id'
    ]);

    try {
        // Retrieve the ID from the request
        $id = $request->id;

        // Perform the update
        $updated = DB::table('users')
            ->where('id', $id)
            ->update(['status' => 'approved', 'updated_at' => now()]);

        // Check if the update was successful
        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Record approved successfully.'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404);
        }
        
    } catch (\Exception $e) {
        // Handle any potential exceptions and return a 500 error
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while approving the record.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function unapprove_users_unapprove(Request $request)
{
    // Validate the request input
    $request->validate([
        'id' => 'required|integer|exists:users,id'
    ]);

    try {
        // Retrieve the ID from the request
        $id = $request->id;

        // Perform the update
        $updated = DB::table('users')
            ->where('id', $id)
            ->update(['status' => 'unapproved', 'updated_at' => now()]);

        // Check if the update was successful
        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Record unapproved successfully.'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found or could not be updated.'], 404);
        }

    } catch (\Exception $e) {
        // Handle any potential exceptions and return a 500 error
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while unapproving the record.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function delete_unapprove_users(Request $request,)
{
    // Validate the request input
    $request->validate([
        'id' => 'required|integer|exists:users,id'
    ]);

    try {
        // Retrieve the ID from the request
        $id = $request->id;

        // Perform the deletion
        $deleted = DB::table('users')->where('id', $id)->delete();

        // Check if the deletion was successful
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found or could not be deleted.'], 404);
        }

    } catch (\Exception $e) {
        // Handle any potential exceptions and return a 500 error
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the record.',
            'error' => $e->getMessage()
        ], 500);
    }
}





}
