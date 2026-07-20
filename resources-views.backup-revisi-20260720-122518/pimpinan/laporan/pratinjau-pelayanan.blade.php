<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pratinjau Laporan Pelayanan</title>

    <style>
        :root {
            color-scheme: light dark;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #111827;
        }

        .container {
            width: min(1500px, calc(100% - 32px));
            margin: 24px auto 50px;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #dbe3ee;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(12px);
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 15px;
            border: 0;
            border-radius: 9px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .button-back {
            background: #e5e7eb;
            color: #111827;
        }

        .button-csv {
            background: #16a34a;
            color: #ffffff;
        }

        .button-pdf {
            background: #dc2626;
            color: #ffffff;
        }

        .button-print {
            background: #2563eb;
            color: #ffffff;
        }

        .report {
            overflow: hidden;
            border: 1px solid #dbe3ee;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
        }

        .report-header {
            padding: 28px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .report-header h1 {
            margin: 0;
            font-size: 25px;
        }

        .report-header p {
            margin: 6px 0 0;
            color: #6b7280;
        }

        .filter-box {
            margin: 20px 24px 0;
            padding: 14px 16px;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            background: #f8fafc;
            line-height: 1.6;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            padding: 20px 24px;
        }

        .summary-card {
            padding: 17px;
            border: 1px solid #dbe3ee;
            border-radius: 12px;
            background: #f8fafc;
        }

        .summary-card strong {
            display: block;
            margin-bottom: 5px;
            font-size: 25px;
        }

        .summary-card span {
            color: #64748b;
            font-size: 13px;
        }

        .table-wrapper {
            overflow-x: auto;
            padding: 0 24px 28px;
        }

        table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
        }

        th {
            padding: 12px 10px;
            border: 1px solid #d1d5db;
            background: #e5e7eb;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 11px 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 12px;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #6b7280;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #09090b;
                color: #f9fafb;
            }

            .toolbar,
            .report {
                border-color: #27272a;
                background: #18181b;
                box-shadow: none;
            }

            .button-back {
                background: #3f3f46;
                color: #ffffff;
            }

            .report-header,
            .filter-box,
            .summary-card {
                border-color: #27272a;
            }

            .filter-box,
            .summary-card {
                background: #202024;
            }

            .report-header p,
            .summary-card span {
                color: #a1a1aa;
            }

            th {
                border-color: #3f3f46;
                background: #27272a;
            }

            td {
                border-color: #27272a;
            }

            tbody tr:nth-child(even) {
                background: #202024;
            }
        }

        @media (max-width: 800px) {
            .summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media print {
            body {
                background: #ffffff;
                color: #000000;
            }

            .container {
                width: 100%;
                margin: 0;
            }

            .toolbar {
                display: none;
            }

            .report {
                border: 0;
                box-shadow: none;
            }

            .table-wrapper {
                overflow: visible;
            }

            table {
                min-width: 0;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <div class="toolbar">
            <a
                class="button button-back"
                href="{{ url('/pimpinan/laporan-pelayanan') }}"
            >
                Kembali ke Laporan
            </a>

            <div class="toolbar-actions">
                <a
                    class="button button-csv"
                    href="{{ route(
                        'pimpinan.laporan.download.csv',
                        request()->query(),
                    ) }}"
                >
                    Download CSV
                </a>

                <a
                    class="button button-pdf"
                    href="{{ route(
                        'pimpinan.laporan.preview.pdf',
                        request()->query(),
                    ) }}"
                    target="_blank"
                >
                    Lihat PDF
                </a>

                <button
                    type="button"
                    class="button button-print"
                    onclick="window.print()"
                >
                    Cetak
                </button>
            </div>
        </div>

        <section class="report">
            <header class="report-header">
                <h1>Laporan Pelayanan Masyarakat</h1>
                <p>Kantor Camat Panakkukang</p>
            </header>

            <div class="filter-box">
                <strong>Filter:</strong>
                {{ $filterDescription }}

                <br>

                <strong>Waktu pratinjau:</strong>
                {{ now()->translatedFormat('d F Y H:i') }}
            </div>

            <div class="summary">
                <div class="summary-card">
                    <strong>{{ $summary['total'] }}</strong>
                    <span>Total Permohonan</span>
                </div>

                <div class="summary-card">
                    <strong>{{ $summary['diproses'] }}</strong>
                    <span>Sedang Diproses</span>
                </div>

                <div class="summary-card">
                    <strong>{{ $summary['selesai'] }}</strong>
                    <span>Permohonan Selesai</span>
                </div>

                <div class="summary-card">
                    <strong>{{ $summary['ditolak'] }}</strong>
                    <span>Permohonan Ditolak</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
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
                                    {{
                                        $record->service?->section?->name
                                        ?? '-'
                                    }}
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
            </div>
        </section>
    </main>
</body>
</html>
