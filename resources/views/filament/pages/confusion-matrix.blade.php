<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Status Validasi
        </x-slot>

        @if (!$evaluation['run_id'])
            <p>Belum ada hasil K-Means.</p>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
                <div>
                    <strong>Run</strong>
                    <div>#{{ $evaluation['run_id'] }}</div>
                </div>

                <div>
                    <strong>Total Data</strong>
                    <div>{{ $evaluation['total_results'] }}</div>
                </div>

                <div>
                    <strong>Sudah Divalidasi</strong>
                    <div>{{ $evaluation['labeled_results'] }}</div>
                </div>

                <div>
                    <strong>Belum Divalidasi</strong>
                    <div>{{ $evaluation['unlabeled_results'] }}</div>
                </div>

                <div>
                    <strong>Cakupan Validasi</strong>
                    <div>{{ number_format($evaluation['coverage'], 2) }}%</div>
                </div>
            </div>

            @if (!$evaluation['is_complete'])
                <div style="margin-top:20px;padding:14px;border:1px solid #d4a84f;border-radius:8px;">
                    Confusion Matrix belum final karena Label Referensi belum lengkap.
                </div>
            @endif
        @endif
    </x-filament::section>

    @if ($evaluation['run_id'])
        <x-filament::section>
            <x-slot name="heading">
                Matriks Aktual vs Prediksi
            </x-slot>

            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;text-align:center;">
                    <thead>
                        <tr>
                            <th style="padding:12px;border:1px solid #4b5563;">
                                Aktual \ Prediksi
                            </th>

                            @foreach ($evaluation['labels'] as $label)
                                <th style="padding:12px;border:1px solid #4b5563;">
                                    {{ $label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($evaluation['labels'] as $actual)
                            <tr>
                                <th style="padding:12px;border:1px solid #4b5563;">
                                    {{ $actual }}
                                </th>

                                @foreach ($evaluation['labels'] as $predicted)
                                    <td style="padding:12px;border:1px solid #4b5563;font-size:18px;font-weight:600;">
                                        {{ $evaluation['matrix'][$actual][$predicted] }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
