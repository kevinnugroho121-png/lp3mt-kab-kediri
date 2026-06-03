<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Latihan Baru') }}
        </h2>
        {{-- CSS Flatpickr (Kalender Cantik) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Gagal Menyimpan!</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf

                        {{-- 1. INPUT TANGGAL --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">📅 Tanggal Latihan</label>
                            <input type="text" name="tanggal" value="{{ old('tanggal') }}"
                                class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                placeholder="Klik untuk pilih tanggal..." required>
                            <p class="text-xs text-gray-500 mt-1">*Pastikan tidak memilih tanggal masa lalu.</p>
                        </div>

                        {{-- 2. KATEGORI & PELATIH --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Umur</label>
                                <select name="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Semua Umur" {{ old('kategori') == 'Semua Umur' ? 'selected' : '' }}>Semua Umur</option>
                                    <option value="KU-10" {{ old('kategori') == 'KU-10' ? 'selected' : '' }}>KU-10 (SD)</option>
                                    <option value="KU-12" {{ old('kategori') == 'KU-12' ? 'selected' : '' }}>KU-12 (SMP Awal)</option>
                                    <option value="KU-14" {{ old('kategori') == 'KU-14' ? 'selected' : '' }}>KU-14 (SMP)</option>
                                    <option value="KU-16" {{ old('kategori') == 'KU-16' ? 'selected' : '' }}>KU-16 (SMA)</option>
                                    <option value="KU-18" {{ old('kategori') == 'KU-18' ? 'selected' : '' }}>KU-18 (SMA Akhir)</option>
                                </select>
                            </div>

                            {{-- Pelatih --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pelatih Bertugas</label>
                                <select name="pelatih_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="">-- Pilih Coach --</option>
                                    @foreach($pelatihs as $pelatih)
                                        <option value="{{ $pelatih->id }}" {{ old('pelatih_id') == $pelatih->id ? 'selected' : '' }}>
                                            Coach {{ $pelatih->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 3. INPUT JAM --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">⏰ Jam Mulai</label>
                                <input type="text" name="jam_mulai" value="{{ old('jam_mulai') }}"
                                    class="timepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    placeholder="00:00" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">🏁 Jam Selesai</label>
                                <input type="text" name="jam_selesai" value="{{ old('jam_selesai') }}"
                                    class="timepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    placeholder="00:00" required>
                            </div>
                        </div>

                        {{-- 4. LOKASI & STATUS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">📍 Lokasi Latihan</label>
                                {{-- Saya buat input text supaya fleksibel, tapi default valuenya sesuai request Mas --}}
                                <input type="text" name="lokasi" 
                                    value="{{ old('lokasi', 'Lapangan Basket Merak Kelud Motor Wates') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Jadwal</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Dibatalkan" {{ old('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan / Libur</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end gap-3 mt-8 border-t pt-4">
                            <a href="{{ route('jadwal.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-300 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-500 transition shadow-lg">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT FLATPICKR (Sesuai Kode Mas) --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Setup Datepicker
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", // Format masuk database: 2026-01-10
            altInput: true,      // Tampilan ke user
            altFormat: "l, d F Y", // Contoh: Senin, 10 Januari 2026
            minDate: "today",    // Mencegah pilih tanggal masa lalu di UI
            locale: {
                firstDayOfWeek: 1 // Mulai hari Senin
            }
        });

        // Setup Timepicker
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // Format 24 jam (16:00)
            time_24hr: true,
        });
    </script>
</x-app-layout>