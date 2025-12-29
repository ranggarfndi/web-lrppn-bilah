{{-- File: resources/views/admin/pasien/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pasien Baru & Klasifikasi Awal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('admin.pasien.store') }}" method="POST" class="space-y-8">
                @csrf
                
                {{-- Menampilkan pesan error --}}
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

                {{-- 1. DATA AKUN PASIEN --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            1. Data Akun Pasien (untuk Login)
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Info ini akan digunakan pasien/keluarga untuk login aplikasi.
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
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
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

                {{-- 2. DATA KLASIFIKASI (AI INPUT) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            2. Data Medis & Klasifikasi Awal
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Data ini akan dikirim ke model AI (KNN & Naive Bayes) untuk prediksi rehabilitasi.
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Jenis Kelamin --}}
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih...</option>
                                    <option value="Laki-Laki" @if(old('jenis_kelamin') == 'Laki-Laki') selected @endif>Laki-Laki</option>
                                    <option value="Perempuan" @if(old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                                </select>
                            </div>

                            {{-- Lama Penggunaan --}}
                            <div>
                                <label for="lama_penggunaan" class="block text-sm font-medium text-gray-700">Lama Penggunaan</label>
                                <input type="text" name="lama_penggunaan" id="lama_penggunaan" value="{{ old('lama_penggunaan') }}" required placeholder="Contoh: 8 Tahun" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Jenis NAPZA --}}
                            <div>
                                <label for="jenis_napza" class="block text-sm font-medium text-gray-700">Jenis NAPZA (pisahkan koma)</label>
                                <input type="text" name="jenis_napza" id="jenis_napza" value="{{ old('jenis_napza') }}" required placeholder="Contoh: Shabu, Ganja" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Riwayat Penyakit --}}
                            <div>
                                <label for="riwayat_penyakit" class="block text-sm font-medium text-gray-700">Riwayat Penyakit (pisahkan koma)</label>
                                <input type="text" name="riwayat_penyakit" id="riwayat_penyakit" value="{{ old('riwayat_penyakit') }}" placeholder="Contoh: Asma, Maag (Isi 'Tidak Ada' jika sehat)" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-yellow-50">
                                <p class="text-xs text-gray-500 mt-1">*Jika tidak ada, tulis "Tidak Ada"</p>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- [BARU] 3. TES URICA --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-indigo-50 border-b border-indigo-200">
                        <h3 class="text-lg leading-6 font-semibold text-indigo-900">
                            3. Tes URICA (Assessment)
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-indigo-700">
                            Jawablah 28 pertanyaan berikut dengan skala 1 (Sangat Tidak Setuju) s.d 5 (Sangat Setuju).
                            <span class="font-bold">Jawaban ini mempengaruhi hasil klasifikasi.</span>
                        </p>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pernyataan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Skala (1-5)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if(isset($urica_questions))
                                        @foreach($urica_questions as $idx => $soal)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">
                                                {{ $idx }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $soal }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-between space-x-2">
                                                    <span class="text-xs text-gray-400">STS</span>
                                                    @for($i = 1; $i <= 5; $i++)
                                                    <div class="flex flex-col items-center cursor-pointer">
                                                        <input type="radio" 
                                                               id="urica_{{ $idx }}_{{ $i }}" 
                                                               name="urica[{{ $idx }}]" 
                                                               value="{{ $i }}" 
                                                               {{ old('urica.'.$idx) == $i ? 'checked' : '' }}
                                                               required
                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 cursor-pointer">
                                                        <label for="urica_{{ $idx }}_{{ $i }}" class="text-[10px] text-gray-500 mt-1 cursor-pointer">{{ $i }}</label>
                                                    </div>
                                                    @endfor
                                                    <span class="text-xs text-gray-400">SS</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-red-500">
                                                Variable $urica_questions tidak ditemukan. Pastikan Controller sudah diperbarui.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 4. DATA PROFIL --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            4. Data Profil (Opsional)
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
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="nama_wali" class="block text-sm font-medium text-gray-700">Nama Wali</label>
                                <input type="text" name="nama_wali" id="nama_wali" value="{{ old('nama_wali') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="no_telepon_wali" class="block text-sm font-medium text-gray-700">No. Telepon Wali</label>
                                <input type="text" name="no_telepon_wali" id="no_telepon_wali" value="{{ old('no_telepon_wali') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 pb-12">
                    <button type="submit" 
                            class="inline-flex justify-center py-3 px-8 border border-transparent shadow-lg text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out hover:shadow-indigo-500/50 hover:-translate-y-1 transform">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Simpan & Proses Klasifikasi
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>