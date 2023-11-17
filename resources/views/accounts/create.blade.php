<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a class="text-blue-800 hover:text-blue-600" href="{{ route('accounts.index') }}">{{ __('Accounts') }}</a>
            <i class="fa-solid fa-arrow-left text-xs"></i>
            {{ __('Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form action="{{ route('accounts.store') }}" method="POST" id="accountForm" class="max-w-lg mx-auto">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome do Caixa:</label>
                        <input type="text" id="name" name="name" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="opening_balance" class="block text-gray-700 text-sm font-bold mb-2">Saldo
                            Inicial:</label>
                        <input type="text" id="opening_balance" name="opening_balance"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            value="0,00">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para validação -->
    <div id="validationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Atenção!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Por favor, preencha o campo Nome do Caixa.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
