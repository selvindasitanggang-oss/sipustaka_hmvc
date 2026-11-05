<?php

namespace Modules\Peminjaman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Peminjaman\Http\Requests\PeminjamanRequest;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('peminjaman::index');
    }

    public function list()
    {
        return response()->json(Peminjaman::latest()->get());
    }

    public function show(Peminjaman $peminjaman)
    {
        return response()->json($peminjaman);
    }

    public function store(PeminjamanRequest $request)
    {
        $data = $request->validated();
        unset($data['bukti']);

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        }

        $p = Peminjaman::create($data);

        return response()->json(['message' => 'Data peminjaman berhasil ditambahkan.', 'data' => $p], 201);
    }

    public function update(PeminjamanRequest $request, Peminjaman $peminjaman)
    {
        $data = $request->validated();
        unset($data['bukti']);

        if ($request->hasFile('bukti')) {
            if ($peminjaman->bukti) {
                Storage::disk('public')->delete($peminjaman->bukti); // hapus berkas lama
            }
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        }

        $peminjaman->update($data);

        return response()->json(['message' => 'Data peminjaman berhasil diubah.', 'data' => $peminjaman]);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->bukti) {
            Storage::disk('public')->delete($peminjaman->bukti);
        }
        $peminjaman->delete();

        return response()->json(['message' => 'Data peminjaman berhasil dihapus.']);
    }
}