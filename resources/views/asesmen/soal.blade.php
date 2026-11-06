<x-asesmen-layout :step="1">
    <div class="rounded-3xl bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <span class="rounded-xl bg-orange-50 px-4 py-2 text-sm font-bold text-orange-800">Operasi Hitung</span>
            <span class="text-sm font-semibold text-slate-500">Soal 1 dari 5</span>
        </div>

        <p class="mt-6 rounded-2xl bg-emerald-50 p-5 text-xl font-semibold leading-relaxed">
            {!! nl2br(e($attempt->question->context_text)) !!}
        </p>

        <form method="POST" action="{{ route('asesmen.jawab', $attempt) }}" class="mt-6">
            @csrf
            <div class="space-y-3">
                @foreach ($attempt->question->options as $kode => $nilai)
                    <label class="block cursor-pointer">
                        <input type="radio" name="selected_option" value="{{ $kode }}" class="peer sr-only" required
                            @checked(old('selected_option', $attempt->log?->selected_option) === $kode)>
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

            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-2xl bg-emerald-600 px-8 py-3 font-bold text-white shadow hover:bg-emerald-700">
                    Selanjutnya
                </button>
            </div>
        </form>
    </div>
</x-asesmen-layout>
