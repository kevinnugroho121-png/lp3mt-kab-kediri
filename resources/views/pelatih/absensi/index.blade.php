<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Isi Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="font-semibold mb-1">Jadwal: {{ $jadwal->kategori }}</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                    </p>

                    <form action="{{ route('pelatih.absensi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                        <input type="hidden" name="tanggal_absensi" value="{{ now()->toDateString() }}">

                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Atlet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($atlets as $atlet)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $atlet->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="radio" name="absensi[{{ $atlet->id }}]" value="Hadir" required> Hadir
                                            <input type="radio" name="absensi[{{ $atlet->id }}]" value="Izin"> Izin
                                            <input type="radio" name="absensi[{{ $atlet->id }}]" value="Sakit"> Sakit
                                            <input type="radio" name="absensi[{{ $atlet->id }}]" value="Alfa"> Alfa
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Simpan Absensi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>