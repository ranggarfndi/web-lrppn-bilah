<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Analisis Model (1.399 Data)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TOMBOL TAB & SEARCH --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4 no-print">
                {{-- Form Pencarian (Hanya untuk Tab Tabel) --}}
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex w-full md:w-auto" id="search-form">
                    <input type="text" name="search" placeholder="Cari Nama / ID..." class="rounded-l-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-64" value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">Cari</button>
                </form>

                {{-- Navigation Tabs --}}
                <div class="bg-gray-200 p-1 rounded-lg flex overflow-x-auto">
                    <button onclick="switchTab('klasifikasi')" id="btn-klasifikasi" class="tab-btn px-4 py-2 rounded-md text-sm font-bold shadow bg-white text-gray-800 transition-all whitespace-nowrap">
                        📄 Data Klasifikasi
                    </button>
                    <button onclick="switchTab('numerik')" id="btn-numerik" class="tab-btn px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:bg-gray-300 transition-all whitespace-nowrap">
                        🔢 Konversi Numerik
                    </button>
                    <button onclick="switchTab('grafik')" id="btn-grafik" class="tab-btn px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:bg-gray-300 transition-all whitespace-nowrap">
                        📊 Lampiran Grafik
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                
                {{-- CONTAINER UNTUK TABEL (Tab 1 & 2) --}}
                <div id="tab-container-tabel">
                     {{-- TABEL 1: HASIL KLASIFIKASI --}}
                    <div id="tab-klasifikasi">
                        <div class="p-4 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                            <h3 class="font-bold text-indigo-800">DATA HASIL PREDIKSI ({{ $data->total() }} Data)</h3>
                             <div class="flex gap-2">
                                <button onclick="toggleKolom('all')" class="text-[10px] px-2 py-1 bg-gray-800 text-white rounded">Semua</button>
                                <button onclick="toggleKolom('knn')" class="text-[10px] px-2 py-1 bg-blue-600 text-white rounded">KNN</button>
                                <button onclick="toggleKolom('nb')" class="text-[10px] px-2 py-1 bg-purple-600 text-white rounded">NB</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                             <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pasien</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase col-knn">Hasil KNN</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase col-nb">Hasil NB</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status Akhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Detail Perhitungan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($data as $index => $user)
                                    @php
                                        $hk = $user->hasilKlasifikasi;
                                        $knnVal = $hk->data_input_json['hasil_ai']['knn']['confidence'] ?? 0;
                                        $nbVal = $hk->data_input_json['hasil_ai']['nb']['confidence'] ?? 0;
                                        $hasilAiData = $hk->data_input_json['hasil_ai'] ?? [];
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-xs text-gray-500">{{ $data->firstItem() + $index }}</td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $user->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-4 py-3 col-knn">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $hk->prediksi_knn }}</span>
                                            <div class="text-[10px] text-gray-500">{{ $knnVal }}%</div>
                                        </td>
                                        <td class="px-4 py-3 col-nb">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">{{ $hk->prediksi_nb }}</span>
                                            <div class="text-[10px] text-gray-500">{{ $nbVal }}%</div>
                                        </td>
                                        <td class="px-4 py-3 text-xs font-bold text-green-700">{{ $hk->rekomendasi_program }}</td>
                                        
                                        <td class="px-4 py-3 text-center">
                                            <button onclick='showCalculation(@json($hasilAiData))' class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition-colors duration-200 shadow-sm" title="Lihat Analisis Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TABEL 2: KONVERSI NUMERIK --}}
                    <div id="tab-numerik" style="display: none;">
                         <div class="p-4 bg-gray-100 border-b border-gray-200">
                            <h3 class="font-bold text-gray-800 uppercase">Data Preprocessing (Numerik)</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 font-mono text-sm">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase">Pasien</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">Gender (0/1)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">Lama</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">URICA_Norm</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">Sakit_Norm</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium uppercase">Zat_Norm</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($data as $index => $user)
                                    @php
                                        $matrix = $user->hasilKlasifikasi->data_input_json['hasil_ai']['matrix_nilai'] ?? [];
                                    @endphp
                                    <tr class="hover:bg-yellow-50">
                                        <td class="px-6 py-3 whitespace-nowrap font-sans font-bold">{{ $user->name }}</td>
                                        <td class="px-6 py-3 text-center text-blue-600">{{ $matrix['gender_num'] ?? '-' }}</td>
                                        <td class="px-6 py-3 text-center">{{ $matrix['lama_val'] ?? '-' }}</td>
                                        <td class="px-6 py-3 text-center">{{ $matrix['urica_norm'] ?? '-' }}</td>
                                        <td class="px-6 py-3 text-center">{{ $matrix['sakit_norm'] ?? '-' }}</td>
                                        <td class="px-6 py-3 text-center">{{ $matrix['zat_norm'] ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-4 border-t">{{ $data->links() }}</div>
                </div>

                {{-- ================= TAB 3: LAMPIRAN GRAFIK (STATIS) ================= --}}
                <div id="tab-grafik" style="display: none;" class="p-8 bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center uppercase">Visualisasi Hasil Evaluasi Model</h3>
                    
                    {{-- GRID 1: GRAFIK UMUM --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        
                        {{-- GAMBAR 1: AKURASI --}}
                        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                            <h4 class="text-lg font-bold text-center mb-4 text-gray-700">Perbandingan Akurasi</h4>
                            <img src="{{ asset('images/grafik-akurasi.png') }}" alt="Grafik Akurasi" class="w-full h-auto rounded border">
                        </div>

                        {{-- GAMBAR 2: SCATTER PLOT --}}
                        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                            <h4 class="text-lg font-bold text-center mb-4 text-gray-700">Pola Sebaran Data</h4>
                            <img src="{{ asset('images/grafik-scatter.png') }}" alt="Scatter Plot" class="w-full h-auto rounded border">
                        </div>

                        {{-- GAMBAR 3: CM KNN --}}
                        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                            <h4 class="text-lg font-bold text-center mb-4 text-blue-800">Confusion Matrix - KNN</h4>
                            <img src="{{ asset('images/cm-knn.png') }}" alt="Confusion Matrix KNN" class="w-full h-auto rounded border">
                        </div>

                        {{-- GAMBAR 4: CM NB --}}
                        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                            <h4 class="text-lg font-bold text-center mb-4 text-purple-800">Confusion Matrix - Naive Bayes</h4>
                            <img src="{{ asset('images/cm-nb.png') }}" alt="Confusion Matrix NB" class="w-full h-auto rounded border">
                        </div>

                    </div>

                    {{-- GRID 2: DETAIL EVALUASI --}}
                    <div class="border-t border-gray-300 pt-8 mt-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 text-center uppercase">Detail Evaluasi Kelas (Recall & Precision)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- GAMBAR 5: DETAIL KNN --}}
                            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                                <h4 class="text-lg font-bold text-center mb-4 text-blue-800">Evaluasi Detail - KNN</h4>
                                <img src="{{ asset('images/eval-knn.png') }}" alt="Detail Evaluasi KNN" class="w-full h-auto rounded border">
                            </div>

                            {{-- GAMBAR 6: DETAIL NB --}}
                            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                                <h4 class="text-lg font-bold text-center mb-4 text-purple-800">Evaluasi Detail - Naive Bayes</h4>
                                <img src="{{ asset('images/eval-nb.png') }}" alt="Detail Evaluasi NB" class="w-full h-auto rounded border">
                            </div>

                        </div>
                    </div>

                    <p class="text-center text-gray-500 mt-8 text-sm italic">*Grafik di atas merupakan hasil evaluasi model pada tahap pelatihan dan pengujian.</p>
                </div>

            </div>
        </div>
    </div>

    {{-- INCLUDE MODAL POPUP RUMUS --}}
    <div id="calcModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-indigo-600 px-4 py-3 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">Detail Perhitungan AI</h3>
                    <button type="button" class="text-white hover:text-gray-200" onclick="closeModal()"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto" id="modal-content-body">
                    {{-- Isi di-inject JS --}}
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function switchTab(tabName) {
            const tabs = ['klasifikasi', 'numerik', 'grafik'];
            const tabContainerTabel = document.getElementById('tab-container-tabel');
            const tabGrafik = document.getElementById('tab-grafik');
            const searchForm = document.getElementById('search-form');

            tabs.forEach(t => {
                const btn = document.getElementById('btn-' + t);
                btn.classList.remove('bg-white', 'text-gray-800', 'shadow');
                btn.classList.add('text-gray-600', 'hover:bg-gray-300');
                if(document.getElementById('tab-' + t)) document.getElementById('tab-' + t).style.display = 'none';
            });

            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.add('bg-white', 'text-gray-800', 'shadow');
            activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-300');

            if (tabName === 'grafik') {
                tabContainerTabel.style.display = 'none';
                tabGrafik.style.display = 'block';
                searchForm.style.visibility = 'hidden';
            } else {
                tabContainerTabel.style.display = 'block';
                tabGrafik.style.display = 'none';
                document.getElementById('tab-' + tabName).style.display = 'block';
                searchForm.style.visibility = 'visible';
            }
        }

        function toggleKolom(mode) {
            const knns = document.querySelectorAll('.col-knn');
            const nbs = document.querySelectorAll('.col-nb');
            if(mode == 'all') { knns.forEach(el => el.style.display = ''); nbs.forEach(el => el.style.display = ''); }
            else if (mode == 'knn') { knns.forEach(el => el.style.display = ''); nbs.forEach(el => el.style.display = 'none'); }
            else if (mode == 'nb') { knns.forEach(el => el.style.display = 'none'); nbs.forEach(el => el.style.display = ''); }
        }

        function showCalculation(dataAI) {
             const modal = document.getElementById('calcModal');
             const modalBody = document.getElementById('modal-content-body');
             if (!dataAI) return;

            const matrix = dataAI.matrix_nilai || {};
            const debugKnn = dataAI.debug_knn || [];
            const nbProbs = dataAI.nb && dataAI.nb.probs ? dataAI.nb.probs : {};
            const nbLabel = dataAI.nb && dataAI.nb.label ? dataAI.nb.label : '-';
            
            let knnRows = '';
            if(debugKnn.length > 0) {
                debugKnn.forEach(row => {
                    knnRows += `<tr class="border-b border-blue-100"><td class="py-1 px-2 font-bold text-blue-800">${row.rank}</td><td class="py-1 px-2 font-mono">${row.jarak}</td><td class="py-1 px-2"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white border border-gray-200">${row.label}</span></td></tr>`;
                });
            } else { knnRows = '<tr><td colspan="3" class="text-center py-2 text-gray-400 text-xs">Data tidak tersedia</td></tr>'; }

            let nbList = '';
            const values = Object.values(nbProbs);
            const maxVal = values.length > 0 ? Math.max(...values) : 0;
            for (const [label, prob] of Object.entries(nbProbs)) {
                const isHighest = prob >= maxVal;
                const colorClass = isHighest ? 'text-purple-700 font-bold' : 'text-gray-600';
                const icon = isHighest ? '✅' : '•';
                nbList += `<li class="${colorClass} flex justify-between"><span>${icon} Kelas ${label}:</span><span>${prob}%</span></li>`;
            }

             // [UPDATE 2] MEMPERJELAS TAMPILAN RUMUS KNN DI MODAL
             modalBody.innerHTML = `
                <div class="mb-6"><h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Data Input (Ternormalisasi)</h4><div class="grid grid-cols-5 gap-2 text-center text-sm bg-gray-50 p-3 rounded border"><div><div class="font-bold text-gray-400 text-[10px]">Gender</div><div>${matrix.gender_num ?? '-'}</div></div><div><div class="font-bold text-gray-400 text-[10px]">Lama</div><div>${matrix.lama_val ?? '-'}</div></div><div><div class="font-bold text-gray-400 text-[10px]">URICA</div><div>${matrix.urica_norm ?? '-'}</div></div><div><div class="font-bold text-gray-400 text-[10px]">Penyakit</div><div>${matrix.sakit_norm ?? '-'}</div></div><div><div class="font-bold text-gray-400 text-[10px]">Zat</div><div>${matrix.zat_norm ?? '-'}</div></div></div></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- KOLOM KNN --}}
                    <div class="border rounded-lg p-4 border-blue-200 bg-blue-50/30">
                        <h4 class="text-md font-bold text-blue-800 mb-3 border-b border-blue-200 pb-2">A. ANALISA KNN (Euclidean Distance)</h4>
                        
                        {{-- RUMUS YANG DIPERJELAS --}}
                        <div class="bg-yellow-50 p-4 rounded-lg border border-blue-100 mb-4 shadow-sm">
                            <p class="font-bold mb-2 text-blue-900 text-sm">Rumus Euclidean Distance:</p>
                            <div class="font-mono text-sm bg-white p-3 rounded border border-blue-200 overflow-x-auto text-gray-800 leading-relaxed">
                                <p><span class="font-bold text-lg">d = √</span> <span style="border-top: 2px solid #1e3a8a; padding-top: 4px; display: inline-block;"> [ (x₁-y₁)² + (x₂-y₂)² + (x₃-y₃)² + (x₄-y₄)² + (x₅-y₅)² ]</span></p>
                            </div>
                            <p class="text-[11px] mt-2 text-gray-500 italic">*Akar kuadrat dari total selisih kuadrat 5 fitur (Gender, Lama, URICA, Penyakit, Zat).</p>
                        </div>

                        <table class="min-w-full text-sm bg-white rounded overflow-hidden">
                            <thead class="bg-blue-100"><tr><th class="py-1 px-2 text-left">Rank</th><th class="py-1 px-2 text-left">Jarak</th><th class="py-1 px-2 text-left">Kelas Tetangga</th></tr></thead>
                            <tbody>${knnRows}</tbody>
                        </table>
                    </div>

                    {{-- KOLOM NB --}}
                    <div class="border rounded-lg p-4 border-purple-200 bg-purple-50/30">
                        <h4 class="text-md font-bold text-purple-800 mb-3 border-b border-purple-200 pb-2">B. ANALISA NAIVE BAYES</h4>
                        <div class="text-xs font-mono bg-white p-3 rounded border border-gray-200 text-gray-700 mb-4 space-y-2"><div><p class="font-bold">1. Teorema Bayes:</p>P(C|X) = P(X|C) * P(C) / P(X)</div></div>
                        <h5 class="text-xs font-bold text-gray-600 mb-2">Hasil Probabilitas Akhir:</h5>
                        <ul class="space-y-1 text-sm bg-white p-2 rounded border">${nbList}</ul>
                        <div class="mt-4 p-2 bg-purple-100 rounded text-center border border-purple-200"><span class="text-xs text-purple-600 font-bold uppercase">Prediksi Akhir NB:</span><div class="text-xl font-extrabold text-purple-800">${nbLabel}</div></div>
                    </div>
                </div>
             `;
             modal.classList.remove('hidden');
        }
        function closeModal() { document.getElementById('calcModal').classList.add('hidden'); }
        
        // Default tab
        switchTab('klasifikasi');
    </script>
</x-app-layout>