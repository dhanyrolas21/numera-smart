<x-asesmen-layout :step="6">
    <div class="rounded-3xl bg-white p-8 text-center shadow-sm">
        <p class="text-5xl">🎉</p>
        <h1 class="mt-4 text-2xl font-extrabold">Hebat, kamu sudah menyelesaikan semua soal!</h1>
        <p class="mt-2 text-slate-600">
            Jawabanmu sudah dikirim ke gurumu. Guru akan meninjau cara berpikirmu dan memberi catatan.
        </p>
        <a href="{{ route('asesmen.start') }}"
            class="mt-6 inline-block rounded-2xl bg-emerald-600 px-8 py-3 font-bold text-white shadow hover:bg-emerald-700">
            Kembali ke Pilih Kelas
        </a>
    </div>
</x-asesmen-layout>