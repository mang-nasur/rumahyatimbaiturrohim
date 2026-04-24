@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill"></i> Manajemen User</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('users.orang-tua.create') }}" class="btn btn-outline-primary">
            <i class="bi bi-person-heart"></i> Buat Akun Orang Tua
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah User
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Cari</label>
                <input type="text" class="form-control form-control-sm" name="search"
                       value="{{ request('search') }}" placeholder="Nama atau email...">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Role</label>
                <select class="form-select form-select-sm" name="role">
                    <option value="">Semua Role</option>
                    <option value="admin"      {{ request('role') == 'admin'      ? 'selected' : '' }}>Admin</option>
                    <option value="staff"      {{ request('role') == 'staff'      ? 'selected' : '' }}>Staff</option>
                    <option value="bendahara"  {{ request('role') == 'bendahara'  ? 'selected' : '' }}>Bendahara</option>
                    <option value="orang_tua"  {{ request('role') == 'orang_tua'  ? 'selected' : '' }}>Orang Tua / Wali</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm w-100">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($users->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada data user</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Anak Yatim (Orang Tua)</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'bendahara')
                                    <span class="badge bg-success">Bendahara</span>
                                @elseif($user->role === 'staff')
                                    <span class="badge bg-info">Staff</span>
                                @elseif($user->role === 'orang_tua')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-person-heart"></i> Orang Tua
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->anakYatim)
                                    <a href="{{ route('anak-yatim.show', $user->anakYatim) }}"
                                       class="text-decoration-none">
                                        <i class="bi bi-person-fill text-primary"></i>
                                        {{ $user->anakYatim->nama_lengkap }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="btn btn-warning"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('users.reset-password', $user) }}"
                                       class="btn btn-info"
                                       title="Reset Password">
                                        <i class="bi bi-key"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger"
                                            title="Hapus"
                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }}
                    dari {{ $users->total() }} data
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus user "' + nama + '"?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/users/' + id;
        form.submit();
    }
}
</script>
@endpush
