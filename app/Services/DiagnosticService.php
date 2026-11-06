<?php

namespace App\Services;

use App\Models\Question;

class DiagnosticService
{
    public function analisis(Question $soal, string $opsi): array
    {
        // Soal cerita perkalian: ambil jumlah benda dan harga satuan dari teks soal.
        preg_match('/membeli\s+(\d+)/i', $soal->context_text, $m1);
        preg_match('/Rp\s?([\d.]+)/i', $soal->context_text, $m2);
        $jumlah = (int) ($m1[1] ?? 0);
        $harga = (int) str_replace('.', '', rtrim($m2[1] ?? '0', '.'));

        $jawaban = (int) $soal->options[$opsi];
        $kunci = (int) $soal->correct_value;

        // Metode numerik: galat absolut dan galat relatif terhadap nilai sebenarnya.
        $galatAbsolut = abs($kunci - $jawaban);
        $galatRelatif = $kunci > 0 ? round($galatAbsolut / $kunci * 100, 1) : 0;

        $hasil = [
            'benar' => $jawaban === $kunci,
            'jawaban' => $jawaban,
            'kunci' => $kunci,
            'jumlah_benda' => $jumlah,
            'harga_satuan' => $harga,
            'pengali_dipakai' => null,
            'galat_absolut' => $galatAbsolut,
            'galat_relatif' => $galatRelatif,
            'kode' => 'benar',
            'rumus_siswa' => $this->rp($jawaban),
            'temuan' => 'Jawabanmu sudah tepat. Kamu menghubungkan jumlah benda dengan harga satuannya dengan benar.',
            'penyebab' => [],
            'dugaan' => 'Tidak ditemukan kesulitan pada soal ini',
        ];

        if ($hasil['benar']) {
            return $hasil;
        }

        // Pola 1: jawaban = harga satuan x angka lain (bukan jumlah benda).
        if ($harga > 0 && $jawaban % $harga === 0 && intdiv($jawaban, $harga) !== $jumlah) {
            $pengali = intdiv($jawaban, $harga);
            $hasil['kode'] = 'salah_pengali';
            $hasil['pengali_dipakai'] = $pengali;
            $hasil['rumus_siswa'] = $this->rp($harga) . ' x ' . $pengali . ' = ' . $this->rp($jawaban);
            $hasil['temuan'] = "Kamu sudah menuliskan harga satuan dengan benar, tetapi angka pengali yang digunakan adalah {$pengali}, padahal jumlah benda pada soal adalah {$jumlah}.";
            $hasil['penyebab'] = [
                'Belum menghubungkan jumlah benda dengan model perkalian.',
                'Masih tertukar antara jumlah benda dengan angka yang digunakan.',
            ];
            $hasil['dugaan'] = 'Konsep perkalian sebagai penjumlahan berulang';
            return $hasil;
        }

        // Pola lain belum dikenali: diserahkan ke pertanyaan verifikasi (dan nanti Gemini).
        $hasil['kode'] = 'belum_dikenali';
        $hasil['rumus_siswa'] = 'Memilih ' . $this->rp($jawaban);
        $hasil['temuan'] = 'Jawabanmu belum tepat, dan pola kesalahannya belum bisa dikenali otomatis.';
        $hasil['penyebab'] = ['Perlu ditelusuri lewat pertanyaan verifikasi di tahap berikutnya.'];
        $hasil['dugaan'] = 'Perlu ditelusuri lebih lanjut';
        return $hasil;
    }

    public function verifikasi(Question $soal): array
    {
        preg_match('/membeli\s+(\d+)/i', $soal->context_text, $m1);
        preg_match('/Rp\s?([\d.]+)/i', $soal->context_text, $m2);
        $jumlah = (int) ($m1[1] ?? 0);
        $harga = (int) str_replace('.', '', rtrim($m2[1] ?? '0', '.'));

        return [
            [
                'teks' => "Operasi apa yang paling tepat digunakan untuk mencari total harga jika ada {$jumlah} benda dengan harga masing-masing yang sama?",
                'pilihan' => [
                    'A' => ['Penjumlahan', 'Menambahkan satu persatu'],
                    'B' => ['Pengurangan', 'Mengurangi jumlah nilai'],
                    'C' => ['Perkalian', 'Menghitung pengulangan nilai yang sama'],
                    'D' => ['Pembagian', 'Membagi menjadi bagian kecil'],
                ],
                'benar' => 'C',
            ],
            [
                'teks' => 'Pada soal tadi, angka mana yang menunjukkan berapa kali harga satu benda dihitung?',
                'pilihan' => [
                    'A' => [$this->rp($harga), 'Harga satu benda'],
                    'B' => [(string) $jumlah, 'Jumlah benda yang dibeli'],
                    'C' => [(string) max($jumlah - 1, 0), 'Jumlah benda dikurangi satu'],
                    'D' => [$this->rp($harga * $jumlah), 'Total harga'],
                ],
                'benar' => 'B',
            ],
        ];
    }

    private function rp(int $n): string
    {
        return 'Rp' . number_format($n, 0, ',', '.');
    }
}
