<x-asesmen-layout :step="6">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Uji Pemahaman</h1>
        <p class="mt-1 text-slate-600">Tunjukkan pemahamanmu setelah sesi belajar. Tetap rileks dan teliti!</p>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <span class="rounded-xl bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-800">
                Soal Penerapan {{ $indeks + 1 }} dari {{ $total }}
            </span>
            <div class="h-2 w-40 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-500" style="width: {{ round($indeks / $total * 100) }}%"></div>
            </div>
        </div>

        <p class="mt-6 rounded-2xl bg-emerald-50 p-5 text-xl font-semibold leading-relaxed">
            {!! nl2br(e($soal->context_text)) !!}
        </p>

        <form method="POST" action="{{ route('asesmen.uji.simpan', $attempt) }}" class="mt-6">
            @csrf
            <div class="space-y-3">
                @foreach ($soal->options as $kode => $nilai)
                    <label class="block cursor-pointer">
                        <input type="radio" name="selected_option" value="{{ $kode }}" class="peer sr-only" required>
                        <div class="flex items-center gap-4 rounded-2xl border-2 border-slate-200 bg-white p-4 transition peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500">
                            <span class="grid size-10 place-items-center rounded-xl bg-blue-100 font-bold text-blue-700">{{ $kode }}</span>
                            <span class="text-xl font-semibold">Rp {{ number_format($nilai, 0, ',', '.') }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('selected_option')
                <p class="mt-3 text-sm font-semibold text-red-600">Pilih salah satu jawaban dulu ya.</p>
            @enderror

            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm text-slate-500">Tidak ada batasan waktu. Utamakan ketepatan berpikirmu!</p>
                <button type="submit" class="rounded-2xl bg-emerald-600 px-8 py-3 font-bold text-white shadow hover:bg-emerald-700">
                    Kirim Jawaban Soal {{ $indeks + 1 }}
                </button>
            </div>
        </form>
    </div>
</x-asesmen-layout>