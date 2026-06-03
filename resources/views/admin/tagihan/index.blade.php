<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Keuangan & SPP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- === BAGIAN ATAS: TOMBOL TAMBAH & FILTER === --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('tagihan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-500 shadow-md transition">
                            + Buat Tagihan Baru
                        </a>

                        {{-- Form Filter --}}
                        <form method="GET" action="{{ route('tagihan.index') }}" class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                            <select name="status" class="border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Status --</option>
                                <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>❌ Belum Lunas</option>
                                <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>✅ Lunas</option>
                            </select>

                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari nama atlet..." value="{{ request('search') }}" 
                                    class="border-gray-300 rounded-l-md text-sm w-full md:w-48 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-r-md hover:bg-gray-700 font-bold shadow-sm transition">
                                    🔍 Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- === TABEL DATA === --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Atlet</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis Tagihan</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Nominal (Rp)</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tagihans as $tagihan)
                                <tr class="hover:bg-gray-50 transition">
                                    
                                    {{-- 1. Tanggal Buat --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('d/m/Y') }}
                                    </td>
                                    
                                    {{-- 2. Nama Atlet --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        {{ $tagihan->atlet->nama_lengkap ?? 'Atlet Terhapus' }}
                                        <div class="text-xs text-gray-500 font-normal">{{ $tagihan->atlet->kategori ?? '-' }}</div>
                                    </td>
                                    
                                    {{-- 3. Jenis Tagihan (OTOMATIS DARI MODEL) --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="font-bold text-blue-800">{{ $tagihan->judul_tagihan }}</div>
                                        <div class="text-xs text-gray-500 italic">
                                            {{ $tagihan->metode_pembayaran ?? 'Belum ada metode' }}
                                        </div>
                                    </td>
                                    
                                    {{-- 4. Nominal --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono font-bold text-gray-900">
                                        Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                                    </td>
                                    
                                    {{-- 5. Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($tagihan->status == 'Lunas')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                LUNAS
                                            </span>
                                            <div class="text-[10px] text-green-600 mt-1 font-semibold">
                                                Tgl: {{ $tagihan->tanggal_lunas ? \Carbon\Carbon::parse($tagihan->tanggal_lunas)->format('d/m/Y') : '-' }}
                                            </div>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                BELUM LUNAS
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- 6. Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            {{-- Tombol Edit / Verifikasi --}}
                                            <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1 rounded-md border border-yellow-200 font-bold transition">
                                                ✏️ {{ $tagihan->status == 'Lunas' ? 'Detail' : 'Bayar' }}
                                            </a>
                                            
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini? Data tidak bisa dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md border border-red-200 font-bold transition">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <p>Belum ada data tagihan yang ditemukan.</p>
                                            <p class="text-xs mt-1">Silakan klik tombol "Buat Tagihan Baru".</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $tagihans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>