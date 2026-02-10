<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $alat = Alat::where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->get();
            
        return view('peminjaman.create', compact('alat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tool_id' => 'required|exists:alats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'surat_peminjaman' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string|max:500'
        ], [
            'tool_id.required' => 'Alat harus dipilih',
            'tool_id.exists' => 'Alat yang dipilih tidak tersedia',
            'jumlah.required' => 'Jumlah barang harus diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini',
            'tanggal_kembali.required' => 'Tanggal pengembalian harus diisi',
            'tanggal_kembali.after' => 'Tanggal pengembalian harus setelah tanggal pinjam',
            'surat_peminjaman.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG',
            'surat_peminjaman.max' => 'Ukuran file maksimal 2MB',
        ]);

        $alat = Alat::findOrFail($validated['tool_id']);
        if ($alat->jumlah < $validated['jumlah']) {
            return back()
                ->withErrors(['jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $alat->jumlah])
                ->withInput();
        }

        if ($request->hasFile('surat_peminjaman')) {
            $file = $request->file('surat_peminjaman');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['surat_peminjaman'] = $file->storeAs('surat-peminjaman', $fileName, 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'menunggu';

        Peminjaman::create($validated);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu persetujuan petugas.');
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }
        
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * USER: Ajukan pengembalian alat
     */
    public function ajukanPengembalian(Peminjaman $peminjaman)
    {
        // Validasi: hanya user pemilik peminjaman yang bisa mengajukan
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        // Validasi: hanya peminjaman dengan status "dipinjam" yang bisa diajukan pengembalian
        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->with('error', 'Hanya peminjaman yang sedang dipinjam yang bisa diajukan pengembalian.');
        }

        // Update status menjadi "pengajuan_pengembalian"
        $peminjaman->update([
            'status' => Peminjaman::STATUS_PENGAJUAN_PENGEMBALIAN
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil. Silakan datang ke petugas untuk mengembalikan alat.');
    }

    public function cariAlat(Request $request)
    {
        $keyword = $request->get('q');
        $alat = Alat::where('nama', 'like', "%{$keyword}%")
            ->orWhere('nama_alat', 'like', "%{$keyword}%")
            ->where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->limit(10)
            ->get(['id', 'nama', 'jumlah', 'kategori']);
            
        return response()->json($alat);
    }
}