<x-filament-panels::page>

    <x-filament::section>
        <x-slot name="heading">
            Ringkasan Pengujian FIFO
        </x-slot>

        <x-slot name="description">
            Pengujian dilakukan dengan membandingkan urutan pendaftaran antrean dengan urutan pemanggilan antrean.
        </x-slot>

        <div style="
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
            gap:16px;
        ">
            <div style="
                padding:18px;
                background:#ffffff;
                border:1px solid #e5e7eb;
                border-radius:12px;
            ">
                <div style="font-size:13px;color:#6b7280;">
                    Total Antrean Diuji
                </div>

                <div style="
                    margin-top:8px;
                    font-size:28px;
                    font-weight:700;
                    color:#111827;
                ">
                    {{ $evaluation['total'] }}
                </div>
            </div>

            <div style="
                padding:18px;
                background:#ffffff;
                border:1px solid #e5e7eb;
                border-radius:12px;
            ">
                <div style="font-size:13px;color:#6b7280;">
                    Sesuai FIFO
                </div>

                <div style="
                    margin-top:8px;
                    font-size:28px;
                    font-weight:700;
                    color:#15803d;
                ">
                    {{ $evaluation['matched'] }}
                </div>
            </div>

            <div style="
                padding:18px;
                background:#ffffff;
                border:1px solid #e5e7eb;
                border-radius:12px;
            ">
                <div style="font-size:13px;color:#6b7280;">
                    Tidak Sesuai
                </div>

                <div style="
                    margin-top:8px;
                    font-size:28px;
                    font-weight:700;
                    color:#b91c1c;
                ">
                    {{ $evaluation['mismatched'] }}
                </div>
            </div>

            <div style="
                padding:18px;
                background:#ffffff;
                border:1px solid #e5e7eb;
                border-radius:12px;
            ">
                <div style="font-size:13px;color:#6b7280;">
                    Tingkat Kesesuaian FIFO
                </div>

                <div style="
                    margin-top:8px;
                    font-size:28px;
                    font-weight:700;
                    color:#1d4ed8;
                ">
                    {{ $evaluation['percentage'] === null
                        ? '-'
                        : number_format($evaluation['percentage'], 2) . '%' }}
                </div>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Metode Pengujian
        </x-slot>

        <div style="
            line-height:1.8;
            color:#374151;
        ">
            <p>
                Metode FIFO atau First In First Out memberikan prioritas pelayanan
                kepada masyarakat yang terlebih dahulu masuk ke dalam antrean.
            </p>

            <p style="margin-top:12px;">
                Urutan seharusnya ditentukan berdasarkan nomor urut dan waktu
                pendaftaran, kemudian dibandingkan dengan waktu pemanggilan
                antrean yang tersimpan pada sistem.
            </p>

            <div style="
                margin-top:16px;
                padding:14px;
                background:#eff6ff;
                border:1px solid #bfdbfe;
                border-radius:10px;
                color:#1e3a8a;
            ">
                Rumus Tingkat Kesesuaian FIFO =
                (Jumlah Antrean Sesuai / Total Antrean Diuji) × 100%
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Hasil Urutan FIFO
        </x-slot>

        @if ($evaluation['total'] === 0)
            <div style="
                padding:16px;
                border:1px solid #f59e0b;
                background:#fffbeb;
                border-radius:10px;
                color:#92400e;
            ">
                Belum terdapat minimal dua antrean pada layanan dan tanggal
                yang sama yang sudah memiliki waktu pemanggilan.
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="
                    width:100%;
                    border-collapse:collapse;
                    background:#ffffff;
                    color:#111827;
                ">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                No.
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Nomor Antrean
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Masyarakat
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Layanan
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Seksi
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Waktu Masuk
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Waktu Dipanggil
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Urutan Seharusnya
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Urutan Aktual
                            </th>
                            <th style="padding:11px;border:1px solid #d1d5db;">
                                Hasil
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($evaluation['rows'] as $index => $row)
                            <tr>
                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    {{ $index + 1 }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                    font-weight:700;
                                ">
                                    {{ $row['queue_number'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                ">
                                    {{ $row['user'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                ">
                                    {{ $row['service'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                ">
                                    {{ $row['section'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    {{ $row['registered_at'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    {{ $row['called_at'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    {{ $row['expected_order'] }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    {{ $row['actual_order'] ?? '-' }}
                                </td>

                                <td style="
                                    padding:10px;
                                    border:1px solid #d1d5db;
                                    text-align:center;
                                ">
                                    @if ($row['is_match'])
                                        <span style="
                                            display:inline-block;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            background:#dcfce7;
                                            color:#166534;
                                            font-weight:700;
                                        ">
                                            Sesuai
                                        </span>
                                    @else
                                        <span style="
                                            display:inline-block;
                                            padding:5px 10px;
                                            border-radius:999px;
                                            background:#fee2e2;
                                            color:#991b1b;
                                            font-weight:700;
                                        ">
                                            Tidak Sesuai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Alur FIFO yang Diuji
        </x-slot>

        <div style="
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:12px;
        ">
            @foreach ($evaluation['rows'] as $index => $row)
                <div style="
                    padding:14px 20px;
                    background:#ffffff;
                    border:2px solid #2563eb;
                    border-radius:12px;
                    color:#111827;
                    font-weight:700;
                ">
                    {{ $row['queue_number'] }}
                </div>

                @if (!$loop->last)
                    <div style="
                        font-size:26px;
                        color:#2563eb;
                        font-weight:700;
                    ">
                        →
                    </div>
                @endif
            @endforeach
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Kesimpulan Pengujian FIFO
        </x-slot>

        @if ($evaluation['percentage'] === null)
            <p>
                Data belum cukup untuk melakukan pengujian FIFO.
            </p>
        @elseif ($evaluation['percentage'] == 100)
            <div style="
                padding:16px;
                background:#f0fdf4;
                border:1px solid #86efac;
                border-radius:10px;
                color:#166534;
            ">
                Seluruh antrean yang diuji telah dipanggil sesuai dengan
                urutan pendaftaran. Implementasi algoritma FIFO pada data
                pengujian berjalan sesuai dengan prinsip First In First Out.
            </div>
        @else
            <div style="
                padding:16px;
                background:#fff7ed;
                border:1px solid #fdba74;
                border-radius:10px;
                color:#9a3412;
            ">
                Ditemukan antrean yang tidak sesuai dengan urutan FIFO.
                Perlu dilakukan pemeriksaan terhadap proses pemanggilan antrean.
            </div>
        @endif
    </x-filament::section>

</x-filament-panels::page>
