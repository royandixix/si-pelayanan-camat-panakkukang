<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Permohonan {{ $permohonan->registration_number }}</title>

    <style>
        @page {
            margin: 30px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #18181b;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
        }

        .line {
            height: 5px;
            margin-bottom: 22px;
            background: #18181b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header td {
            vertical-align: top;
        }

        .portal {
            color: #71717a;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .title {
            margin-top: 6px;
            font-size: 20px;
            font-weight: bold;
        }

        .subtitle {
            margin-top: 3px;
            color: #71717a;
        }

        .number {
            width: 225px;
            padding: 12px 14px;
            color: #ffffff;
            background: #18181b;
        }

        .label {
            color: #71717a;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .value {
            margin-top: 4px;
            font-weight: bold;
        }

        .summary {
            margin-top: 22px;
            border: 1px solid #d4d4d8;
        }

        .summary td {
            width: 33.33%;
            padding: 12px;
            border-right: 1px solid #d4d4d8;
        }

        .summary td:last-child {
            border-right: 0;
        }

        .section {
            margin-top: 22px;
        }

        .section-title {
            padding-bottom: 7px;
            border-bottom: 1px solid #d4d4d8;
            font-size: 12px;
            font-weight: bold;
        }

        .info {
            margin-top: 8px;
        }

        .info td {
            width: 50%;
            padding: 6px 8px 6px 0;
            vertical-align: top;
        }

        .documents {
            margin-top: 10px;
        }

        .documents th,
        .documents td {
            padding: 7px 8px;
            border: 1px solid #d4d4d8;
            text-align: left;
            vertical-align: top;
        }

        .documents th {
            color: #52525b;
            background: #f4f4f5;
            font-size: 9px;
        }

        .verification {
            margin-top: 22px;
            padding: 14px;
            border: 1px dashed #a1a1aa;
            text-align: center;
            background: #fafafa;
        }

        .verification-code {
            margin-top: 5px;
            font-family: DejaVu Sans Mono, monospace;
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #d4d4d8;
            color: #71717a;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="line"></div>

    <table class="header">
        <tr>
            <td>
                <div class="portal">Portal Pelayanan Masyarakat</div>
                <div class="title">Tanda Terima Permohonan</div>
                <div class="subtitle">Kecamatan Panakkukang</div>
            </td>

            <td style="width: 225px;">
                <div class="number">
                    <div class="label" style="color: #d4d4d8;">
                        Nomor permohonan
                    </div>

                    <div class="value">
                        {{ $permohonan->registration_number }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Status</div>
                <div class="value">{{ $statusLabel }}</div>
            </td>

            <td>
                <div class="label">Tanggal pengajuan</div>
                <div class="value">
                    {{ $tanggalPengajuan?->format('d M Y, H:i') ?? '-' }}
                </div>
            </td>

            <td>
                <div class="label">Jumlah dokumen</div>
                <div class="value">
                    {{ $daftarDokumen->count() }} dokumen
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Identitas Pemohon</div>

        <table class="info">
            <tr>
                <td>
                    <div class="label">Nama lengkap</div>
                    <div>{{ $pengguna->name }}</div>
                </td>

                <td>
                    <div class="label">NIK</div>
                    <div>{{ $pengguna->nik ?: '-' }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="label">Email</div>
                    <div>{{ $pengguna->email }}</div>
                </td>

                <td>
                    <div class="label">Nomor telepon</div>
                    <div>{{ $pengguna->phone ?: '-' }}</div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="label">Alamat</div>
                    <div>{{ $pengguna->address ?: '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Informasi Pelayanan</div>

        <table class="info">
            <tr>
                <td>
                    <div class="label">Jenis layanan</div>
                    <div>{{ $permohonan->service?->name ?? '-' }}</div>
                </td>

                <td>
                    <div class="label">Seksi pelayanan</div>
                    <div>{{ $permohonan->service?->section?->name ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if ($detailPengajuan->isNotEmpty())
        <div class="section">
            <div class="section-title">Data Pengajuan</div>

            <table class="info">
                @foreach ($detailPengajuan->chunk(2) as $baris)
                    <tr>
                        @foreach ($baris as $item)
                            <td>
                                <div class="label">{{ $item['label'] }}</div>
                                <div>{{ $item['nilai'] }}</div>
                            </td>
                        @endforeach

                        @if ($baris->count() === 1)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    @if (filled($permohonan->applicant_notes))
        <div class="section">
            <div class="section-title">Catatan Tambahan</div>
            <p>{{ $permohonan->applicant_notes }}</p>
        </div>
    @endif

    <div class="section">
        <div class="section-title">Dokumen Persyaratan</div>

        <table class="documents">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th>Dokumen</th>
                    <th>Nama file</th>
                    <th style="width: 75px;">Ukuran</th>
                    <th style="width: 120px;">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($daftarDokumen as $index => $dokumen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $dokumen['nama'] }}</td>
                        <td>{{ $dokumen['nama_file'] }}</td>
                        <td>{{ $dokumen['ukuran'] }}</td>
                        <td>{{ $dokumen['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            Tidak ada dokumen yang dilampirkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="verification">
        <div class="label">Kode verifikasi</div>
        <div class="verification-code">{{ $kodeVerifikasi }}</div>
        <div style="margin-top: 5px; color: #71717a; font-size: 9px;">
            Simpan nomor permohonan dan kode ini sebagai bukti bahwa pengajuan telah tercatat di dalam sistem.
        </div>
    </div>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Portal Pelayanan Masyarakat Kecamatan Panakkukang.
    </div>
</body>
</html>
