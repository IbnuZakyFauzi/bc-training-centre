<?php

namespace App\Support;

/**
 * Konversi skala penilaian item evaluasi logbook OJT.
 *
 * Saat pengisian (Trainee create/edit & Trainer edit) item evaluasi disimpan
 * sebagai angka 1-4. Untuk tampilan View (Trainer / Admin TC) dan dokumen cetak
 * (PDF) angka tersebut dikonversi kembali menjadi K / BK:
 *
 *   1 (Belum)  -> BK
 *   2 (Cukup)  -> BK
 *   3 (Bisa)   -> K
 *   4 (Mahir)  -> K
 *
 * Data lama yang masih menyimpan string 'K' / 'BK' tetap didukung (backward
 * compatible) sehingga logbook lama tidak perlu dimigrasi.
 */
class CompetencyScale
{
    public const SCALE_BELUM = 1;
    public const SCALE_CUKUP = 2;
    public const SCALE_BISA = 3;
    public const SCALE_MAHIR = 4;

    public const STATUS_KOMPETEN = 'K';
    public const STATUS_BELUM_KOMPETEN = 'BK';

    /** Nilai minimum yang dianggap Kompeten (K) pada dokumen akhir. */
    public const THRESHOLD_KOMPETEN = self::SCALE_BISA;

    /**
     * Daftar pilihan skala beserta labelnya (dipakai form pengisian).
     *
     * @return array<int, string>
     */
    public static function options(): array
    {
        return [
            self::SCALE_BELUM => 'Belum',
            self::SCALE_CUKUP => 'Cukup',
            self::SCALE_BISA => 'Bisa',
            self::SCALE_MAHIR => 'Mahir',
        ];
    }

    /**
     * Daftar nilai skala yang valid.
     *
     * @return array<int, int>
     */
    public static function values(): array
    {
        return array_keys(self::options());
    }

    /**
     * Normalisasi nilai apa pun (int, string angka, legacy 'K'/'BK') menjadi
     * skala 1-4. Mengembalikan null jika item belum dinilai.
     */
    public static function toScale($value): ?int
    {
        if (is_array($value) || is_bool($value) || $value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return null;
            }

            $legacy = strtoupper($value);
            if ($legacy === self::STATUS_BELUM_KOMPETEN) {
                return self::SCALE_CUKUP;
            }
            if ($legacy === self::STATUS_KOMPETEN) {
                return self::SCALE_BISA;
            }
        }

        if (!is_numeric($value)) {
            return null;
        }

        $scale = (int) $value;

        return in_array($scale, self::values(), true) ? $scale : null;
    }

    /**
     * Konversi nilai skala menjadi status dokumen akhir: 'K', 'BK', atau null.
     */
    public static function toStatus($value): ?string
    {
        $scale = self::toScale($value);

        if ($scale === null) {
            return null;
        }

        return $scale >= self::THRESHOLD_KOMPETEN
            ? self::STATUS_KOMPETEN
            : self::STATUS_BELUM_KOMPETEN;
    }

    public static function isKompeten($value): bool
    {
        return self::toStatus($value) === self::STATUS_KOMPETEN;
    }

    public static function isBelumKompeten($value): bool
    {
        return self::toStatus($value) === self::STATUS_BELUM_KOMPETEN;
    }

    /**
     * Apakah item evaluasi sudah dinilai (punya nilai skala valid).
     */
    public static function isFilled($value): bool
    {
        return self::toScale($value) !== null;
    }

    /**
     * Label skala, contoh: 3 => "Bisa".
     */
    public static function label($value): ?string
    {
        $scale = self::toScale($value);

        return $scale === null ? null : (self::options()[$scale] ?? null);
    }

    /**
     * Normalisasi satu nilai status untuk disimpan.
     *
     * @param  bool  $preserveInvalid  true = nilai tak dikenal dibiarkan apa
     *         adanya agar ditolak validator (dipakai FormRequest), false =
     *         nilai tak dikenal dibersihkan menjadi null.
     */
    public static function normalizeValue($value, bool $preserveInvalid = false)
    {
        $scale = self::toScale($value);

        if ($scale !== null) {
            return $scale;
        }

        if (!$preserveInvalid || $value === null || (is_string($value) && trim($value) === '')) {
            return null;
        }

        return $value;
    }

    /**
     * Normalisasi seluruh status item evaluasi pada sop_payload menjadi
     * integer 1-4 (atau null bila belum dinilai), tanpa mengubah struktur
     * payload lainnya.
     *
     * @param  array<string, mixed>  $payload
     * @param  bool  $preserveInvalid  lihat normalizeValue()
     * @return array<string, mixed>
     */
    public static function normalizePayload(array $payload, bool $preserveInvalid = false): array
    {
        foreach ($payload as $familyKey => $family) {
            if ($familyKey === 'meta' || !is_array($family)) {
                continue;
            }

            foreach ($family['groups'] ?? [] as $groupIndex => $group) {
                if (!is_array($group)) {
                    continue;
                }

                foreach ($group['items'] ?? [] as $itemIndex => $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $payload[$familyKey]['groups'][$groupIndex]['items'][$itemIndex]['status']
                        = self::normalizeValue($item['status'] ?? null, $preserveInvalid);
                }
            }

            foreach (['compliance', 'behavior'] as $section) {
                foreach ($family[$section] ?? [] as $itemIndex => $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $payload[$familyKey][$section][$itemIndex]['status']
                        = self::normalizeValue($item['status'] ?? null, $preserveInvalid);
                }
            }
        }

        return $payload;
    }

    /**
     * Kumpulkan seluruh item evaluasi (groups + compliance + behavior) dari
     * satu checklist family.
     *
     * @param  array<string, mixed>  $checklist
     * @return array<int, array<string, mixed>>
     */
    public static function flattenChecklistItems(array $checklist): array
    {
        $items = [];

        foreach ($checklist['groups'] ?? [] as $group) {
            foreach ($group['items'] ?? [] as $item) {
                if (is_array($item)) {
                    $items[] = $item;
                }
            }
        }

        foreach (['compliance', 'behavior'] as $section) {
            foreach ($checklist[$section] ?? [] as $item) {
                if (is_array($item)) {
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    /**
     * Kesimpulan otomatis: jika ada minimal satu item bernilai 1 atau 2 (BK)
     * maka logbook dianggap BELUM KOMPETEN.
     *
     * @param  array<string, mixed>  $checklist
     */
    public static function hasBelumKompetenItem(array $checklist): bool
    {
        foreach (self::flattenChecklistItems($checklist) as $item) {
            if (self::isBelumKompeten($item['status'] ?? null)) {
                return true;
            }
        }

        return false;
    }
}
