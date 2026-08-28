<div class="space-y-6">
    @if (! $run)
        <x-filament::section>
            <div class="py-8 text-center">
                <div class="text-lg font-semibold">
                    Belum Ada Hasil K-Means
                </div>

                <div class="mt-2 text-sm text-gray-500">
                    Jalankan proses K-Means terlebih dahulu.
                </div>
            </div>
        </x-filament::section>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-filament::section>
                <div class="text-sm text-gray-500">
                    Run Final
                </div>

                <div class="mt-1 text-2xl font-bold">
                    #{{ $run->id }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">
                    Data Uji
                </div>

                <div class="mt-1 text-2xl font-bold">
                    {{ $results->count() }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">
                    Sudah Validasi
                </div>

                <div class="mt-1 text-2xl font-bold">
                    {{ $validated->count() }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">
                    Belum Validasi
                </div>

                <div class="mt-1 text-2xl font-bold">
                    {{ $results->count() - $validated->count() }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">
                    Akurasi Keseluruhan
                </div>

                <div class="mt-1 text-2xl font-bold">
                    @if ($overallAccuracy !== null)
                        {{ number_format($overallAccuracy, 2, ',', '.') }}%
                    @else
                        -
                    @endif
                </div>
            </x-filament::section>
        </div>

        @if ($validated->count() < $results->count())
            <div
                class="rounded-xl border border-warning-300 bg-warning-50 p-4 dark:border-warning-700 dark:bg-warning-950/30"
            >
                <div class="font-semibold text-warning-700 dark:text-warning-400">
                    Pengujian belum dapat dihitung secara penuh
                </div>

                <div class="mt-1 text-sm text-warning-700 dark:text-warning-400">
                    {{ $validated->count() }} dari
                    {{ $results->count() }} data sudah memiliki label referensi.
                    Isi seluruh label referensi terlebih dahulu agar TP, TN, FP, FN,
                    Accuracy, Precision, Recall, dan F1-Score dapat dihitung secara valid.
                </div>
            </div>
        @else
            <div
                class="rounded-xl border border-success-300 bg-success-50 p-4 dark:border-success-700 dark:bg-success-950/30"
            >
                <div class="font-semibold text-success-700 dark:text-success-400">
                    Validasi lengkap
                </div>

                <div class="mt-1 text-sm text-success-700 dark:text-success-400">
                    Seluruh {{ $results->count() }} data telah memiliki label referensi
                    dan dapat digunakan untuk perhitungan Confusion Matrix.
                </div>
            </div>
        @endif

        <x-filament::section>
            <x-slot name="heading">
                Perbandingan Hasil K-Means dan Label Referensi
            </x-slot>

            <x-slot name="description">
                Tabel membandingkan hasil clustering K-Means dengan label referensi
                yang digunakan sebagai data aktual.
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm dark:divide-white/10">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <th class="px-4 py-3 text-left font-semibold">
                                No
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Dataset
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Tahun
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Bulan
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Jumlah
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Hari Aktif
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                K-Means
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Label Referensi
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Hasil
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @foreach ($results as $index => $row)
                            @php
                                $hasReference = in_array(
                                    $row->reference_label,
                                    $labels,
                                    true
                                );

                                $isCorrect = $hasReference
                                    && $row->reference_label
                                        === $row->cluster_label;
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $row->dataset_name }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $row->year }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ str_pad($row->month, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $row->jumlah_pelayanan }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $row->hari_aktif }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        @class([
                                            'inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold',
                                            'bg-success-100 text-success-700 dark:bg-success-950 dark:text-success-400' => $row->cluster_label === 'Tinggi',
                                            'bg-warning-100 text-warning-700 dark:bg-warning-950 dark:text-warning-400' => $row->cluster_label === 'Sedang',
                                            'bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-gray-300' => $row->cluster_label === 'Rendah',
                                        ])
                                    >
                                        {{ $row->cluster_label }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($hasReference)
                                        <span
                                            class="inline-flex rounded-lg bg-primary-100 px-2.5 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-950 dark:text-primary-400"
                                        >
                                            {{ $row->reference_label }}
                                        </span>
                                    @else
                                        <a
                                            href="{{ url('/admin/hasil-clustering/'.$row->id.'/label-referensi') }}"
                                            class="font-semibold text-primary-600 hover:underline dark:text-primary-400"
                                        >
                                            Isi Label
                                        </a>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if (! $hasReference)
                                        <span
                                            class="inline-flex rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 dark:bg-white/10 dark:text-gray-300"
                                        >
                                            Menunggu
                                        </span>
                                    @elseif ($isCorrect)
                                        <span
                                            class="inline-flex rounded-lg bg-success-100 px-2.5 py-1 text-xs font-semibold text-success-700 dark:bg-success-950 dark:text-success-400"
                                        >
                                            Benar
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-lg bg-danger-100 px-2.5 py-1 text-xs font-semibold text-danger-700 dark:bg-danger-950 dark:text-danger-400"
                                        >
                                            Salah
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            @foreach ($labels as $label)
                @php
                    $m = $metrics[$label];
                @endphp

                <x-filament::section>
                    <x-slot name="heading">
                        Kelas {{ $label }}
                    </x-slot>

                    <x-slot name="description">
                        Confusion Matrix One-vs-Rest
                    </x-slot>

                    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-white/10">
                        <table class="w-full text-center text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-white/5">
                                    <th
                                        rowspan="2"
                                        class="border-b border-r border-gray-200 px-3 py-3 dark:border-white/10"
                                    >
                                        Aktual
                                    </th>

                                    <th
                                        colspan="2"
                                        class="border-b border-gray-200 px-3 py-2 font-semibold dark:border-white/10"
                                    >
                                        Prediksi
                                    </th>
                                </tr>

                                <tr class="bg-gray-50 dark:bg-white/5">
                                    <th
                                        class="border-b border-r border-gray-200 px-3 py-2 dark:border-white/10"
                                    >
                                        True
                                    </th>

                                    <th
                                        class="border-b border-gray-200 px-3 py-2 dark:border-white/10"
                                    >
                                        False
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th
                                        class="border-r border-b border-gray-200 bg-gray-50 px-3 py-4 dark:border-white/10 dark:bg-white/5"
                                    >
                                        True
                                    </th>

                                    <td
                                        class="border-r border-b border-gray-200 px-3 py-4 dark:border-white/10"
                                    >
                                        <div class="text-xs text-gray-500">
                                            TP
                                        </div>

                                        <div class="mt-1 text-xl font-bold">
                                            {{ $validated->count() ? $m['tp'] : '-' }}
                                        </div>
                                    </td>

                                    <td
                                        class="border-b border-gray-200 px-3 py-4 dark:border-white/10"
                                    >
                                        <div class="text-xs text-gray-500">
                                            FN
                                        </div>

                                        <div class="mt-1 text-xl font-bold">
                                            {{ $validated->count() ? $m['fn'] : '-' }}
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th
                                        class="border-r border-gray-200 bg-gray-50 px-3 py-4 dark:border-white/10 dark:bg-white/5"
                                    >
                                        False
                                    </th>

                                    <td
                                        class="border-r border-gray-200 px-3 py-4 dark:border-white/10"
                                    >
                                        <div class="text-xs text-gray-500">
                                            FP
                                        </div>

                                        <div class="mt-1 text-xl font-bold">
                                            {{ $validated->count() ? $m['fp'] : '-' }}
                                        </div>
                                    </td>

                                    <td class="px-3 py-4">
                                        <div class="text-xs text-gray-500">
                                            TN
                                        </div>

                                        <div class="mt-1 text-xl font-bold">
                                            {{ $validated->count() ? $m['tn'] : '-' }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5">
                            <div class="text-xs text-gray-500">
                                Accuracy
                            </div>

                            <div class="mt-1 font-semibold">
                                {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5">
                            <div class="text-xs text-gray-500">
                                Precision
                            </div>

                            <div class="mt-1 font-semibold">
                                {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5">
                            <div class="text-xs text-gray-500">
                                Recall
                            </div>

                            <div class="mt-1 font-semibold">
                                {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5">
                            <div class="text-xs text-gray-500">
                                F1-Score
                            </div>

                            <div class="mt-1 font-semibold">
                                {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            @endforeach
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Ringkasan Hasil Evaluasi
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm dark:divide-white/10">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <th class="px-4 py-3 text-left">
                                Kelas
                            </th>

                            <th class="px-4 py-3 text-center">
                                TP
                            </th>

                            <th class="px-4 py-3 text-center">
                                TN
                            </th>

                            <th class="px-4 py-3 text-center">
                                FP
                            </th>

                            <th class="px-4 py-3 text-center">
                                FN
                            </th>

                            <th class="px-4 py-3 text-center">
                                Accuracy
                            </th>

                            <th class="px-4 py-3 text-center">
                                Precision
                            </th>

                            <th class="px-4 py-3 text-center">
                                Recall
                            </th>

                            <th class="px-4 py-3 text-center">
                                F1-Score
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        @foreach ($labels as $label)
                            @php
                                $m = $metrics[$label];
                            @endphp

                            <tr>
                                <td class="px-4 py-3 font-semibold">
                                    {{ $label }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $validated->count() ? $m['tp'] : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $validated->count() ? $m['tn'] : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $validated->count() ? $m['fp'] : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $validated->count() ? $m['fn'] : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Interpretasi True / False
            </x-slot>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="font-bold">
                        True Positive
                    </div>

                    <div class="mt-2 text-sm text-gray-500">
                        Data aktual termasuk suatu kelas dan hasil K-Means
                        memprediksi kelas yang sama.
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="font-bold">
                        False Negative
                    </div>

                    <div class="mt-2 text-sm text-gray-500">
                        Data aktual termasuk suatu kelas tetapi hasil K-Means
                        memprediksi kelas lain.
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="font-bold">
                        False Positive
                    </div>

                    <div class="mt-2 text-sm text-gray-500">
                        Data aktual bukan suatu kelas tetapi hasil K-Means
                        memprediksi sebagai kelas tersebut.
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="font-bold">
                        True Negative
                    </div>

                    <div class="mt-2 text-sm text-gray-500">
                        Data aktual bukan suatu kelas dan K-Means juga
                        tidak memprediksi sebagai kelas tersebut.
                    </div>
                </div>
            </div>
        </x-filament::section>
    @endif
</div>
