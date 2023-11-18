<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form action="{{ route('entries.update', $entry->id) }}" method="POST" id="accountForm"
                    class="max-w-lg mx-auto">
                    @method('PUT')
                    @csrf
                    <x-alert></x-alert>
                    <input type="hidden" name="account_id" value="{{ $account_id }}">
                    <div>
                        <label for="transaction_description"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('Description') }}:</label>
                        <input type="text" id="transaction_description" name="transaction_description"
                            value="{{ $entry->transaction_description }}"
                            class="mb-6 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('transaction_description') border-rose-600 @enderror"
                            required>
                        @error('transaction_description')
                            <div class="text-red-600 -mt-6">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex flex-row gap-2">
                        <div class="basis-1/2">
                            <label for="transaction_date" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Date') }}:</label>
                            <input type="date" id="transaction_date" name="transaction_date"
                                value="{{ explode(' ', $entry->transaction_date)[0] }}"
                                class="mb-6 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('transaction_date') border-rose-600 @enderror"
                                required value="{{ date('Y-m-d') }}">
                            @error('transaction_date')
                                <div class="text-red-600 -mt-6">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="basis-1/2">
                            <label for="transaction_value" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Value') }}:</label>
                            <input type="text" id="transaction_value" name="transaction_value"
                                value="{{ number_format($entry->transaction_value, 2, ',', '.') }}"
                                class="mb-6 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('transaction_value') border-rose-600 @enderror value_mask"
                                required>
                            @error('transaction_value')
                                <div class="text-red-600 -mt-6">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-row gap-2">
                        <div class="basis-1/2">
                            <label for="transaction_type" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Type') }}:</label>
                            <select name="transaction_type" id="transaction_type"
                                class="mb-6 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('transaction_type') border-rose-600 @enderror"
                                required>
                                <option @if ($entry->transaction_type == 'inflow') selected @endif value="inflow">
                                    {{ __('Inflow') }}
                                </option>
                                <option @if ($entry->transaction_type == 'outflow') selected @endif value="outflow">
                                    {{ __('Outflow') }}</option>
                            </select>
                            @error('transaction_type')
                                <div class="text-red-600 -mt-6">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex items-center justify-center basis-1/2">
                            <button type="submit"
                                class="w-1/2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-5 rounded focus:outline-none focus:shadow-outline">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
                <a href="{{ route('accounts.show', $account_id) }}">
                    < {{ __('Back') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
