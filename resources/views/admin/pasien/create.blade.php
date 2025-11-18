{{-- File: resources/views/admin/pasien/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pasien Baru & Klasifikasi Awal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Kita ganti max-w-7xl menjadi max-w-4xl agar form lebih fokus --}}
            
            <form action="{{ route('admin.pasien.store') }}" method="POST" class="space-y-8">
                @csrf
                
                {{-- Menampilkan pesan error (Python/DB/Validasi) --}}
                @if (session('error') || $errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 shadow-md rounded-lg" role="alert">
                        <h3 class="font-bold text-red-800">Oops! Ada kesalahan</h3>
                        @if (session('error'))
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        @endif
                        @if ($errors->any())
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Data Akun Pasien (untuk Login)
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Info ini akan digunakan pasien/keluarga untuk login.
                        </p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap Pasien</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email (untuk Login Pasien)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Data Klasifikasi Awal
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Data ini akan dikirim ke model AI untuk prediksi.
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    <option value="">Pilih...</option>
                                    <option value="Laki-Laki" @if(old('jenis_kelamin') == 'Laki-Laki') selected @endif>Laki-Laki</option>
                                    <option value="Perempuan" @if(old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="lama_penggunaan" class="block text-sm font-medium text-gray-700">Lama Penggunaan</label>
                                <input type="text" name="lama_penggunaan" id="lama_penggunaan" value="{{ old('lama_penggunaan') }}" required placeholder="Contoh: 8 Tahun" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="jenis_napza" class="block text-sm font-medium text-gray-700">Jenis NAPZA (pisahkan koma/spasi)</label>
                                <input type="text" name="jenis_napza" id="jenis_napza" value="{{ old('jenis_napza') }}" required placeholder="Contoh: Shabu Ganja" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Data Profil (Opsional)
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Informasi tambahan mengenai pasien dan wali.
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="nama_wali" class="block text-sm font-medium text-gray-700">Nama Wali</label>
                                <input type="text" name="nama_wali" id="nama_wali" value="{{ old('nama_wali') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                            <div>
                                <label for="no_telepon_wali" class="block text-sm font-medium text-gray-700">No. Telepon Wali</d>
                                <input type="text" name="no_telepon_wali" id="no_telepon_wali" value="{{ old('no_telepon_wali') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-lg text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out hover:shadow-indigo-500/50 hover:-translate-y-px">
                        Daftar dan Klasifikasi Pasien
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>