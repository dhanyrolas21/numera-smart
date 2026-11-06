<?php
namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Question::create([
            'dunia' => 'operasi_hitung',
            'context_text' => "Rani membeli 3 buku cerita di toko.\nHarga satu buku Rp4.000.\nBerapa total uang yang harus dibayar Rani?",
            'options' => ['A' => 7000, 'B' => 8000, 'C' => 10000, 'D' => 12000],
            'correct_option' => 'D',
            'correct_value' => 12000,
        ]);
    }
}
