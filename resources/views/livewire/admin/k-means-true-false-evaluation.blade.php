<div class="km-evaluation">
    <style>
        .km-evaluation {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .km-panel {
            border: 1px solid rgba(148, 163, 184, .18);
            background: rgba(255, 255, 255, .025);
            border-radius: 14px;
            overflow: hidden;
        }

        .km-panel-header {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(148, 163, 184, .16);
        }

        .km-panel-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .km-panel-desc {
            margin-top: 5px;
            font-size: 13px;
            opacity: .65;
            line-height: 1.6;
        }

        .km-panel-body {
            padding: 20px;
        }

        .km-stats {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .km-stat {
            border: 1px solid rgba(148, 163, 184, .20);
            border-radius: 12px;
            padding: 14px 16px;
            min-height: 82px;
        }

        .km-stat-label {
            font-size: 12px;
            opacity: .60;
        }

        .km-stat-value {
            margin-top: 7px;
            font-size: 24px;
            font-weight: 800;
        }

        .km-stat-primary {
            border-color: rgba(59, 130, 246, .40);
            background: rgba(37, 99, 235, .08);
        }

        .km-stat-success {
            border-color: rgba(34, 197, 94, .35);
            background: rgba(34, 197, 94, .07);
        }

        .km-stat-warning {
            border-color: rgba(245, 158, 11, .35);
            background: rgba(245, 158, 11, .07);
        }

        .km-alert {
            border: 1px solid rgba(245, 158, 11, .35);
            background: rgba(245, 158, 11, .07);
            border-radius: 12px;
            padding: 16px 18px;
        }

        .km-alert-title {
            font-size: 14px;
            font-weight: 700;
        }

        .km-alert-text {
            margin-top: 5px;
            font-size: 13px;
            line-height: 1.7;
            opacity: .75;
        }

        .km-matrix-wrap {
            overflow-x: auto;
        }

        .km-matrix-table {
            width: 100%;
            min-width: 720px;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, .18);
        }

        .km-matrix-table th,
        .km-matrix-table td {
            padding: 15px 16px;
            border-right: 1px solid rgba(148, 163, 184, .14);
            border-bottom: 1px solid rgba(148, 163, 184, .14);
            text-align: center;
        }

        .km-matrix-table th {
            background: rgba(148, 163, 184, .10);
            font-size: 13px;
            font-weight: 700;
        }

        .km-matrix-table tr:last-child td,
        .km-matrix-table tr:last-child th {
            border-bottom: 0;
        }

        .km-matrix-table th:last-child,
        .km-matrix-table td:last-child {
            border-right: 0;
        }

        .km-matrix-row-title {
            text-align: left !important;
            width: 190px;
        }

        .km-matrix-cell {
            font-size: 23px;
            font-weight: 800;
            background: rgba(30, 64, 175, .13);
        }

        .km-matrix-diagonal {
            background: rgba(37, 99, 235, .45);
        }

        .km-heatmap {
            display: grid;
            grid-template-columns: 160px repeat(3, minmax(120px, 1fr));
            gap: 6px;
            max-width: 820px;
            margin: 18px auto 0;
        }

        .km-heat-title {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 68px;
            font-size: 12px;
            font-weight: 700;
        }

        .km-heat-cell {
            min-height: 78px;
            border-radius: 10px;
            border: 1px solid rgba(59, 130, 246, .25);
            background: rgba(30, 64, 175, .22);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .km-heat-cell.diagonal {
            background: rgba(37, 99, 235, .65);
        }

        .km-heat-value {
            font-size: 26px;
            font-weight: 800;
        }

        .km-heat-caption {
            margin-top: 4px;
            font-size: 10px;
            opacity: .65;
        }

        .km-class-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .km-class-card {
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 12px;
            padding: 16px;
        }

        .km-class-title {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 14px;
        }

        .km-binary {
            display: grid;
            grid-template-columns: 86px 1fr 1fr;
            gap: 6px;
            text-align: center;
        }

        .km-binary-head,
        .km-binary-side {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            border-radius: 8px;
            background: rgba(148, 163, 184, .10);
            font-size: 11px;
            font-weight: 700;
        }

        .km-binary-cell {
            min-height: 72px;
            border: 1px solid rgba(59, 130, 246, .20);
            border-radius: 9px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .km-binary-cell.good {
            background: rgba(37, 99, 235, .18);
        }

        .km-code {
            font-size: 11px;
            opacity: .55;
        }

        .km-number {
            margin-top: 3px;
            font-size: 22px;
            font-weight: 800;
        }

        .km-mini-grid {
            margin-top: 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .km-mini {
            border: 1px solid rgba(148, 163, 184, .16);
            border-radius: 9px;
            padding: 10px 12px;
        }

        .km-mini-label {
            font-size: 10px;
            opacity: .60;
        }

        .km-mini-value {
            margin-top: 4px;
            font-size: 15px;
            font-weight: 700;
        }

        .km-metric-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 12px;
            overflow: hidden;
        }

        .km-metric-table th,
        .km-metric-table td {
            padding: 13px 14px;
            border-bottom: 1px solid rgba(148, 163, 184, .13);
            text-align: center;
        }

        .km-metric-table th {
            background: rgba(148, 163, 184, .10);
            font-size: 12px;
        }

        .km-metric-table th:first-child,
        .km-metric-table td:first-child {
            text-align: left;
        }

        .km-metric-table tr:last-child td {
            border-bottom: 0;
        }

        .km-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .km-formula {
            margin-top: 16px;
            border: 1px solid rgba(59, 130, 246, .30);
            background: rgba(37, 99, 235, .07);
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            line-height: 1.8;
        }

        @media (max-width: 1100px) {
            .km-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .km-class-grid {
                grid-template-columns: 1fr;
            }

            .km-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    @if (! $run)
        <div class="km-panel">
            <div class="km-panel-body">
                Belum ada hasil K-Means.
            </div>
        </div>
    @else
        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Pengujian dan Evaluasi K-Means
                </h3>

                <div class="km-panel-desc">
                    Evaluasi Run Final #{{ $run->id }} menggunakan label referensi dan Confusion Matrix.
                </div>
            </div>

            <div class="km-panel-body">
                <div class="km-stats">
                    <div class="km-stat km-stat-primary">
                        <div class="km-stat-label">Run Final</div>
                        <div class="km-stat-value">#{{ $run->id }}</div>
                    </div>

                    <div class="km-stat">
                        <div class="km-stat-label">Data Uji</div>
                        <div class="km-stat-value">{{ $results->count() }}</div>
                    </div>

                    <div class="km-stat km-stat-success">
                        <div class="km-stat-label">Sudah Validasi</div>
                        <div class="km-stat-value">{{ $validated->count() }}</div>
                    </div>

                    <div class="km-stat km-stat-warning">
                        <div class="km-stat-label">Belum Validasi</div>
                        <div class="km-stat-value">
                            {{ $results->count() - $validated->count() }}
                        </div>
                    </div>

                    <div class="km-stat km-stat-primary">
                        <div class="km-stat-label">Accuracy</div>
                        <div class="km-stat-value">
                            {{ $overallAccuracy !== null ? number_format($overallAccuracy, 2, ',', '.').'%' : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (! $ready)
            <div class="km-alert">
                <div class="km-alert-title">
                    Pengujian Belum Lengkap
                </div>

                <div class="km-alert-text">
                    {{ $validated->count() }} dari {{ $results->count() }}
                    data telah memiliki label referensi.
                    Lengkapi seluruh label referensi terlebih dahulu agar
                    Confusion Matrix, TP, TN, FP, FN, Accuracy, Precision,
                    Recall, dan F1-Score dapat dihitung secara valid.
                </div>
            </div>
        @endif

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Perbandingan Hasil K-Means dan Label Referensi
                </h3>

                <div class="km-panel-desc">
                    Tabel data pengujian menggunakan komponen tabel Filament.
                </div>
            </div>

            <div>
                {{ $this->table }}
            </div>
        </div>

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Tabel Confusion Matrix 3×3
                </h3>

                <div class="km-panel-desc">
                    Baris menunjukkan kelas aktual dan kolom menunjukkan kelas hasil clustering K-Means.
                </div>
            </div>

            <div class="km-panel-body">
                <div class="km-matrix-wrap">
                    <table class="km-matrix-table">
                        <thead>
                            <tr>
                                <th class="km-matrix-row-title">
                                    Aktual \ Prediksi
                                </th>

                                @foreach ($labels as $label)
                                    <th>{{ $label }}</th>
                                @endforeach

                                <th>Total Aktual</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($labels as $actual)
                                <tr>
                                    <th class="km-matrix-row-title">
                                        {{ $actual }}
                                    </th>

                                    @foreach ($labels as $predicted)
                                        <td
                                            class="km-matrix-cell {{ $actual === $predicted ? 'km-matrix-diagonal' : '' }}"
                                        >
                                            {{ $ready ? $matrix[$actual][$predicted] : '-' }}
                                        </td>
                                    @endforeach

                                    <td>
                                        @if ($ready)
                                            {{ array_sum($matrix[$actual]) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="km-matrix-row-title">
                                    Total Prediksi
                                </th>

                                @foreach ($labels as $predicted)
                                    <td>
                                        @if ($ready)
                                            {{
                                                collect($labels)->sum(
                                                    fn ($actual) =>
                                                        $matrix[$actual][$predicted]
                                                )
                                            }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach

                                <td>
                                    {{ $ready ? $results->count() : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 style="margin-top:24px;font-size:15px;font-weight:700;">
                    Heatmap Confusion Matrix
                </h4>

                <div class="km-heatmap">
                    <div></div>

                    @foreach ($labels as $predicted)
                        <div class="km-heat-title">
                            {{ $predicted }}
                        </div>
                    @endforeach

                    @foreach ($labels as $actual)
                        <div class="km-heat-title">
                            {{ $actual }}
                        </div>

                        @foreach ($labels as $predicted)
                            <div class="km-heat-cell {{ $actual === $predicted ? 'diagonal' : '' }}">
                                <div class="km-heat-value">
                                    {{ $ready ? $matrix[$actual][$predicted] : '-' }}
                                </div>

                                <div class="km-heat-caption">
                                    {{ $actual === $predicted ? 'Benar' : 'Salah' }}
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Confusion Matrix True / False Per Kelas
                </h3>

                <div class="km-panel-desc">
                    Pendekatan One-vs-Rest untuk kelas Rendah, Sedang, dan Tinggi.
                </div>
            </div>

            <div class="km-panel-body">
                <div class="km-class-grid">
                    @foreach ($labels as $label)
                        @php
                            $m = $metrics[$label];
                        @endphp

                        <div class="km-class-card">
                            <div class="km-class-title">
                                Kelas {{ $label }}
                            </div>

                            <div class="km-binary">
                                <div></div>
                                <div class="km-binary-head">Prediksi True</div>
                                <div class="km-binary-head">Prediksi False</div>

                                <div class="km-binary-side">Aktual True</div>

                                <div class="km-binary-cell good">
                                    <div class="km-code">TP</div>
                                    <div class="km-number">
                                        {{ $ready ? $m['tp'] : '-' }}
                                    </div>
                                </div>

                                <div class="km-binary-cell">
                                    <div class="km-code">FN</div>
                                    <div class="km-number">
                                        {{ $ready ? $m['fn'] : '-' }}
                                    </div>
                                </div>

                                <div class="km-binary-side">Aktual False</div>

                                <div class="km-binary-cell">
                                    <div class="km-code">FP</div>
                                    <div class="km-number">
                                        {{ $ready ? $m['fp'] : '-' }}
                                    </div>
                                </div>

                                <div class="km-binary-cell good">
                                    <div class="km-code">TN</div>
                                    <div class="km-number">
                                        {{ $ready ? $m['tn'] : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="km-mini-grid">
                                <div class="km-mini">
                                    <div class="km-mini-label">Accuracy</div>
                                    <div class="km-mini-value">
                                        {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                                    </div>
                                </div>

                                <div class="km-mini">
                                    <div class="km-mini-label">Precision</div>
                                    <div class="km-mini-value">
                                        {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                                    </div>
                                </div>

                                <div class="km-mini">
                                    <div class="km-mini-label">Recall</div>
                                    <div class="km-mini-value">
                                        {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                                    </div>
                                </div>

                                <div class="km-mini">
                                    <div class="km-mini-label">F1-Score</div>
                                    <div class="km-mini-value">
                                        {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Metrik Evaluasi Per Kelas
                </h3>
            </div>

            <div class="km-panel-body" style="overflow-x:auto;">
                <table class="km-metric-table">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>TP</th>
                            <th>TN</th>
                            <th>FP</th>
                            <th>FN</th>
                            <th>Accuracy</th>
                            <th>Precision</th>
                            <th>Recall</th>
                            <th>F1-Score</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($labels as $label)
                            @php
                                $m = $metrics[$label];
                            @endphp

                            <tr>
                                <td><strong>{{ $label }}</strong></td>
                                <td>{{ $ready ? $m['tp'] : '-' }}</td>
                                <td>{{ $ready ? $m['tn'] : '-' }}</td>
                                <td>{{ $ready ? $m['fp'] : '-' }}</td>
                                <td>{{ $ready ? $m['fn'] : '-' }}</td>

                                <td>
                                    {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td>
                                    {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td>
                                    {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td>
                                    {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Metrik Evaluasi Keseluruhan
                </h3>
            </div>

            <div class="km-panel-body">
                <div class="km-summary">
                    <div class="km-stat km-stat-primary">
                        <div class="km-stat-label">Accuracy</div>
                        <div class="km-stat-value">
                            {{ $overallAccuracy !== null ? number_format($overallAccuracy, 2, ',', '.').'%' : '-' }}
                        </div>
                    </div>

                    <div class="km-stat">
                        <div class="km-stat-label">Macro Precision</div>
                        <div class="km-stat-value">
                            {{ $macroPrecision !== null ? number_format($macroPrecision, 2, ',', '.').'%' : '-' }}
                        </div>
                    </div>

                    <div class="km-stat">
                        <div class="km-stat-label">Macro Recall</div>
                        <div class="km-stat-value">
                            {{ $macroRecall !== null ? number_format($macroRecall, 2, ',', '.').'%' : '-' }}
                        </div>
                    </div>

                    <div class="km-stat">
                        <div class="km-stat-label">Macro F1-Score</div>
                        <div class="km-stat-value">
                            {{ $macroF1 !== null ? number_format($macroF1, 2, ',', '.').'%' : '-' }}
                        </div>
                    </div>
                </div>

                <div class="km-formula">
                    <strong>Perhitungan Accuracy</strong><br>
                    Accuracy = (Jumlah Prediksi Benar / Jumlah Data Uji) × 100%<br>

                    @if ($ready)
                        Accuracy = ({{ $correct }} / {{ $results->count() }}) × 100%<br>
                        <strong>
                            Accuracy = {{ number_format($overallAccuracy, 2, ',', '.') }}%
                        </strong>
                    @else
                        <strong>
                            Perhitungan menunggu seluruh label referensi selesai divalidasi.
                        </strong>
                    @endif
                </div>
            </div>
        </div>

        <div class="km-panel">
            <div class="km-panel-header">
                <h3 class="km-panel-title">
                    Rumus dan Penjelasan Perhitungan
                </h3>

                <div class="km-panel-desc">
                    Penjelasan rumus evaluasi K-Means menggunakan Confusion Matrix
                    dengan pendekatan One-vs-Rest.
                </div>
            </div>

            <div class="km-panel-body">
                <div class="km-formula">
                    <strong>1. True Positive (TP)</strong><br>
                    TP adalah jumlah data pada suatu kelas yang diprediksi
                    dengan benar sebagai kelas tersebut.<br><br>

                    <strong>2. True Negative (TN)</strong><br>
                    TN adalah jumlah data yang bukan termasuk suatu kelas
                    dan juga tidak diprediksi sebagai kelas tersebut.<br><br>

                    <strong>3. False Positive (FP)</strong><br>
                    FP adalah jumlah data dari kelas lain yang salah
                    diprediksi sebagai kelas yang sedang diuji.<br><br>

                    <strong>4. False Negative (FN)</strong><br>
                    FN adalah jumlah data suatu kelas yang salah
                    diprediksi sebagai kelas lain.
                </div>

                <div class="km-formula">
                    <strong>Rumus Accuracy Per Kelas</strong><br><br>

                    Accuracy =
                    ((TP + TN) / (TP + TN + FP + FN)) × 100%
                </div>

                <div class="km-formula">
                    <strong>Rumus Precision</strong><br><br>

                    Precision =
                    TP / (TP + FP) × 100%
                </div>

                <div class="km-formula">
                    <strong>Rumus Recall</strong><br><br>

                    Recall =
                    TP / (TP + FN) × 100%
                </div>

                <div class="km-formula">
                    <strong>Rumus F1-Score</strong><br><br>

                    F1-Score =
                    2 × ((Precision × Recall) / (Precision + Recall))
                </div>

                <div class="km-formula">
                    <strong>Rumus Accuracy Keseluruhan</strong><br><br>

                    Accuracy =
                    (Jumlah Prediksi Benar / Jumlah Data Uji) × 100%<br><br>

                    Accuracy =
                    (21 / 25) × 100%<br><br>

                    <strong>Accuracy = 84,00%</strong>
                </div>

                <div class="km-formula">
                    <strong>Perhitungan Kelas Rendah</strong><br><br>

                    TP = 9,
                    TN = 13,
                    FP = 3,
                    FN = 0<br><br>

                    Accuracy =
                    ((9 + 13) / 25) × 100%
                    = <strong>88,00%</strong><br>

                    Precision =
                    9 / (9 + 3) × 100%
                    = <strong>75,00%</strong><br>

                    Recall =
                    9 / (9 + 0) × 100%
                    = <strong>100,00%</strong><br>

                    F1-Score =
                    2 × ((75 × 100) / (75 + 100))
                    = <strong>85,71%</strong>
                </div>

                <div class="km-formula">
                    <strong>Perhitungan Kelas Sedang</strong><br><br>

                    TP = 4,
                    TN = 17,
                    FP = 0,
                    FN = 4<br><br>

                    Accuracy =
                    ((4 + 17) / 25) × 100%
                    = <strong>84,00%</strong><br>

                    Precision =
                    4 / (4 + 0) × 100%
                    = <strong>100,00%</strong><br>

                    Recall =
                    4 / (4 + 4) × 100%
                    = <strong>50,00%</strong><br>

                    F1-Score =
                    2 × ((100 × 50) / (100 + 50))
                    = <strong>66,67%</strong>
                </div>

                <div class="km-formula">
                    <strong>Perhitungan Kelas Tinggi</strong><br><br>

                    TP = 8,
                    TN = 16,
                    FP = 1,
                    FN = 0<br><br>

                    Accuracy =
                    ((8 + 16) / 25) × 100%
                    = <strong>96,00%</strong><br>

                    Precision =
                    8 / (8 + 1) × 100%
                    = <strong>88,89%</strong><br>

                    Recall =
                    8 / (8 + 0) × 100%
                    = <strong>100,00%</strong><br>

                    F1-Score =
                    2 × ((88,89 × 100) / (88,89 + 100))
                    = <strong>94,12%</strong>
                </div>

                <div class="km-formula">
                    <strong>Macro Precision</strong><br><br>

                    Macro Precision =
                    (75,00% + 100,00% + 88,89%) / 3<br>

                    <strong>Macro Precision = 87,96%</strong>
                </div>

                <div class="km-formula">
                    <strong>Macro Recall</strong><br><br>

                    Macro Recall =
                    (100,00% + 50,00% + 100,00%) / 3<br>

                    <strong>Macro Recall = 83,33%</strong>
                </div>

                <div class="km-formula">
                    <strong>Macro F1-Score</strong><br><br>

                    Macro F1-Score =
                    (85,71% + 66,67% + 94,12%) / 3<br>

                    <strong>Macro F1-Score = 82,17%</strong>
                </div>

                <div class="km-formula">
                    <strong>Interpretasi Hasil</strong><br><br>

                    Dari 25 data uji, sebanyak 21 data berhasil dikelompokkan
                    sesuai dengan label referensi dan 4 data berbeda dengan
                    label referensi. Berdasarkan hasil tersebut, K-Means
                    memperoleh Accuracy keseluruhan sebesar
                    <strong>84,00%</strong>.<br><br>

                    Kelas Tinggi memperoleh performa terbaik dengan Accuracy
                    96,00%, Precision 88,89%, Recall 100,00%, dan F1-Score
                    94,12%. Kelas Rendah memperoleh Accuracy 88,00%,
                    sedangkan kelas Sedang memperoleh Accuracy 84,00%.
                    Kesalahan terbesar pada kelas Sedang terjadi karena
                    terdapat 4 False Negative.
                </div>
            </div>
        </div>

    @endif
</div>
