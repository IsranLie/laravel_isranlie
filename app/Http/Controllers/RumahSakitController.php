<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use Illuminate\Http\Request;

class RumahSakitController extends Controller
{
    public function index()
    {
        $title = "Rumah Sakit";
        $data = RumahSakit::all();

        return view('rumahsakit/rumahsakit', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_rs' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|string',
            'telepon' => 'required|string',
        ]);

        try {
            $rs = RumahSakit::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Data Rumah Sakit berhasil ditambahkan!',
                'rumahsakit' => $rs
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Rumah Sakit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, RumahSakit $rs)
    {
        $validatedData = $request->validate([
            'nama_rs' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|string',
            'telepon' => 'required|string',
        ]);

        try {
            $rs->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Data Rumah Sakit berhasil diperbarui!',
                'rumahsakit' => $rs
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data Rumah Sakit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rs = RumahSakit::findOrFail($id);
            $rs->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Rumah Sakit berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data Rumah Sakit: ' . $e->getMessage()
            ], 500);
        }
    }
}
