@props(['current' => 1])
@php $steps = [1 => 'Soal', 2 => 'Analisis', 3 => 'Pemetaan', 4 => 'Verifikasi', 5 => 'Remediasi', 6 => 'Tinjauan']; @endphp

<ol class="flex items-center gap-2 overflow-x-auto" aria-label="Tahap asesmen">
  @foreach ($steps as $n => $label)
    @php $done = $n < $current; $active = $n === $current; @endphp
    <li class="flex items-center gap-2 shrink-0" @if($active) aria-current="step" @endif>
      <span @class([
        'grid size-8 place-items-center rounded-full text-sm font-bold',
        'bg-emerald-100 text-emerald-700' => $done,
        'bg-emerald-600 text-white shadow' => $active,
        'bg-slate-100 text-slate-400' => ! $done && ! $active,
      ])>{{ $done ? '✓' : $n }}</span>
      <span @class(['text-sm font-semibold', 'text-emerald-700' => $done || $active, 'text-slate-400' => ! $done && ! $active])>{{ $label }}</span>
      @unless ($loop->last)<span class="h-px w-6 bg-slate-200"></span>@endunless
    </li>
  @endforeach
</ol>
