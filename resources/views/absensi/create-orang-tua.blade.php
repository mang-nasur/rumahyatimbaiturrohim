@extends('layouts.app')

@section('title', 'Absensi Bulanan')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-calendar-check"></i> Absensi Bulanan</h2>
    <p class="text-muted">Isi kehadiran untuk anak Anda bulan ini.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">

        {{-- Info anak --}}
        <div class="card border-primary mb-4">
            <div class="card-body d-flex align-items-center gap-3">
                @if($anakYatim->foto)
                    <img src="{{ asset('storage/' . $anakYatim->foto) }}"
                         class="rounded-circle" style="width:60px;height:60px;object-fit:cover;"
                         alt="Foto {{ $anakYatim->nama_lengkap }}">
                @else
                    <i class="bi bi-person-circle text-primary" style="font-size:3.5rem;"></i>
                @endif
                <div>
                    <h5 class="mb-0">{{ $anakYatim->nama_lengkap }}</h5>
                    <small class="text-muted">{{ $anakYatim->usia }} tahun &bull; {{ $anakYatim->pendidikan_terakhir ?? '-' }}</small>
                </div>
            </div>
        </div>

        @if($sudahAbsen && $sudahAbsen->status === 'disetujui')
            {{-- Sudah disetujui bulan ini --}}
            <div class="card border-success">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                    <h5 class="mt-3 text-success">Absensi Bulan Ini Sudah Disetujui</h5>
                    <p class="text-muted">
                        Hadir sebagai: <strong>{{ $sudahAbsen->hadir_sebagai === 'anak' ? 'Anak' : 'Ibu / Wali' }}</strong><br>
                        Disetujui pada: {{ $sudahAbsen->approved_at?->format('d/m/Y H:i') }}
                    </p>
                    <a href="{{ route('absensi.riwayat', $anakYatim) }}" class="btn btn-outline-primary">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat
                    </a>
                </div>
            </div>

        @elseif($sudahAbsen && $sudahAbsen->status === 'pending')
            {{-- Sudah submit, menunggu --}}
            <div class="card border-warning">
                <div class="card-body text-center py-5">
                    <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                    <h5 class="mt-3 text-warning">Absensi Sedang Menunggu Persetujuan</h5>
                    <p class="text-muted">
                        Disubmit pada: {{ $sudahAbsen->submitted_at?->format('d/m/Y H:i') }}<br>
                        Hadir sebagai: <strong>{{ $sudahAbsen->hadir_sebagai === 'anak' ? 'Anak' : 'Ibu / Wali' }}</strong>
                    </p>
                    <a href="{{ route('absensi.riwayat', $anakYatim) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat
                    </a>
                </div>
            </div>

        @else
            {{-- Belum absen atau ditolak — tampilkan form --}}
            @if($sudahAbsen && $sudahAbsen->status === 'ditolak')
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill"></i>
                    Absensi bulan ini <strong>ditolak</strong>.
                    @if($sudahAbsen->catatan_staff)
                        Alasan: {{ $sudahAbsen->catatan_staff }}
                    @endif
                    Silakan submit ulang.
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Absensi</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf

                        {{-- Periode --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bulan" class="form-label fw-semibold">
                                    Bulan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('bulan') is-invalid @enderror"
                                        id="bulan" name="bulan" required>
                                    @php
                                        $namaBulan = [
                                            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                                            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
                                            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
                                        ];
                                    @endphp
                                    @foreach($namaBulan as $num => $nama)
                                        <option value="{{ $num }}"
                                            {{ old('bulan', $bulanSekarang) == $num ? 'selected' : '' }}>
                                            {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tahun" class="form-label fw-semibold">
                                    Tahun <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('tahun') is-invalid @enderror"
                                        id="tahun" name="tahun" required>
                                    @for($y = date('Y'); $y >= 2024; $y--)
                                        <option value="{{ $y }}"
                                            {{ old('tahun', $tahunSekarang) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Siapa yang hadir --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Yang Hadir <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3">
                                <div class="form-check border rounded p-3 flex-fill text-center"
                                     style="cursor:pointer;" id="card-anak">
                                    <input class="form-check-input" type="radio" name="hadir_sebagai"
                                           id="hadir_anak" value="anak"
                                           {{ old('hadir_sebagai') == 'anak' ? 'checked' : '' }}>
                                    <label class="form-check-label d-block mt-1" for="hadir_anak" style="cursor:pointer;">
                                        <i class="bi bi-person-fill fs-2 text-primary d-block"></i>
                                        <strong>Anak</strong>
                                        <div class="text-muted small">Anak hadir sendiri</div>
                                    </label>
                                </div>
                                <div class="form-check border rounded p-3 flex-fill text-center"
                                     style="cursor:pointer;" id="card-ibu">
                                    <input class="form-check-input" type="radio" name="hadir_sebagai"
                                           id="hadir_ibu" value="ibu"
                                           {{ old('hadir_sebagai') == 'ibu' ? 'checked' : '' }}>
                                    <label class="form-check-label d-block mt-1" for="hadir_ibu" style="cursor:pointer;">
                                        <i class="bi bi-person-heart fs-2 text-danger d-block"></i>
                                        <strong>Ibu / Wali</strong>
                                        <div class="text-muted small">Ibu atau wali mewakili</div>
                                    </label>
                                </div>
                            </div>
                            @error('hadir_sebagai')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-check"></i> Submit Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="mt-3 text-center">
            <a href="{{ route('dashboard') }}" class="text-muted small">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            &nbsp;&bull;&nbsp;
            <a href="{{ route('absensi.riwayat', $anakYatim) }}" class="text-muted small">
                <i class="bi bi-clock-history"></i> Riwayat Absensi
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="hadir_sebagai"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('card-anak').classList.remove('border-primary', 'bg-primary-subtle');
            document.getElementById('card-ibu').classList.remove('border-danger', 'bg-danger-subtle');
            if (this.value === 'anak') {
                document.getElementById('card-anak').classList.add('border-primary', 'bg-primary-subtle');
            } else {
                document.getElementById('card-ibu').classList.add('border-danger', 'bg-danger-subtle');
            }
        });
        if (radio.checked) radio.dispatchEvent(new Event('change'));
    });
</script>
@endpush
