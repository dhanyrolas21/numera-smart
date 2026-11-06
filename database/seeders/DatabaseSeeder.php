<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(QuestionSeeder::class);

        User::factory()->create([
            'name' => 'Rahmat Hidayat', 'email' => 'siswa@example.com',
            'role' => 'siswa', 'kelas' => 'III-A',
        ]);
        User::factory()->create([
            'name' => 'Ibu Guru Rini', 'email' => 'guru@example.com',
            'role' => 'guru', 'kelas' => 'III-A',
        ]);
    }
}
