<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function showAddPoint()
    {
        return view('add_point');
    }

    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1|max:10000'
            ]);

            // Create a new transaction record
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'pending',
                'reference_id' => Str::uuid(),
            ]);

            // UPI payment details
            $merchantUpiId = config('payment.upi.merchant_id'); // Add this to your config
            $merchantName = config('app.name');
            $transactionNote = 'Add Points - ' . $transaction->reference_id;

            // Generate UPI deep link URL
            $upiUrl = 'upi://pay?' . http_build_query([
                'pa' => $merchantUpiId,
                'pn' => $merchantName,
                'tn' => $transactionNote,
                'am' => $request->amount,
                'cu' => 'INR',
                'tr' => $transaction->reference_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment initiated',
                'transaction_id' => $transaction->reference_id,
                'upi_link' => $upiUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment initiation failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function paymentStatus(Request $request)
    {
        try {
            // Find the transaction
            $transaction = Transaction::where('reference_id', $request->tr)
                                    ->where('status', 'pending')
                                    ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Update transaction status
            $transaction->status = 'completed';
            $transaction->save();

            // Get the user
            $user = $transaction->user;

            // Convert amount to points (1 rupee = 1 point)
            $points = $transaction->amount;

            // Add points to user's wallet
            $user->coins += $points;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment completed and points added to wallet',
                'points_added' => $points,
                'new_balance' => $user->coins
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
