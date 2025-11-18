{{-- File: resources/views/klasifikasi/hasil.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Klasifikasi untuk Pasien: ') }} {{ $nama_pasien }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="md:col-span-2 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="text-2xl font-bold text-blue-800 mb-2">
                            Rekomendasi Program Sistem
                        </h3>
                        <p class="text-xl font-medium text-gray-700 mb-1">
                            Berdasarkan: Prediksi KNN ({{ $rekomendasi['berdasarkan'] }})
                        </p>

                        {{-- Ini adalah output utama --}}
                        <p class="text-3xl font-bold text-blue-600 py-3">
                            {{ $rekomendasi['program'] }}
                        </p>

                        <p class="text-sm text-gray-600 mt-2">
                            <strong>Catatan Sistem:</strong> {{ $rekomendasi['catatan'] }}
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 border rounded-lg">
                        <h4 class="text-lg font-semibold mb-3">Perbandingan Model</h4>
                        <div class="mb-3">
                            <span class="text-sm font-medium text-gray-500">Prediksi K-Nearest Neighbors (KNN):</span>
                            <span class="text-lg font-bold text-gray-800 block">{{ $hasil_knn }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Prediksi Naive Bayes:</span>
                            <span class="text-lg font-bold text-gray-800 block">{{ $hasil_nb }}</span>
                        </div>
                    </div>

                    <div class="md:col-span-3 p-4 bg-gray-50 border rounded-lg">
                        <h4 class="text-lg font-semibold mb-3">Ringkasan Data Input</h4>
                        <ul class="list-disc list-inside">
                            <li><strong>Nama Pasien:</strong> {{ $nama_pasien }}</li>
                            <li><strong>Jenis Kelamin:</strong> {{ $data_input['jenis_kelamin'] }}</li>
                            <li><strong>Lama Penggunaan:</strong> {{ $data_input['lama_penggunaan'] }}</li>
                            <li><strong>Jenis NAPZA:</strong> {{ $data_input['jenis_napza'] }}</li>
                        </ul>
                    </div>

                    <div class="md:col-span-3 p-4 bg-yellow-50 text-yellow-800 border border-yellow-200 rounded-lg">
                        <h4 class="font-bold">Peringatan Akurasi Model</h4>
                        <p class="text-sm">{{ $catatan_model }}</p>
                    </div>

                    <div class="md:col-span-3">
                        <a href="{{ route('klasifikasi.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            &larr; Klasifikasi Pasien Lain
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
