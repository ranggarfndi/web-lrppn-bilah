{{-- File: resources/views/pasien/dashboard.blade.php (Variabel $pasien diperbaiki menjadi $user) --}}

<x-app-layout>
    {{-- <x-slot name="header"> ... </x-slot> Dihapus --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- === JUDUL HALAMAN === --}}
            <div>
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                    Dashboard Saya
                </h2>
                <p class="mt-1 text-gray-600">
                    {{-- Di sini kita gunakan $user --}}
                    Selamat datang kembali, <span class="font-medium">{{ $user->name }}</span>!
                </p>
            </div>
            {{-- === AKHIR JUDUL HALAMAN === --}}


            <a href="{{ route('pasien.riwayat.show') }}"
                class="block bg-white overflow-hidden shadow-xl sm:rounded-lg
                        p-8 sm:p-12 
                        transform transition-all duration-300 ease-in-out
                        hover:shadow-indigo-400/50 hover:-translate-y-2 
                        hover:bg-gradient-to-r hover:from-indigo-600 hover:to-blue-500
                        text-gray-900 hover:text-white group">

                <div class="flex flex-col md:flex-row items-center justify-between gap-8">

                    {{-- Kolom Teks (Kiri) --}}
                    <div class="max-w-lg">
                        {{-- Di sini kita gunakan $user --}}
                        <h2 class="text-3xl font-bold">
                            Riwayat {{ $user->name }}
                        </h2>

                        <p
                            class="mt-4 text-lg text-gray-600 group-hover:text-indigo-100 transition-colors duration-300">
                            Tinjau hasil klasifikasi, pantau catatan SOAP dari konselor,
                            dan lihat grafik perkembangan Anda di satu tempat.
                        </p>
                        <div
                            class="mt-8 text-xl font-semibold text-indigo-700 group-hover:text-white transition-colors duration-300">
                            Lihat Sekarang &rarr;
                        </div>
                    </div>

                    {{-- Kolom Ilustrasi (Kanan) --}}
                    <div class="shrink-0 p-4 hidden md:block">
                        <svg class="h-32 w-32 text-indigo-200 group-hover:text-white transition-colors duration-300"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12M3.75 3.75h16.5M3.75 12h16.5m-16.5 4.5h16.5M4.5 20.25h15A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                </div>
            </a>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kartu 1: Program Saat Ini --}}
                <div
                    class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-start space-x-4
                            transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="shrink-0 bg-green-100 rounded-full p-4">
                        <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Program Saat Ini</h4>
                        {{-- Di sini kita gunakan $program --}}
                        <p class="text-xl font-bold text-gray-900 mt-1 truncate" title="{{ $program }}">
                            {{ $program }}
                        </p>
                    </div>
                </div>

                {{-- Kartu 2: Tes Selesai --}}
                <div
                    class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-start space-x-4
                            transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="shrink-0 bg-blue-100 rounded-full p-4">
                        <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tes Selesai</h4>
                        {{-- Di sini kita gunakan $totalTes --}}
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTes }}</p>
                    </div>
                </div>

                {{-- Kartu 3: Catatan Konselor --}}
                <div
                    class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6 flex items-start space-x-4
                            transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="shrink-0 bg-indigo-100 rounded-full p-4">
                        <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Catatan Konselor</h4>
                        {{-- Di sini kita gunakan $totalSoap --}}
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalSoap }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
