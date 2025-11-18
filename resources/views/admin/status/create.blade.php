<x-app-layout>
    {{-- Kita hilangkan header dan gabungkan judul --}}

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul Halaman --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __('Ubah Status/Program Manual') }}
                    </h2>
                    <p class="mt-1 text-gray-600">
                        Untuk Pasien: <span class="font-medium">{{ $user->name }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.pasien.show', $user->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                    &larr; Batal dan Kembali
                </a>
            </div>

            {{-- Form dibungkus dalam Kartu --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">

                <form action="{{ route('admin.status.store', $user->id) }}" method="POST">
                    @csrf

                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Evaluasi Manual
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Tetapkan status dan program baru berdasarkan observasi klinis.
                        </p>
                    </div>

                    {{-- Menampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="p-6 border-b border-gray-200">
                            <div class="p-4 bg-red-50 text-red-700 border border-red-300 rounded-md">
                                <p><strong>Oops! Pastikan semua field terisi:</strong></p>
                                <ul class="list-disc list-inside mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Bagian Form --}}
                    <div class="p-6 space-y-6">

                        {{-- Menampilkan status terakhir --}}
                        @if ($statusTerakhir)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm font-medium text-blue-800">Status Saat Ini:</p>
                                <p class="text-lg font-semibold text-blue-900">{{ $statusTerakhir->status_baru }}
                                    (Program: {{ $statusTerakhir->program_baru }})</p>
                                <p class="text-xs text-gray-600 mt-1">Sejak:
                                    {{ $statusTerakhir->created_at->isoFormat('LL') }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status_baru" class="block text-sm font-medium text-gray-700">
                                    Status Baru
                                </label>
                                <input type="text" name="status_baru" id="status_baru"
                                    value="{{ old('status_baru', $statusTerakhir->status_baru ?? '') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                                    placeholder="Contoh: Membaik, Stabil, Perlu Perhatian">
                            </div>

                            <div>
                                <label for="program_baru" class="block text-sm font-medium text-gray-700">
                                    Program Baru
                                </label>
                                <select name="program_baru" id="program_baru" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                                    <option value="">Pilih program...</option>
                                    @foreach ($daftarProgram as $program)
                                        <option value="{{ $program }}"
                                            @if (old('program_baru', $statusTerakhir->program_baru ?? '') == $program) selected @endif>
                                            {{ $program }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="faktor_penyebab" class="block text-sm font-medium text-gray-700">
                                Faktor Penyebab / Alasan Perubahan (Wajib Diisi)
                            </label>
                            <textarea name="faktor_penyebab" id="faktor_penyebab" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                                placeholder="Contoh: Pasien menunjukkan kemandirian dan hasil tes urin negatif selama 2 minggu.">{{ old('faktor_penyebab') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Alasan ini akan ditampilkan di log riwayat pasien.</p>
                        </div>

                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-lg text-base font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 ease-in-out hover:shadow-yellow-500/50 hover:-translate-y-px">
                            Simpan Perubahan Status
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
