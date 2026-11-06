<x-asesmen-layout :step="4">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Pertanyaan Verifikasi ✨</h1>
        <p class="mt-1 text-slate-600">Jawablah dengan pikiranmu sendiri. Tidak apa-apa jika belum yakin, kita belajar bersama!</p>
    </div>

    <form method="POST" action="{{ route('asesmen.verifikasi.simpan', $attempt) }}" class="space-y-6">
        @csrf

        @foreach ($soal as $i => $s)
            <fieldset class="rounded-3xl bg-white p-6 shadow-sm">
                <legend class="sr-only">Pertanyaan {{ $i + 1 }}</legend>
                <p class="text-sm font-semibold text-violet-700">Pertanyaan {{ $i + 1 }} dari {{ count($soal) }}</p>
                <p class="mt-2 text-lg font-bold">{{ $s['teks'] }}</p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach ($s['pilihan'] as $kode => [$judul, $ket])
                        <label class="block cursor-pointer">
                            <input type="radio" name="jawaban[{{ $i }}]" value="{{ $kode }}" class="peer sr-only"
                                @checked(old("jawaban.$i", $tersimpan[$i] ?? null) === $kode)>
                            <div class="flex items-center gap-3 rounded-2xl border-2 border-slate-200 p-4 transition peer-checked:border-violet-600 peer-checked:bg-violet-50 peer-focus-visible:ring-2 peer-focus-visible:ring-violet-500">
                                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-violet-100 font-bold text-violet-700">{{ $kode }}</span>
                                <span>
                                    <span class="block font-bold">{{ $judul }}</span>
                                    <span class="block text-xs text-slate-500">{{ $ket }}</span>
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach

        @if ($errors->any())
            <p class="text-sm font-semibold text-red-600">Pilih satu jawaban di setiap pertanyaan dulu ya.</p>
        @endif

        <div class="flex items-center justify-between">
            <a href="{{ route('asesmen.pemetaan', $attempt) }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-600">Kembali ke Pemetaan</a>
            <button type="submit" class="rounded-2xl bg-violet-600 px-8 py-3 font-bold text-white shadow hover:bg-violet-700">Selanjutnya</button>
        </div>
    </form>
</x-asesmen-layout>
