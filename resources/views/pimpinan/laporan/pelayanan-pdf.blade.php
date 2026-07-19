<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Laporan Pelayanan Masyarakat</title>

    <style>
        @page {
            margin: 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111827;
        }

        h1 {
            margin: 0;
            text-align: center;
            font-size: 19px;
        }

        h2 {
            margin: 4px 0 16px;
            text-align: center;
            font-size: 12px;
            font-weight: normal;
        }

        .filter {
            margin-bottom: 12px;
            padding: 8px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            line-height: 1.5;
        }

        .summary {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            padding: 8px;
            border: 1px solid #d1d5db;
            text-align: center;
        }

        .summary strong {
            display: block;
            margin-bottom: 3px;
            font-size: 15px;
        }

        .data {
            width: 100%;
            border-collapse: collapse;
        }

        .data th {
            padding: 5px 3px;
            border: 1px solid #9ca3af;
            background: #e5e7eb;
            text-align: center;
            font-size: 8px;
        }

        .data td {
            padding: 4px 3px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            font-size: 7px;
        }

        .data tr:nth-child(even) {
            background: #f9fafb;
        }

        .empty {
            padding: 20px !important;
            text-align: center;
        }

        .footer {
            margin-top: 12px;
            text-align: right;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <h1>LAPORAN PELAYANAN MASYARAKAT</h1>
    <h2>Kantor Camat Panakkukang</h2>

    <div class="filter">
        <strong>Filter:</strong>
        {{ $filterDescription }}

        <br>

        <strong>Dicetak:</strong>
        {{ now()->translatedFormat('d F Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                <strong>{{ $summary['total'] }}</strong>
                Total Permohonan
            </td>

            <td>
                <strong>{{ $summary['diproses'] }}</strong>
                Sedang Diproses
            </td>

            <td>
                <strong>{{ $summary['selesai'] }}</strong>
                Selesai
            </td>

            <td>
                <strong>{{ $summary['ditolak'] }}</strong>
                Ditolak
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Permohonan</th>
                <th>Nama Pemohon</th>
                <th>NIK</th>
                <th>Jenis Layanan</th>
                <th>Seksi</th>
                <th>Status</th>
                <th>Petugas</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $record->registration_number ?? '-' }}
                    </td>

                    <td>
                        {{ $record->user?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $record->user?->nik ?? '-' }}
                    </td>

                    <td>
                        {{ $record->service?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $record->service?->section?->name ?? '-' }}
                    </td>

                    <td>
                        {{
                            $record->status instanceof
                                \App\Enums\ApplicationStatus
                                ? $record->status->getLabel()
                                : str((string) $record->status)
                                    ->replace('_', ' ')
                                    ->title()
                        }}
                    </td>

                    <td>
                        {{
                            $record->assignedAdmin?->name
                            ?? 'Belum ditugaskan'
                        }}
                    </td>

                    <td>
                        {{
                            $record->submitted_at?->format(
                                'd-m-Y H:i',
                            ) ?? '-'
                        }}
                    </td>

                    <td>
                        {{
                            $record->completed_at?->format(
                                'd-m-Y H:i',
                            ) ?? '-'
                        }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty">
                        Tidak ada data sesuai filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistem Informasi Pelayanan Kantor Camat Panakkukang
    </div>
</body>
</html>
