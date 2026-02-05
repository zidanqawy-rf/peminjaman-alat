<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $results = [];
        $errors = [];
        $imported = 0;

        // Baca file
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            $lineNumber = 1;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $lineNumber++;
                
                if (count($data) < 3) {
                    $errors[] = "Baris $lineNumber: Data tidak lengkap";
                    continue;
                }

                $name = trim($data[0]);
                $email = trim($data[1]);
                $password = trim($data[2]);
                $role = isset($data[3]) ? trim($data[3]) : 'user';

                // Validasi
                if (empty($name)) {
                    $errors[] = "Baris $lineNumber: Nama tidak boleh kosong";
                    continue;
                }

                if (empty($email)) {
                    $errors[] = "Baris $lineNumber: Email tidak boleh kosong";
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris $lineNumber: Format email tidak valid ($email)";
                    continue;
                }

                if (empty($password)) {
                    $errors[] = "Baris $lineNumber: Password tidak boleh kosong";
                    continue;
                }

                if (!in_array($role, ['user', 'petugas', 'admin'])) {
                    $role = 'user';
                }

                // Cek email sudah ada
                if (User::where('email', $email)->exists()) {
                    $errors[] = "Baris $lineNumber: Email sudah terdaftar ($email)";
                    continue;
                }

                // Simpan user
                try {
                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => $role,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris $lineNumber: Gagal menyimpan data - " . $e->getMessage();
                }
            }

            fclose($handle);
        }

        $message = "$imported user berhasil diimport.";
        
        // Return JSON response
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => $message . " " . count($errors) . " baris error.",
                'imported' => $imported,
                'errors' => $errors
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'errors' => []
        ]);
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_user.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, ['Nama', 'Email', 'Password', 'Role (user/petugas/admin)']);

        // Sample data
        fputcsv($handle, ['John Doe', 'john@example.com', 'Password123', 'user']);
        fputcsv($handle, ['Jane Smith', 'jane@example.com', 'Password123', 'petugas']);
        fputcsv($handle, ['Admin User', 'admin2@example.com', 'Password123', 'admin']);

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
