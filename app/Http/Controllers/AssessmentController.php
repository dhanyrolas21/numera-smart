<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\StudentAttempt;
use App\Services\DiagnosticService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function pilihKelas()
    {
        return view('asesmen.pilih-kelas');
    }

    public function mulai(Request $request)
    {
        $data = $request->validate([
            'tingkat' => ['required', 'in:4,5,6'],
        ]);
        $tingkat = (int) $data['tingkat'];
        $user = $request->user();

        // Lanjutkan percobaan yang belum selesai di kelas yang sama, kalau ada.
        $attempt = StudentAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->whereHas('question', fn ($q) => $q->where('tingkat', $tingkat))
            ->latest()
            ->first();

        if (! $attempt) {
            $soal = Question::where('tingkat', $tingkat)
                ->where('jenis', 'diagnostik')
                ->where('dunia', 'operasi_hitung')
                ->inRandomOrder()
                ->firstOrFail();

            $attempt = StudentAttempt::create([
                'user_id' => $user->id,
                'question_id' => $soal->id,
            ]);
        }

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
        if (! $attempt->log) {
            return redirect()->route('asesmen.soal', $attempt);
        }

        $hasil = app(DiagnosticService::class)
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
            ?: app(DiagnosticService::class)->analisis($attempt->question, $attempt->log->selected_option);

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

        $soal = app(DiagnosticService::class)->verifikasi($attempt->question);
        $tersimpan = json_decode($attempt->log->verification_answer ?? '', true)['jawaban'] ?? [];
        $attempt->update(['current_step' => max($attempt->current_step, 4)]);

        return view('asesmen.verifikasi', compact('attempt', 'soal', 'tersimpan'));
    }

    public function verifikasiSimpan(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        abort_unless($attempt->log, 404);

        $soal = app(DiagnosticService::class)->verifikasi($attempt->question);

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
        $attempt->load('question', 'log');
        abort_unless($attempt->log, 404);

        $tepat = json_decode($attempt->log->verification_answer ?? '', true)['tepat'] ?? [];
        $fokus = [
            'operasi' => ($tepat[0] ?? true) === false,
            'angka' => ($tepat[1] ?? true) === false,
        ];
        $hasil = json_decode($attempt->log->ai_detected_misconception ?? '', true) ?: [];
        $attempt->update(['current_step' => max($attempt->current_step, 5)]);

        return view('asesmen.remediasi', compact('attempt', 'fokus', 'hasil'));
    }

    public function remediasiSelesai(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('log');
        abort_unless($attempt->log, 404);

        $attempt->log->update(['remediation_status' => 'selesai']);
        $attempt->update(['current_step' => max($attempt->current_step, 6)]);

        return redirect()->route('asesmen.uji', $attempt);
    }

    public function uji(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        abort_unless($attempt->log, 404);

        $soalUji = $this->soalUji($attempt);
        $jawaban = json_decode($attempt->log->uji_answers ?? '', true) ?: [];
        $indeks = count($jawaban);
        $total = $soalUji->count();

        // Semua soal uji sudah dijawab.
        if ($indeks >= $total) {
            return view('asesmen.uji-selesai', compact('attempt'));
        }

        $soal = $soalUji[$indeks];

        return view('asesmen.uji', compact('attempt', 'soal', 'indeks', 'total'));
    }

    public function ujiSimpan(Request $request, StudentAttempt $attempt)
    {
        $this->milikSiswa($request, $attempt);
        $attempt->load('question', 'log');
        abort_unless($attempt->log, 404);

        $soalUji = $this->soalUji($attempt);
        $jawaban = json_decode($attempt->log->uji_answers ?? '', true) ?: [];
        $indeks = count($jawaban);
        abort_if($indeks >= $soalUji->count(), 404);

        $soal = $soalUji[$indeks];
        $data = $request->validate([
            'selected_option' => ['required', 'in:' . implode(',', array_keys($soal->options))],
        ]);

        $jawaban[(string) $soal->id] = $data['selected_option'];
        $attempt->log->update(['uji_answers' => json_encode($jawaban)]);

        // Setelah soal terakhir dikirim, percobaan menunggu tinjauan guru.
        if (count($jawaban) >= $soalUji->count()) {
            $attempt->update(['status' => 'waiting_review', 'current_step' => 6]);
        }

        return redirect()->route('asesmen.uji', $attempt);
    }

    private function soalUji(StudentAttempt $attempt)
    {
        return Question::where('jenis', 'uji')
            ->where('tingkat', $attempt->question->tingkat)
            ->orderBy('id')
            ->get();
    }

    private function milikSiswa(Request $request, StudentAttempt $attempt): void
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
    }
}