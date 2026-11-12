<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class UjiQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Format tiap soal: [teks, pilihan, huruf jawaban benar]
        $daftar = [
            4 => [
                ["Dina membeli 15 permen.\nHarga satu permen Rp500.\nBerapa total uang yang harus dibayar Dina?",
                    ['A' => 6500, 'B' => 7000, 'C' => 7500, 'D' => 8000], 'C'],
                ["Bu Lina membeli 8 botol sirup.\nHarga satu botol Rp15.000.\nBerapa total uang yang harus dibayar Bu Lina?",
                    ['A' => 105000, 'B' => 110000, 'C' => 135000, 'D' => 120000], 'D'],
                ["Ayah membeli 25 kg jeruk.\nHarga satu kg Rp18.000.\nBerapa total uang yang harus dibayar Ayah?",
                    ['A' => 450000, 'B' => 432000, 'C' => 468000, 'D' => 440000], 'A'],
            ],
            5 => [
                ["Pak Budi membeli 48 kotak donat.\nHarga satu kotak Rp6.500.\nBerapa total uang yang harus dibayar Pak Budi?",
                    ['A' => 299000, 'B' => 305500, 'C' => 312000, 'D' => 325000], 'C'],
                ["Panitia membeli 125 bendera.\nHarga satu bendera Rp3.200.\nBerapa total uang yang harus dibayar panitia?",
                    ['A' => 400000, 'B' => 396800, 'C' => 392000, 'D' => 403200], 'A'],
                ["Bu Rina membeli 64 meter kain.\nHarga satu meter Rp12.500.\nBerapa total uang yang harus dibayar Bu Rina?",
                    ['A' => 750000, 'B' => 787500, 'C' => 812500, 'D' => 800000], 'D'],
            ],
            6 => [
                ["Koperasi membeli 250 buku.\nHarga satu buku Rp8.400.\nBerapa total uang yang harus dibayar koperasi?",
                    ['A' => 2091600, 'B' => 2100000, 'C' => 2000000, 'D' => 2108400], 'B'],
                ["Sekolah membeli 135 kursi.\nHarga satu kursi Rp45.000.\nBerapa total uang yang harus dibayar sekolah?",
                    ['A' => 6030000, 'B' => 6120000, 'C' => 6075000, 'D' => 6000000], 'C'],
                ["Panitia membeli 320 snack.\nHarga satu snack Rp2.750.\nBerapa total uang yang harus dibayar panitia?",
                    ['A' => 880000, 'B' => 877250, 'C' => 850000, 'D' => 882750], 'A'],
            ],
        ];

        foreach ($daftar as $tingkat => $soalList) {
            foreach ($soalList as [$teks, $opsi, $kunci]) {
                Question::create([
                    'dunia' => 'operasi_hitung',
                    'tingkat' => $tingkat,
                    'jenis' => 'uji',
                    'context_text' => $teks,
                    'options' => $opsi,
                    'correct_option' => $kunci,
                    'correct_value' => $opsi[$kunci],
                ]);
            }
        }
    }
}