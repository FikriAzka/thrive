<!-- All Meetings Content -->
<div id="all-meetings" class="meeting-content {{ !request('meeting_id') ? 'block' : 'hidden' }}">
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
        <h1 class="text-xl font-semibold text-gray-800">Customer Satisfaction Score - All Meetings</h1>

        @php
            // Calculation for all meetings
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

            $satisfactionCounts = [
                'Very Dissatisfied' => 0,
                'Dissatisfied' => 0,
                'Neutral' => 0,
                'Satisfied' => 0,
                'Very Satisfied' => 0,
            ];

            foreach ($ratings as $rating) {
                $averageScore = collect(range(1, 8))->map(fn($i) => $rating["pertanyaan{$i}"])->avg();
                $percentage = ($averageScore / 5) * 100;

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

            $totalRespondents = $ratings->count();
            $satisfiedTotal = $satisfactionCounts['Very Satisfied'] + $satisfactionCounts['Satisfied'];
            $csatScore = $totalRespondents > 0 ? ($satisfiedTotal / $totalRespondents) * 100 : 0;

            // Calculate percentages
            $percentVeryDissatisfied =
                $totalRespondents > 0 ? ($satisfactionCounts['Very Dissatisfied'] / $totalRespondents) * 100 : 0;
            $percentDissatisfied =
                $totalRespondents > 0 ? ($satisfactionCounts['Dissatisfied'] / $totalRespondents) * 100 : 0;
            $percentNeutral = $totalRespondents > 0 ? ($satisfactionCounts['Neutral'] / $totalRespondents) * 100 : 0;
            $percentSatisfied =
                $totalRespondents > 0 ? ($satisfactionCounts['Satisfied'] / $totalRespondents) * 100 : 0;
            $percentVerySatisfied =
                $totalRespondents > 0 ? ($satisfactionCounts['Very Satisfied'] / $totalRespondents) * 100 : 0;
        @endphp

        <!-- Score Summary -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Customer Satisfaction Score</h3>
            <div class="flex gap-4 mt-4">
                @foreach ($satisfactionCounts as $label => $count)
                    <div class="flex items-center gap-2">
                        <span
                            class="w-3 h-3 rounded-full {{ $label === 'Very Satisfied' ? 'bg-teal-500' : ($label === 'Satisfied' ? 'bg-green-400' : ($label === 'Neutral' ? 'bg-yellow-400' : ($label === 'Dissatisfied' ? 'bg-orange-400' : 'bg-red-500'))) }}"></span>
                        <span class="text-sm">{{ $label }} ({{ $count }})</span>
                    </div>
                @endforeach
                <div class="ml-auto">
                    <span class="font-semibold text-lg">Total Score: {{ number_format($csatScore, 2) }}%</span>
                </div>
            </div>
        </div>

        <!-- Score Bar Chart -->
        <div class="space-y-2 mt-4">
            @foreach ([['label' => 'Very Dissatisfied', 'percentage' => $percentVeryDissatisfied, 'color' => 'bg-red-500'], ['label' => 'Dissatisfied', 'percentage' => $percentDissatisfied, 'color' => 'bg-orange-400'], ['label' => 'Neutral', 'percentage' => $percentNeutral, 'color' => 'bg-yellow-400'], ['label' => 'Satisfied', 'percentage' => $percentSatisfied, 'color' => 'bg-green-400'], ['label' => 'Very Satisfied', 'percentage' => $percentVerySatisfied, 'color' => 'bg-teal-500']] as $item)
                <div class="flex items-center gap-4">
                    <div class="w-32 text-sm text-gray-600">{{ $item['label'] }}</div>
                    <div class="flex-1 bg-gray-100 rounded-full h-6">
                        <div class="{{ $item['color'] }} h-full rounded-full" style="width: {{ $item['percentage'] }}%">
                        </div>
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format($item['percentage'], 2) }}%</span>
                </div>
            @endforeach
        </div>

        <!-- Responses Table -->
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6" x-data="{ searchTerm: '' }">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">CSAT Responses</h2>
                    <p class="text-sm text-gray-500">
                        Showing of {{ $ratings->count() }} total responses
                    </p>
                </div>
                <input type="text" x-model="searchTerm" placeholder="Search Respondents"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
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
                                $percentage = ($score / 5) * 100;

                                if ($percentage >= 80) {
                                    $category = 'Very Satisfied';
                                    $emote = '😊';
                                } elseif ($percentage >= 60) {
                                    $category = 'Satisfied';
                                    $emote = '😄';
                                } elseif ($percentage >= 40) {
                                    $category = 'Neutral';
                                    $emote = '😐';
                                } elseif ($percentage >= 20) {
                                    $category = 'Dissatisfied';
                                    $emote = '😞';
                                } else {
                                    $category = 'Very Dissatisfied';
                                    $emote = '😢';
                                }
                            @endphp
                            <tr class="rating-row hover:bg-gray-50 cursor-pointer" data-rating-id="{{ $rating->id }}"
                                x-show="searchTerm === '' || 
                                         '{{ $rating->name }}'.toLowerCase().includes(searchTerm.toLowerCase()) || 
                                         '{{ $rating->phone }}'.toLowerCase().includes(searchTerm.toLowerCase())">
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
                                    <span class="text-sm text-gray-600">{{ $category }}
                                        {{ $emote }} ({{ number_format($percentage, 2) }}%)</span>
                                </td>
                            </tr>
                            <tr class="details-row hidden bg-gray-50" data-rating-id="{{ $rating->id }}">
                                <td colspan="7" class="px-6 py-4">
                                    <div class="grid grid-cols-2 gap-8">
                                        <!-- Kolom Kiri - Detail Pertanyaan -->
                                        <div class="bg-white rounded-lg shadow-sm p-4">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail
                                                Pertanyaan</h3>
                                            <table class="w-full">
                                                <tbody class="divide-y divide-gray-100">
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600 w-3/4">1. Berikan
                                                            penilaian Anda secara keseluruhan terhadap Specialist yang
                                                            membantu proses
                                                            onboarding</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan1 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan1 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan1 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">2. Bagaimana penilaian
                                                            Anda terhadap keramahan, kedisiplinan, dan sopan santun yang
                                                            ditunjukkan oleh tim Specialist kami dalam memberikan
                                                            pelayanan?</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan2 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan2 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan2 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">3. Bagaimana penilaian
                                                            Anda terhadap product knowledge yang dimiliki oleh
                                                            Specialist kami
                                                            dalam membantu Anda selama proses onboarding?</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan3 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan3 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan3 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">4. Bagaimana penilaian
                                                            Anda terhadap penyelesaian masalah dan solusi yang diberikan
                                                            Specialist kami dalam membantu kesuksesan proses onboarding?
                                                        </td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan4 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan4 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan4 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">5. Bagaimana penilaian
                                                            Anda terhadap cara komunikasi dari Specialist kami baik
                                                            verbal maupun
                                                            non-verbal?</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan5 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan5 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan5 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">6. Penilaian Produk
                                                        </td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan6 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan6 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan6 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">7. Bagaimana penilaian
                                                            Anda terhadap ketepatan waktu yang dijanjikan Specialist
                                                            kami dalam
                                                            menyelesaikan proses onboarding?</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan7 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan7 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan7 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-2.5 text-sm text-gray-600">8. Bagaimana penilaian
                                                            Anda terhadap keseluruhan proses onboarding yang telah
                                                            dilakukan?</td>
                                                        <td class="py-2.5">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $rating->pertanyaan8 >= 4 ? 'bg-green-100 text-green-800' : ($rating->pertanyaan8 >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                {{ $rating->pertanyaan8 }}/5
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Kolom Kanan - Tambahan Informasi -->
                                        <div class="bg-white rounded-lg shadow-sm p-4">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Tambahan
                                                Informasi</h3>
                                            <div class="space-y-4">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Saran untuk
                                                        Peningkatan Layanan:</h4>
                                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                                        {{ $rating->suggestions ?: 'Tidak ada saran yang diberikan' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Masukan Tambahan:
                                                    </h4>
                                                    <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                                        {{ $rating->suggestions2 ?: 'Tidak ada masukan tambahan' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
