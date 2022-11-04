<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * @todo Have an API to return all transactions
     * @todo Have an API to return all transactions for a specific OVA
     * @todo Have an API to return all transactions for a specific phone number / MNO wallet
     */

    public function payment()
    {
        return view('payments.index', [
            'accounts' => auth()->user()->accounts
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return new TransactionCollection(Transaction::paginate());

        } catch (Exception $ex) {
            $response['status'] = 'Failed';
            $response['message'] = 'Request failed. Please try again or contact customer care for support';
            $response['responded_at'] = now()->format('c');

            info('Exception:', ['code' => $ex->getCode(), 'message' => $ex->getMessage()]);
            info('Response:', $response);

            return response(collect($response), 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function makePayment(Request $request)
    {
        // dd($request);
        // dd($request->requestTime, $request->reference);

        try {
            /**
             * @todo Check client credentials -- done
             * @todo Validate the parameters -- done
             * @todo Post the transaction -- done
             * @todo Respond back with the codes they suggested -- done
             * @todo Update the balances for the user's account after the transaction -- done
             */

            info('Request:', [
                'Payment request received for' => $request->creditAccount,
                'received_at' => now()->format('c')
            ]);

            $validator = Validator::make($request->all(), [
                'reference' => 'bail|required|alpha_num|unique:transactions,reference',
                'debitAccount' => 'bail|required|min:10|max:10|exists:accounts,account_id',
                'creditAccount' => 'bail|required|regex:/^(07[0-9\s\-\+\(\)]{8})$/|min:10|max:10',
                'recipientName' => 'required|alpha|min:5|max:255',
                'description' => 'required|string|min:3|max:255',
                'amount' => 'required|numeric|between:5000,5000000',
                'transactionType' => 'required|string|max:20',
                'requestTime' => 'required|date_format:Y-m-d H:i:s',
            ]);

            if ($validator->fails()) {
                $response = [
                    'status' => 'Failed',
                    'message' => $validator->errors()->first(),
                    'responded_at' => now()->format('c'),
                ];

                Log::error('Response:', $response);

                // return response(collect($response), 400);
                return back()->with('error', $response['message']);
            }

            $attributes = $validator->validated();

            // Log the transaction -- done
            // Make a curl or guzzle request to the momo-processor -- done
            // Capture response and update the transaction -- done
            // Test scenarios where the number is whitelisted, blacklisted and not setup -- done

            // Pick account details
            $accountData = Account::where('account_id', $attributes['debitAccount'])->firstOrFail();
            $accountBalance = $accountData->balance;

            // Check if the account balance is sufficient for the transaction
            if ($accountBalance < $attributes['amount']) {
                $response = [
                    'status' => 'Failed',
                    'message' => 'Insufficient funds on the source account',
                    'responded_at' => now()->format('c'),
                ];

                info('Response:', $response);

                // return response(collect($response), 400);
                return back()->with('error', $response['message']);
            }

            $transaction = Transaction::create([
                'reference' => $attributes['reference'],
                'cif' => $accountData->cif,
                'debit_account' => $attributes['debitAccount'],
                'credit_account' => $attributes['creditAccount'],
                'recipient_name' => $attributes['recipientName'],
                'description' => $attributes['description'],
                'transaction_type' => $attributes['transactionType'],
                'amount' => $attributes['amount'],
                'created_by' => '1',
                'client_ip' => $request->ip(),
            ]);

            if ( ! in_array(strtolower($attributes['transactionType']), ['mtn', 'airtel'])) {
                $response = [
                    'status' => 'Failed',
                    'message' => 'Unsupported transaction type',
                    'responded_at' => now()->format('c'),
                ];

                Transaction::where('id', $transaction->id)->update([
                    'status' => 'Failed',
                    'reason' => $response['error'],
                ]);

                info('Response:', $response);

                // return response(collect($response), 400);
                return back()->with('error', $response['message']);
            }

            $apiUrl = env('MOMO_API_URL').strtolower($attributes['transactionType']);
            info($apiUrl);

            // Make the MoMo request
            $apiResponse = Http::withHeaders([
                'x-client-id' => env('CLIENT_ID'),
                'x-client-secret' => env('CLIENT_SECRET'),
            ])->post($apiUrl, [
                'reference' => $attributes['reference'],
                'creditAccount' => $attributes['creditAccount'],
                'amount' => $attributes['amount'],
                'transactionType' => $attributes['transactionType'],
                'requestTime' => now()->format('Y-m-d H:i:s')
            ]);

            $responseData = json_decode($apiResponse->body());

            Transaction::where('id', $transaction->id)->update([
                'status' => $responseData->status,
                'reason' => $responseData->message
            ]);

            Account::where('account_id', $attributes['debitAccount'])->update([
                'balance' => $accountBalance - $attributes['amount'],
                'last_transacted_at' => now(),
            ]);

            $clientResponse = [
                'status' => $responseData->status,
                'message' => $responseData->message . ' with transaction id ' . $attributes['reference'],
                'responded_at' => now()->format('c'),
            ];

            info('Response:', $clientResponse);

            // return response($clientResponse, 200);
            return back()->with('success', $clientResponse['message']);

        } catch (Exception $ex) {
            $response['status'] = 'Failed';
            $response['message'] = 'Request failed. Please try again in a minute or contact support';
            $response['responded_at'] = now()->format('c');

            Log::error('Exception:', ['code' => $ex->getCode(), 'message' => $ex->getMessage()]);
            Log::error('Response:', $response);

            // return response(collect($response), 400);
            return back()->with('error', $response['message']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        try {
            $referenceNumber = request('referenceNumber');

            info('View details for:', ['Transaction Id' => $referenceNumber, 'received_at' => now()->format('c')]);

            return new TransactionResource(
                Transaction::where('reference', $referenceNumber)->firstOrfail()
            );

        } catch (Exception $ex) {
            $response['status'] = 'Failed';
            $response['message'] = 'Reference ' . request('referenceNumber') . ' not found';
            $response['reference'] = request('referenceNumber');
            $response['responded_at'] = now()->format('c');

            info('Exception:', ['code' => $ex->getCode(), 'message' => $ex->getMessage()]);
            info('Response:', $response);

            return response(collect($response)->except('referenceNumber'), 404);
        }
    }

    public function showAccountTxns(Request $request)
    {
        $transactions = Transaction::where('debit_account', $request->accountNumber)
            ->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()])
            ->paginate();

        return new TransactionCollection($transactions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
