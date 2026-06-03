<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Latihan') }}
        </h2>
        {{-- CSS Flatpickr --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Gagal Menyimpan:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('PUT') 

                        {{-- 1. INPUT TANGGAL --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tanggal Latihan</label>
                            <input type="text" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>

                        {{-- 2. KATEGORI & PELATIH (Disampingkan) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(['Semua Umur', 'KU-10', 'KU-12', 'KU-14', 'KU-16', 'KU-18'] as $kat)
                                        <option value="{{ $kat }}" {{ $jadwal->kategori == $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- [BARU] EDIT PELATIH --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Pelatih Bertugas</label>
                                <select name="pelatih_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="">-- Pilih Coach --</option>
                                    @foreach($pelatihs as $pelatih)
                                        {{-- Logika: Jika ID pelatih sama dengan data lama, tambahkan 'selected' --}}
                                        <option value="{{ $pelatih->id }}" {{ $jadwal->pelatih_id == $pelatih->id ? 'selected' : '' }}>
                                            Coach {{ $pelatih->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 3. INPUT JAM --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                <input type="text" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" class="timepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                <input type="text" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" class="timepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                        </div>

                        {{-- 4. LOKASI & STATUS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Lokasi Latihan</label>
                                <select name="lokasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="Lapangan Basket Merak Kelud Motor Wates" selected>Lapangan Basket Merak Kelud Motor Wates</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Status Jadwal</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="Aktif" {{ $jadwal->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Dibatalkan" {{ $jadwal->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('jadwal.index') }}" class="mr-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Perbarui Jadwal
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT FLATPICKR --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", 
            altInput: true,      
            altFormat: "l, d F Y",
            allowInput: true
        });

        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", 
            time_24hr: true,
        });
    </script>
</x-app-layout>