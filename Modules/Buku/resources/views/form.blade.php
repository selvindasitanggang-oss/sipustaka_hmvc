@extends('layouts.app')
@section('content')
@php $isEdit = isset($buku); @endphp
<div class="card"><div class="card-body">
    <h4 class="mb-3">{{ $isEdit ? 'Edit Buku' : 'Tambah Buku' }}</h4>
    <form method="POST" action="{{ $isEdit ? route('buku.update', $buku) : route('buku.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif

        @foreach(['judul'=>'Judul','penulis'=>'Penulis','penerbit'=>'Penerbit','tahun_terbit'=>'Tahun Terbit','stok'=>'Stok'] as $name => $label)
        <div class="mb-3">
            <label class="form-label">{{ $label }}</label>
            <input type="{{ in_array($name, ['tahun_terbit','stok']) ? 'number' : 'text' }}"
                   name="{{ $name }}"
                   value="{{ old($name, $buku->$name ?? '') }}"
                   class="form-control @error($name) is-invalid @enderror">
            @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @endforeach

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection