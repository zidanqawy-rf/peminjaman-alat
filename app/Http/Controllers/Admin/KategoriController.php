<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Alat;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::withCount('alats')->orderBy('nama')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique' => 'Kategori dengan nama ini sudah ada',
            'nama.max' => 'Nama kategori maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique' => 'Kategori dengan nama ini sudah ada',
            'nama.max' => 'Nama kategori maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        // Update kategori di tabel alat jika nama kategori berubah
        if ($kategori->nama !== $validated['nama']) {
            Alat::where('kategori', $kategori->nama)
                ->update(['kategori' => $validated['nama']]);
        }

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        // Cek apakah kategori masih digunakan oleh alat
        $jumlahAlat = Alat::where('kategori', $kategori->nama)->count();
        
        if ($jumlahAlat > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', "Kategori tidak dapat dihapus karena masih digunakan oleh {$jumlahAlat} alat");
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}