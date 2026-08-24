<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pengumuman (Broadcast)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header & Tombol Tambah --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Pesan Terkirim</h3>
                        <a href="{{ route('notifikasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-500 shadow-md transition">
                            + Buat Pengumuman Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penerima</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Pesan</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($notifikasis as $notif)
                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- 1. Tanggal --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $notif->created_at->format('d/m/Y H:i') }}
                                        </td>

                                        {{-- 2. Penerima (Nama User) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                            {{ $notif->user->name ?? 'User Terhapus' }}
                                            <div class="text-xs text-gray-500 font-normal">
                                                Role: {{ ucfirst($notif->user->role ?? '-') }}
                                            </div>
                                        </td>

                                        {{-- 3. Isi Pesan --}}
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div class="font-bold text-blue-800">{{ $notif->judul }}</div>
                                            <div class="text-gray-600 truncate max-w-xs text-xs italic">
                                                {{ Str::limit($notif->pesan, 60) }}
                                            </div>
                                        </td>

                                        {{-- 4. Status Dibaca --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($notif->is_read)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    ✅ Sudah Dibaca
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    ⏳ Belum Dibaca
                                                </span>
                                            @endif
                                        </td>

                                        {{-- 5. Aksi (Hapus Saja) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus notifikasi ini untuk user tersebut?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md border border-red-200 font-bold transition">
                                                     Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                                <p>Belum ada pengumuman yang dikirim.</p>
                                                <p class="text-xs mt-1">Klik tombol "Buat Pengumuman Baru" di atas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Link --}}
                    <div class="mt-4">
                        {{ $notifikasis->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>