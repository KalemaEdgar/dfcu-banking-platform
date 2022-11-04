@extends('layouts.app')
@section('title', 'Mobile Money Funds Transfer')
@section('content')

    <div class="mt-10 sm:mt-0 max-w-7xl mx-auto">
        <div class="md:grid md:grid-cols-2 md:gap-6">
            <div class="px-4 sm:px-0 mt-5 md:col-span-2 md:mt-0">

                @if ($message = Session::get('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-1" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @endif

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-1"
                        role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @endif

                <div class="px-4 sm:px-0">
                    {{-- <h3 class="text-lg font-medium leading-6 text-gray-900">Mobile money funds transfer</h3> --}}
                    {{-- <h1 class="mt-1 text-md text-gray-900">Platform currently offers MTN and Airtel.</h1> --}}
                    <h1 class="text-sm font-medium leading-6 text-gray-600">Platform currently offers MTN and Airtel.</h1>
                    <p class="mt-1 text-sm text-red-500">All fields are mandatory</p>
                </div>
            </div>

            <div class="mt-5 md:col-span-2 md:mt-0">
                <form action="{{ route('payments.create') }}" method="POST">
                    @csrf
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-2">
                                    <label for="transactionType" class="block text-sm font-medium text-gray-700">
                                        Mobile Money Provider
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <select id="transactionType" name="transactionType" autocomplete="transactionType"
                                        required
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option value="AIRTEL">Airtel</option>
                                        <option value="MTN">MTN</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="creditAccount" class="block text-sm font-medium text-gray-700">
                                        Recipient Phone Number
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <input type="text" name="creditAccount" id="creditAccount"
                                        autocomplete="creditAccount" required pattern="^(07[0-9\s\-\+\(\)]{8})$"
                                        minlength="10" maxlength="10" value="0775623646"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="recipientName" class="block text-sm font-medium text-gray-700">
                                        Recipient Name
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <input type="text" name="recipientName" id="recipientName"
                                        autocomplete="recipientName" required minlength="5" value="Kalema"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="debitAccount" class="block text-sm font-medium text-gray-700">
                                        Source Account
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <select id="debitAccount" name="debitAccount" autocomplete="debitAccount" required
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->account_id }}">{{ $account->account_id }} Bal:
                                                {{ number_format($account->balance) }}</option>
                                        @endforeach
                                        {{-- <option>Canada</option> --}}
                                        {{-- <option>Mexico</option> --}}
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">
                                        Amount
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <input type="number" name="amount" id="amount" autocomplete="amount" required
                                        min="5000" max="5000000" value="5000"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700">
                                        Description
                                        <span class="text-red-700">*</span>
                                    </label>
                                    <input type="text" name="description" id="description" autocomplete="description"
                                        required value="Some test description"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 text-right sm:px-0">
                                <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Complete
                                    Transfer</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="ref{{ random_int(100000, 999999) }}" name="reference" />
                    <input type="hidden" value="{{ \Carbon\Carbon::now() }}" name="requestTime" />
                </form>
            </div>

        </div>
    </div>
@endsection
