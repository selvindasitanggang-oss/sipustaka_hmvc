@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card"><div class="card-body">
      <h4 class="mb-3">Register</h4>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        @foreach(['name'=>['Nama','text'],'email'=>['Email','email'],'password'=>['Password','password'],'password_confirmation'=>['Konfirmasi Password','password']] as $f=>[$label,$type])
        <div class="mb-3">
          <label class="form-label">{{ $label }}</label>
          <input type="{{ $type }}" name="{{ $f }}" value="{{ in_array($type,['password']) ? '' : old($f) }}" class="form-control @error($f) is-invalid @enderror">
          @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @endforeach
        <button class="btn btn-primary w-100">Daftar</button>
      </form>
      <p class="mt-3 mb-0 small">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
    </div></div>
  </div>
</div>
@endsection