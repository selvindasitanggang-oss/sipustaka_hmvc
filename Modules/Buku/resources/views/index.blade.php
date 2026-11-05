@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Buku</h4>
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('buku.create') }}" class="btn btn-primary">+ Tambah Buku</a>
    @endif
</div>

<form method="GET" class="d-flex gap-2 mb-3">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul / penulis / penerbit...">
    <button class="btn btn-outline-primary">Cari</button>
</form>

<table class="table table-bordered bg-white">
    <thead class="table-light">
        <tr>
            <th>No</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Stok</th>
            @if(auth()->user()->role === 'admin')<th>Aksi</th>@endif
        </tr>
    </thead>
    <tbody>
    @forelse($books as $b)
        <tr>
            <td>{{ $books->firstItem() + $loop->index }}</td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->penulis }}</td>
            <td>{{ $b->penerbit }}</td>
            <td>{{ $b->tahun_terbit }}</td>
            <td>{{ $b->stok }}</td>
            @if(auth()->user()->role === 'admin')
            <td class="d-flex gap-1">
                <a href="{{ route('buku.edit', $b) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('buku.destroy', $b) }}" onsubmit="return confirm('Hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
            @endif
        </tr>
    @empty
        <tr><td colspan="7" class="text-center">Data buku tidak ditemukan.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $books->links() }}
@endsection