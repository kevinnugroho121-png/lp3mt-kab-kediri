<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Rapor Perkembangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header Identitas Atlet --}}
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 mb-6 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-indigo-500 font-bold uppercase tracking-wide">Nama Atlet</span>
                            <h3 class="font-bold text-2xl text-indigo-900">{{ $atlet->nama_lengkap }}</h3>
                            <p class="text-sm text-gray-600">Kategori: {{ $atlet->kategori }} | Posisi: {{ $atlet->posisi ?? '-' }}</p>
                        </div>
                        <div class="text-4xl">🏀</div>
                    </div>

                    {{-- Form Input --}}
                    <form action="{{ route('pelatih.progres.store') }}" method="POST">
                        @csrf
                        {{-- Data Hidden (Otomatis terisi) --}}
                        <input type="hidden" name="atlet_id" value="{{ $atlet->id }}">
                        <input type="hidden" name="pelatih_id" value="{{ $pelatih->id }}">

                        {{-- Tanggal --}}
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Tanggal Penilaian</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <hr class="mb-6 border-gray-200">

                        {{-- Input 4 Nilai Rapor --}}
                        <h4 class="font-bold text-lg mb-4 text-gray-800">📊 Penilaian Skill (0 - 100)</h4>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                            {{-- Teknik --}}
                            <div>
                                <label class="block text-sm font-bold text-blue-700 mb-1">Teknik</label>
                                <span class="text-xs text-gray-500 block mb-2">(Dribble, Shoot)</span>
                                <input type="number" name="teknik" min="0" max="100" class="w-full text-center text-xl font-bold text-blue-800 rounded-md border-blue-200 focus:border-blue-500" placeholder="0" required>
                            </div>

                            {{-- Fisik --}}
                            <div>
                                <label class="block text-sm font-bold text-green-700 mb-1">Fisik</label>
                                <span class="text-xs text-gray-500 block mb-2">(Speed, Power)</span>
                                <input type="number" name="fisik" min="0" max="100" class="w-full text-center text-xl font-bold text-green-800 rounded-md border-green-200 focus:border-green-500" placeholder="0" required>
                            </div>

                            {{-- Mental --}}
                            <div>
                                <label class="block text-sm font-bold text-yellow-700 mb-1">Mental</label>
                                <span class="text-xs text-gray-500 block mb-2">(Disiplin, Fokus)</span>
                                <input type="number" name="mental" min="0" max="100" class="w-full text-center text-xl font-bold text-yellow-800 rounded-md border-yellow-200 focus:border-yellow-500" placeholder="0" required>
                            </div>

                            {{-- Taktik --}}
                            <div>
                                <label class="block text-sm font-bold text-purple-700 mb-1">Taktik</label>
                                <span class="text-xs text-gray-500 block mb-2">(IQ Game, Posisi)</span>
                                <input type="number" name="taktik" min="0" max="100" class="w-full text-center text-xl font-bold text-purple-800 rounded-md border-purple-200 focus:border-purple-500" placeholder="0" required>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Catatan Evaluasi (Opsional)</label>
                            <textarea name="catatan" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Berikan masukan untuk atlet ini..."></textarea>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('pelatih.progres.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-bold hover:bg-gray-300 transition">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 transition shadow-lg">
                                Simpan Rapor 💾
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>