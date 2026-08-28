<x-filament-panels::page>
    {{ $this->table }}

    <div class="mt-8">
        <x-filament::section>
            <x-slot name="heading">
                Pengujian Confusion Matrix
            </x-slot>

            <x-slot name="description">
                Pengujian hasil clustering K-Means menggunakan label referensi dengan pendekatan True Positive, True Negative, False Positive, dan False Negative.
            </x-slot>

            <livewire:admin.k-means-true-false-evaluation />
        </x-filament::section>
    </div>
</x-filament-panels::page>
