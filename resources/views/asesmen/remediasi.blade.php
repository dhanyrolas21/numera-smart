@php
    $n = $hasil['jumlah_benda'] ?? 3;
    $h = $hasil['harga_satuan'] ?? 4000;
    $total = $n * $h;
    $rp = fn ($x) => 'Rp' . number_format($x, 0, ',', '.');
@endphp
<x-asesmen-layout :step="5">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Belajar Bersama Yuk!</h1>
        <p class="mt-1 text-slate-600">Penjelasan ini disesuaikan dengan caramu berpikir.</p>
    </div>

    <div x-data="{ tab: 'visual', pilih: null }" class="rounded-3xl bg-white p-6 shadow-sm">
        {{-- Tab --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4">
            @foreach (['visual' => 'Penjelasan Visual', 'contoh' => 'Contoh Serupa', 'latihan' => 'Latihan Terarah', 'simpul' => 'Kesimpulan'] as $k => $label)
                <button type="button" x-on:click="tab = '{{ $k }}'"
                    x-bind:class="tab === '{{ $k }}' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-50 text-slate-500'"
                    class="rounded-xl px-4 py-2 text-sm font-bold">{{ $label }}</button>
            @endforeach
        </div>

        {{-- 1. Penjelasan visual --}}
        <div x-show="tab === 'visual'" class="mt-6 space-y-5">
            @if ($fokus['operasi'])
                <p class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    Tadi kamu memilih operasi lain. Kalau ada beberapa benda dengan harga sama, menjumlahkannya berulang boleh, tapi bisa diringkas dengan <b>perkalian</b>.
                </p>
            @endif
            @if ($fokus['angka'])
                <p class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    Angka di depan tanda × adalah <b>jumlah benda ({{ $n }})</b>, bukan angka lain. Angka di belakangnya adalah <b>harga satu benda</b>.
                </p>
            @endif
            @unless ($fokus['operasi'] || $fokus['angka'])
                <p class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    Kamu sudah paham operasinya. Sekarang kita pastikan angkanya dipasang di tempat yang tepat.
                </p>
            @endunless

            <p class="font-bold">{{ $n }} benda dengan harga sama</p>
            <div class="flex flex-wrap items-center gap-2">
                @for ($i = 1; $i <= $n; $i++)
                    <div class="rounded-2xl border-2 border-emerald-400 bg-emerald-50 px-4 py-3 text-center">
                        <p class="text-xs font-bold text-emerald-700">Benda {{ $i }}</p>
                        <p class="font-bold">{{ $rp($h) }}</p>
                    </div>
                    @if ($i < $n)<span class="font-bold text-slate-400">+</span>@endif
                @endfor
            </div>
            <p class="rounded-2xl bg-slate-50 p-4 font-mono">
                {{ implode(' + ', array_fill(0, $n, $rp($h))) }} = {{ $rp($total) }}
            </p>
            <div class="rounded-2xl border-2 border-amber-300 bg-amber-50 p-5 text-center">
                <p class="text-xs font-bold text-amber-800">BENTUK PERKALIAN LEBIH RINGKAS</p>
                <p class="mt-2 text-2xl font-extrabold">
                    {{ $n }} <span class="text-amber-600">×</span> {{ $rp($h) }} <span class="text-amber-600">=</span> {{ $rp($total) }}
                </p>
                <p class="mt-2 text-sm text-amber-900">{{ $n }} = jumlah benda, {{ $rp($h) }} = harga satu benda.</p>
            </div>
        </div>

        {{-- 2. Contoh serupa --}}
        <div x-show="tab === 'contoh'" x-cloak class="mt-6 space-y-4">
            <p class="font-bold">Dina membeli 4 pensil. Harga satu pensil Rp2.000. Berapa total yang dibayar?</p>
            <p class="rounded-2xl bg-slate-50 p-4 font-mono">Rp2.000 + Rp2.000 + Rp2.000 + Rp2.000 = Rp8.000</p>
            <p class="rounded-2xl bg-emerald-50 p-4 font-mono font-bold text-emerald-800">4 × Rp2.000 = Rp8.000</p>
            <p class="text-sm text-slate-600">Ada 4 pensil, jadi angka depannya 4, bukan angka lain.</p>
        </div>

        {{-- 3. Latihan terarah --}}
        <div x-show="tab === 'latihan'" x-cloak class="mt-6 space-y-4">
            <p class="font-bold">Rudi membeli 5 buku. Harga satu buku Rp3.000. Total harganya Rp15.000.</p>
            <p class="text-lg font-semibold">Isi bagian yang kosong: <span class="font-mono">___ × Rp3.000 = Rp15.000</span></p>
            <div class="flex gap-3">
                @foreach ([3, 5, 8] as $angka)
                    <button type="button" x-on:click="pilih = {{ $angka }}"
                        x-bind:class="pilih === {{ $angka }} ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200'"
                        class="size-16 rounded-2xl border-2 text-xl font-bold">{{ $angka }}</button>
                @endforeach
            </div>
            <p x-show="pilih === 5" x-cloak class="font-semibold text-emerald-700">Tepat! Ada 5 buku, jadi angka depannya 5.</p>
            <p x-show="pilih !== null && pilih !== 5" x-cloak class="font-semibold text-amber-700">Coba lihat lagi: berapa jumlah buku yang dibeli Rudi?</p>
        </div>

        {{-- 4. Kesimpulan --}}
        <div x-show="tab === 'simpul'" x-cloak class="mt-6 space-y-4">
            <div class="rounded-2xl bg-emerald-600 p-5 text-white">
                <p class="font-bold">Ingat ya: Jumlah Benda × Harga Satu Benda = Total Harga</p>
            </div>
            <form method="POST" action="{{ route('asesmen.remediasi.selesai', $attempt) }}">
                @csrf
                <button type="submit" class="rounded-2xl bg-emerald-600 px-8 py-3 font-bold text-white shadow hover:bg-emerald-700">
                    Lanjut ke Uji Pemahaman
                </button>
            </form>
        </div>
    </div>
</x-asesmen-layout>
