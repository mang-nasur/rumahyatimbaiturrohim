<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 16px;
        }
        .header h2 { margin: 4px 0; font-size: 14px; }
        .header h3 { margin: 4px 0; font-size: 12px; }
        .header p  { margin: 2px 0; font-size: 9px; color: #555; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 4px 3px;
            vertical-align: top;
        }
        table th {
            background-color: #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 7.5px;
        }
        .center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; }
        @page { margin: 10mm; size: A3 landscape; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rumah Yatim Baiturrohim</h2>
        <h3>{{ $title }}</h3>
        <p>Tanggal Cetak: {{ $tanggalCetak }}</p>
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:3%">No</th>
                    <th style="width:9%">Nama Lengkap</th>
                    <th style="width:9%">Tempat, Tgl Lahir</th>
                    <th style="width:4%">Usia</th>
                    <th style="width:4%">JK</th>
                    <th style="width:10%">Alamat</th>
                    <th style="width:7%">NIK</th>
                    <th style="width:7%">No KK</th>
                    <th style="width:7%">Nama Ayah</th>
                    <th style="width:5%">Sts Ayah</th>
                    <th style="width:7%">Nama Ibu</th>
                    <th style="width:5%">Sts Ibu</th>
                    <th style="width:6%">No. Telp Wali</th>
                    <th style="width:5%">Sekolah Skrg</th>
                    <th style="width:7%">Sekolah</th>
                    <th style="width:6%">Kelas Masuk</th>
                    <th style="width:5%">Tgl Masuk</th>
                    <th style="width:5%">Tgl Keluar</th>
                    <th style="width:4%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $anak)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $anak->nama_lengkap }}</td>
                        <td>{{ $anak->tempat_lahir }}, {{ $anak->tanggal_lahir->format('d/m/Y') }}</td>
                        <td class="center">{{ $anak->usia }} th</td>
                        <td class="center">{{ $anak->jenis_kelamin }}</td>
                        <td>{{ $anak->alamat ?? '-' }}</td>
                        <td class="center">{{ $anak->nik ?? '-' }}</td>
                        <td class="center">{{ $anak->no_kk ?? '-' }}</td>
                        <td>{{ $anak->nama_ayah ?? '-' }}</td>
                        <td class="center">{{ $anak->status_ayah ?? '-' }}</td>
                        <td>{{ $anak->nama_ibu ?? '-' }}</td>
                        <td class="center">{{ $anak->status_ibu ?? '-' }}</td>
                        <td>{{ $anak->nomor_telepon_wali ?? '-' }}</td>
                        <td class="center">{{ $anak->kelas_sekarang }}</td>
                        <td>{{ $anak->sekolah_saat_ini ?? '-' }}</td>
                        <td class="center">{{ $anak->kelas_saat_masuk ?? '-' }}</td>
                        <td class="center">{{ $anak->tanggal_masuk->format('d/m/Y') }}</td>
                        <td class="center">{{ $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d/m/Y') : '-' }}</td>
                        <td class="center">{{ $anak->is_aktif ? 'Aktif' : 'Tdk Aktif' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="19" style="text-align:right; font-weight:bold;">
                        Total: {{ $data->count() }} anak
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align:center; margin-top:40px;">Tidak ada data yang sesuai dengan kriteria laporan.</p>
    @endif

    <div class="footer">
        <p>Dicetak oleh: Sistem Data Anak Yatim</p>
    </div>
</body>
</html>
