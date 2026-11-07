<x-asesmen-layout :step="0">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold">Pilih Kelasmu 🎒</h1>
        <p class="mt-1 text-slate-600">Soal yang kamu kerjakan akan disesuaikan dengan kelasmu.</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-3">
        @foreach ([4, 5, 6] as $tingkat)
            <form method="POST" action="{{ route('asesmen.mulai') }}">
                @csrf
                <input type="hidden" name="tingkat" value="{{ $tingkat }}">
                <button type="submit"
                    class="w-full rounded-3xl border-2 border-transparent bg-white p-8 text-center shadow-sm transition hover:border-emerald-500 hover:shadow-md focus-visible:ring-2 focus-visible:ring-emerald-500">
                    <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-emerald-100 text-3xl font-extrabold text-emerald-700">{{ $tingkat }}</span>
                    <span class="mt-4 block text-lg font-bold">Kelas {{ $tingkat }}</span>
                    <span class="mt-1 block text-sm text-slate-500">Operasi Hitung</span>
                </button>
            </form>
        @endforeach
    </div>

    @error('tingkat')
        <p class="mt-4 text-sm font-semibold text-red-600">Pilih salah satu kelas dulu ya.</p>
    @enderror
</x-asesmen-layout>