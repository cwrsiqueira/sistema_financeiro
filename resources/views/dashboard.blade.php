<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto p-4">


                        <form method="GET" action="{{ route('dashboard.index') }}" class="mx-auto w-1/2 mb-6">
                            <label for="filter_period" class="form-label text-gray-700 font-semibold text-sm">
                                {{ __('Filter by Month') }}
                            </label>
                            <div class="flex flex-row gap-1 items-center">
                                <select name="month" id="month"
                                    class="flex-grow px-2 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                                    @php
                                        $months = [
                                            '1' => __('January'),
                                            '2' => __('February'),
                                            '3' => __('March'),
                                            '4' => __('April'),
                                            '5' => __('May'),
                                            '6' => __('June'),
                                            '7' => __('July'),
                                            '8' => __('August'),
                                            '9' => __('September'),
                                            '10' => __('October'),
                                            '11' => __('November'),
                                            '12' => __('December'),
                                        ];
                                    @endphp
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option @if ($month == $m) selected @endif
                                            value="{{ $m }}">
                                            {{ $months[$m] }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="year" id="year"
                                    class="w-20 px-2 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                                    @for ($y = date('Y') - 5; $y <= date('Y') + 10; $y++)
                                        <option @if ($year == $y) selected @endif
                                            value="{{ $y }}">
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <a href="{{ route('dashboard.index') }}"
                                    class="whitespace-nowrap px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <i class="fa-solid fa-rotate-left mr-1"></i>
                                    {{ __('Clear Filters') }}
                                </a>
                            </div>
                        </form>

                        <!-- Charts -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Overall Equity Evolution -->
                            <div class="p-4 border border-gray-200 rounded-md">
                                <h2 class="font-bold text-lg mb-2">{{ __('Overall Equity Evolution') }}</h2>
                                <canvas id="overallEquityEvolution"></canvas>
                            </div>

                            <!-- Evolution of Cash Balances -->
                            <div class="p-4 border border-gray-200 rounded-md">
                                <h2 class="font-bold text-lg mb-2">{{ __('Evolution of Cash Balances') }}</h2>
                                <canvas id="cashBalanceChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @section('javascript')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let equity = document.getElementById('overallEquityEvolution');
                let account = document.getElementById('cashBalanceChart');

                let labels = [{{ $selected_days }}];
                let datasets_balance = [{{ $current_day_balance }}]
                let datasets_inflows = [{{ $current_day_inflows }}]
                let datasets_outflows = [{{ $current_day_outflows }}]

                datasets = [@json($balance_accounts)];

                new Chart(equity, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "{{ __('Equity') }}",
                            data: datasets_balance,
                            borderWidth: 1
                        }, {
                            label: "{{ __('Inflows') }}",
                            data: datasets_inflows,
                            borderWidth: 1
                        }, {
                            label: "{{ __('Outflows') }}",
                            data: datasets_outflows,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });


                new Chart(account, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets[0]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        @endsection
</x-app-layout>
