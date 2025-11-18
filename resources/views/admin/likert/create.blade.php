{{-- File: resources/views/admin/likert/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tes Perkembangan (Skala Likert)') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Untuk Pasien: <span class="font-medium text-gray-900">{{ $user->name }}</span>
                </p>
            </div>
            <a href="{{ route('admin.pasien.show', $user->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 ease-in-out hover:shadow-lg hover:-translate-y-px">
                &larr; Batal dan Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Form dibungkus dalam Kartu --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                
                <form action="{{ route('admin.likert.store', $user->id) }}" method="POST">
                    @csrf
                    
                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Formulir Tes Perkembangan
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Beri skor 1 (Sangat Tidak Setuju) sampai 5 (Sangat Setuju) untuk setiap pernyataan.
                        </p>
                    </div>

                    {{-- Menampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="p-6 border-b border-gray-200">
                            <div class="p-4 bg-red-50 text-red-700 border border-red-300 rounded-md">
                                <p><strong>Oops! Pastikan semua pertanyaan dijawab:</strong></p>
                                <ul class="list-disc list-inside mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Bagian Form (Daftar Pertanyaan) --}}
                    <div class="p-6 space-y-8">
                        {{-- Kita akan me-looping pertanyaan dari controller --}}
                        @foreach ($pertanyaan as $key => $pertanyaan_teks)
                            <div class="border-b border-gray-200 pb-6">
                                <label class="block text-base font-semibold text-gray-900">{{ $loop->iteration }}. {{ $pertanyaan_teks }}</label>
                                <fieldset class="mt-4">
                                    <legend class="sr-only">Skor untuk {{ $key }}</legend>
                                    {{-- Dibuat responsif agar rapi di mobile --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <div class="flex items-center">
                                                <input id="{{ $key }}_{{ $i }}" name="{{ $key }}" type="radio" value="{{ $i }}" required 
                                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 transition duration-150 ease-in-out">
                                                <label for="{{ $key }}_{{ $i }}" class="ml-3 block text-sm font-medium text-gray-700">
                                                    {{ $i }} 
                                                    @if ($i == 1) <span class="text-gray-500 font-normal ml-1">(Sangat Tidak Setuju)</span> @endif
                                                    @if ($i == 3) <span class="text-gray-500 font-normal ml-1">(Netral)</span> @endif
                                                    @if ($i == 5) <span class="text-gray-500 font-normal ml-1">(Sangat Setuju)</span> @endif
                                                </label>
                                            </div>
                                        @endfor
                                    </div>
                                </fieldset>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button typeD="submit" 
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-lg text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 ease-in-out hover:shadow-green-500/50 hover:-translate-y-px">
                            Simpan Hasil Tes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>