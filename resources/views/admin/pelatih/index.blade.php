<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Pelatih (Coach)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline font-bold">✅ {{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- AREA ATAS: TOMBOL TAMBAH & SEARCH --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('pelatih.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition ease-in-out duration-150 shadow-sm">
                            + Tambah Coach Baru
                        </a>

                        {{-- Form Pencarian --}}
                        <form method="GET" action="{{ route('pelatih.index') }}" class="flex flex-row gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama coach..." class="rounded-md border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm w-64">
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700 transition ease-in-out duration-150">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('pelatih.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-md text-xs hover:bg-gray-300 transition ease-in-out duration-150 font-bold">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- TABEL DATA --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">No</th>
                                    <th class="py-3 px-6 text-left">Nama Coach</th>
                                    <th class="py-3 px-6 text-left">Email Login</th> {{-- KOLOM BARU --}}
                                    <th class="py-3 px-6 text-left">No. HP (WA)</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse($pelatihs as $index => $pelatih)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $pelatihs->firstItem() + $index }}</td>
                                        
                                        <td class="py-3 px-6 text-left font-bold text-gray-800">
                                            {{ $pelatih->nama_lengkap }}
                                            <div class="text-[10px] text-gray-400 font-normal">
                                                Lahir: {{ \Carbon\Carbon::parse($pelatih->tanggal_lahir)->format('d-m-Y') }}
                                            </div>
                                        </td>

                                        {{-- MENAMPILKAN EMAIL --}}
                                        <td class="py-3 px-6 text-left font-mono text-gray-600">
                                            {{-- Pakai tanda tanya ?? supaya tidak error jika user dihapus manual --}}
                                            {{ $pelatih->user->email ?? '-' }}
                                        </td>

                                        <td class="py-3 px-6 text-left">{{ $pelatih->no_hp ?? '-' }}</td>
                                        
                                        <td class="py-3 px-6 text-center">
                                            @if($pelatih->status == 'Aktif')
                                                <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold border border-green-200">Aktif</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold border border-red-200">Non-Aktif</span>
                                            @endif
                                        </td>
                                        
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center space-x-4">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('pelatih.edit', $pelatih->id) }}" class="w-5 h-5 transform hover:text-indigo-600 hover:scale-110 transition duration-150" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('pelatih.destroy', $pelatih->id) }}" method="POST" onsubmit="return confirm('Yakin hapus Coach {{ $pelatih->nama_lengkap }}? \n\nPERHATIAN: Akun Login coach ini juga akan DIHAPUS PERMANEN!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-5 h-5 transform hover:text-red-600 hover:scale-110 transition duration-150" title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-6 text-center text-gray-500 italic bg-gray-50 rounded-b-lg">
                                            <div class="flex flex-col items-center justify-center">
                                                <span class="text-2xl mb-2">📭</span>
                                                <span>Data coach belum tersedia.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $pelatihs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>