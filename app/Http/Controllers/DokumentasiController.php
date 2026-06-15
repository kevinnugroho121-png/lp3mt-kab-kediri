<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    // 1. Tampilkan tabel manajemen foto
    public function index()
    {
        $dokumentasis = Dokumentasi::latest()->get();
        return view('admin.dokumentasi.index', compact('dokumentasis'));
    }

    // 2. Simpan foto baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto'  => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        // Upload ke folder 'dokumentasi' di storage/app/public/dokumentasi
        $path = $request->file('foto')->store('dokumentasi', 'public');

        Dokumentasi::create([
            'judul'     => $request->judul,
            'foto_path' => $path,
        ]);

        return back()->with('success', 'Foto dokumentasi berhasil ditambahkan!');
    }

    // Tambahkan di bawah fungsi store
    public function edit($id)
    {
        $dok = Dokumentasi::findOrFail($id);
        return view('admin.dokumentasi.edit', compact('dok'));
    }

    public function update(Request $request, $id)
    {
        $dok = Dokumentasi::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = ['judul' => $request->judul];

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            Storage::disk('public')->delete($dok->foto_path);
            // Upload foto baru
            $data['foto_path'] = $request->file('foto')->store('dokumentasi', 'public');
        }

        $dok->update($data);
        return redirect()->route('dokumentasi.index')->with('success', 'Dokumentasi berhasil diupdate!');
    }

    // 3. Hapus foto
    public function destroy($id)
    {
        $dok = Dokumentasi::findOrFail($id);
        
        // Hapus file fisik dari storage
        if ($dok->foto_path) {
            Storage::disk('public')->delete($dok->foto_path);
        }

        $dok->delete();
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}