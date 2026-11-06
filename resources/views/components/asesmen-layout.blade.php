@props(['step' => 1])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NUMERA SMART</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 text-slate-800 antialiased">
    <header class="border-b border-emerald-100 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="grid size-11 place-items-center rounded-2xl bg-emerald-600 text-xl text-white">📖</div>
                <div>
                    <p class="text-lg font-extrabold leading-none">NUMERA <span class="text-emerald-600">SMART</span></p>
                    <p class="mt-1 text-xs font-semibold text-slate-400">KELAS {{ auth()->user()->kelas }} SD</p>
                </div>
            </div>
            <x-stepper :current="$step" />
            <div class="rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm">
                <span class="font-bold">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-8">
        {{ $slot }}
    </main>
</body>
</html>
