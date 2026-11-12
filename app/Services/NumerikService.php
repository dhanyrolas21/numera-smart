<?php

namespace App\Services;

class NumerikService
{
    /**
     * Galat absolut dan galat relatif (%) terhadap nilai sebenarnya (kunci).
     */
    public function galat(float $kunci, float $jawaban): array
    {
        $absolut = abs($kunci - $jawaban);
        $relatif = $kunci != 0.0 ? $absolut / abs($kunci) * 100 : 0.0;

        return ['absolut' => $absolut, 'relatif' => $relatif];
    }

    /**
     * Rata-rata. Dipakai untuk MAE (rata-rata galat absolut)
     * dan rata-rata galat relatif.
     */
    public function rataRata(array $nilai): float
    {
        return count($nilai) > 0 ? array_sum($nilai) / count($nilai) : 0.0;
    }

    /**
     * RMSE = akar dari rata-rata kuadrat galat.
     */
    public function rmse(array $nilai): float
    {
        if (count($nilai) === 0) {
            return 0.0;
        }

        $kuadrat = array_map(fn ($x) => $x * $x, $nilai);

        return sqrt(array_sum($kuadrat) / count($nilai));
    }

    /**
     * Persentase penurunan galat: (awal - akhir) / awal x 100.
     * Mengembalikan null kalau galat awal nol (tidak bisa dibandingkan).
     */
    public function penurunan(float $awal, float $akhir): ?float
    {
        return $awal > 0 ? ($awal - $akhir) / $awal * 100 : null;
    }
}