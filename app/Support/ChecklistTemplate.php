<?php

namespace App\Support;

/**
 * Struktur checklist SOP evaluasi per family alat.
 *
 * Definisi ini identik dengan yang diisi Trainee pada form
 * (resources/views/ojt/logbooks/partials/create-form.blade.php) sehingga
 * halaman View Review Trainer dapat merender item evaluasi secara dinamis
 * dengan label / kode / tipe yang sama persis, terlepas dari apakah
 * payload tersimpan membawa label tersebut atau hanya menyimpan nilai
 * (status) dan feedback.
 */
class ChecklistTemplate
{
    public static function structure(string $family): ?array
    {
        return match ($family) {
            'track' => self::track(),
            'excavator' => self::excavator(),
            'dumptruck' => self::dumptruck(),
            'semidump' => self::semidump(),
            'wheelloader' => self::wheelloader(),
            default => null,
        };
    }

    public static function families(): array
    {
        return ['track', 'excavator', 'dumptruck', 'semidump', 'wheelloader'];
    }

    private static function track(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Dozing & Digging untuk Unit (DZ/GR) / Grading & Digging untuk Unit (GR)',
                    'subtitle' => 'Cara memosisisikan blade, menggali, dan mendorong material',
                    'items' => [
                        ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan Blade pada saat mendorong/ grading'],
                        ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Penggunaan Tilt Blade'],
                        ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk mendorong/ ditching'],
                        ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk menggali/sloping'],
                        ['code' => '1.5', 'kind' => 'Skl', 'label' => 'Penyesuaian beban dengan rpm/posisi transmissi'],
                        ['code' => '1.6', 'kind' => 'Skl', 'label' => 'Teknik dozing/grading/digging'],
                    ],
                ],
                [
                    'title' => 'Spreading & Leveling',
                    'subtitle' => 'Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja',
                    'items' => [
                        ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak'],
                        ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Cara leveling menggunakan tilt'],
                        ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll'],
                        ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja'],
                        ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Steering'],
                        ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan Articulated (Khusus untuk unit GR)'],
                        ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Teknik spreading/leveling'],
                    ],
                ],
                [
                    'title' => 'Ripping',
                    'subtitle' => 'Khusus untuk pekerjaan ripping dan pembukaan material keras',
                    'items' => [
                        ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan Ripper'],
                        ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik Penetrasi Ripping'],
                        ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material'],
                    ],
                ],
                [
                    'title' => 'Finishing',
                    'subtitle' => 'Finishing grading dan koreksi permukaan kerja',
                    'items' => [
                        ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Kesesuaian penggunaan speed'],
                        ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Hasil akhir pendorongan (hasil pekerjaan)'],
                    ],
                ],
            ],
            'compliance' => [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni Unit (Three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Skl', 'label' => 'Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)'],
                ['code' => '10', 'kind' => 'Knw', 'label' => 'Keselamatan selama operasi'],
            ],
            'behavior' => self::disciplineItems(),
        ];
    }

    private static function excavator(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Positioning',
                    'subtitle' => 'Cara memposisikan unit, track, dan upper structure di front loading',
                    'items' => [
                        ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan unit di front loading'],
                        ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Cara membuat landasan'],
                        ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara mengatur track dan upper structure'],
                    ],
                ],
                [
                    'title' => 'Loading & Dumping',
                    'subtitle' => 'Cara swing, memuat, dan dumping yang aman',
                    'items' => [
                        ['code' => '2.1', 'kind' => 'Skl', 'label' => 'Cara swing muatan'],
                        ['code' => '2.2', 'kind' => 'Skl', 'label' => 'Cara swing kosongan'],
                        ['code' => '2.3', 'kind' => 'Knw', 'label' => 'Kombinasi gerakan'],
                        ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Cara dumping dan kerapihan muatan'],
                        ['code' => '2.5', 'kind' => 'Knw', 'label' => 'Sudut swing'],
                        ['code' => '2.6', 'kind' => 'Knw', 'label' => 'Cycle time'],
                    ],
                ],
                [
                    'title' => 'Digging',
                    'subtitle' => 'Teknik digging dan pengaturan kerja bucket',
                    'items' => [
                        ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Teknik digging (urutan pengambilan)'],
                        ['code' => '3.2', 'kind' => 'Skl', 'label' => 'Sudut pengambilan (digging)'],
                        ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Gerakan kombinasi pada saat digging'],
                        ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Volume bucket'],
                    ],
                ],
                [
                    'title' => 'Sloping',
                    'subtitle' => 'Teknik pembuatan slope dan kerapihan permukaan',
                    'items' => [
                        ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Teknik pembuatan slope'],
                        ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Kerapihan slope'],
                    ],
                ],
            ],
            'compliance' => [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Knw', 'label' => 'Menaiki dan menuruni Unit (Three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Skl', 'label' => 'Keselamatan saat loading, unloading, positioning, traveling, dan digging'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)'],
                ['code' => '10', 'kind' => 'Knw', 'label' => 'Keselamatan selama operasi'],
            ],
            'behavior' => self::disciplineItems(),
        ];
    }

    private static function dumptruck(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Loading',
                    'subtitle' => 'Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi/brake saat loading',
                    'items' => [
                        ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk loading/ posisi antri/'],
                        ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Posisi terhadap Alat muat (rata, aman & keras)'],
                        ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Tranmission "N" & Penggunaan brake saat loading'],
                        ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/payload meter (Khusus untuk Unit HDT)'],
                        ['code' => '1.5', 'kind' => 'Knw', 'label' => 'Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)'],
                        ['code' => '1.6', 'kind' => 'Knw', 'label' => 'Perhatian terhadap standart muatan (Khusus untuk Unit LDT)'],
                    ],
                ],
                [
                    'title' => 'Hauling',
                    'subtitle' => 'Penggunaan speed/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak',
                    'items' => [
                        ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)'],
                        ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Clutch (Khusus untuk Unit LDT)'],
                        ['code' => '2.3', 'kind' => 'Knw', 'label' => 'Penggunaan Shift Limit & Power/ Eco Mode (Khusus untuk Unit HDT)'],
                        ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)'],
                        ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)'],
                        ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)'],
                        ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ di tikungan'],
                        ['code' => '2.8', 'kind' => 'Skl', 'label' => 'Ketrampilan/ kelembutan mengemudi'],
                        ['code' => '2.9', 'kind' => 'Knw', 'label' => 'Cycle time'],
                    ],
                ],
                [
                    'title' => 'Dumping',
                    'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
                    'items' => [
                        ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver'],
                        ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)'],
                        ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping'],
                        ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)'],
                        ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel'],
                        ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)'],
                        ['code' => '3.7', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan vesel di hopper/ stock pile (Khusus untuk Unit LDT)'],
                    ],
                ],
            ],
            'compliance' => [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni unit (Three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk dan steering wheel'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/ safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Atd', 'label' => 'Keselamatan saat loading/harus didalam kabin'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap Rambu lalu lintas'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Keselamatan saat dumping'],
                ['code' => '10', 'kind' => 'Atd', 'label' => 'Sopan santun mengemudi'],
                ['code' => '11', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)'],
            ],
            'behavior' => self::disciplineItems(),
        ];
    }

    private static function semidump(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Loading',
                    'subtitle' => 'Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi/brake saat loading',
                    'items' => [
                        ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Penempatan posisi untuk loading/ posisi antri'],
                        ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Posisi Trailer terhadap Alat muat (rata & aman)'],
                        ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Transmission "N" & Penggunaan Parking Brake saat loading'],
                        ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/ vessel penuh'],
                    ],
                ],
                [
                    'title' => 'Hauling',
                    'subtitle' => 'Penggunaan speed/transmisi, power/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak',
                    'items' => [
                        ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)'],
                        ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Power/ Offroad Mode'],
                        ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)'],
                        ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penggunaan Trailer Brake'],
                        ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ditikungan'],
                        ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Ketrampilan/kelembutan mengemudi'],
                        ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Cycle time'],
                    ],
                ],
                [
                    'title' => 'Dumping',
                    'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
                    'items' => [
                        ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver'],
                        ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)'],
                        ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping'],
                        ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)'],
                        ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel'],
                        ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di Hopper/Stock Pile'],
                    ],
                ],
            ],
            'compliance' => [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni unit (three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk dan steering wheel'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/ safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Atd', 'label' => 'Keselamatan saat loading/ harus didalam kabin'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap rambu lalu lintas'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Keselamatan saat dumping'],
                ['code' => '10', 'kind' => 'Atd', 'label' => 'Sopan santun mengemudi'],
                ['code' => '11', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)'],
            ],
            'behavior' => self::disciplineItems(),
        ];
    }

    private static function wheelloader(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Traveling',
                    'subtitle' => 'Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur',
                    'items' => [
                        ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Memposisikan attachment dengan benar'],
                        ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Penyesuaian speed dengan kondisi medan'],
                        ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara manuver & membelok di tikungan'],
                    ],
                ],
                [
                    'title' => 'Scoping & Loading',
                    'subtitle' => 'Teknik scopping, loading, dan load & carry ke hauling truck',
                    'items' => [
                        ['code' => '2.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan bucket pada saat scopping'],
                        ['code' => '2.2', 'kind' => 'Skl', 'label' => 'Cara manuver/ pengoperasian load & carry'],
                        ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara loading ke hauling truck'],
                        ['code' => '2.4', 'kind' => 'Knw', 'label' => 'Cycle Time'],
                    ],
                ],
                [
                    'title' => 'Digging',
                    'subtitle' => 'Teknik digging: posisi unit, penetrasi bucket, dan kapasitas',
                    'items' => [
                        ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Position unit'],
                        ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik penetrasi bucket'],
                        ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi lift arm'],
                        ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Kapasitas bucket'],
                    ],
                ],
                [
                    'title' => 'Leveling',
                    'subtitle' => 'Teknik leveling: speed, tilt, steering, dan penempatan material',
                    'items' => [
                        ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Penggunaan speed/transmisi saat bergerak'],
                        ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Cara leveling menggunakan tilt'],
                        ['code' => '4.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll'],
                        ['code' => '4.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja'],
                        ['code' => '4.5', 'kind' => 'Skl', 'label' => 'Penggunaan steering'],
                    ],
                ],
            ],
            'compliance' => [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni unit (three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/ safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Skl', 'label' => 'Keselamatan saat travelling, scopping, loading, digging, dan leveling'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)'],
                ['code' => '10', 'kind' => 'Knw', 'label' => 'Keselamatan selama operasi'],
            ],
            'behavior' => self::disciplineItems(),
        ];
    }

    private static function disciplineItems(): array
    {
        return [
            ['code' => '1', 'kind' => 'Atd', 'label' => 'Mempedulikan pemakaian fuel/ bahan bakar'],
            ['code' => '2', 'kind' => 'Atd', 'label' => 'Mempedulikan pemakaian tyre/ undercarriage'],
            ['code' => '3', 'kind' => 'Atd', 'label' => 'Mempedulikan akan ketidaknormalan unit'],
            ['code' => '4', 'kind' => 'Atd', 'label' => 'Mempedulikan untuk bekerja dengan efektif dan efisien'],
            ['code' => '5', 'kind' => 'Atd', 'label' => 'Mempedulikan untuk meniadakan pemborosan dimanapun'],
            ['code' => '6', 'kind' => 'Atd', 'label' => 'Melaksanakan aktivitas sesuai instruksi'],
            ['code' => '7', 'kind' => 'Atd', 'label' => 'Berusaha untuk melakukan yang terbaik'],
            ['code' => '8', 'kind' => 'Atd', 'label' => 'Selalu siap menerima tugas yang diberikan'],
            ['code' => '9', 'kind' => 'Atd', 'label' => 'Berani mengingatkan jika ada yang berbuat kesalahan'],
            ['code' => '10', 'kind' => 'Atd', 'label' => 'Disiplin waktu saat pelaksanaan pelatihan'],
            ['code' => '11', 'kind' => 'Atd', 'label' => 'Mematuhi semua aturan yang berlaku'],
            ['code' => '12', 'kind' => 'Atd', 'label' => 'Tidak pernah mangkir'],
            ['code' => '13', 'kind' => 'Atd', 'label' => 'Melaksanakan tugas kelompok bersama-sama'],
            ['code' => '14', 'kind' => 'Atd', 'label' => 'Berinisiatif untuk membantu'],
            ['code' => '15', 'kind' => 'Atd', 'label' => 'Selalu antusias jika diberi tugas'],
            ['code' => '16', 'kind' => 'Atd', 'label' => 'Melaporkan setiap kejadian diluar wewenangnya'],
            ['code' => '17', 'kind' => 'Atd', 'label' => 'Tidak ragu-ragu jika diberi instruksi'],
            ['code' => '18', 'kind' => 'Atd', 'label' => 'Mengoperasikan unit dengan penuh keyakinan'],
            ['code' => '19', 'kind' => 'Atd', 'label' => 'Bersikap proaktif di setiap kegiatan'],
            ['code' => '20', 'kind' => 'Atd', 'label' => 'Tidak malu untuk bertanya jika ada kesulitan'],
        ];
    }
}
