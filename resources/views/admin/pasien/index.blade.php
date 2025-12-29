{{-- File: resources/views/admin/pasien/index.blade.php --}}

<x-app-layout>
    {{-- Konten Utama Halaman --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- === JUDUL HALAMAN === --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __('Manajemen Pasien') }}
                    </h2>
                    <p class="mt-1 text-gray-600">
                        Cari, sortir, lihat riwayat, atau tambah pasien baru.
                    </p>
                </div>
                <a href="{{ route('admin.pasien.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Tambah Pasien Baru
                </a>
            </div>
            {{-- === AKHIR JUDUL HALAMAN === --}}


            {{-- Kartu Utama Pembungkus --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 pb-6 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                            @if (session('success'))
                                <div
                                    class="my-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md shadow-sm">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="p-4 bg-slate-50 border-b border-gray-200">
                                <form action="{{ route('admin.pasien.index') }}" method="GET" id="filterForm">
                                    <div class="flex flex-col md:flex-row gap-4">

                                        {{-- 1. Search Bar --}}
                                        <div class="flex-grow">
                                            <label for="search" class="block text-sm font-medium text-gray-700">Cari
                                                Pasien (Nama/Email)</label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input type="text" name="search" id="search"
                                                    class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150"
                                                    placeholder="Ketik nama atau email..." value="{{ $search ?? '' }}">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-sm font-medium text-gray-700 hover:bg-gray-100 transition duration-150">
                                                    <svg class="h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- 2. Dropdown Sortir --}}
                                        <div class="shrink-0">
                                            <label for="sort"
                                                class="block text-sm font-medium text-gray-700">Urutkan
                                                Berdasarkan</label>
                                            <select name="sort" id="sort"
                                                class="mt-1 block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150">
                                                <option value="date_desc"
                                                    @if ($sort == 'date_desc') selected @endif>
                                                    Baru Ditambahkan
                                                </option>
                                                <option value="name_asc"
                                                    @if ($sort == 'name_asc') selected @endif>
                                                    Nama (A - Z)
                                                </option>
                                                <option value="name_desc"
                                                    @if ($sort == 'name_desc') selected @endif>
                                                    Nama (Z - A)
                                                </option>
                                                <option value="date_asc"
                                                    @if ($sort == 'date_asc') selected @endif>
                                                    Paling Lama
                                                </option>
                                            </select>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            
                            {{-- Pembungkus Tabel --}}
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-b-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Nama Pasien</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Info Medis & Assessment
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Info Wali</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Tanggal Terdaftar</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($pasienList as $pasien)
                                            <tr class="hover:bg-slate-50 transition-colors duration-150 group">
                                                {{-- 1. NAMA --}}
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('admin.pasien.show', $pasien->id) }}"
                                                        class="flex items-center group-hover:underline">
                                                        <div
                                                            class="shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 rounded-full text-indigo-600 font-semibold">
                                                            {{ substr($pasien->name, 0, 2) }}
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $pasien->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $pasien->email }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>

                                                {{-- 2. INFO MEDIS (NAPZA + URICA) --}}
                                                <td class="px-6 py-4">
                                                    @if ($pasien->hasilKlasifikasi)
                                                        {{-- NAPZA --}}
                                                        <div class="text-sm font-bold text-gray-900">
                                                            {{ $pasien->hasilKlasifikasi->data_input_json['jenis_napza'] ?? '-' }}
                                                        </div>
                                                        {{-- LAMA --}}
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Lama:
                                                            {{ $pasien->hasilKlasifikasi->data_input_json['lama_penggunaan'] ?? '-' }}
                                                        </div>
                                                        {{-- URICA SCORE --}}
                                                        <div class="mt-2">
                                                            @php
                                                                // Ambil URICA dari JSON Klasifikasi atau dari Profil (Fallback)
                                                                $urica = $pasien->hasilKlasifikasi->data_input_json['urica_score'] 
                                                                         ?? $pasien->profil->urica_score 
                                                                         ?? 0;
                                                            @endphp
                                                            @if($urica > 0)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                                    URICA: {{ number_format($urica, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Belum diklasifikasi</span>
                                                    @endif
                                                </td>

                                                {{-- 3. INFO WALI --}}
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($pasien->profil)
                                                        <div class="text-sm text-gray-900">
                                                            {{ $pasien->profil->nama_wali ?? 'Wali belum diisi' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $pasien->profil->no_telepon_wali ?? 'Kontak belum diisi' }}
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-400 italic">Profil belum lengkap
                                                        </div>
                                                    @endif
                                                </td>

                                                {{-- 4. TANGGAL --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pasien->created_at->format('d M Y') }}
                                                </td>

                                                {{-- 5. AKSI --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.pasien.show', $pasien->id) }}"
                                                        class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold tracking-wider hover:bg-indigo-200 transition-colors duration-150 transform hover:scale-105">
                                                        Lihat Riwayat
                                                        <svg class="w-4 h-4 ml-1.5" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- EMPTY STATE --}}
                                            <tr>
                                                <td colspan="5">
                                                    <div class="text-center p-12">
                                                        @if ($search ?? false)
                                                            <svg class="mx-auto h-12 w-12 text-gray-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                            </svg>
                                                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Tidak
                                                                Ada Hasil Ditemukan</h3>
                                                            <p class="mt-1 text-sm text-gray-500">Kami tidak dapat
                                                                menemukan pasien yang cocok dengan kata kunci
                                                                "{{ $search }}".</p>
                                                        @else
                                                            <svg class="mx-auto h-12 w-12 text-gray-400"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum
                                                                Ada Pasien</h3>
                                                            <p class="mt-1 text-sm text-gray-500">Mulai dengan
                                                                mendaftarkan pasien baru.</p>
                                                            <div class="mt-6">
                                                                <a href="{{ route('admin.pasien.create') }}"
                                                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                                                    + Tambah Pasien Baru
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="m-6">
                                {{ $pasienList->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sortDropdown = document.getElementById('sort');
                if (sortDropdown) {
                    sortDropdown.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>