<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RumahSakit;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $title = "Pasien";
        $data = Pasien::with('rumahsakit')->get();

        $rumahsakit = RumahSakit::all();

        return view('pasien/pasien', compact('title', 'data', 'rumahsakit'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pasien' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'rs_id' => 'required|exists:rumah_sakits,id',
        ]);

        try {
            $pasien = Pasien::create($validatedData);
            $pasien->load('rumahsakit');
            return response()->json([
                'success' => true,
                'message' => 'Data Pasien berhasil ditambahkan!',
                'pasien' => $pasien
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Pasien: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validatedData = $request->validate([
            'nama_pasien' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'rs_id' => 'required|exists:rumah_sakits,id',
        ]);

        try {
            $pasien->update($validatedData);
            $pasien->load('rumahsakit');
            return response()->json([
                'success' => true,
                'message' => 'Data Pasien berhasil diperbarui!',
                'pasien' => $pasien
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data Pasien: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pasien = Pasien::findOrFail($id);
            $pasien->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Pasien berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data Pasien: ' . $e->getMessage()
            ], 500);
        }
    }
}
