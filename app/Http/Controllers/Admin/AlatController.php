<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::orderBy('created_at', 'desc')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        
        return view('admin.alat.index', compact('alats', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'status' => 'required|string|in:tersedia,dipinjam,rusak',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama alat wajib diisi',
            'kategori.required' => 'Kategori wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
            'status.required' => 'Status wajib dipilih',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('alat-images', 'public');
        }

        Alat::create($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'status' => 'required|string|in:tersedia,dipinjam,rusak',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama alat wajib diisi',
            'kategori.required' => 'Kategori wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
            'status.required' => 'Status wajib dipilih',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('alat-images', 'public');
        }

        $alat->update($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);
        
        // Delete image if exists
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }
        
        $alat->delete();

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus');
    }

    /**
     * Download Template Excel
     */
    public function template()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set header
            $sheet->setCellValue('A1', 'Nama');
            $sheet->setCellValue('B1', 'Kategori');
            $sheet->setCellValue('C1', 'Jumlah');
            $sheet->setCellValue('D1', 'Status');
            
            // Style header
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
            
            // Sample data
            $sheet->setCellValue('A2', 'Laptop Asus');
            $sheet->setCellValue('B2', 'Elektronik');
            $sheet->setCellValue('C2', '5');
            $sheet->setCellValue('D2', 'tersedia');
            
            $sheet->setCellValue('A3', 'Proyektor Epson');
            $sheet->setCellValue('B3', 'Elektronik');
            $sheet->setCellValue('C3', '2');
            $sheet->setCellValue('D3', 'tersedia');
            
            $sheet->setCellValue('A4', 'Bola Basket');
            $sheet->setCellValue('B4', 'Olahraga');
            $sheet->setCellValue('C4', '10');
            $sheet->setCellValue('D4', 'tersedia');
            
            // Auto size columns
            foreach(range('A','D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create writer
            $writer = new Xlsx($spreadsheet);
            
            // Set headers for download
            $filename = 'template_alat_' . date('Y-m-d_His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            return redirect()->route('admin.alat.index')
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    /**
     * Import from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB
        ], [
            'file.required' => 'File Excel wajib dipilih',
            'file.mimes' => 'File harus berformat .xlsx atau .xls',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            
            // Load Excel file
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Remove header row
            array_shift($rows);
            
            $imported = 0;
            $errors = [];
            $skipped = 0;
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index mulai dari 0 dan skip header
                
                // Skip empty rows
                if (empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3])) {
                    $skipped++;
                    continue;
                }
                
                // Validate required fields
                if (empty($row[0])) {
                    $errors[] = "Baris {$rowNumber}: Nama alat tidak boleh kosong";
                    continue;
                }
                
                if (empty($row[1])) {
                    $errors[] = "Baris {$rowNumber}: Kategori tidak boleh kosong";
                    continue;
                }
                
                // Validate status
                $status = strtolower(trim($row[3] ?? ''));
                if (!in_array($status, ['tersedia', 'dipinjam', 'rusak'])) {
                    $errors[] = "Baris {$rowNumber}: Status harus tersedia/dipinjam/rusak (Anda menulis: '{$row[3]}')";
                    continue;
                }
                
                // Validate jumlah
                $jumlah = (int)($row[2] ?? 0);
                if ($jumlah < 0) {
                    $errors[] = "Baris {$rowNumber}: Jumlah tidak boleh negatif";
                    continue;
                }
                
                // Check if kategori exists
                $kategoriExists = Kategori::where('nama', trim($row[1]))->exists();
                if (!$kategoriExists) {
                    $errors[] = "Baris {$rowNumber}: Kategori '{$row[1]}' tidak ditemukan. Silakan tambah kategori terlebih dahulu.";
                    continue;
                }
                
                // Insert data
                try {
                    Alat::create([
                        'nama' => trim($row[0]),
                        'kategori' => trim($row[1]),
                        'jumlah' => $jumlah,
                        'status' => $status,
                        'gambar' => null, // Import tidak support gambar
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Gagal menyimpan data - " . $e->getMessage();
                }
            }
            
            // Generate result message
            if ($imported > 0 && empty($errors)) {
                $message = "✅ Berhasil import {$imported} data alat";
                if ($skipped > 0) {
                    $message .= " ({$skipped} baris kosong dilewati)";
                }
                return redirect()->route('admin.alat.index')->with('success', $message);
                
            } elseif ($imported > 0 && !empty($errors)) {
                $errorCount = count($errors);
                $message = "⚠️ Import berhasil untuk {$imported} data, namun {$errorCount} data gagal: ";
                
                // Show first 5 errors
                $errorPreview = array_slice($errors, 0, 5);
                $message .= implode('; ', $errorPreview);
                
                if ($errorCount > 5) {
                    $message .= "; dan " . ($errorCount - 5) . " error lainnya.";
                }
                
                return redirect()->route('admin.alat.index')->with('success', $message);
                
            } else {
                $message = "❌ Import gagal. Error: " . implode('; ', array_slice($errors, 0, 5));
                return redirect()->route('admin.alat.index')->with('error', $message);
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.alat.index')
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}