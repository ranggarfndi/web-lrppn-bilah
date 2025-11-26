{{-- File: resources/views/pasien/riwayat.blade.php (FINAL - Ditambah Status Saat Ini) --}}

<x-app-layout>
    {{-- <x-slot name="header"> ... </x-slot> Dihapus --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- === JUDUL HALAMAN === --}}
            <div>
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                    Riwayat Perkembangan Saya
                </h2>
                <p class="mt-1 text-gray-600">
                    Tinjau semua catatan, hasil tes, dan grafik perkembangan Anda.
                </p>
            </div>
            {{-- === AKHIR JUDUL HALAMAN === --}}

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-500 to-indigo-500">
                    <h3 class="text-lg leading-6 font-semibold text-white">
                        Status & Program Anda Saat Ini
                    </h3>
                </div>
                <div class="p-6 text-gray-900">
                    @php
                        // Ambil status terbaru dari koleksi yang sudah di-load
                        // Relasi 'riwayatStatus' sudah diurutkan 'desc' dari Model User
                        $statusTerkini = $pasien->riwayatStatus->first();
                    @endphp

                    @if ($statusTerkini)
                        <p class="text-2xl font-bold text-indigo-600">
                            {{ $statusTerkini->program_baru }}
                        </p>
                        <p class="text-sm font-medium text-gray-800">
                            Status: <span class="font-bold">{{ $statusTerkini->status_baru }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            Diperbarui: {{ $statusTerkini->created_at->isoFormat('LL') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">Belum ada status yang ditetapkan.</p>
                    @endif
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        Grafik Perkembangan (Tes Likert)
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Visualisasi skor tes Anda dari waktu ke waktu.
                    </p>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="h-64 relative">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>

            @if ($pasien->hasilKlasifikasi)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Hasil Klasifikasi Awal Anda
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Rekomendasi program awal Anda pada
                            {{ $pasien->hasilKlasifikasi->created_at->isoFormat('LL') }}.
                        </p>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- === TAMBAHKAN BAGIAN INI === --}}
                        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-bold text-indigo-800 mb-2 uppercase tracking-wider">Data Penggunaan Awal Anda</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-indigo-500 uppercase">Jenis NAPZA</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $pasien->hasilKlasifikasi->data_input_json['jenis_napza'] ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 uppercase">Lama Penggunaan</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $pasien->hasilKlasifikasi->data_input_json['lama_penggunaan'] ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- === AKHIR TAMBAHAN === --}}
                        <div>
                            <span class="text-sm font-medium text-gray-500">Rekomendasi Program</span>
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ $pasien->hasilKlasifikasi->rekomendasi_program }}
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-md p-4">
                            <p class="text-sm text-gray-700">
                                <strong>Catatan Sistem:</strong> {{ $pasien->hasilKlasifikasi->catatan_sistem }}
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Prediksi KNN</span>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $pasien->hasilKlasifikasi->prediksi_knn }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Prediksi Naive Bayes</span>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $pasien->hasilKlasifikasi->prediksi_nb }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        Riwayat Status & Program Anda
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Catatan program rehabilitasi Anda dari waktu ke waktu.
                    </p>
                </div>
                <div class="p-6 space-y-4">
                    <ul class="divide-y divide-gray-200">
                        @forelse ($pasien->riwayatStatus as $status)
                            <li class="py-4 filterable-card"
                                data-searchable-content="{{ strtolower($status->program_baru . ' ' . $status->status_baru . ' ' . $status->faktor_penyebab) }}">
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $status->created_at->isoFormat('LLLL') }}
                                </p>
                                <p class="mt-2 text-lg font-bold text-indigo-600">
                                    Program: {{ $status->program_baru }}
                                </p>
                                <p class="text-sm font-medium text-gray-800">
                                    Status: <span class="font-bold">{{ $status->status_baru }}</span>
                                </p>
                                <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                    <p class="text-xs font-medium text-gray-500">Faktor Penyebab:</p>
                                    <p class="text-sm text-gray-700">{{ $status->faktor_penyebab }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada riwayat
                                status.</li>
                        @endforelse
                    </ul>
                </div>
            </div>


            <div class="bg-white p-4 shadow-lg sm:rounded-lg">
                <label for="timelineSearch" class="block text-sm font-medium text-gray-700">Cari Riwayat</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="timelineSearch"
                        class="block w-full pl-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        placeholder="Ketik kata kunci (misal: 'optimis', 'Rawat Jalan', 'SOAP')...">
                </div>
                <p id="search-results-count" class="mt-2 text-sm text-gray-500" style="display: none;"></p>
            </div>
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        Riwayat Tes Perkembangan
                    </h3>
                </div>
                <div class="p-6">
                    <ul class="divide-y divide-gray-200" id="likert-list">
                        @forelse ($pasien->tesLikertHasil as $tes)
                            <li class="py-4 filterable-card"
                                data-searchable-content="skor {{ $tes->total_skor }} {{ $tes->created_at->isoFormat('LL') }}">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-medium text-gray-600">{{ $tes->created_at->isoFormat('LL') }}</span>
                                    <span class="text-2xl font-bold text-green-600">
                                        Skor: {{ $tes->total_skor }} / {{ $tes->skor_maksimal }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada data tes
                                perkembangan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        Riwayat Catatan Konselor (SOAP)
                    </h3>
                </div>
                <div class="p-6 space-y-6" id="soap-list">
                    @forelse ($pasien->catatanSoap as $catatan)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 hover:shadow-md filterable-card"
                            data-searchable-content="{{ strtolower($catatan->subjective . ' ' . $catatan->objective . ' ' . $catatan->assessment . ' ' . $catatan->plan . ' ' . $catatan->created_at->isoFormat('LLLL')) }}">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $catatan->created_at->isoFormat('LLLL') }}</p>
                                <p class="text-sm text-gray-500">Ditulis oleh: Staf LRPPN</p>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="text-sm searchable-text">
                                    <p class="font-semibold text-gray-600">S (Subjective):</p>
                                    <p class="text-gray-800 pl-2">{{ $catatan->subjective }}</p>
                                </div>
                                <div class="text-sm searchable-text">
                                    <p class="font-semibold text-gray-600">O (Objective):</p>
                                    <p class="text-gray-800 pl-2">{{ $catatan->objective }}</p>
                                </div>
                                <div class="text-sm searchable-text">
                                    <p class="font-semibold text-gray-600">A (Assessment):</p>
                                    <p class="text-gray-800 pl-2">{{ $catatan->assessment }}</p>
                                </div>
                                <div class="text-sm searchable-text">
                                    <p class="font-semibold text-gray-600">P (Plan):</p>
                                    <p class="text-gray-800 pl-2">{{ $catatan->plan }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada catatan dari
                            konselor.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Chart dan Search --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // === LOGIKA UNTUK CHART ===
                fetch('{{ route('pasien.riwayat.chart') }}')
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('progressChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Total Skor Perkembangan',
                                    data: data.data,
                                    borderColor: 'rgb(37, 99, 235)',
                                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                    fill: true,
                                    tension: 0.1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching chart data:', error));


                // === LOGIKA UNTUK SEARCH BAR ===
                const searchInput = document.getElementById('timelineSearch');
                const resultsCount = document.getElementById('search-results-count');
                const cards = document.querySelectorAll('.filterable-card');
                const emptyMessages = document.querySelectorAll('.filterable-card-empty');

                searchInput.addEventListener('keyup', function() {
                    const searchTerm = searchInput.value.toLowerCase();
                    let visibleCount = 0;

                    cards.forEach(card => {
                        // Perbarui data-searchable-content untuk menyertakan riwayat status
                        const content = (card.dataset.searchableContent || '').toLowerCase();
                        if (content.includes(searchTerm)) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (searchTerm.length > 0) {
                        resultsCount.textContent = `${visibleCount} hasil ditemukan.`;
                        resultsCount.style.display = 'block';
                    } else {
                        resultsCount.style.display = 'none';
                    }

                    emptyMessages.forEach(msg => {
                        if (searchTerm.length > 0) {
                            // Sembunyikan pesan "empty" hanya jika daftar induknya sedang dicari
                            if (msg.closest('#likert-list') && document.querySelectorAll(
                                    '#likert-list .filterable-card[style*="display: none"]').length >
                                0) {
                                msg.style.display = 'none';
                            } else if (msg.closest('#soap-list') && document.querySelectorAll(
                                    '#soap-list .filterable-card[style*="display: none"]').length > 0) {
                                msg.style.display = 'none';
                            } else if (msg.closest('ul') && msg.closest('ul').querySelectorAll(
                                    '.filterable-card[style*="display: none"]').length > 0) {
                                msg.style.display = 'none';
                            }

                        } else {
                            msg.style.display = '';
                        }
                    });
                });

            });
        </script>
    @endpush
</x-app-layout>
