<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of peminjaman for current user
     */
    public function index()
    {
        // Filter peminjaman yang alat-nya masih ada
        $peminjaman = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->whereHas('alat') // Filter hanya peminjaman dengan alat yang masih ada
            ->latest()
            ->paginate(10);
            
        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show the form for creating a new peminjaman
     */
    public function create()
    {
        $alat = Alat::where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->get();
            
        return view('peminjaman.create', compact('alat'));
    }

    /**
     * Store a newly created peminjaman in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tool_id' => 'required|exists:alats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);
                    $today = Carbon::today();
                    $tomorrow = Carbon::tomorrow();
                    
                    // Validasi hanya hari ini atau besok
                    if (!$date->isSameDay($today) && !$date->isSameDay($tomorrow)) {
                        $fail('Tanggal peminjaman hanya boleh hari ini atau besok.');
                    }
                },
            ],
            'tanggal_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],
            'surat_peminjaman' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string|max:500',
            'keperluan' => 'nullable|string|max:500'
        ], [
            'tool_id.required' => 'Alat harus dipilih',
            'tool_id.exists' => 'Alat yang dipilih tidak tersedia',
            'jumlah.required' => 'Jumlah barang harus diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini',
            'tanggal_kembali.required' => 'Tanggal pengembalian harus diisi',
            'tanggal_kembali.after_or_equal' => 'Tanggal pengembalian tidak boleh lebih awal dari tanggal peminjaman',
            'surat_peminjaman.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG',
            'surat_peminjaman.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Validasi stok alat
        $alat = Alat::findOrFail($validated['tool_id']);
        if ($alat->jumlah < $validated['jumlah']) {
            return back()
                ->withErrors(['jumlah' => 'Stok tidak mencukupi. Stok tersedia: ' . $alat->jumlah])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Upload surat peminjaman jika ada
            if ($request->hasFile('surat_peminjaman')) {
                $file = $request->file('surat_peminjaman');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $validated['surat_peminjaman'] = $file->storeAs('surat-peminjaman', $fileName, 'public');
            }

            // Set user dan status
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu';

            // Buat peminjaman baru
            Peminjaman::create($validated);

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu persetujuan petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file jika ada error
            if (isset($validated['surat_peminjaman'])) {
                Storage::disk('public')->delete($validated['surat_peminjaman']);
            }
            
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified peminjaman
     */
    public function show(Peminjaman $peminjaman)
    {
        // Validasi kepemilikan
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }
        
        // Load relasi dan cek apakah alat masih ada
        $peminjaman->load(['user', 'alat']);
        
        // Cek apakah alat masih ada
        if (!$peminjaman->alat) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Data alat untuk peminjaman ini tidak ditemukan.');
        }
        
        // Ambil semua alat untuk keperluan lain (opsional)
        $alat = Alat::where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->get();
        
        return view('peminjaman.show', compact('peminjaman', 'alat'));
    }

    /**
     * USER: Ajukan pengembalian dengan foto dan tanggal
     * PERBAIKAN: Tanggal pengembalian bisa kapan saja (tidak dibatasi)
     */
    public function ajukanPengembalian(Request $request, Peminjaman $peminjaman)
    {
        // Validasi kepemilikan
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi status - gunakan string literal karena mungkin tidak ada konstanta STATUS_DIPINJAM
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman tidak dalam status dipinjam.');
        }

        // Validasi input - MINIMAL HARI INI (tidak bisa kemarin)
        $request->validate([
            'tanggal_kembali_actual' => 'required|date|after_or_equal:today',
            'foto_pengembalian' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'tanggal_kembali_actual.required' => 'Tanggal pengembalian harus diisi',
            'tanggal_kembali_actual.date' => 'Format tanggal tidak valid',
            'tanggal_kembali_actual.after_or_equal' => 'Tanggal pengembalian tidak boleh kurang dari hari ini',
            'foto_pengembalian.required' => 'Foto dokumentasi pengembalian harus diupload',
            'foto_pengembalian.image' => 'File harus berupa gambar',
            'foto_pengembalian.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_pengembalian.max' => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto
            $fotoPath = $request->file('foto_pengembalian')->store('pengembalian', 'public');

            // Set tanggal kembali actual
            $tanggalKembaliActual = Carbon::parse($request->tanggal_kembali_actual)->endOfDay();

            // Hitung denda dan hari terlambat
            $peminjaman->tanggal_kembali_actual = $tanggalKembaliActual;
            $denda = $peminjaman->hitungDenda();
            $hariTerlambat = $peminjaman->hitungHariTerlambat();

            // Update data peminjaman
            $peminjaman->update([
                'tanggal_kembali_actual' => $tanggalKembaliActual,
                'foto_pengembalian' => $fotoPath,
                'denda' => $denda,
                'jumlah_hari_terlambat' => $hariTerlambat,
                'status' => 'pengajuan_pengembalian',
            ]);

            DB::commit();

            // Jika ada denda, redirect ke halaman pembayaran
            if ($denda > 0) {
                return redirect()->route('peminjaman.show', $peminjaman)
                    ->with('warning', "Anda terlambat {$hariTerlambat} hari. Silakan upload bukti pembayaran denda sebesar Rp " . number_format($denda, 0, ',', '.'));
            }

            return redirect()->route('peminjaman.show', $peminjaman)
                ->with('success', 'Pengajuan pengembalian berhasil! Menunggu verifikasi petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto jika ada error
            if (isset($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * USER: Upload bukti pembayaran denda
     */
    public function uploadBuktiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        // Validasi kepemilikan
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi ada denda
        if ($peminjaman->denda <= 0) {
            return back()->with('error', 'Tidak ada denda yang harus dibayar.');
        }

        // Validasi input
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format harus jpeg, png, atau jpg',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // Hapus bukti lama jika ada
            if ($peminjaman->bukti_pembayaran_denda) {
                Storage::disk('public')->delete($peminjaman->bukti_pembayaran_denda);
            }

            // Upload bukti baru
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            // Update status
            $peminjaman->update([
                'bukti_pembayaran_denda' => $buktiPath,
                'status_pembayaran_denda' => 'menunggu_verifikasi',
                'catatan_admin_pembayaran' => null, // Reset catatan penolakan
            ]);

            DB::commit();

            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file jika ada error
            if (isset($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * USER: Cancel peminjaman (hanya untuk status pending/menunggu)
     */
    public function cancel(Peminjaman $peminjaman)
    {
        // Validasi kepemilikan
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Hanya bisa cancel jika status menunggu
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dibatalkan',
                'catatan' => ($peminjaman->catatan ? $peminjaman->catatan . ' | ' : '') . 'Dibatalkan oleh peminjam',
            ]);

            // Hapus file surat peminjaman jika ada
            if ($peminjaman->surat_peminjaman) {
                Storage::disk('public')->delete($peminjaman->surat_peminjaman);
            }

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Cari alat berdasarkan keyword
     */
    public function cariAlat(Request $request)
    {
        $keyword = $request->get('q');
        
        $alat = Alat::where(function($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nama_alat', 'like', "%{$keyword}%");
            })
            ->where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->limit(10)
            ->get(['id', 'nama', 'jumlah', 'kategori']);
            
        return response()->json($alat);
    }
}