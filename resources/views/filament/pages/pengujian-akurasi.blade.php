<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Status Pengujian Akurasi
        </x-slot>

        <x-slot name="description">
            Pengujian akurasi dilakukan dengan membandingkan kategori hasil K-Means terhadap Label Referensi yang diperoleh secara independen.
        </x-slot>

        @if (!$evaluation['run_id'])
            <div style="padding:14px;border:1px solid #f59e0b;border-radius:10px;background:#fffbeb;color:#92400e;">
                Belum ada hasil K-Means.
            </div>
        @elseif (!$evaluation['is_complete'])
            <div style="padding:14px;border:1px solid #f59e0b;border-radius:10px;background:#fffbeb;color:#92400e;">
                Pengujian akurasi belum final karena Label Referensi belum lengkap.
                Saat ini {{ $evaluation['labeled_results'] }} dari
                {{ $evaluation['total_results'] }} data telah memiliki Label Referensi.
            </div>
        @else
            <div style="padding:14px;border:1px solid #86efac;border-radius:10px;background:#f0fdf4;color:#166534;">
                Seluruh data telah memiliki Label Referensi dan pengujian akurasi dapat dihitung secara lengkap.
            </div>
        @endif

        @if ($evaluation['run_id'])
            <div style="margin-top:18px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
                <div>
                    <strong>Total Hasil</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['total_results'] }}
                    </div>
                </div>

                <div>
                    <strong>Sudah Divalidasi</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['labeled_results'] }}
                    </div>
                </div>

                <div>
                    <strong>Belum Divalidasi</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ $evaluation['unlabeled_results'] }}
                    </div>
                </div>

                <div>
                    <strong>Cakupan Validasi</strong>
                    <div style="font-size:24px;font-weight:700;">
                        {{ number_format($evaluation['coverage'], 2) }}%
                    </div>
                </div>
            </div>
        @endif
    </x-filament::section>

    @if ($evaluation['run_id'])
        <x-filament::section>
            <x-slot name="heading">
                Hasil Pengujian Akurasi
            </x-slot>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;">
                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <strong>Accuracy</strong>
                    <div style="font-size:26px;font-weight:700;">
                        {{ $evaluation['accuracy'] === null ? '-' : number_format($evaluation['accuracy'] * 100, 2).'%' }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <strong>Macro Precision</strong>
                    <div style="font-size:26px;font-weight:700;">
                        {{ $evaluation['precision_macro'] === null ? '-' : number_format($evaluation['precision_macro'] * 100, 2).'%' }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <strong>Macro Recall</strong>
                    <div style="font-size:26px;font-weight:700;">
                        {{ $evaluation['recall_macro'] === null ? '-' : number_format($evaluation['recall_macro'] * 100, 2).'%' }}
                    </div>
                </div>

                <div style="padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;">
                    <strong>Macro F1-Score</strong>
                    <div style="font-size:26px;font-weight:700;">
                        {{ $evaluation['f1_macro'] === null ? '-' : number_format($evaluation['f1_macro'] * 100, 2).'%' }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Evaluasi Per Kategori
            </x-slot>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;text-align:center;background:#fff;color:#111827;">
                    <thead>
                        <tr style="background:#f3f4f6;">
                            <th style="padding:10px;border:1px solid #d1d5db;">Kategori</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">TP</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">FP</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">FN</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">TN</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">Precision</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">Recall</th>
                            <th style="padding:10px;border:1px solid #d1d5db;">F1-Score</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($evaluation['class_metrics'] as $label => $metric)
                            <tr>
                                <td style="padding:10px;border:1px solid #d1d5db;font-weight:600;">
                                    {{ $label }}
                                </td>
                                <td style="padding:10px;border:1px solid #d1d5db;">{{ $metric['tp'] }}</td>
                                <td style="padding:10px;border:1px solid #d1d5db;">{{ $metric['fp'] }}</td>
                                <td style="padding:10px;border:1px solid #d1d5db;">{{ $metric['fn'] }}</td>
                                <td style="padding:10px;border:1px solid #d1d5db;">{{ $metric['tn'] }}</td>
                                <td style="padding:10px;border:1px solid #d1d5db;">
                                    {{ number_format($metric['precision'] * 100, 2) }}%
                                </td>
                                <td style="padding:10px;border:1px solid #d1d5db;">
                                    {{ number_format($metric['recall'] * 100, 2) }}%
                                </td>
                                <td style="padding:10px;border:1px solid #d1d5db;">
                                    {{ number_format($metric['f1'] * 100, 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
