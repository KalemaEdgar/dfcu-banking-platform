<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return new AccountCollection(Account::paginate());

        } catch (Exception $ex) {
            $response['status'] = 'Failed';
            $response['message'] = 'Request failed. Please try again or contact customer care for support';
            $response['responded_at'] = now()->format('c');

            info('Exception:', ['code' => $ex->getCode(), 'message' => $ex->getMessage()]);
            info('Response:', $response);

            return response(collect($response), 404);
        }
    }

    public function blocked()
    {
        return new AccountCollection(Account::blocked()->get());
    }

    public function active()
    {
        try {
            // dd(new AccountCollection(Account::active()->get()));
            return new AccountCollection(Account::active()->get());

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        try {
            $accountNumber = request('account_number');

            info('View details for:', ['Account number' => $accountNumber, 'received_at' => now()->format('c')]);

            return new AccountResource(
                Account::where('account_id', $accountNumber)->firstOrfail()
            );

        } catch (Exception $ex) {
            $response['status'] = 'Failed';
            $response['message'] = 'Account ' . request('account_number') . ' not found';
            $response['account_number'] = request('account_number');
            $response['responded_at'] = now()->format('c');

            info('Exception:', ['code' => $ex->getCode(), 'message' => $ex->getMessage()]);
            info('Response:', $response);

            return response(collect($response)->except('account_number'), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }

    public function showUserAccounts(Request $request)
    {
        $userInfo = User::where('email', $request->email)->firstOrFail();

        $accounts = Account::where('cif', $userInfo->cif)->where('blocked', false)->get();

        return new AccountCollection($accounts);
    }
}
