<x-asesmen-layout :step="2">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Analisis Bukti Respons ✨</h1>
        <p class="mt-1 text-slate-600">Mari kita lihat lebih dekat jawabanmu untuk memahami cara berpikirmu!</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Kolom kiri: jawaban siswa --}}
        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-800">Jawabanmu</span>
                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 font-mono text-lg">
                    <p>{{ $hasil['rumus_siswa'] }}</p>
                    <p class="mt-2 font-bold">Jawaban: Rp{{ number_format($hasil['jawaban'], 0, ',', '.') }}</p>
                </div>

                @if ($hasil['benar'])
                    <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="font-bold text-emerald-700">Jawaban sudah tepat!</p>
                        <p class="text-sm text-emerald-700">Hebat, kamu memahami langkahnya.</p>
                    </div>
                @else
                    <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 p-4">
                        <p class="font-bold text-rose-700">Jawaban belum tepat</p>
                        <p class="text-sm text-rose-700">Coba kita lihat langkah-langkah yang kamu gunakan.</p>
                    </div>
                @endif
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <p class="font-bold">Selisih dari jawaban tepat</p>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Galat absolut</dt>
                        <dd class="font-mono font-bold">Rp{{ number_format($hasil['galat_absolut'], 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Galat relatif</dt>
                        <dd class="font-mono font-bold">{{ number_format($hasil['galat_relatif'], 1, ',', '.') }}%</dd></div>
                </dl>
            </div>
        </div>

        {{-- Kolom kanan: hasil analisis --}}
        <div class="space-y-6">
            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                <p class="font-bold text-emerald-800">🔍 Apa yang kami temukan?</p>
                <p class="mt-2 text-slate-700">{{ $hasil['temuan'] }}</p>
                @if ($hasil['pengali_dipakai'])
                    <p class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                        Ada kesenjangan antara <b>jumlah benda ({{ $hasil['jumlah_benda'] }})</b>
                        dengan <b>pengali ({{ $hasil['pengali_dipakai'] }})</b>.
                    </p>
                @endif
            </div>

            @if ($hasil['penyebab'])
                <div class="rounded-3xl border border-sky-200 bg-white p-6 shadow-sm">
                    <p class="font-bold text-sky-900">⚙️ Kemungkinan Penyebab</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-slate-700">
                        @foreach ($hasil['penyebab'] as $p)<li>{{ $p }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-rose-200 bg-white p-6 shadow-sm">
                <p class="font-bold text-rose-800">🎯 Dugaan Kesulitan</p>
                <p class="mt-2 font-semibold text-rose-900">{{ $hasil['dugaan'] }}</p>
            </div>

            <a href="{{ route('asesmen.pemetaan', $attempt) }}" class="block rounded-2xl bg-emerald-600 px-6 py-3 text-center font-bold text-white shadow hover:bg-emerald-700">Lanjut ke Pemetaan</a>
        </div>
    </div>
</x-asesmen-layout>
