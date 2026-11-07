@props(['step' => 1])
@php $tingkat = request()->route('attempt')?->question?->tingkat; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NUMERA SMART</title>
    <style>[x-cloak]{display:none !important}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 text-slate-800 antialiased">
    <header class="border-b border-emerald-100 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="grid size-11 place-items-center rounded-2xl bg-emerald-600 text-xl text-white">📖</div>
                <div>
                    <p class="text-lg font-extrabold leading-none">NUMERA <span class="text-emerald-600">SMART</span></p>
                    <p class="mt-1 text-xs font-semibold text-slate-400">KELAS {{ $tingkat ?? '4-6' }} SD</p>
                </div>
            </div>

            @if ($step > 0)
                <x-stepper :current="$step" />
            @endif

            <div class="flex items-center gap-2">
                <div class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm">
                    <span class="font-bold">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-8">
        {{ $slot }}
    </main>
</body>
</html>