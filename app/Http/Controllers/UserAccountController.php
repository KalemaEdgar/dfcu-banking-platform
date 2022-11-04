<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class UserAccountController extends Controller
{
    public function index()
    {
        return view('accounts.index', [
            'accounts' => auth()->user()->accounts
        ]);
    }

    public function show($account_id)
    {
        $account = Account::where('account_id', $account_id)->firstOrFail();

        abort_if($account->cif !== auth()->user()->cif, 403);

        $transactions = Transaction::query()
            ->where('debit_account', $account->account_id)
            ->orWhere('credit_account', $account->account_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('accounts.show', [
            'account'      => $account,
            'transactions' => $transactions
        ]);
    }

    public function export($accountId)
    {
        // $account = Account::where('account_id', $accountId)->firstOrFail();

        // abort_if($account->cif !== auth()->user()->cif, 403);

        // dd(Carbon::now()->subMonth(6), Carbon::now());

        $transactions = Transaction::query()
            ->where(function ($query) use ($accountId) {
                $query->where('debit_account', $accountId)
                ->orWhere('credit_account', $accountId);
            })
            ->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()])
            ->orderBy('created_at', 'DESC')
            ->get();

        // dd($transactions);

        return Excel::download(new TransactionsExport($transactions), $accountId.'-transactions.xlsx');
    }
}
