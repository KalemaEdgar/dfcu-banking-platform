@extends('layouts.app')
@section('title', 'Accounts')
@section('content')
    <div class="space-y-6 max-w-7xl mx-auto">
        {{-- @foreach ($accounts as $account)
            <div class="rounded-lg bg-white shadow p-6">
                <p class="text-lg font-medium leading-6 text-gray-900">{{ $account->account_id }}</p>
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl">
                        UGX {{ $account->balance }}
                    </h1>
                    <a href="{{ url('/accounts/' . $account->account_id) }}" class="text-blue-600 hover:text-blue-800">View
                        transactions</a>
                </div>
                <div class="flex items-center justify-between">
                    <h1 class="text-sm">
                        Last transacted at: {{ $account->last_transacted_at }}
                    </h1>
                </div>
            </div>
        @endforeach --}}

        @foreach ($accounts as $account)
            <div class="overflow-hidden bg-white shadow sm:rounded-lg w-25">
                <div class="px-4 py-5 sm:px-6 flex items-center justify-between bg-gray-100">
                    <h3 class="text-2xl font-medium leading-6 text-gray-900">
                        UGX {{ number_format($account->balance) }}
                    </h3>

                    <a href="{{ url('/accounts/' . $account->account_id) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm">View transactions</a>

                    <a href="{{ url('/transactions/' . $account->account_id . '/export') }}"
                        class="text-blue-600 hover:text-blue-800 text-sm">Export</a>
                </div>

                <div class="border-t border-gray-200">
                    <dl>
                        <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $account->account_id }}</dd>
                        </div>
                        <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Last transacted at</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $account->last_transacted_at ?: 'Not yet transacted' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        @endforeach
    </div>
@endsection
