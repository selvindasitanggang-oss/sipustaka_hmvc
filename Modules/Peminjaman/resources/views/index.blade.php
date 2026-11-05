@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Peminjaman</h4>
    <button class="btn btn-primary" onclick="tambahData()">+ Tambah Peminjaman</button>
</div>

<table class="table table-bordered bg-white">
    <thead class="table-light">
        <tr>
            <th>No</th><th>Nama Peminjam</th><th>Judul Buku</th><th>Tgl Pinjam</th>
            <th>Tgl Kembali</th><th>Status</th><th>Bukti</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tbody"></tbody>
</table>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
  <div class="modal-dialog">
    <form id="formPeminjaman" class="modal-content" enctype="multipart/form-data" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Peminjaman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Peminjam</label>
          <input type="text" name="nama_peminjam" class="form-control">
          <div class="invalid-feedback" data-error="nama_peminjam"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Judul Buku</label>
          <input type="text" name="judul_buku" class="form-control">
          <div class="invalid-feedback" data-error="judul_buku"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal Pinjam</label>
          <input type="date" name="tanggal_pinjam" class="form-control">
          <div class="invalid-feedback" data-error="tanggal_pinjam"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal Kembali</label>
          <input type="date" name="tanggal_kembali" class="form-control">
          <div class="invalid-feedback" data-error="tanggal_kembali"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="Dipinjam">Dipinjam</option>
            <option value="Dikembalikan">Dikembalikan</option>
          </select>
          <div class="invalid-feedback" data-error="status"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Foto Bukti (jpg/png/pdf, maks 2 MB)</label>
          <input type="file" name="bukti" class="form-control">
          <div class="invalid-feedback" data-error="bukti"></div>
          <div id="buktiLama" class="small mt-1"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const baseUrl = "{{ url('peminjaman') }}";
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const modal = new bootstrap.Modal(document.getElementById('modalForm'));
const form = document.getElementById('formPeminjaman');
let editId = null;

const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

function notif(msg, type = 'success') {
    document.getElementById('alert-area').innerHTML =
        `<div class="alert alert-${type} alert-dismissible fade show">${esc(msg)}
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
}

function clearErrors() {
    form.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
    form.querySelectorAll('[data-error]').forEach(e => e.textContent = '');
}

function showErrors(errors) {
    for (const field in errors) {
        const input = form.querySelector(`[name="${field}"]`);
        const fb = form.querySelector(`[data-error="${field}"]`);
        if (input) input.classList.add('is-invalid');
        if (fb) fb.textContent = errors[field][0];
    }
}

async function loadData() {
    const res = await fetch(`${baseUrl}/data`, { headers: { 'Accept': 'application/json' } });
    const rows = await res.json();
    const tbody = document.getElementById('tbody');

    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center">Belum ada data.</td></tr>';
        return;
    }

    tbody.innerHTML = rows.map((r, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${esc(r.nama_peminjam)}</td>
            <td>${esc(r.judul_buku)}</td>
            <td>${esc(r.tanggal_pinjam)}</td>
            <td>${esc(r.tanggal_kembali)}</td>
            <td><span class="badge ${r.status === 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success'}">${esc(r.status)}</span></td>
            <td>${r.bukti_url ? `<a href="${esc(r.bukti_url)}" target="_blank">Lihat berkas</a>` : '-'}</td>
            <td class="d-flex gap-1">
                <button class="btn btn-sm btn-warning" onclick="editData(${r.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="hapusData(${r.id})">Hapus</button>
            </td>
        </tr>`).join('');
}

function tambahData() {
    editId = null;
    form.reset();
    clearErrors();
    document.getElementById('modalTitle').textContent = 'Tambah Peminjaman';
    document.getElementById('buktiLama').innerHTML = '';
    modal.show();
}

async function editData(id) {
    editId = id;
    form.reset();
    clearErrors();
    const res = await fetch(`${baseUrl}/${id}`, { headers: { 'Accept': 'application/json' } });
    const d = await res.json();

    ['nama_peminjam','judul_buku','tanggal_pinjam','tanggal_kembali','status'].forEach(f => {
        form.querySelector(`[name="${f}"]`).value = d[f];
    });
    document.getElementById('buktiLama').innerHTML = d.bukti_url
        ? `Berkas saat ini: <a href="${esc(d.bukti_url)}" target="_blank">buka</a> (pilih file baru untuk mengganti)`
        : '';
    document.getElementById('modalTitle').textContent = 'Edit Peminjaman';
    modal.show();
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const fd = new FormData(form);
    let url = baseUrl;
    if (editId) {
        url = `${baseUrl}/${editId}`;
        fd.append('_method', 'PUT'); // method spoofing agar upload file jalan
    }

    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: fd
    });
    const json = await res.json();

    if (res.status === 422) { showErrors(json.errors); return; }
    if (!res.ok) { notif('Terjadi kesalahan pada server.', 'danger'); return; }

    modal.hide();
    notif(json.message);
    loadData();
});

async function hapusData(id) {
    if (!confirm('Hapus data peminjaman ini?')) return;
    const res = await fetch(`${baseUrl}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
    });
    const json = await res.json();
    if (res.ok) { notif(json.message); loadData(); }
    else notif('Gagal menghapus data.', 'danger');
}

loadData();
</script>
@endpush