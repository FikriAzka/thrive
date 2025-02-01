@extends('adminlte::page')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
                <h1 class="text-xl font-semibold text-gray-800">Customer Satisfaction Score</h1>

                <!-- CSAT Breakdown -->
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-sm text-gray-600">CSAT Breakdown</h2>
                        <span class="text-sm text-gray-500">Showing data for {{ $ratings->count() }} respondents</span>
                    </div>

                    @php
                        // Hitung rata-rata dari 8 pertanyaan untuk setiap responden
                        $averageRatings = $ratings->map(function ($rating) {
                            return collect([
                                $rating->pertanyaan1,
                                $rating->pertanyaan2,
                                $rating->pertanyaan3,
                                $rating->pertanyaan4,
                                $rating->pertanyaan5,
                                $rating->pertanyaan6,
                                $rating->pertanyaan7,
                                $rating->pertanyaan8,
                            ])->avg();
                        });

                        // Hitung jumlah responden dalam setiap kategori kepuasan berdasarkan rata-rata skor
                        $countVeryDissatisfied = $averageRatings->whereBetween(null, [1, 1.9])->count();
                        $countDissatisfied = $averageRatings->whereBetween(null, [2, 2.9])->count();
                        $countNeutral = $averageRatings->whereBetween(null, [3, 3.9])->count();
                        $countSatisfied = $averageRatings->whereBetween(null, [4, 4.4])->count();
                        $countVerySatisfied = $averageRatings->whereBetween(null, [4.5, 5])->count();

                        // Total responden
                        $totalRespondents = $ratings->count();

                        // Hitung persentase tiap kategori
                        $percentVeryDissatisfied =
                            $totalRespondents > 0 ? ($countVeryDissatisfied / $totalRespondents) * 100 : 0;
                        $percentDissatisfied =
                            $totalRespondents > 0 ? ($countDissatisfied / $totalRespondents) * 100 : 0;
                        $percentNeutral = $totalRespondents > 0 ? ($countNeutral / $totalRespondents) * 100 : 0;
                        $percentSatisfied = $totalRespondents > 0 ? ($countSatisfied / $totalRespondents) * 100 : 0;
                        $percentVerySatisfied =
                            $totalRespondents > 0 ? ($countVerySatisfied / $totalRespondents) * 100 : 0;

                        // CSAT Score (menggunakan rata-rata semua pertanyaan dikalikan 20 agar berbentuk persentase)
                        $csatScore = $averageRatings->avg() * 20;
                    @endphp

                    <!-- Score Summary -->
                    <!-- Score Summary (Digabungkan Semua Pertanyaan) -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Customer Satisfaction Score</h3>
                        <div class="flex gap-4 mt-4">
                            @php
                                $totalRespondents = $ratings->count(); // Jumlah total responden
                                $satisfactionCounts = [
                                    'Very Dissatisfied' => 0,
                                    'Dissatisfied' => 0,
                                    'Neutral' => 0,
                                    'Satisfied' => 0,
                                    'Very Satisfied' => 0,
                                ];

                                // Kategorikan responden berdasarkan rata-rata skor mereka
                                foreach ($ratings as $rating) {
                                    // Hitung rata-rata skor responden dari 8 pertanyaan
                                    $averageScore = collect(range(1, 8))
                                        ->map(fn($i) => $rating["pertanyaan{$i}"])
                                        ->avg();

                                    // Tentukan kategori berdasarkan rata-rata skor
                                    $percentage = ($averageScore / 5) * 100; // Menghitung persentase (skor 5 adalah 100%)

                                    if ($percentage >= 80) {
                                        $satisfactionCounts['Very Satisfied']++;
                                    } elseif ($percentage >= 60) {
                                        $satisfactionCounts['Satisfied']++;
                                    } elseif ($percentage >= 40) {
                                        $satisfactionCounts['Neutral']++;
                                    } elseif ($percentage >= 20) {
                                        $satisfactionCounts['Dissatisfied']++;
                                    } else {
                                        $satisfactionCounts['Very Dissatisfied']++;
                                    }
                                }

                                // CSAT dihitung dari total responden yang sangat puas atau puas
                                $satisfiedTotal =
                                    $satisfactionCounts['Very Satisfied'] + $satisfactionCounts['Satisfied'];
                                $csatScore = $totalRespondents > 0 ? ($satisfiedTotal / $totalRespondents) * 100 : 0;
                            @endphp

                            <!-- Tampilkan kategori dan jumlah responden di setiap kategori -->
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-sm">Very Dissatisfied
                                    ({{ $satisfactionCounts['Very Dissatisfied'] }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                <span class="text-sm">Dissatisfied ({{ $satisfactionCounts['Dissatisfied'] }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="text-sm">Neutral ({{ $satisfactionCounts['Neutral'] }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                                <span class="text-sm">Satisfied ({{ $satisfactionCounts['Satisfied'] }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                                <span class="text-sm">Very Satisfied ({{ $satisfactionCounts['Very Satisfied'] }})</span>
                            </div>

                            <div class="ml-auto">
                                <span class="font-semibold text-lg">Total Score: {{ number_format($csatScore, 2) }}%</span>
                            </div>
                        </div>
                    </div>



                    <!-- Score Bar Chart -->
                    <div class="space-y-2 mt-4">
                        @php
                            // Menghitung total responden
                            $totalRespondents = $ratings->count();

                            // Menghitung jumlah responden di setiap kategori
                            $satisfactionCounts = [
                                'Very Dissatisfied' => 0,
                                'Dissatisfied' => 0,
                                'Neutral' => 0,
                                'Satisfied' => 0,
                                'Very Satisfied' => 0,
                            ];

                            // Mengkategorikan responden berdasarkan skor rata-rata mereka
                            foreach ($ratings as $rating) {
                                $averageScore = collect(range(1, 8))->map(fn($i) => $rating["pertanyaan{$i}"])->avg();
                                $percentage = ($averageScore / 5) * 100; // Menghitung persentase

                                // Tentukan kategori berdasarkan persentase
                                if ($percentage >= 80) {
                                    $satisfactionCounts['Very Satisfied']++;
                                } elseif ($percentage >= 60) {
                                    $satisfactionCounts['Satisfied']++;
                                } elseif ($percentage >= 40) {
                                    $satisfactionCounts['Neutral']++;
                                } elseif ($percentage >= 20) {
                                    $satisfactionCounts['Dissatisfied']++;
                                } else {
                                    $satisfactionCounts['Very Dissatisfied']++;
                                }
                            }

                            // Menghitung persentase masing-masing kategori
                            $percentVeryDissatisfied =
                                $totalRespondents > 0
                                    ? ($satisfactionCounts['Very Dissatisfied'] / $totalRespondents) * 100
                                    : 0;
                            $percentDissatisfied =
                                $totalRespondents > 0
                                    ? ($satisfactionCounts['Dissatisfied'] / $totalRespondents) * 100
                                    : 0;
                            $percentNeutral =
                                $totalRespondents > 0 ? ($satisfactionCounts['Neutral'] / $totalRespondents) * 100 : 0;
                            $percentSatisfied =
                                $totalRespondents > 0
                                    ? ($satisfactionCounts['Satisfied'] / $totalRespondents) * 100
                                    : 0;
                            $percentVerySatisfied =
                                $totalRespondents > 0
                                    ? ($satisfactionCounts['Very Satisfied'] / $totalRespondents) * 100
                                    : 0;
                        @endphp

                        @foreach ([['label' => 'Very Dissatisfied', 'percentage' => $percentVeryDissatisfied, 'color' => 'bg-red-500'], ['label' => 'Dissatisfied', 'percentage' => $percentDissatisfied, 'color' => 'bg-orange-400'], ['label' => 'Neutral', 'percentage' => $percentNeutral, 'color' => 'bg-yellow-400'], ['label' => 'Satisfied', 'percentage' => $percentSatisfied, 'color' => 'bg-green-400'], ['label' => 'Very Satisfied', 'percentage' => $percentVerySatisfied, 'color' => 'bg-teal-500']] as $item)
                            <div class="flex items-center gap-4">
                                <div class="w-32 text-sm text-gray-600">{{ $item['label'] }}</div>
                                <div class="flex-1 bg-gray-100 rounded-full h-6">
                                    <div class="{{ $item['color'] }} h-full rounded-full"
                                        style="width: {{ $item['percentage'] }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ number_format($item['percentage'], 2) }}%</span>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- Responses Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">CSAT Responses</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-sky-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Respondents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    PIC</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Comments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Average Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ratings as $rating)
                                @php
                                    $averageScore = collect([
                                        $rating->pertanyaan1,
                                        $rating->pertanyaan2,
                                        $rating->pertanyaan3,
                                        $rating->pertanyaan4,
                                        $rating->pertanyaan5,
                                        $rating->pertanyaan6,
                                        $rating->pertanyaan7,
                                        $rating->pertanyaan8,
                                    ])->avg();
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $rating->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $rating->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rating->created_at->setTimezone('Asia/Jakarta')->format('M d, Y, h:i A') }}

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rating->project_or_product ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rating->pic }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $rating->suggestions || $rating->suggestions2 ? "$rating->suggestions, $rating->suggestions2" : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($averageScore, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            // Misalnya pertanyaan1 adalah nilai skor untuk responden ini.
                                            $score =
                                                ($rating->pertanyaan1 +
                                                    $rating->pertanyaan2 +
                                                    $rating->pertanyaan3 +
                                                    $rating->pertanyaan4 +
                                                    $rating->pertanyaan5 +
                                                    $rating->pertanyaan6 +
                                                    $rating->pertanyaan7 +
                                                    $rating->pertanyaan8) /
                                                8;

                                            // Menghitung persentase CSAT (misalnya, total score dibagi 5, lalu dikali 100)
                                            $percentage = ($score / 5) * 100;

                                            // Menentukan kategori dan emote
                                            if ($percentage >= 80) {
                                                $category = 'Very Satisfied';
                                                $emote = '😊'; // Emote untuk Very Satisfied
                                            } elseif ($percentage >= 60) {
                                                $category = 'Satisfied';
                                                $emote = '😄'; // Emote untuk Satisfied
                                            } elseif ($percentage >= 40) {
                                                $category = 'Neutral';
                                                $emote = '😐'; // Emote untuk Neutral
                                            } elseif ($percentage >= 20) {
                                                $category = 'Dissatisfied';
                                                $emote = '😞'; // Emote untuk Dissatisfied
                                            } else {
                                                $category = 'Very Dissatisfied';
                                                $emote = '😢'; // Emote untuk Very Dissatisfied
                                            }
                                        @endphp

                                        <span class="text-sm text-gray-600">{{ $category }} {{ $emote }}
                                            ({{ number_format($percentage, 2) }}%)</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Get all th elements
        const ths = document.querySelectorAll('th');
        let sortDirection = 1; // 1 for ascending, -1 for descending

        // Add click event listeners to all th elements
        ths.forEach((th, index) => {
            th.style.cursor = 'pointer';

            // Add sort indicators
            const indicator = document.createElement('span');
            indicator.innerHTML = ' ↕️';
            indicator.style.opacity = '0.3';
            th.appendChild(indicator);

            th.addEventListener('click', () => {
                // Reset all indicators
                ths.forEach(header => {
                    header.querySelector('span').innerHTML = ' ↕️';
                    header.querySelector('span').style.opacity = '0.3';
                });

                // Update clicked header indicator
                indicator.style.opacity = '1';
                indicator.innerHTML = sortDirection === 1 ? ' ↓' : ' ↑';

                // Get the table body
                const tbody = th.closest('table').querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Sort the rows
                rows.sort((a, b) => {
                    let aValue = a.querySelectorAll('td')[index].textContent.trim();
                    let bValue = b.querySelectorAll('td')[index].textContent.trim();

                    // Handle date sorting
                    if (index === 1) { // Date column
                        aValue = new Date(aValue);
                        bValue = new Date(bValue);
                    }
                    // Handle numeric sorting
                    else if (index === 5) { // Average Score column
                        aValue = parseFloat(aValue);
                        bValue = parseFloat(bValue);
                    }
                    // Handle percentage sorting
                    else if (index === 6) { // Score column
                        aValue = parseFloat(aValue.match(/\d+\.\d+/)[0]);
                        bValue = parseFloat(bValue.match(/\d+\.\d+/)[0]);
                    }

                    if (aValue < bValue) return -1 * sortDirection;
                    if (aValue > bValue) return 1 * sortDirection;
                    return 0;
                });

                // Update the table
                rows.forEach(row => tbody.appendChild(row));

                // Toggle sort direction for next click
                sortDirection *= -1;
            });
        });
    </script>
@stop

@section('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

@stop
