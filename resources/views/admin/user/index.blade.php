<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User System') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: JUDUL & TOMBOL --}}
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Daftar Pengguna</h3>
                    <p class="text-xs text-gray-500">Kelola akun Admin, Verifikator, dan Korcam</p>
                </div>
                
                <div class="flex gap-2">
                    {{-- SEARCH BOX --}}
                    <form action="{{ route('user.index') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..." 
                               class="w-64 border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 shadow-sm">
                        <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>

                    {{-- TOMBOL TAMBAH --}}
                    <a href="{{ route('user.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-medium flex items-center gap-1 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>

            {{-- PESAN SUKSES --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded shadow-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            {{-- TABEL ALA EXCEL (BORDERED & COMPACT) --}}
            <div class="bg-white border border-gray-400 overflow-hidden shadow-sm">
                <table class="w-full text-sm border-collapse">
                    {{-- HEADER --}}
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
                            <th class="border border-gray-300 px-2 py-2 text-center w-12 font-bold">No</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Nama Lengkap</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Email (Login)</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Jabatan (Role)</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold">Wilayah Tugas</th>
                            <th class="border border-gray-300 px-3 py-2 text-center font-bold w-24">Aksi</th>
                        </tr>
                    </thead>
                    
                    {{-- BODY --}}
                    <tbody class="text-gray-600">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-yellow-50 transition duration-150">
                                
                                {{-- NO --}}
                                <td class="border border-gray-300 px-2 py-1 text-center bg-gray-50 font-medium">
                                    {{ $users->firstItem() + $index }}
                                </td>

                                {{-- NAMA --}}
                                <td class="border border-gray-300 px-3 py-1 font-bold text-gray-800">
                                    {{ $user->name }}
                                </td>

                                {{-- EMAIL --}}
                                <td class="border border-gray-300 px-3 py-1 text-gray-600">
                                    {{ $user->email }}
                                </td>

                                {{-- JABATAN (ROLE) --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    @if($user->role == 'admin')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                            Super Admin
                                        </span>
                                    @elseif($user->role == 'verifikator')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            Verifikator Kab
                                        </span>
                                    @elseif($user->role == 'korcam')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                            Korcam - {{ $user->jabatan_korcam ?? 'Tim' }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-xs">{{ $user->role }}</span>
                                    @endif
                                </td>

                                {{-- WILAYAH TUGAS --}}
                                <td class="border border-gray-300 px-3 py-1 text-center">
                                    @if($user->kecamatan)
                                        <span class="font-bold text-gray-700">Kec. {{ $user->kecamatan->nama_kecamatan }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Seluruh Kabupaten</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="border border-gray-300 px-2 py-1 text-center">
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-red-100 text-red-500 transition" title="Hapus User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="text-xs">Belum ada data user.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>