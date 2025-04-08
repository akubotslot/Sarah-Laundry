<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = Pelanggan::oldest()->paginate(10);
        return view('pelanggan.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|unique:pelanggans,nomor_whatsapp',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('pelanggan.create')
                ->withErrors($validator)
                ->withInput();
        }

        Pelanggan::create($request->all());

        if ($request->has('from_pesanan')) {
            return redirect()
                ->back()
                ->with('success', 'Pelanggan baru berhasil ditambahkan');
        }

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|unique:pelanggans,nomor_whatsapp,' . $pelanggan->id,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('pelanggan.edit', $pelanggan)
                ->withErrors($validator)
                ->withInput();
        }

        $pelanggan->update($request->all());

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus');
    }
    public function search(Request $request)
{
    $keyword = $request->input('keyword');

    $pelanggans = Pelanggan::where('nama', 'like', "%$keyword%")
                    ->orWhere('nomor_whatsapp', 'like', "%$keyword%")
                    ->orderBy('nama')
                    ->get();

    return response()->json($pelanggans);
}

}
