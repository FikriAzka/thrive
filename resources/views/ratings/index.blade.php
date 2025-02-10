@extends('adminlte::page')

@section('content')
    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200" x-data="{ isOpen: false, selectedMeeting: '{{ request('meeting_id') }}', activeTab: '{{ request('meeting_id') ? 'specific' : 'all' }}' }">
                <nav class="flex -mb-px">
                    <!-- All Meetings Tab with Indicator -->
                    <a href="#"
                        class="tab-link whitespace-nowrap pb-2 px-3 border-b text-xs font-medium flex items-center gap-2 {{ !request('meeting_id') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        @click="activeTab = 'all'" data-target="all-meetings">
                        <span>Semua Meeting</span>
                        <!-- Indicator Dot -->
                        <span x-show="activeTab === 'all'" class="w-2 h-2 rounded-full bg-blue-500">
                        </span>
                    </a>

                    <!-- Dropdown Button dengan Indicator -->
                    <div class="relative">
                        <button @click="isOpen = !isOpen"
                            class="whitespace-nowrap pb-2 px-3 border-b text-xs font-medium flex items-center gap-2"
                            :class="selectedMeeting ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                            <span
                                x-text="selectedMeeting ? $refs.meetingList.querySelector(`[data-id='${selectedMeeting}']`).textContent : 'Pilih Meeting'">
                                Pilih Meeting
                            </span>
                            <!-- Indicator Dot -->
                            <span x-show="activeTab === 'specific'" class="w-2 h-2 rounded-full bg-blue-500">
                            </span>
                            <svg :class="isOpen ? 'rotate-180' : ''" class="w-3 h-3 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="isOpen" x-ref="meetingList" @click.away="isOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1 max-h-60 overflow-auto">
                                @foreach ($ratings->groupBy('meeting_id') as $meetingId => $meetingRatings)
                                    <a href="#" data-id="{{ $meetingId }}"
                                        class="tab-link px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center justify-between {{ request('meeting_id') == $meetingId ? 'bg-blue-50 text-blue-600' : '' }}"
                                        data-target="meeting-{{ $meetingId }}"
                                        @click="selectedMeeting = '{{ $meetingId }}'; activeTab = 'specific'; isOpen = false">
                                        <span>{{ $meetingRatings->first()->meeting->nama_rapat }}</span>
                                        <!-- Selected Meeting Indicator -->
                                        <span x-show="selectedMeeting === '{{ $meetingId }}'"
                                            class="w-2 h-2 rounded-full bg-blue-500">
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Content Sections -->
            @include('ratings.all-meetings', ['ratings' => $ratings])

            @foreach ($ratings->groupBy('meeting_id') as $meetingId => $meetingRatings)
                @include('ratings.single-meeting', [
                    'meetingId' => $meetingId,
                    'meetingRatings' => $meetingRatings,
                ])
            @endforeach
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const contentDivs = document.querySelectorAll('.meeting-content');

            // Function to initialize table handlers
            function initializeTableHandlers(tableContainer) {
                const tables = tableContainer.querySelectorAll('table');

                tables.forEach(table => {
                    // Remove existing event listeners before adding new ones
                    const newTable = table.cloneNode(true);
                    table.parentNode.replaceChild(newTable, table);

                    // Row click handler
                    newTable.addEventListener('click', function(e) {
                        const row = e.target.closest('.rating-row');
                        if (row) {
                            const ratingId = row.getAttribute('data-rating-id');
                            // Look for details row within the same table
                            const detailsRow = newTable.querySelector(
                                `.details-row[data-rating-id="${ratingId}"]`);

                            if (detailsRow) {
                                detailsRow.classList.toggle('hidden');
                                row.classList.toggle('bg-gray-100');
                            }
                        }
                    });

                    // Sorting functionality remains the same...
                    const ths = newTable.querySelectorAll('th');
                    let sortDirection = 1;

                    ths.forEach((th, index) => {
                        th.style.cursor = 'pointer';

                        // Only add indicator if it doesn't exist
                        if (!th.querySelector('.sort-indicator')) {
                            const indicator = document.createElement('span');
                            indicator.className = 'sort-indicator';
                            indicator.innerHTML = ' ↕️';
                            indicator.style.opacity = '0.3';
                            th.appendChild(indicator);
                        }

                        th.addEventListener('click', () => {
                            // Reset all indicators
                            ths.forEach(header => {
                                const ind = header.querySelector('.sort-indicator');
                                if (ind) {
                                    ind.innerHTML = ' ↕️';
                                    ind.style.opacity = '0.3';
                                }
                            });

                            // Update clicked indicator
                            const indicator = th.querySelector('.sort-indicator');
                            indicator.style.opacity = '1';
                            indicator.innerHTML = sortDirection === 1 ? ' ↓' : ' ↑';

                            const tbody = th.closest('table').querySelector('tbody');
                            const rowPairs = [];
                            const mainRows = tbody.querySelectorAll('tr.rating-row');

                            mainRows.forEach(mainRow => {
                                const ratingId = mainRow.getAttribute(
                                    'data-rating-id');
                                const detailRow = tbody.querySelector(
                                    `.details-row[data-rating-id="${ratingId}"]`
                                );
                                rowPairs.push({
                                    mainRow,
                                    detailRow,
                                    value: mainRow.querySelectorAll('td')[
                                        index].textContent.trim()
                                });
                            });

                            // Sort the pairs
                            rowPairs.sort((a, b) => {
                                let aValue = a.value;
                                let bValue = b.value;

                                if (index === 1) { // Date column
                                    aValue = new Date(aValue);
                                    bValue = new Date(bValue);
                                } else if (index === 5) { // Average Score column
                                    aValue = parseFloat(aValue);
                                    bValue = parseFloat(bValue);
                                } else if (index === 6) { // Score column
                                    aValue = parseFloat(aValue.match(/\d+\.\d+/)[
                                        0]);
                                    bValue = parseFloat(bValue.match(/\d+\.\d+/)[
                                        0]);
                                }

                                if (aValue < bValue) return -1 * sortDirection;
                                if (aValue > bValue) return 1 * sortDirection;
                                return 0;
                            });

                            // Reattach rows
                            rowPairs.forEach(pair => {
                                tbody.appendChild(pair.mainRow);
                                if (pair.detailRow) {
                                    tbody.appendChild(pair.detailRow);
                                }
                            });

                            sortDirection *= -1;
                        });
                    });
                });
            }

            // Tab click handlers with immediate table initialization
            tabLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Update tab styles
                    tabLinks.forEach(tab => {
                        tab.classList.remove('border-blue-500', 'text-blue-600');
                        tab.classList.add('border-transparent', 'text-gray-500');
                    });

                    link.classList.remove('border-transparent', 'text-gray-500');
                    link.classList.add('border-blue-500', 'text-blue-600');

                    // Hide all content divs
                    contentDivs.forEach(div => div.classList.add('hidden'));

                    // Show selected content immediately
                    const targetId = link.getAttribute('data-target');
                    const targetDiv = document.getElementById(targetId);
                    targetDiv.classList.remove('hidden');

                    // Initialize table handlers immediately without setTimeout
                    initializeTableHandlers(targetDiv);

                    // Update URL
                    const meetingId = targetId.replace('meeting-', '');
                    const newUrl = meetingId === 'all-meetings' ?
                        window.location.pathname :
                        `${window.location.pathname}?meeting_id=${meetingId}`;
                    history.pushState({}, '', newUrl);
                });
            });

            // Initialize handlers for initial content
            initializeTableHandlers(document);

        });
    </script>
@endsection

@section('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection
