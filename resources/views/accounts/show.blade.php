<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account') }}: {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                @php
                    $messageType = session('success') ? 'success' : (session('error') ? 'error' : null);
                    $messageContent = session($messageType);
                    $messageColors = ['success' => 'green', 'error' => 'red'];
                    $messageIcons = ['success' => 'thumbs-up', 'error' => 'alert']; // Exemplo de ícones
                @endphp

                @if ($messageType)
                    <div class="bg-{{ $messageColors[$messageType] }}-100 border-l-4 border-{{ $messageColors[$messageType] }}-500 text-{{ $messageColors[$messageType] }}-700 p-4 mb-3"
                        role="alert">
                        <p>
                            @if ($messageType == 'success')
                                <i class="fa-solid fa-thumbs-up"></i>
                            @elseif ($messageType == 'error')
                                <i class="fa-solid fa-thumbs-down"></i>
                            @endif{{ $messageContent }}
                        </p>
                    </div>
                @endif

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
                                <td class="px-6 py-4 whitespace-nowrap">{{ date('01/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">SALDO ANTERIOR</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"> - </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right"> - </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    R$ {{ number_format($account->opening_balance, 2, ',', '.') }}</td>
                            </tr>
                            @foreach ($entries as $item)
                                <tr>
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
                    {{ $entries->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
