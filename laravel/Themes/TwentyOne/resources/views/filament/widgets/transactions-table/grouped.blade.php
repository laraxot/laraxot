@php
    $groupedTransactions = collect([
        '2025-08-07' => [
            (object)[
                'id' => 101,
                'description' => 'Bought Solana Prediction',
                'amount' => '+120.50',
                'created_at' => now()->setTime(14, 23),
            ],
            (object)[
                'id' => 102,
                'description' => 'Sold Solana Prediction',
                'amount' => '-75.00',
                'created_at' => now()->setTime(16, 10),
            ],
        ],
        '2025-08-06' => [
            (object)[
                'id' => 99,
                'description' => 'Reward Payout',
                'amount' => '+50.00',
                'created_at' => now()->setTime(10, 45),
            ],
        ],
    ]);

    // Simulate balance per day
    $dailyBalances = [
        '2025-08-07' => '9.733,80',
        '2025-08-06' => '9.650,00',
    ];
@endphp

@foreach ($groupedTransactions as $date => $transactions)
    <div class="mb-6 bg-gray-50 rounded-md shadow-sm p-4">
        <!-- Header: Date and Balance -->
        <div class="flex justify-between items-center border-b pb-2 mb-3">
            <div class="text-gray-800 font-semibold text-sm">{{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}</div>
            <div class="text-gray-600 text-sm">Balance : <span class="font-bold text-black">{{ $dailyBalances[$date] ?? '0,00' }}</span></div>
        </div>

        <!-- Transactions List -->
        @foreach ($transactions as $transaction)
            <div class="flex items-center justify-between py-2">
                <!-- Time -->
                <div class="text-xs text-gray-500 w-16">
                    {{ $transaction->created_at->format('g:i a') }}
                </div>

                <!-- Status (fake "Won" if positive amount) -->
                <div class="px-2 py-1 rounded-full text-xs font-semibold mr-4
                    {{ str_starts_with($transaction->amount, '+') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ str_starts_with($transaction->amount, '+') ? 'Won' : 'Lost' }}
                </div>

                <!-- Description -->
                <div class="text-sm text-gray-800 flex-1">
                    {{ $transaction->description }}
                </div>

                <!-- Amount -->
                <div class="text-sm font-semibold ml-4 
                    {{ str_starts_with($transaction->amount, '+') ? 'text-green-600' : 'text-red-600' }}">
                    {{ str_replace('.', ',', $transaction->amount) }} <span class="text-xs">ø</span>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
