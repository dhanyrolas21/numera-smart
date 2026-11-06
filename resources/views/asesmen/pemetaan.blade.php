<x-asesmen-layout :step="3">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Pemetaan Dugaan Kesulitan 🗺️</h1>
        <p class="mt-1 text-slate-600">Ini peta cara berpikirmu. Dari sini kita tahu bagian mana yang perlu dilatih.</p>
    </div>

    @if ($hasil['benar'])
        <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
            <p class="font-bold text-emerald-700">Tidak ada kesulitan yang ditemukan.</p>
            <p class="mt-1 text-slate-600">{{ $hasil['temuan'] }}</p>
        </div>
    @else
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="font-bold">Jumlah benda pada soal: {{ $hasil['jumlah_benda'] }}</p>

            @if ($hasil['pengali_dipakai'])
                <div class="mt-4 flex flex-wrap gap-3">
                    @for ($i = 1; $i <= $hasil['jumlah_benda']; $i++)
                        @if ($i <= $hasil['pengali_dipakai'])
                            <div class="rounded-2xl border-2 border-emerald-500 bg-emerald-50 px-5 py-4 text-center">
                                <p class="font-bold text-emerald-700">Benda {{ $i }}</p>
                                <p class="text-xs text-emerald-700">Dihitung</p>
                            </div>
                        @else
                            <div class="rounded-2xl border-2 border-dashed border-rose-400 bg-rose-50 px-5 py-4 text-center">
                                <p class="font-bold text-rose-700">Benda {{ $i }}</p>
                                <p class="text-xs text-rose-700">Terlewat</p>
                            </div>
                        @endif
                    @endfor
                </div>
                <p class="mt-3 text-sm text-slate-600">
                    Harga satuan dihitung {{ $hasil['pengali_dipakai'] }} kali, padahal ada {{ $hasil['jumlah_benda'] }} benda.
                </p>
            @endif
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="rounded-3xl bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-rose-700">Yang kamu kerjakan</p>
                <p class="mt-2 font-mono">{{ $hasil['rumus_siswa'] }}</p>
            </div>
            <div class="rounded-3xl bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">Yang seharusnya</p>
                <p class="mt-2 font-mono">{{ $hasil['jumlah_benda'] }} x Rp{{ number_format($hasil['harga_satuan'], 0, ',', '.') }} = Rp{{ number_format($hasil['kunci'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">Dugaan kesulitan</p>
                <p class="mt-2 font-semibold">{{ $hasil['dugaan'] }}</p>
            </div>
        </div>
    @endif

    <div class="mt-8 flex items-center justify-between">
        <a href="{{ route('asesmen.analisis', $attempt) }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-600">Kembali ke Analisis</a>
        <a href="{{ route('asesmen.verifikasi', $attempt) }}" class="rounded-2xl bg-emerald-600 px-8 py-3 font-bold text-white shadow hover:bg-emerald-700">Lanjut ke Verifikasi</a>
    </div>
</x-asesmen-layout>
