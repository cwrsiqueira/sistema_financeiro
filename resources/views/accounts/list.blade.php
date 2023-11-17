<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Accounts') }}
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
                        <a href="{{ route('accounts.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Add Account') }}
                        </a>
                        <p>{{ __('Equity') }}: R$ {{ number_format($current_balance, 2, ',', '.') }}</p>
                    </div>
                    <table class="min-w-full table-auto mt-3">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    {{ __('ID') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    {{ __('Account') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                    {{ __('Current Balance') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($accounts as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('entries.index') }}" class="hover:text-blue-400">
                                            <i class="fa-solid fa-right-to-bracket"></i> {{ $item->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        R$ {{ number_format($item->opening_balance, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center flex justify-around">
                                        <a href="{{ route('accounts.edit', [$item->id]) }}" title="Editar Caixa">
                                            <i
                                                class="fa-solid fa-pen-to-square cursor-pointer hover:text-green-400 hover:scale-110 transition ease-in-out delay-50"></i>
                                        </a>

                                        <form action="{{ route('accounts.destroy', [$item->id]) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" title="Excluir Caixa"
                                                onclick="return confirm('{{ __('ATTENTION! Do you confirm the deletion of account and all its entries? This action has no return.') }}');">
                                                <i
                                                    class="fa-solid fa-trash cursor-pointer hover:text-red-400 hover:scale-110 transition ease-in-out delay-50"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
