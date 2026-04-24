@extends('layouts.app')

@section('title', 'Approval Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-clipboard2-check"></i> Approval Absensi</h2>
        <p class="text-muted mb-0">Validasi kehadiran anak yatim per bulan</p>
    </div>
    <a href="{{ route('absensi.tidak-hadir') }}" class="btn btn-warning">
        <i class="bi bi-exclamation-triangle"></i> Anak Tidak Hadir
    </a>
</div>

{{-- Statistik Periode --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalAktif }}</h3>
                <small>Total Anak Aktif</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalPending }}</h3>
                <small>Menunggu Persetujuan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalDisetujui }}</h3>
                <small>Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $totalDitolak }}</h3>
                <small>Ditolak</small>
            </div>
        </div>
    </div>
</div>

{{-- Filter Periode --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('absensi.approval') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select class="form-select" name="bulan">
                    @php
                        $namaBulan = [
                            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
                            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
                        ];
                    @endphp
                    @foreach($namaBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select class="form-select" name="tahun">
                    @for($y = date('Y'); $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="pending"   {{ $status == 'pending'   ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"   {{ $status == 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                    <option value="semua"     {{ $status == 'semua'     ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            @if($totalPending > 0 && $status === 'pending')
            <div class="col-md-2">
                <form method="POST" action="{{ route('absensi.approve-all') }}"
                      onsubmit="return confirm('Setujui semua {{ $totalPending }} absensi pending bulan ini?')">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-all"></i> Setujui Semua
                    </button>
                </form>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Tabel Absensi --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            Absensi
            @php
                $namaBulanArr = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                 7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
            @endphp
            {{ $namaBulanArr[$bulan] }} {{ $tahun }}
            <span class="badge bg-secondary ms-2">{{ $absensiList->total() }} data</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($absensiList->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Tidak ada data absensi untuk periode ini</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Anak</th>
                            <th>Yang Hadir</th>
                            <th>Waktu Submit</th>
                            <th class="text-center">Status</th>
                            <th>Diproses Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensiList as $absensi)
                        <tr>
                            <td>
                                <a href="{{ route('anak-yatim.show', $absensi->anakYatim) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $absensi->anakYatim->nama_lengkap }}
                                </a>
                            </td>
                            <td>
                                @if($absensi->hadir_sebagai === 'anak')
                                    <i class="bi bi-person-fill text-primary"></i> Anak
                                @elseif($absensi->hadir_sebagai === 'ibu')
                                    <i class="bi bi-person-heart text-danger"></i> Ibu / Wali
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $absensi->submitted_at ? $absensi->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $absensi->badge_status }}">
                                    {{ $absensi->label_status }}
                                </span>
                            </td>
                            <td>
                                @if($absensi->approvedBy)
                                    {{ $absensi->approvedBy->name }}
                                    <div class="text-muted small">{{ $absensi->approved_at?->format('d/m/Y H:i') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($absensi->status === 'pending')
                                    {{-- Tombol Setujui → buka modal --}}
                                    <button type="button" class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $absensi->id }}">
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- Modal Setujui (dengan opsi koreksi hadir_sebagai) --}}
                                    <div class="modal fade" id="approveModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('absensi.approve', $absensi) }}">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-check-circle"></i> Setujui Absensi
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-3">
                                                            Setujui absensi <strong>{{ $absensi->anakYatim->nama_lengkap }}</strong>?
                                                        </p>
                                                        <div class="mb-0">
                                                            <label class="form-label fw-semibold">Yang Hadir</label>
                                                            <div class="d-flex gap-2">
                                                                <div class="form-check border rounded p-2 flex-fill text-center">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="hadir_sebagai" id="approve_anak_{{ $absensi->id }}"
                                                                           value="anak"
                                                                           {{ $absensi->hadir_sebagai === 'anak' ? 'checked' : '' }}>
                                                                    <label class="form-check-label d-block" for="approve_anak_{{ $absensi->id }}">
                                                                        <i class="bi bi-person-fill text-primary"></i><br>
                                                                        <strong>Anak</strong>
                                                                    </label>
                                                                </div>
                                                                <div class="form-check border rounded p-2 flex-fill text-center">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="hadir_sebagai" id="approve_ibu_{{ $absensi->id }}"
                                                                           value="ibu"
                                                                           {{ $absensi->hadir_sebagai === 'ibu' ? 'checked' : '' }}>
                                                                    <label class="form-check-label d-block" for="approve_ibu_{{ $absensi->id }}">
                                                                        <i class="bi bi-person-heart text-danger"></i><br>
                                                                        <strong>Ibu / Wali</strong>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted mt-1 d-block">
                                                                Disubmit sebagai: <strong>{{ $absensi->hadir_sebagai === 'anak' ? 'Anak' : 'Ibu / Wali' }}</strong>.
                                                                Ubah jika tidak sesuai.
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-check-lg"></i> Setujui
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tombol Tolak --}}
                                    <button type="button" class="btn btn-danger btn-sm" title="Tolak"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $absensi->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                    {{-- Modal Tolak --}}
                                    <div class="modal fade" id="rejectModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('absensi.reject', $absensi) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Absensi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Tolak absensi <strong>{{ $absensi->anakYatim->nama_lengkap }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan (opsional)</label>
                                                            <textarea class="form-control" name="catatan_staff" rows="3"
                                                                      placeholder="Alasan penolakan..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $absensiList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
