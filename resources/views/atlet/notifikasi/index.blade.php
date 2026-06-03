<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Papan Pengumuman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Kecil --}}
            <div class="mb-6 px-2 flex justify-between items-end">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">🔔 Riwayat Notifikasi</h3>
                    <p class="text-sm text-gray-500">Arsip semua pemberitahuan masuk.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-0"> {{-- Padding 0 biar listnya rapi --}}
                    
                    @forelse($notifikasis as $notifikasi)
                        {{-- LOGIKA WARNA: 
                             Jika is_read = 0 (Belum dibaca/diklik) -> Background Biru Muda 
                             Jika is_read = 1 (Sudah) -> Putih biasa 
                        --}}
                        <div class="p-6 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition flex gap-4 
                                    {{ $notifikasi->is_read == 0 ? 'bg-blue-50' : 'bg-white' }}">
                            
                            {{-- KOLOM IKON --}}
                            <div class="flex-shrink-0">
                                @if($notifikasi->is_read == 0)
                                    {{-- Ikon Biru (Belum Baca) --}}
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                @else
                                    {{-- Ikon Abu (Sudah Baca) --}}
                                    <div class="w-10 h-10 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7.171-2.653a2 2 0 011.378 0l7.172 2.653c.52.192.89.686.89 1.664V19a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- KOLOM TEXT --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-bold text-lg {{ $notifikasi->is_read == 0 ? 'text-blue-900' : 'text-gray-700' }}">
                                        {{ $notifikasi->judul }}
                                        @if($notifikasi->is_read == 0)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Baru
                                            </span>
                                        @endif
                                    </h3>
                                    <span class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $notifikasi->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 leading-relaxed text-sm">
                                    {{ $notifikasi->pesan }}
                                </p>

                                {{-- Jika Notifikasi punya Link (misal ke tagihan), tampilkan tombol --}}
                                @if(!empty($notifikasi->link))
                                    <div class="mt-3">
                                        <a href="{{ url($notifikasi->link) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                            Lihat Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        {{-- Tampilan Kosong --}}
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Belum ada pengumuman</h3>
                            <p class="mt-1 text-sm text-gray-500">Semua riwayat notifikasi akan tersimpan di sini.</p>
                        </div>
                    @endforelse

                    {{-- Pagination (Hanya muncul jika lebih dari 10 notif) --}}
                    @if($notifikasis->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-gray-50">
                            {{ $notifikasis->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>