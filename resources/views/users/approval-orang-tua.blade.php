@extends('layouts.app')

@section('title', 'Approval Pendaftaran Orang Tua')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-person-check-fill"></i> Approval Pendaftaran Orang Tua</h2>
        <p class="text-muted mb-0">Verifikasi dan setujui akun orang tua / wali yang mendaftar</p>
    </div>
</div>

{{-- Statistik --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalPending }}</h3>
                <small>Menunggu Persetujuan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalAktif }}</h3>
                <small>Sudah Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalDitolak }}</h3>
                <small>Ditolak</small>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('users.approval-orang-tua') }}" class="d-flex gap-2 align-items-center">
            <label class="mb-0 text-muted small">Tampilkan:</label>
            <select class="form-select form-select-sm w-auto" name="status" onchange="this.form.submit()">
                <option value="pending"   {{ $status == 'pending'   ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="aktif"     {{ $status == 'aktif'     ? 'selected' : '' }}>Sudah Disetujui</option>
                <option value="ditolak"   {{ $status == 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                <option value="semua"     {{ $status == 'semua'     ? 'selected' : '' }}>Semua</option>
            </select>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            Daftar Pendaftaran
            <span class="badge bg-secondary ms-2">{{ $users->total() }} data</span>
            @if($totalPending > 0 && $status === 'pending')
                <span class="badge bg-warning ms-1">{{ $totalPending }} menunggu</span>
            @endif
        </h5>
    </div>
    <div class="card-body p-0">
        @if($users->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Tidak ada data pendaftaran</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pendaftar</th>
                            <th>Email</th>
                            <th>Anak Yatim</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-center">Status</th>
                            <th>Diproses Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
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
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                @if($user->status_akun === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($user->status_akun === 'aktif')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($user->status_akun === 'ditolak')
                                    <span class="badge bg-danger" title="{{ $user->catatan_penolakan }}">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->approvedByUser)
                                    {{ $user->approvedByUser->name }}
                                    <div class="text-muted small">{{ $user->approved_at?->format('d/m/Y H:i') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if($user->catatan_penolakan)
                                    <div class="text-danger small">
                                        <i class="bi bi-chat-left-text"></i> {{ $user->catatan_penolakan }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->status_akun === 'pending')
                                    {{-- Setujui --}}
                                    <form method="POST"
                                          action="{{ route('users.approve-orang-tua', $user) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Setujui pendaftaran {{ $user->name }}?\nOrang tua akan bisa login setelah ini.')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                            <i class="bi bi-check-lg"></i> Setujui
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <button type="button" class="btn btn-danger btn-sm" title="Tolak"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $user->id }}">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>

                                    {{-- Modal Tolak --}}
                                    <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST"
                                                      action="{{ route('users.reject-orang-tua', $user) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Pendaftaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Tolak pendaftaran <strong>{{ $user->name }}</strong>
                                                           untuk anak <strong>{{ $user->anakYatim?->nama_lengkap }}</strong>?
                                                        </p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Penolakan (opsional)</label>
                                                            <textarea class="form-control" name="catatan_penolakan"
                                                                      rows="3"
                                                                      placeholder="Contoh: Data tidak sesuai, silakan hubungi pengurus..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($user->status_akun === 'ditolak')
                                    {{-- Bisa disetujui ulang --}}
                                    <form method="POST"
                                          action="{{ route('users.approve-orang-tua', $user) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Setujui ulang pendaftaran {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-arrow-counterclockwise"></i> Setujui Ulang
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Sudah aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
