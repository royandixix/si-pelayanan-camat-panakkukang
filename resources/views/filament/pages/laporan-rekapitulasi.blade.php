<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Data Pelayanan Seluruh Seksi
        </x-slot>

        <x-slot name="description">
            Super Admin dapat memantau seluruh permohonan dari lima seksi,
            melakukan filter data, melihat detail, dan mengunduh dokumen
            hasil pelayanan dalam format PDF.
        </x-slot>

        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>
