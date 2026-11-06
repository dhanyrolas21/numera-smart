<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\StudentAttempt;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function start(Request $request)
    {
        $user = $request->user();

        $attempt = StudentAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')->latest()->first()
            ?? StudentAttempt::create([
                'user_id' => $user->id,
                'question_id' => Question::where('dunia', 'operasi_hitung')->firstOrFail()->id,
            ]);

        return redirect()->route('asesmen.soal', $attempt);
    }

    public function soal(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');

        return view('asesmen.soal', compact('attempt'));
    }

    public function jawab(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);

        $data = $request->validate([
            'selected_option' => ['required', 'in:' . implode(',', array_keys($attempt->question->options))],
        ]);

        $attempt->log()->updateOrCreate([], ['selected_option' => $data['selected_option']]);
        $attempt->update(['current_step' => max($attempt->current_step, 2)]);

        return redirect()->route('asesmen.analisis', $attempt);
    }

    public function analisis(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');

        $hasil = app(\App\Services\DiagnosticService::class)
            ->analisis($attempt->question, $attempt->log->selected_option);

        $attempt->log->update([
            'student_formula' => $hasil['rumus_siswa'],
            'ai_detected_misconception' => json_encode($hasil, JSON_UNESCAPED_UNICODE),
        ]);

        return view('asesmen.analisis', compact('attempt', 'hasil'));
    }

    public function pemetaan(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        if (! $attempt->log) {
            return redirect()->route('asesmen.soal', $attempt);
        }

        $hasil = json_decode($attempt->log->ai_detected_misconception ?? '', true)
            ?: app(\App\Services\DiagnosticService::class)->analisis($attempt->question, $attempt->log->selected_option);

        $attempt->update(['current_step' => max($attempt->current_step, 3)]);

        return view('asesmen.pemetaan', compact('attempt', 'hasil'));
    }

    public function verifikasi(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        if (! $attempt->log) {
            return redirect()->route('asesmen.soal', $attempt);
        }

        $soal = app(\App\Services\DiagnosticService::class)->verifikasi($attempt->question);
        $tersimpan = json_decode($attempt->log->verification_answer ?? '', true)['jawaban'] ?? [];
        $attempt->update(['current_step' => max($attempt->current_step, 4)]);

        return view('asesmen.verifikasi', compact('attempt', 'soal', 'tersimpan'));
    }

    public function verifikasiSimpan(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        abort_unless($attempt->log, 404);

        $soal = app(\App\Services\DiagnosticService::class)->verifikasi($attempt->question);

        $rules = [];
        foreach (array_keys($soal) as $i) {
            $rules["jawaban.$i"] = ['required', 'in:A,B,C,D'];
        }
        $data = $request->validate($rules);

        $tepat = [];
        foreach ($soal as $i => $s) {
            $tepat[$i] = ($data['jawaban'][$i] ?? null) === $s['benar'];
        }

        $attempt->log->update([
            'verification_answer' => json_encode(['jawaban' => $data['jawaban'], 'tepat' => $tepat]),
        ]);
        $attempt->update(['current_step' => max($attempt->current_step, 5)]);

        return redirect()->route('asesmen.remediasi', $attempt);
    }

    public function remediasi(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);

        return view('asesmen.remediasi', compact('attempt'));
    }

    private function milikSiswa(Request $request, StudentAttempt $attempt): void
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
    }
}
