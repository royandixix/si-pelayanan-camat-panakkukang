<div class="space-y-6">
    @if (! $run)
        <x-filament::section>
            <div class="py-10 text-center">
                <div class="text-lg font-semibold">
                    Belum Ada Hasil K-Means
                </div>

                <div class="mt-2 text-sm text-gray-500">
                    Belum tersedia hasil clustering untuk dilakukan pengujian.
                </div>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <x-slot name="heading">
                Status Pengujian K-Means
            </x-slot>

            <x-slot name="description">
                Ringkasan pengujian Run Final #{{ $run->id }}.
            </x-slot>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="text-xs text-gray-500">Run Final</div>
                    <div class="mt-1 text-2xl font-bold">#{{ $run->id }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="text-xs text-gray-500">Data Uji</div>
                    <div class="mt-1 text-2xl font-bold">{{ $results->count() }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="text-xs text-gray-500">Sudah Validasi</div>
                    <div class="mt-1 text-2xl font-bold">{{ $validated->count() }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="text-xs text-gray-500">Belum Validasi</div>
                    <div class="mt-1 text-2xl font-bold">
                        {{ $results->count() - $validated->count() }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                    <div class="text-xs text-gray-500">Accuracy</div>
                    <div class="mt-1 text-2xl font-bold">
                        {{ $overallAccuracy !== null ? number_format($overallAccuracy, 2, ',', '.').'%' : '-' }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        @if (! $ready)
            <x-filament::section>
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-warning-500" />

                    <div>
                        <div class="font-semibold">
                            Pengujian Belum Lengkap
                        </div>

                        <div class="mt-1 text-sm leading-6 text-gray-500">
                            {{ $validated->count() }} dari {{ $results->count() }}
                            data sudah memiliki label referensi.
                            Lengkapi seluruh label referensi sebelum hasil evaluasi
                            digunakan sebagai hasil pengujian final.
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        <x-filament::section>
            <x-slot name="heading">
                Perbandingan Hasil K-Means dan Label Referensi
            </x-slot>

            <x-slot name="description">
                Tabel Native Filament untuk membandingkan hasil clustering dengan label aktual.
            </x-slot>

            {{ $this->table }}
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Confusion Matrix True / False
            </x-slot>

            <x-slot name="description">
                Pengujian One-vs-Rest untuk kelas Rendah, Sedang, dan Tinggi.
            </x-slot>

            <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
                @foreach ($labels as $label)
                    @php
                        $m = $metrics[$label];
                    @endphp

                    <div class="rounded-xl border border-gray-200 p-5 dark:border-white/10">
                        <div class="mb-5">
                            <div class="text-base font-bold">
                                Kelas {{ $label }}
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                Confusion Matrix One-vs-Rest
                            </div>
                        </div>

                        <div class="mb-2 grid grid-cols-3 gap-2 text-center">
                            <div></div>

                            <div class="rounded-lg bg-gray-100 p-2 text-xs font-semibold dark:bg-white/5">
                                Prediksi True
                            </div>

                            <div class="rounded-lg bg-gray-100 p-2 text-xs font-semibold dark:bg-white/5">
                                Prediksi False
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="flex items-center justify-center rounded-lg bg-gray-100 p-3 text-xs font-semibold dark:bg-white/5">
                                Aktual True
                            </div>

                            <div class="rounded-xl border border-primary-500/30 bg-primary-50 p-4 dark:bg-primary-950/20">
                                <div class="text-xs text-gray-500">TP</div>
                                <div class="mt-2 text-2xl font-bold">
                                    {{ $ready ? $m['tp'] : '-' }}
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                                <div class="text-xs text-gray-500">FN</div>
                                <div class="mt-2 text-2xl font-bold">
                                    {{ $ready ? $m['fn'] : '-' }}
                                </div>
                            </div>

                            <div class="flex items-center justify-center rounded-lg bg-gray-100 p-3 text-xs font-semibold dark:bg-white/5">
                                Aktual False
                            </div>

                            <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
                                <div class="text-xs text-gray-500">FP</div>
                                <div class="mt-2 text-2xl font-bold">
                                    {{ $ready ? $m['fp'] : '-' }}
                                </div>
                            </div>

                            <div class="rounded-xl border border-primary-500/30 bg-primary-50 p-4 dark:bg-primary-950/20">
                                <div class="text-xs text-gray-500">TN</div>
                                <div class="mt-2 text-2xl font-bold">
                                    {{ $ready ? $m['tn'] : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-lg border border-gray-200 p-3 dark:border-white/10">
                                <div class="text-xs text-gray-500">Accuracy</div>
                                <div class="mt-1 font-bold">
                                    {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-white/10">
                                <div class="text-xs text-gray-500">Precision</div>
                                <div class="mt-1 font-bold">
                                    {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-white/10">
                                <div class="text-xs text-gray-500">Recall</div>
                                <div class="mt-1 font-bold">
                                    {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 p-3 dark:border-white/10">
                                <div class="text-xs text-gray-500">F1-Score</div>
                                <div class="mt-1 font-bold">
                                    {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Ringkasan Hasil Evaluasi
            </x-slot>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($labels as $label)
                    @php
                        $m = $metrics[$label];
                    @endphp

                    <div class="rounded-xl border border-gray-200 p-5 dark:border-white/10">
                        <div class="text-base font-bold">
                            {{ $label }}
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div>TP</div>
                            <div class="text-right font-bold">{{ $ready ? $m['tp'] : '-' }}</div>

                            <div>TN</div>
                            <div class="text-right font-bold">{{ $ready ? $m['tn'] : '-' }}</div>

                            <div>FP</div>
                            <div class="text-right font-bold">{{ $ready ? $m['fp'] : '-' }}</div>

                            <div>FN</div>
                            <div class="text-right font-bold">{{ $ready ? $m['fn'] : '-' }}</div>

                            <div>Accuracy</div>
                            <div class="text-right font-bold">
                                {{ $m['accuracy'] !== null ? number_format($m['accuracy'], 2, ',', '.').'%' : '-' }}
                            </div>

                            <div>Precision</div>
                            <div class="text-right font-bold">
                                {{ $m['precision'] !== null ? number_format($m['precision'], 2, ',', '.').'%' : '-' }}
                            </div>

                            <div>Recall</div>
                            <div class="text-right font-bold">
                                {{ $m['recall'] !== null ? number_format($m['recall'], 2, ',', '.').'%' : '-' }}
                            </div>

                            <div>F1-Score</div>
                            <div class="text-right font-bold">
                                {{ $m['f1'] !== null ? number_format($m['f1'], 2, ',', '.').'%' : '-' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endif
</div>
