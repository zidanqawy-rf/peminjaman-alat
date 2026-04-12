<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index()
    {
        $peminjaman = Peminjaman::with(['items.alat'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    // ── CREATE ────────────────────────────────────────────
    public function create()
    {
        $alat = Alat::where('jumlah', '>', 0)
            ->where('status', 'tersedia')
            ->get();

        return view('peminjaman.create', compact('alat'));
    }

    // ── STORE (Multi Item) ────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pinjam'    => 'required|date|after_or_equal:today',
            'tanggal_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
            'surat_peminjaman'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'catatan'           => 'nullable|string|max:500',
            'keperluan'         => 'nullable|string|max:500',
            // items array
            'items'             => 'required|array|min:1',
            'items.*.alat_id'   => 'required|exists:alats,id',
            'items.*.jumlah'    => 'required|integer|min:1',
        ], [
            'items.required'            => 'Pilih minimal 1 alat.',
            'items.*.alat_id.required'  => 'Alat harus dipilih.',
            'items.*.jumlah.required'   => 'Jumlah harus diisi.',
            'items.*.jumlah.min'        => 'Jumlah minimal 1.',
        ]);

        // Validasi stok per alat
        foreach ($request->items as $idx => $item) {
            $alat = Alat::findOrFail($item['alat_id']);
            if ($alat->jumlah < $item['jumlah']) {
                return back()
                    ->withErrors(["items.{$idx}.jumlah" => "Stok {$alat->nama} tidak cukup. Tersedia: {$alat->jumlah}"])
                    ->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Upload surat
            $suratPath = null;
            if ($request->hasFile('surat_peminjaman')) {
                $file      = $request->file('surat_peminjaman');
                $suratPath = $file->storeAs('surat-peminjaman', time() . '_' . $file->getClientOriginalName(), 'public');
            }

            // Buat record peminjaman
            $peminjaman = Peminjaman::create([
                'user_id'          => Auth::id(),
                'tanggal_pinjam'   => $request->tanggal_pinjam,
                'tanggal_kembali'  => $request->tanggal_kembali,
                'surat_peminjaman' => $suratPath,
                'catatan'          => $request->catatan,
                'keperluan'        => $request->keperluan,
                'status'           => 'menunggu',
            ]);

            // Buat item per alat
            foreach ($request->items as $item) {
                PeminjamanItem::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $item['alat_id'],
                    'jumlah'        => $item['jumlah'],
                ]);
            }

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu persetujuan petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($suratPath)) Storage::disk('public')->delete($suratPath);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // ── SHOW ──────────────────────────────────────────────
    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) abort(403);

        $peminjaman->load(['user', 'items.alat']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    // ── AJUKAN PENGEMBALIAN ───────────────────────────────
    public function ajukanPengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) abort(403);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman tidak dalam status dipinjam.');
        }

        $request->validate([
            'tanggal_kembali_actual' => 'required|date|after_or_equal:today',
            'foto_pengembalian'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $fotoPath = $request->file('foto_pengembalian')->store('pengembalian', 'public');

            $tanggalActual = Carbon::parse($request->tanggal_kembali_actual)->startOfDay();
            $tanggalRencana = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();

            $hariTerlambat = $tanggalActual->gt($tanggalRencana)
                ? $tanggalRencana->diffInDays($tanggalActual)
                : 0;
            $denda = $hariTerlambat * 5000;

            $peminjaman->update([
                'tanggal_kembali_actual' => $tanggalActual,
                'foto_pengembalian'      => $fotoPath,
                'denda'                  => $denda,
                'jumlah_hari_terlambat'  => $hariTerlambat,
                'status'                 => 'pengajuan_pengembalian',
            ]);

            DB::commit();

            if ($denda > 0) {
                return redirect()->route('peminjaman.show', $peminjaman)
                    ->with('warning', "Terlambat {$hariTerlambat} hari. Denda: Rp " . number_format($denda, 0, ',', '.') . ". Silakan upload bukti pembayaran.");
            }

            return redirect()->route('peminjaman.show', $peminjaman)
                ->with('success', 'Pengajuan pengembalian berhasil! Menunggu verifikasi petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($fotoPath)) Storage::disk('public')->delete($fotoPath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── UPLOAD BUKTI PEMBAYARAN ───────────────────────────
    public function uploadBuktiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) abort(403);

        if ($peminjaman->denda <= 0) {
            return back()->with('error', 'Tidak ada denda yang harus dibayar.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($peminjaman->bukti_pembayaran_denda) {
                Storage::disk('public')->delete($peminjaman->bukti_pembayaran_denda);
            }

            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            $peminjaman->update([
                'bukti_pembayaran_denda'  => $buktiPath,
                'status_pembayaran_denda' => 'menunggu_verifikasi',
                'catatan_admin_pembayaran' => null,
            ]);

            DB::commit();
            return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi petugas.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath)) Storage::disk('public')->delete($buktiPath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ── CANCEL ────────────────────────────────────────────
    public function cancel(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== Auth::id()) abort(403);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update(['status' => 'dibatalkan']);

            if ($peminjaman->surat_peminjaman) {
                Storage::disk('public')->delete($peminjaman->surat_peminjaman);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}