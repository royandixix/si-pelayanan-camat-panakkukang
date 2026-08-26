<x-filament-panels::page>
    @if (!$evaluation['run_id'])
        <x-filament::section>
            Belum ada hasil proses K-Means yang dapat diuji.
        </x-filament::section>
    @else
        <x-filament::section>
            <x-slot name="heading">
                Ringkasan Pengujian K-Means
            </x-slot>

            <x-slot name="description">
                Pengujian ini mengevaluasi proses clustering K-Means secara internal. Pengujian ini berbeda dengan Confusion Matrix dan pengujian akurasi terhadap label referensi.
            </x-slot>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;">
                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <div style="font-size:13px;color:#6b7280;">Run</div>
                    <div style="font-size:26px;font-weight:700;color:#111827;">
                        #{{ $evaluation['run_id'] }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <div style="font-size:13px;color:#6b7280;">K</div>
                    <div style="font-size:26px;font-weight:700;color:#111827;">
                        {{ $evaluation['k'] }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <div style="font-size:13px;color:#6b7280;">Iterasi</div>
                    <div style="font-size:26px;font-weight:700;color:#111827;">
                        {{ $evaluation['iterations'] }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <div style="font-size:13px;color:#6b7280;">WCSS</div>
                    <div style="font-size:24px;font-weight:700;color:#111827;">
                        {{ number_format($evaluation['wcss'], 8) }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <div style="font-size:13px;color:#6b7280;">Silhouette Score</div>
                    <div style="font-size:24px;font-weight:700;color:#111827;">
                        {{ number_format($evaluation['silhouette_score'], 6) }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Data yang Digunakan
            </x-slot>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
                <div>
                    <strong>Data Sumber</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['total_source_records'] }}
                    </div>
                </div>

                <div>
                    <strong>Data Valid</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['valid_source_records'] }}
                    </div>
                </div>

                <div>
                    <strong>Data Dikeluarkan</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['excluded_records'] }}
                    </div>
                </div>

                <div>
                    <strong>Titik Clustering</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['total_points'] }}
                    </div>
                </div>
            </div>

            <div style="margin-top:18px;padding:14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                <strong>Fitur:</strong>
                {{ implode(', ', $evaluation['features']) }}
                <br>
                <strong>Normalisasi:</strong>
                {{ $evaluation['normalization'] === 'z_score' ? 'Z-Score' : $evaluation['normalization'] }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Parameter Normalisasi Z-Score
            </x-slot>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;background:#fff;color:#111827;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:11px;border:1px solid #d1d5db;">Fitur</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Mean</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Standar Deviasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluation['normalization_stats'] as $feature => $stat)
                            <tr>
                                <td style="padding:10px;border:1px solid #d1d5db;">
                                    {{ str($feature)->replace('_', ' ')->title() }}
                                </td>
                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($stat['mean'], 8) }}
                                </td>
                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($stat['std'], 8) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Centroid Akhir dan Distribusi Cluster
            </x-slot>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;background:#fff;color:#111827;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:11px;border:1px solid #d1d5db;">Cluster</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Kategori</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Jumlah Titik</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Jumlah Pelayanan</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Hari Aktif</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Rata-rata/Hari</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Z Jumlah</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Z Hari</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($evaluation['clusters'] as $cluster)
                            <tr>
                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;font-weight:700;">
                                    C{{ $cluster['cluster'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ $cluster['label'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ $cluster['jumlah_titik'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($cluster['jumlah_pelayanan'], 4) }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($cluster['hari_aktif'], 4) }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($cluster['rata_rata_harian'], 4) }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($cluster['z_jumlah'], 8) }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ number_format($cluster['z_hari'], 8) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Pemeriksaan Proses K-Means
            </x-slot>

            <x-slot name="description">
                Pemeriksaan berikut memastikan proses dan hasil clustering tersimpan secara konsisten. Nilai ini bukan Accuracy dari Confusion Matrix.
            </x-slot>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;background:#fff;color:#111827;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:11px;border:1px solid #d1d5db;">No.</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Pengujian</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Diharapkan</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Aktual</th>
                            <th style="padding:11px;border:1px solid #d1d5db;">Hasil</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($evaluation['checks'] as $index => $check)
                            <tr>
                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ $index + 1 }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;">
                                    {{ $check['name'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ $check['expected'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    {{ $check['actual'] }}
                                </td>

                                <td style="padding:10px;border:1px solid #d1d5db;text-align:center;">
                                    @if ($check['passed'])
                                        <span style="display:inline-block;padding:5px 10px;border-radius:999px;background:#dcfce7;color:#166534;font-weight:700;">
                                            Sesuai
                                        </span>
                                    @else
                                        <span style="display:inline-block;padding:5px 10px;border-radius:999px;background:#fee2e2;color:#991b1b;font-weight:700;">
                                            Tidak Sesuai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Kesimpulan Pengujian K-Means
            </x-slot>

            <div style="padding:16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;color:#1e3a8a;line-height:1.7;">
                Proses K-Means pada Run #{{ $evaluation['run_id'] }}
                menggunakan {{ $evaluation['total_points'] }} titik data dengan
                K = {{ $evaluation['k'] }} dan mencapai hasil setelah
                {{ $evaluation['iterations'] }} iterasi.
                Nilai WCSS sebesar
                {{ number_format($evaluation['wcss'], 8) }}
                menunjukkan tingkat kekompakan hasil clustering, sedangkan
                Silhouette Score sebesar
                {{ number_format($evaluation['silhouette_score'], 6) }}
                digunakan untuk mengevaluasi pemisahan antar-cluster.
                Pengujian ini merupakan evaluasi internal clustering dan bukan
                nilai akurasi klasifikasi.
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
