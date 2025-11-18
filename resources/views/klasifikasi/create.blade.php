{{-- File: resources/views/klasifikasi/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Klasifikasi Pasien Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class_="text-lg font-medium mb-4">Masukkan Data Pasien</h3>

                    {{-- Menampilkan pesan error jika server Python mati --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif

                    {{-- Menampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Arahkan form ke rute 'klasifikasi.store' yang kita buat --}}
                    <form action="{{ route('klasifikasi.store') }}" method="POST">
                        @csrf {{-- Token keamanan wajib Laravel --}}

                        <div class="mb-4">
                            <label for="nama_pasien" class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                            <input type="text" name="nama_pasien" id="nama_pasien" value="{{ old('nama_pasien') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis
                                Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" @if (old('jenis_kelamin') == 'Laki-Laki') selected @endif>Laki-Laki
                                </option>
                                <option value="Perempuan" @if (old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="lama_penggunaan" class="block text-sm font-medium text-gray-700">Lama Penggunaan
                                NAPZA (Contoh: "5 Tahun" atau "3 Bulan")</label>
                            <input type="text" name="lama_penggunaan" id="lama_penggunaan"
                                value="{{ old('lama_penggunaan') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Contoh: 8 Tahun">
                        </div>

                        <div class="mb-4">
                            <label for="jenis_napza" class="block text-sm font-medium text-gray-700">Jenis NAPZA
                                (Pisahkan dengan spasi, koma, atau titik-koma)</label>
                            <input type="text" name="jenis_napza" id="jenis_napza" value="{{ old('jenis_napza') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Contoh: Shabu Ganja Heroin">
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Proses Klasifikasi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
