<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- === JUDUL HALAMAN === --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __('Riwayat Pasien') }}
                    </h2>
                    <p class="mt-1 text-gray-600">
                        Detail lengkap untuk: <span class="font-medium text-gray-900">{{ $user->name }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.pasien.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                    &larr; Kembali ke Daftar Pasien
                </a>
            </div>

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 shadow-md rounded-lg" role="alert">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI (Profil & Menu) --}}
                <div class="md:col-span-1 space-y-6">
                    
                    {{-- 1. PROFIL PASIEN --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Profil Pasien
                            </h3>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
                                </div>
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                                </div>
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Tgl Lahir</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $user->profil?->tgl_lahir ? \Carbon\Carbon::parse($user->profil->tgl_lahir)->isoFormat('LL') : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $user->profil?->alamat ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nama Wali</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $user->profil?->nama_wali ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">No. Wali</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $user->profil?->no_telepon_wali ?? 'N/A' }}
                                    </dd>
                                </div>
                                {{-- [BARU] Menampilkan Skor URICA di Profil --}}
                                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-indigo-50">
                                    <dt class="text-sm font-medium text-indigo-700">Skor URICA</dt>
                                    <dd class="mt-1 text-sm font-bold text-indigo-900 sm:mt-0 sm:col-span-2">
                                        {{ $user->profil?->urica_score ? number_format($user->profil->urica_score, 2) : 'N/A' }}
                                        <span class="text-xs font-normal text-gray-500">(Skala 1-5)</span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- 2. STATUS TERKINI --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-500 to-indigo-500">
                            <h3 class="text-lg leading-6 font-semibold text-white">
                                Status & Program Saat Ini
                            </h3>
                        </div>
                        <div class="p-6 text-gray-900">
                            @php
                                $statusTerkini = $user->riwayatStatus->first();
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

                    {{-- 3. TOMBOL MENU --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Input Perkembangan
                            </h3>
                        </div>
                        <div class="p-6 flex flex-col space-y-4">
                            <a href="{{ route('admin.soap.create', $user->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                                + Tambah Catatan SOAP
                            </a>
                            <a href="{{ route('admin.likert.create', $user->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                                + Isi Tes Likert
                            </a>
                            <div class="border-t pt-4">
                                <a href="{{ route('admin.status.create', $user->id) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px w-full">
                                    Ubah Status/Program
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN (Grafik, History, Search) --}}
                <div class="md:col-span-2 space-y-6">

                    {{-- 1. GRAFIK --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Grafik Perkembangan (Tes Likert)
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Visualisasi skor tes dari waktu ke waktu.
                            </p>
                        </div>
                        <div class="p-6 text-gray-900">
                            <div class="h-64 relative">
                                <canvas id="progressChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- 2. HASIL KLASIFIKASI (KOMPARASI AI) --}}
                    @if ($user->hasilKlasifikasi)
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                    Hasil Analisa & Klasifikasi AI
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Dihitung pada: {{ $user->hasilKlasifikasi->created_at->isoFormat('LLLL') }}
                                </p>
                            </div>
                            <div class="p-6 space-y-6">
                                
                                {{-- A. Data Input Awal --}}
                                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                                    <h4 class="text-xs font-bold text-indigo-800 mb-3 uppercase tracking-wider border-b border-indigo-200 pb-2">
                                        Parameter Input
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <p class="text-xs text-indigo-500 uppercase">Jenis NAPZA</p>
                                            <p class="font-semibold text-gray-900">{{ $user->hasilKlasifikasi->data_input_json['jenis_napza'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-indigo-500 uppercase">Lama Pakai</p>
                                            <p class="font-semibold text-gray-900">{{ $user->hasilKlasifikasi->data_input_json['lama_penggunaan'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-indigo-500 uppercase">Penyakit</p>
                                            <p class="font-semibold text-gray-900">{{ $user->hasilKlasifikasi->data_input_json['riwayat_penyakit'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-indigo-500 uppercase">Skor URICA</p>
                                            <p class="font-bold text-indigo-700">
                                                {{ number_format($user->hasilKlasifikasi->data_input_json['urica_score'] ?? 0, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- B. KOMPARASI METODE (KNN vs NAIVE BAYES) --}}
                                <div>
                                    <h4 class="text-md font-bold text-gray-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        Perbandingan Hasil Metode
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        {{-- KOTAK KIRI: KNN --}}
                                        @php
                                            // Ambil data KNN dari JSON hasil_ai (yang disimpan Seeder/Controller)
                                            $knnRaw = $user->hasilKlasifikasi->data_input_json['hasil_ai']['knn'] ?? [];
                                            // Fallback ke kolom database biasa jika JSON kosong
                                            $knnLabel = $knnRaw['label'] ?? $user->hasilKlasifikasi->prediksi_knn;
                                            $knnConf = $knnRaw['confidence'] ?? 0;
                                        @endphp
                                        <div class="relative p-5 bg-white border-2 border-blue-100 rounded-xl shadow-sm hover:shadow-md transition-all">
                                            <div class="absolute top-0 right-0 px-3 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-bl-xl rounded-tr-xl">
                                                KNN
                                            </div>
                                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">
                                                K-Nearest Neighbor
                                            </p>
                                            
                                            <p class="text-3xl font-extrabold text-blue-700 my-2">
                                                {{ $knnLabel }}
                                            </p>
                                            
                                            <div class="flex items-center mt-3">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $knnConf }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold text-blue-700">{{ $knnConf }}%</span>
                                            </div>
                                            <p class="text-[10px] text-gray-400 mt-1 text-right">Tingkat Keyakinan</p>
                                        </div>

                                        {{-- KOTAK KANAN: NAIVE BAYES --}}
                                        @php
                                            $nbRaw = $user->hasilKlasifikasi->data_input_json['hasil_ai']['nb'] ?? [];
                                            $nbLabel = $nbRaw['label'] ?? $user->hasilKlasifikasi->prediksi_nb;
                                            $nbConf = $nbRaw['confidence'] ?? 0;
                                            $nbProbs = $nbRaw['probs'] ?? [];
                                        @endphp
                                        <div class="relative p-5 bg-white border-2 border-purple-100 rounded-xl shadow-sm hover:shadow-md transition-all">
                                            <div class="absolute top-0 right-0 px-3 py-1 bg-purple-100 text-purple-600 text-xs font-bold rounded-bl-xl rounded-tr-xl">
                                                Naive Bayes
                                            </div>
                                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">
                                                Gaussian Naive Bayes
                                            </p>
                                            
                                            <p class="text-3xl font-extrabold text-purple-800 my-2">
                                                {{ $nbLabel }}
                                            </p>

                                            <div class="flex items-center mt-3 mb-2">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $nbConf }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold text-purple-700">{{ $nbConf }}%</span>
                                            </div>

                                            {{-- Detail Probabilitas --}}
                                            @if(!empty($nbProbs))
                                                <div class="mt-3 pt-3 border-t border-dashed border-gray-200">
                                                    <p class="text-[10px] text-gray-400 mb-1">Detail Probabilitas:</p>
                                                    <div class="flex justify-between gap-1">
                                                        @foreach($nbProbs as $lbl => $val)
                                                            <div class="text-center px-2 py-1 bg-purple-50 rounded w-full">
                                                                <div class="text-[10px] font-semibold text-gray-600">{{ $lbl }}</div>
                                                                <div class="text-xs font-bold text-purple-700">{{ $val }}%</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                {{-- C. KESIMPULAN / PROGRAM --}}
                                <div class="bg-slate-800 rounded-lg p-5 text-white flex flex-col md:flex-row justify-between items-center shadow-lg">
                                    <div>
                                        <p class="text-sm text-slate-400 mb-1">Rekomendasi Program (Berdasarkan {{ $user->hasilKlasifikasi->prediksi_nb == $user->hasilKlasifikasi->prediksi_knn ? 'Kedua Metode' : 'Naive Bayes' }})</p>
                                        <p class="text-2xl font-bold tracking-tight">
                                            {{ $user->hasilKlasifikasi->rekomendasi_program }}
                                        </p>
                                    </div>
                                    <div class="mt-4 md:mt-0 text-right">
                                        <span class="text-xs bg-slate-700 px-2 py-1 rounded text-slate-300">
                                            {{ $user->hasilKlasifikasi->catatan_sistem }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- 3. SEARCH & HISTORY --}}
                    
                    {{-- SEARCH BAR --}}
                    <div class="bg-white p-4 shadow-lg sm:rounded-lg">
                        <label for="timelineSearch" class="block text-sm font-medium text-gray-700">Cari Riwayat</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="timelineSearch" 
                                   class="block w-full pl-10 sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Ketik kata kunci (misal: 'optimis', 'Rawat Inap', 'SOAP')...">
                        </div>
                        <p id="search-results-count" class="mt-2 text-sm text-gray-500" style="display: none;"></p>
                    </div>

                    {{-- RIWAYAT STATUS --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Riwayat Status & Program
                            </h3>
                        </div>
                        <div class="p-6">
                            <ul class="divide-y divide-gray-200" id="status-list">
                                @forelse ($user->riwayatStatus as $status)
                                    <li class="py-4 filterable-card" 
                                        data-searchable-content="{{ strtolower($status->program_baru . ' ' . $status->status_baru . ' ' . $status->faktor_penyebab . ' ' . $status->created_at->isoFormat('LLLL')) }}">
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
                                            <p class="text-xs font-medium text-gray-500">Faktor Penyebab (dicatat oleh Admin #{{ $status->admin_id }}):</p>
                                            <p class="text-sm text-gray-700">{{ $status->faktor_penyebab }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada riwayat status.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- RIWAYAT LIKERT --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Riwayat Tes Perkembangan (Likert)
                            </h3>
                        </div>
                        <div class="p-6">
                            <ul class="divide-y divide-gray-200" id="likert-list">
                                @forelse ($user->tesLikertHasil as $tes)
                                    <li class="py-4 filterable-card" 
                                        data-searchable-content="skor {{ $tes->total_skor }} {{ $tes->created_at->isoFormat('LL') }}">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-600">{{ $tes->created_at->isoFormat('LL') }}</span>
                                            <span class="text-sm font-medium text-gray-500">Diisi oleh: Admin #{{ $tes->admin_id }}</span>
                                        </div>
                                        <p class="mt-2 text-2xl font-bold text-green-600">
                                            Skor: {{ $tes->total_skor }} / {{ $tes->skor_maksimal }}
                                        </p>
                                    </li>
                                @empty
                                    <li class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada data tes perkembangan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- RIWAYAT SOAP --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Riwayat Catatan SOAP
                            </h3>
                        </div>
                        <div class="p-6 space-y-6" id="soap-list">
                            @forelse ($user->catatanSoap as $catatan)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 hover:shadow-md filterable-card" 
                                     data-searchable-content="{{ strtolower($catatan->subjective . ' ' . $catatan->objective . ' ' . $catatan->assessment . ' ' . $catatan->plan . ' ' . $catatan->created_at->isoFormat('LLLL')) }}">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-700">{{ $catatan->created_at->isoFormat('LLLL') }}</p>
                                        <p class="text-sm text-gray-500">Ditulis oleh: Admin #{{ $catatan->admin_id }}</p>
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
                                <p class="py-4 text-sm text-gray-500 text-center filterable-card-empty">Belum ada catatan SOAP.</p>
                            @endforelse
                        </div>
                    </div>
                    
                </div> 
            </div> 
        </div>
    </div>

    {{-- SCRIPT UNTUK CHART DAN SEARCH --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // === LOGIKA CHART ===
            fetch('{{ route('admin.pasien.chart', $user->id) }}')
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
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                })
                .catch(error => console.error('Error fetching chart data:', error));

            // === LOGIKA SEARCH ===
            const searchInput = document.getElementById('timelineSearch');
            const resultsCount = document.getElementById('search-results-count');
            const statusCards = document.querySelectorAll('#status-list .filterable-card');
            const soapCards = document.querySelectorAll('#soap-list .filterable-card');
            const likertCards = document.querySelectorAll('#likert-list .filterable-card');
            const emptyMessages = document.querySelectorAll('.filterable-card-empty');

            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();
                let totalVisible = 0;

                function filterList(list) {
                    let listVisibleCount = 0;
                    list.forEach(card => {
                        const content = card.dataset.searchableContent.toLowerCase();
                        if (content.includes(searchTerm)) {
                            card.style.display = '';
                            listVisibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    return listVisibleCount;
                }
                
                totalVisible += filterList(statusCards);
                totalVisible += filterList(soapCards);
                totalVisible += filterList(likertCards);

                if (searchTerm.length > 0) {
                    resultsCount.textContent = `${totalVisible} hasil ditemukan.`;
                    resultsCount.style.display = 'block';
                    emptyMessages.forEach(msg => msg.style.display = 'none');
                } else {
                    resultsCount.style.display = 'none';
                    emptyMessages.forEach(msg => msg.style.display = ''); 
                }
            });
        });
    </script>
    @endpush
</x-app-layout>