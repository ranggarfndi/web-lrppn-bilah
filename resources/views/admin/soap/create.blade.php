{{-- File: resources/views/admin/soap/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Buat Catatan SOAP') }}
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

                <form action="{{ route('admin.soap.store', $user->id) }}" method="POST">
                    @csrf

                    <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">
                            Catatan Perkembangan (SOAP)
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Isi keempat bagian di bawah ini.
                        </p>
                    </div>

                    {{-- Menampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="p-6">
                            <div class="mb-4 p-4 bg-red-50 text-red-700 border border-red-300 rounded-md">
                                <p><strong>Oops! Pastikan semua field terisi:</strong></p>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Bagian Form --}}
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="subjective" class="block text-sm font-medium text-gray-700">
                                S (Subjective) - Apa yang dikatakan pasien?
                            </label>
                            <textarea name="subjective" id="subjective" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('subjective') }}</textarea>
                        </div>

                        <div>
                            <label for="objective" class="block text-sm font-medium text-gray-700">
                                O (Objective) - Apa yang Anda amati (perilaku, penampilan)?
                            </label>
                            <textarea name="objective" id="objective" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('objective') }}</textarea>
                        </div>

                        <div>
                            <label for="assessment" class="block text-sm font-medium text-gray-700">
                                A (Assessment) - Penilaian Anda terhadap perkembangan/masalah.
                            </label>
                            <textarea name="assessment" id="assessment" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('assessment') }}</textarea>
                        </div>

                        <div>
                            <label for="plan" class="block text-sm font-medium text-gray-700">
                                P (Plan) - Rencana tindak lanjut untuk sesi berikutnya.
                            </label>
                            <textarea name="plan" id="plan" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('plan') }}</textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-lg text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out hover:shadow-blue-500/50 hover:-translate-y-px">
                            Simpan Catatan SOAP
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
