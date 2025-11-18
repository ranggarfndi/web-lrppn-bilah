{{-- File: resources/views/admin/dashboard.blade.php (IKON DIPERBAIKI) --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- === JUDUL HALAMAN DIPINDAHKAN KE SINI === --}}
            {{-- <div class="mb-8">
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
            </div> --}}
            {{-- === AKHIR JUDUL HALAMAN === --}}

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Selamat datang kembali, {{ $user->name }}!</h3>
                        <p class="mt-2 text-gray-600 max-w-lg">
                            Ini adalah pusat kendali Anda. Pantau statistik, kelola pasien, dan catat perkembangan
                            rehabilitasi.
                        </p>
                    </div>
                    <div class="shrink-0">
                        <svg class="h-24 w-24 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Kartu 1: Total Pasien (IKON DIPERBAIKI LAGI) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-center space-x-4
            transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 cursor-pointer"
                    onclick="window.location='{{ route('admin.pasien.index') }}'">
                    <div class="shrink-0 bg-indigo-100 rounded-full p-4">
                        {{-- === IKON BARU (USERS SOLID - Versi Simpel) === --}}
                        <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.004 3.004 0 013.75-2.906z" />
                        </svg>
                        {{-- === AKHIR IKON BARU === --}}
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pasien</h4>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPasien }}</p>
                    </div>
                </div>

                {{-- Kartu 2: Klasifikasi Bulan Ini (Ikon ini sudah benar) --}}
                <div
                    class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-center space-x-4
                            transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="shrink-0 bg-green-100 rounded-full p-4">
                        <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Klasifikasi Bulan Ini
                        </h4>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $klasifikasiBulanIni }}</p>
                    </div>
                </div>

                {{-- Kartu 3: Catatan SOAP Hari Ini (IKON DIPERBAIKI) --}}
                <div
                    class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-center space-x-4
                            transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="shrink-0 bg-blue-100 rounded-full p-4">
                        {{-- === IKON BARU (DOCUMENT TEXT SOLID) === --}}
                        <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                        {{-- === AKHIR IKON BARU === --}}
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tes SOAP Hari Ini</h4>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $soapHariIni }}</p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Pasien Baru Ditambahkan
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                5 pasien terakhir yang didaftarkan ke sistem.
                            </p>
                        </div>
                        <ul role="list" class="divide-y divide-gray-200">
                            @forelse ($pasienTerbaru as $pasien)
                                <li class="transition-colors duration-150 hover:bg-slate-50">
                                    <a href="{{ route('admin.pasien.show', $pasien->id) }}"
                                        class="flex items-center justify-between px-4 py-4 sm:px-6">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 rounded-full text-indigo-600 font-semibold">
                                                {{ substr($pasien->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $pasien->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $pasien->email }}</p>
                                            </div>
                                        </div>
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </li>
                            @empty
                                <li class="px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data pasien yang ditambahkan.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                            <div class="space-y-4">
                                <a href="{{ route('admin.pasien.create') }}"
                                    class="flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px w-full">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    Tambah Pasien
                                </a>
                                <a href="{{ route('admin.pasien.index') }}"
                                    class="flex items-center justify-center px-6 py-3 bg-slate-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px w-full">

                                    {{-- === IKON BARU (BARS 3 SOLID) === --}}
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{-- === AKHIR IKON BARU === --}}

                                    Lihat Semua Pasien
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
