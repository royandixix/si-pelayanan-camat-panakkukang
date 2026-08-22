@extends('layouts.pengunjung')

@section('title', 'Pegawai Kecamatan - Kecamatan Panakkukang')

@section('content')
<x-pengunjung.page-hero
    kicker="Aparatur Kecamatan"
    title="Pejabat dan Pegawai"
    description="Mengenal aparatur Kecamatan Panakkukang yang menjalankan tugas pemerintahan dan pelayanan kepada masyarakat."
/>

<section class="public-section">
    <div class="public-container">
        <div class="public-employee-page-heading" data-public-reveal>
            <div>
                <div class="public-kicker">
                    Daftar Pegawai
                </div>

                <h2 class="public-title-sm">
                    Aparatur Kecamatan Panakkukang.
                </h2>

                <p class="public-copy public-employee-intro">
                    Pilih unit kerja untuk melihat pegawai berdasarkan
                    bagian atau seksi masing-masing.
                </p>
            </div>
        </div>

        @if($unitKerja->isNotEmpty())
            <div class="public-employee-filters" data-public-reveal>
                <a
                    href="{{ route('pegawai.index') }}"
                    class="public-employee-filter {{ $unit === '' ? 'is-active' : '' }}"
                >
                    Semua
                </a>

                @foreach($unitKerja as $namaUnit)
                    <a
                        href="{{ route('pegawai.index', ['unit' => $namaUnit]) }}"
                        class="public-employee-filter {{ $unit === $namaUnit ? 'is-active' : '' }}"
                    >
                        {{ $namaUnit }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="public-employee-grid public-employee-grid-page">
            @forelse($pegawai as $item)
                <article class="public-employee-card" data-public-reveal>
                    <div class="public-employee-photo">
                        @if($item->photo)
                            <img
                                src="{{ asset('storage/' . $item->photo) }}"
                                alt="{{ $item->name }}"
                            >
                        @else
                            <div class="public-employee-placeholder">
                                <span>
                                    {{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="public-employee-body">
                        <div class="public-employee-unit">
                            {{ $item->work_unit ?: 'Kecamatan Panakkukang' }}
                        </div>

                        <h3>
                            {{ $item->name }}
                        </h3>

                        <p>
                            {{ $item->position }}
                        </p>
                    </div>
                </article>
            @empty
                <div class="public-employee-empty">
                    <div class="public-employee-empty-icon">
                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>

                    <h3>
                        Data pegawai belum tersedia
                    </h3>

                    <p>
                        Belum ada pegawai aktif untuk unit kerja yang dipilih.
                    </p>
                </div>
            @endforelse
        </div>

        @if($pegawai->hasPages())
            <div class="public-employee-pagination">
                {{ $pegawai->links() }}
            </div>
        @endif
    </div>
</section>
@endsection