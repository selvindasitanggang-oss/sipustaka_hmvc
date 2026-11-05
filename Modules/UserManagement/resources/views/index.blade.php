@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Kelola User</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
</div>

<table class="table table-bordered bg-white">
    <thead class="table-light">
        <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    @foreach($users as $u)
        <tr>
            <td>{{ $users->firstItem() + $loop->index }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $u->role }}</span></td>
            <td class="d-flex gap-1">
                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">Edit</a>
                @if($u->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $users->links() }}
@endsection