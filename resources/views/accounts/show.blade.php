<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account') }}: {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <div class="flex flex-row py-2">
                    <div class="basis-1/2"><x-alert></x-alert></div>
                    <form method="GET" action="{{ route('accounts.show', $account->id) }}">
                        <div class="basis-1/2">
                            <label for="filter_period"
                                class="form-label text-gray-700 font-semibold text-sm">{{ __('Filter by Period') }}</label>
                            <div class="flex flex-row mt-1 items-center">
                                <div class="flex-grow pr-2">
                                    <input type="date" name="ini_date" id="ini_date"
                                        class="w-full px-2 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="{{ explode(' ', $ini_date)[0] ?? date('Y-m-01') }}">
                                </div>
                                <div class="flex-grow pl-2">
                                    <input type="date" name="fin_date" id="fin_date"
                                        class="w-full px-2 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="{{ explode(' ', $fin_date)[0] ?? date('Y-m-t') }}">
                                </div>
                                <button type="submit"
                                    class="flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-bold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ml-4">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> {{ __('Filter') }}
                                </button>
                                <a href="{{ route('accounts.show', $account->id) }}" type="button"
                                    class="flex items-center px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 ml-4">
                                    <i class="fa-solid fa-rotate-left mr-1"></i> {{ __('Clear Filters') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="w-full">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('entries.create', ['account' => $account->id]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Add Entry') }}
                        </a>
                        <p>{{ __('Current Balance') }}: R$ {{ number_format($balance, 2, ',', '.') }}</p>
                    </div>
                    <table class="min-w-full table-auto mt-3">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    {{ __('Transaction Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    {{ __('Description') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                    {{ __('Inflow') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                    {{ __('Outflow') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                    {{ __('Balance') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ date('d/m/Y', strtotime(explode(' ', $ini_date)[0])) ?? date('01/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">SALDO ANTERIOR</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"> - </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"> - </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    R$ {{ number_format($opening_balance, 2, ',', '.') }}</td>
                            </tr>
                            @foreach ($entries as $item)
                                <tr class="hover:bg-slate-100">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ date('d/m/Y', strtotime($item->transaction_date)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->transaction_description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if ($item->transaction_type === 'inflow')
                                            R$ {{ number_format($item->transaction_value, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if ($item->transaction_type === 'outflow')
                                            R$ {{ number_format($item->transaction_value, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        R$ {{ number_format($item->current_balance, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center flex justify-around">
                                        <a href="{{ route('entries.edit', [$item->id, 'account_id' => $account->id]) }}"
                                            title="{{ __('Edit Transaction') }}">
                                            <i
                                                class="fa-solid fa-pen-to-square cursor-pointer hover:text-green-400 hover:scale-110 transition ease-in-out delay-50"></i>
                                        </a>

                                        <form action="{{ route('entries.destroy', [$item->id]) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="account_id" value="{{ $account->id }}">
                                            <button type="submit" title="{{ __('Delete Transaction') }}"
                                                onclick="return confirm('{{ __('ATTENTION! Do you confirm the deletion of account and all its entries? This action has no return.') }}')">
                                                <i
                                                    class="fa-solid fa-trash cursor-pointer hover:text-red-400 hover:scale-110 transition ease-in-out delay-50"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $entries->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
