@extends('layouts.app')

@section('content')
<div class="p-6 max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Tambah Kecamatan</h1>

    <form action="{{ route('kecamatan.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Kecamatan</label>
            <input
                type="text"
                name="nama_kecamatan"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <div class="flex gap-2">
            <button
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded"
            >
                Simpan
            </button>

            <a
                href="{{ route('kecamatan.index') }}"
                class="px-4 py-2 border rounded"
            >
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
