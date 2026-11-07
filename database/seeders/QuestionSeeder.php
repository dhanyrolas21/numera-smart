<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Kelas 4: 12 x Rp5.000 = Rp60.000
        Question::create([
            'dunia' => 'operasi_hitung',
            'tingkat' => 4,
            'context_text' => "Pak Anto membeli 12 kotak pensil.\nHarga satu kotak Rp5.000.\nBerapa total uang yang harus dibayar Pak Anto?",
            'options' => ['A' => 50000, 'B' => 60000, 'C' => 55000, 'D' => 48000],
            'correct_option' => 'B',
            'correct_value' => 60000,
        ]);

        // Kelas 5: 36 x Rp2.500 = Rp90.000
        Question::create([
            'dunia' => 'operasi_hitung',
            'tingkat' => 5,
            'context_text' => "Bu Sari membeli 36 buku tulis.\nHarga satu buku Rp2.500.\nBerapa total uang yang harus dibayar Bu Sari?",
            'options' => ['A' => 72000, 'B' => 80000, 'C' => 90000, 'D' => 75000],
            'correct_option' => 'C',
            'correct_value' => 90000,
        ]);

        // Kelas 6: 45 x Rp12.500 = Rp562.500
        Question::create([
            'dunia' => 'operasi_hitung',
            'tingkat' => 6,
            'context_text' => "Sekolah membeli 45 buku cerita.\nHarga satu buku Rp12.500.\nBerapa total uang yang harus dibayar sekolah?",
            'options' => ['A' => 540000, 'B' => 550000, 'C' => 562500, 'D' => 575000],
            'correct_option' => 'C',
            'correct_value' => 562500,
        ]);
    }
}