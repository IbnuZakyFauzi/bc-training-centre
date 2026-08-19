<?php
    $isEditing = isset($logbook);
    $isTrainerEditing = $isTrainerEditing ?? false;
    $formPayload = old('sop_payload', $isEditing ? ($logbook->sop_payload ?? []) : []);
    $categoryMap = $categories->pluck('code', 'id')->all();
    $equipmentMap = $equipments->groupBy('equipment_category_id')
        ->map(fn ($group) => $group->map(fn ($eq) => [
            'id' => $eq->id,
            'label' => $eq->unit_code . ' - ' . $eq->model_name,
        ])->values())
        ->toArray();
    $trainee = $isEditing ? $logbook->trainee : $user;
    $selectedCategoryId = old('equipment_category_id', $isEditing ? $logbook->equipment_category_id : ($trainee->equipment_category_id ?? ''));
    $selectedCategoryCode = $selectedCategoryId ? ($categoryMap[$selectedCategoryId] ?? '') : '';

    $trackGroups = [
        [
            'title' => 'Dozing & Digging untuk Unit (DZ) / Grading & Digging untuk Unit (GR)',
            'subtitle' => 'Cara memosisisikan blade, menggali, dan mendorong material',
            'items' => [
                ['code' => '1.1', 'label' => 'Cara memposisikan Blade pada saat mendorong/ grading', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Penggunaan Tilt Blade', 'kind' => 'Knw'],
                ['code' => '1.3', 'label' => 'Cara Pengoperasian blade untuk mendorong/ ditching', 'kind' => 'Skl'],
                ['code' => '1.4', 'label' => 'Cara Pengoperasian blade untuk menggali/sloping', 'kind' => 'Skl'],
                ['code' => '1.5', 'label' => 'Penyesuaian beban dengan rpm/posisi transmissi', 'kind' => 'Skl'],
                ['code' => '1.6', 'label' => 'Teknik dozing/grading/digging', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Spreading & Leveling',
            'subtitle' => 'Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja',
            'items' => [
                ['code' => '2.1', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak', 'kind' => 'Knw'],
                ['code' => '2.2', 'label' => 'Cara leveling menggunakan tilt', 'kind' => 'Knw'],
                ['code' => '2.3', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll', 'kind' => 'Skl'],
                ['code' => '2.4', 'label' => 'Filling pada saat melevelkan area kerja', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Penggunaan Steering', 'kind' => 'Skl'],
                ['code' => '2.6', 'label' => 'Penggunaan Articulated (Khusus untuk unit GR)', 'kind' => 'Skl'],
                ['code' => '2.7', 'label' => 'Teknik spreading/levelling', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Ripping',
            'subtitle' => 'Khusus untuk pekerjaan ripping dan pembukaan material keras',
            'items' => [
                ['code' => '3.1', 'label' => 'Cara memposisikan Ripper', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Teknik Penetrasi Ripping', 'kind' => 'Knw'],
                ['code' => '3.3', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Finishing',
            'subtitle' => 'Finishing grading dan koreksi permukaan kerja',
            'items' => [
                ['code' => '4.1', 'label' => 'Kesesuaian penggunaan speed', 'kind' => 'Skl'],
                ['code' => '4.2', 'label' => 'Hasil akhir pendorongan (hasil pekerjaan)', 'kind' => 'Skl'],
            ],
        ],
    ];

    $disciplineItems = [
        ['code' => '1', 'label' => 'Mempedulikan pemakaian fuel/ bahan bakar', 'kind' => 'Atd'],
        ['code' => '2', 'label' => 'Mempedulikan pemakaian tyre/ undercarriage', 'kind' => 'Atd'],
        ['code' => '3', 'label' => 'Mempedulikan akan ketidaknormalan unit', 'kind' => 'Atd'],
        ['code' => '4', 'label' => 'Mempedulikan untuk bekerja dengan efektif dan efisien', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Mempedulikan untuk meniadakan pemborosan dimanapun', 'kind' => 'Atd'],
        ['code' => '6', 'label' => 'Melaksanakan aktivitas sesuai instruksi', 'kind' => 'Atd'],
        ['code' => '7', 'label' => 'Berusaha untuk melakukan yang terbaik', 'kind' => 'Atd'],
        ['code' => '8', 'label' => 'Selalu siap menerima tugas yang diberikan', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Berani mengingatkan jika ada yang berbuat kesalahan', 'kind' => 'Atd'],
        ['code' => '10', 'label' => 'Disiplin waktu saat pelaksanaan pelatihan', 'kind' => 'Atd'],
        ['code' => '11', 'label' => 'Mematuhi semua aturan yang berlaku', 'kind' => 'Atd'],
        ['code' => '12', 'label' => 'Tidak pernah mangkir', 'kind' => 'Atd'],
        ['code' => '13', 'label' => 'Melaksanakan tugas kelompok bersama-sama', 'kind' => 'Atd'],
        ['code' => '14', 'label' => 'Berinisiatif untuk membantu', 'kind' => 'Atd'],
        ['code' => '15', 'label' => 'Selalu antusias jika diberi tugas', 'kind' => 'Atd'],
        ['code' => '16', 'label' => 'Melaporkan setiap kejadian diluar wewenangnya', 'kind' => 'Atd'],
        ['code' => '17', 'label' => 'Tidak ragu-ragu jika diberi instruksi', 'kind' => 'Atd'],
        ['code' => '18', 'label' => 'Mengoperasikan unit dengan penuh keyakinan', 'kind' => 'Atd'],
        ['code' => '19', 'label' => 'Bersikap proaktif di setiap kegiatan', 'kind' => 'Atd'],
        ['code' => '20', 'label' => 'Tidak malu untuk bertanya jika ada kesulitan', 'kind' => 'Atd'],
    ];

    $excavatorGroups = [
        [
            'title' => 'Positioning',
            'subtitle' => 'Cara memposisikan unit, track, dan upper structure di front loading',
            'items' => [
                ['code' => '1.1', 'label' => 'Cara memposisikan unit di front loading', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Cara membuat landasan', 'kind' => 'Skl'],
                ['code' => '1.3', 'label' => 'Cara mengatur track dan upper structure', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Loading & Dumping',
            'subtitle' => 'Cara swing, memuat, dan dumping yang aman',
            'items' => [
                ['code' => '2.1', 'label' => 'Cara swing muatan', 'kind' => 'Skl'],
                ['code' => '2.2', 'label' => 'Cara swing kosongan', 'kind' => 'Skl'],
                ['code' => '2.3', 'label' => 'Kombinasi gerakan', 'kind' => 'Knw'],
                ['code' => '2.4', 'label' => 'Cara dumping dan kerapihan muatan', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Sudut swing', 'kind' => 'Knw'],
                ['code' => '2.6', 'label' => 'Cycle time', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Digging',
            'subtitle' => 'Teknik digging dan pengaturan kerja bucket',
            'items' => [
                ['code' => '3.1', 'label' => 'Teknik digging (urutan pengambilan)', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Sudut pengambilan (digging)', 'kind' => 'Skl'],
                ['code' => '3.3', 'label' => 'Gerakan kombinasi pada saat digging', 'kind' => 'Skl'],
                ['code' => '3.4', 'label' => 'Volume bucket', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Sloping',
            'subtitle' => 'Teknik pembuatan slope dan kerapihan permukaan',
            'items' => [
                ['code' => '4.1', 'label' => 'Teknik pembuatan slope', 'kind' => 'Skl'],
                ['code' => '4.2', 'label' => 'Kerapihan slope', 'kind' => 'Skl'],
            ],
        ],
    ];

    $complianceItems = [
        ['code' => '1', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD', 'kind' => 'Knw'],
        ['code' => '2', 'label' => 'Menaiki dan menuruni Unit (Three point contact)', 'kind' => 'Skl'],
        ['code' => '3', 'label' => 'Penyetelan tempat duduk', 'kind' => 'Skl'],
        ['code' => '4', 'label' => 'Penggunaan sabuk pengaman/safety belt', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Penggunaan klakson dan lampu-lampu', 'kind' => 'Skl'],
        ['code' => '6', 'label' => 'Keselamatan saat loading, unloading, positioning, traveling, dan digging', 'kind' => 'Skl'],
        ['code' => '7', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan', 'kind' => 'Skl'],
        ['code' => '8', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)', 'kind' => 'Skl'],
        ['code' => '10', 'label' => 'Keselamatan selama operasi', 'kind' => 'Knw'],
    ];

    $trackComplianceItems = [
        ['code' => '1', 'label' => 'Kesehatan fisik dan perlengkapan/penggunaan APD', 'kind' => 'Knw'],
        ['code' => '2', 'label' => 'Menaiki dan menuruni Unit (Three point contact)', 'kind' => 'Skl'],
        ['code' => '3', 'label' => 'Penyetelan tempat duduk', 'kind' => 'Skl'],
        ['code' => '4', 'label' => 'Penggunaan sabuk pengaman/ safety belt', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Penggunaan klakson dan lampu-lampu', 'kind' => 'Skl'],
        ['code' => '6', 'label' => 'Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling', 'kind' => 'Skl'],
        ['code' => '7', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan', 'kind' => 'Skl'],
        ['code' => '8', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)', 'kind' => 'Skl'],
        ['code' => '10', 'label' => 'Keselamatan selama operasi', 'kind' => 'Knw'],
    ];

    $dumptruckGroups = [
        [
            'title' => 'Loading',
            'subtitle' => 'Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi/brake saat loading',
            'items' => [
                ['code' => '1.1', 'label' => 'Pengambilan haluan untuk loading/ posisi antri/', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Posisi terhadap Alat muat (rata, aman & keras)', 'kind' => 'Knw'],
                ['code' => '1.3', 'label' => 'Tranmission "N" & Penggunaan brake saat loading', 'kind' => 'Skl'],
                ['code' => '1.4', 'label' => 'Perhatian saat loading terhadap beban/payload meter (Khusus untuk Unit HDT)', 'kind' => 'Knw'],
                ['code' => '1.5', 'label' => 'Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)', 'kind' => 'Knw'],
                ['code' => '1.6', 'label' => 'Perhatian terhadap standart muatan (Khusus untuk Unit LDT)', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Hauling',
            'subtitle' => 'Penggunaan speed/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak',
            'items' => [
                ['code' => '2.1', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)', 'kind' => 'Knw'],
                ['code' => '2.2', 'label' => 'Penggunaan Clutch (Khusus untuk Unit LDT)', 'kind' => 'Knw'],
                ['code' => '2.3', 'label' => 'Penggunaan Shift Limit & Power/ Eco Mode (Khusus untuk Unit HDT)', 'kind' => 'Knw'],
                ['code' => '2.4', 'label' => 'Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)', 'kind' => 'Skl'],
                ['code' => '2.6', 'label' => 'Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)', 'kind' => 'Skl'],
                ['code' => '2.7', 'label' => 'Pengembalian haluan saat membelok/ di tikungan', 'kind' => 'Skl'],
                ['code' => '2.8', 'label' => 'Ketrampilan/ kelembutan mengemudi', 'kind' => 'Skl'],
                ['code' => '2.9', 'label' => 'Cycle time', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Dumping',
            'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
            'items' => [
                ['code' => '3.1', 'label' => 'Pengambilan haluan untuk dumping/manuver', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Posisi dumping (lokasi harus rata)', 'kind' => 'Knw'],
                ['code' => '3.3', 'label' => 'Penggunaan brake saat Dumping', 'kind' => 'Skl'],
                ['code' => '3.4', 'label' => 'Prosedur Dumping (Penggunaan RPM)', 'kind' => 'Knw'],
                ['code' => '3.5', 'label' => 'Prosedur menurunkan Vessel', 'kind' => 'Knw'],
                ['code' => '3.6', 'label' => 'Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)', 'kind' => 'Knw'],
                ['code' => '3.7', 'label' => 'Prosedur menurunkan vesel di hopper/ stock pile (Khusus untuk Unit LDT)', 'kind' => 'Knw'],
            ],
        ],
    ];

    $dumptruckComplianceItems = [
        ['code' => '1', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD', 'kind' => 'Knw'],
        ['code' => '2', 'label' => 'Menaiki dan menuruni unit (Three point contact)', 'kind' => 'Skl'],
        ['code' => '3', 'label' => 'Penyetelan tempat duduk dan steering wheel', 'kind' => 'Skl'],
        ['code' => '4', 'label' => 'Penggunaan sabuk pengaman/safety belt', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Penggunaan klakson dan lampu-lampu', 'kind' => 'Skl'],
        ['code' => '6', 'label' => 'Keselamatan saat loading/harus didalam kabin', 'kind' => 'Atd'],
        ['code' => '7', 'label' => 'Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)', 'kind' => 'Skl'],
        ['code' => '8', 'label' => 'Kepedulian terhadap Rambu lalu lintas', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Keselamatan saat dumping', 'kind' => 'Skl'],
        ['code' => '10', 'label' => 'Sopan santun mengemudi', 'kind' => 'Atd'],
        ['code' => '11', 'label' => 'Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)', 'kind' => 'Skl'],
    ];

    $semidumpGroups = [
        [
            'title' => 'Loading',
            'subtitle' => 'Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi/brake saat loading',
            'items' => [
                ['code' => '1.1', 'label' => 'Penempatan posisi untuk loading/ posisi antri', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Posisi Trailer terhadap Alat muat (rata & aman)', 'kind' => 'Knw'],
                ['code' => '1.3', 'label' => 'Transmission "N" & Penggunaan Parking Brake saat loading', 'kind' => 'Skl'],
                ['code' => '1.4', 'label' => 'Perhatian saat loading terhadap beban/ vessel penuh', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Hauling',
            'subtitle' => 'Penggunaan speed/transmisi, power/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak',
            'items' => [
                ['code' => '2.1', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)', 'kind' => 'Knw'],
                ['code' => '2.2', 'label' => 'Penggunaan Power/ Offroad Mode', 'kind' => 'Knw'],
                ['code' => '2.3', 'label' => 'Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)', 'kind' => 'Skl'],
                ['code' => '2.4', 'label' => 'Penggunaan Trailer Brake', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Pengembalian haluan saat membelok/ditikungan', 'kind' => 'Skl'],
                ['code' => '2.6', 'label' => 'Ketrampilan/kelembutan mengemudi', 'kind' => 'Skl'],
                ['code' => '2.7', 'label' => 'Cycle time', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Dumping',
            'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
            'items' => [
                ['code' => '3.1', 'label' => 'Pengambilan haluan untuk dumping/ manuver', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Posisi dumping (lokasi harus rata)', 'kind' => 'Knw'],
                ['code' => '3.3', 'label' => 'Penggunaan brake saat Dumping', 'kind' => 'Skl'],
                ['code' => '3.4', 'label' => 'Prosedur Dumping (Penggunaan RPM)', 'kind' => 'Knw'],
                ['code' => '3.5', 'label' => 'Prosedur menurunkan Vessel', 'kind' => 'Knw'],
                ['code' => '3.6', 'label' => 'Penempatan material yang tepat di Hopper/Stock Pile', 'kind' => 'Knw'],
            ],
        ],
    ];

    $semidumpComplianceItems = [
        ['code' => '1', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD', 'kind' => 'Knw'],
        ['code' => '2', 'label' => 'Menaiki dan menuruni unit (three point contact)', 'kind' => 'Skl'],
        ['code' => '3', 'label' => 'Penyetelan tempat duduk dan steering wheel', 'kind' => 'Skl'],
        ['code' => '4', 'label' => 'Penggunaan sabuk pengaman/ safety belt', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Penggunaan klakson dan lampu-lampu', 'kind' => 'Skl'],
        ['code' => '6', 'label' => 'Keselamatan saat loading/ harus didalam kabin', 'kind' => 'Atd'],
        ['code' => '7', 'label' => 'Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)', 'kind' => 'Skl'],
        ['code' => '8', 'label' => 'Kepedulian terhadap rambu lalu lintas', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Keselamatan saat dumping', 'kind' => 'Skl'],
        ['code' => '10', 'label' => 'Sopan santun mengemudi', 'kind' => 'Atd'],
        ['code' => '11', 'label' => 'Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)', 'kind' => 'Skl'],
    ];

    $wlGroups = [
        [
            'title' => 'Traveling',
            'subtitle' => 'Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur',
            'items' => [
                ['code' => '1.1', 'label' => 'Memposisikan attachment dengan benar', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Penyesuaian speed dengan kondisi medan', 'kind' => 'Skl'],
                ['code' => '1.3', 'label' => 'Cara manuver & membelok di tikungan', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Scoping & Loading',
            'subtitle' => 'Teknik scopping, loading, dan load & carry ke hauling truck',
            'items' => [
                ['code' => '2.1', 'label' => 'Cara memposisikan bucket pada saat scopping', 'kind' => 'Skl'],
                ['code' => '2.2', 'label' => 'Cara manuver/ pengoperasian load & carry', 'kind' => 'Skl'],
                ['code' => '2.3', 'label' => 'Cara loading ke hauling truck', 'kind' => 'Skl'],
                ['code' => '2.4', 'label' => 'Cycle Time', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Digging',
            'subtitle' => 'Teknik digging: posisi unit, penetrasi bucket, dan kapasitas',
            'items' => [
                ['code' => '3.1', 'label' => 'Position unit', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Teknik penetrasi bucket', 'kind' => 'Knw'],
                ['code' => '3.3', 'label' => 'Penyesuaian posisi lift arm', 'kind' => 'Skl'],
                ['code' => '3.4', 'label' => 'Kapasitas bucket', 'kind' => 'Knw'],
            ],
        ],
        [
            'title' => 'Leveling',
            'subtitle' => 'Teknik leveling: speed, tilt, steering, dan penempatan material',
            'items' => [
                ['code' => '4.1', 'label' => 'Penggunaan speed/transmisi saat bergerak', 'kind' => 'Skl'],
                ['code' => '4.2', 'label' => 'Cara leveling menggunakan tilt', 'kind' => 'Skl'],
                ['code' => '4.3', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll', 'kind' => 'Skl'],
                ['code' => '4.4', 'label' => 'Filling pada saat melevelkan area kerja', 'kind' => 'Skl'],
                ['code' => '4.5', 'label' => 'Penggunaan steering', 'kind' => 'Skl'],
            ],
        ],
    ];

    $wlComplianceItems = [
        ['code' => '1', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD', 'kind' => 'Knw'],
        ['code' => '2', 'label' => 'Menaiki dan menuruni unit (three point contact)', 'kind' => 'Skl'],
        ['code' => '3', 'label' => 'Penyetelan tempat duduk', 'kind' => 'Skl'],
        ['code' => '4', 'label' => 'Penggunaan sabuk pengaman/ safety belt', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Penggunaan klakson dan lampu-lampu', 'kind' => 'Skl'],
        ['code' => '6', 'label' => 'Keselamatan saat travelling, scopping, loading, digging, dan leveling', 'kind' => 'Skl'],
        ['code' => '7', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan', 'kind' => 'Skl'],
        ['code' => '8', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)', 'kind' => 'Skl'],
        ['code' => '10', 'label' => 'Keselamatan selama operasi', 'kind' => 'Knw'],
    ];
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
            <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="hover:underline">My Logbook</a>
            <span>/</span>
            <span class="text-slate-500"><?php echo e($isTrainerEditing ? 'Edit Logbook Trainer' : ($isEditing ? 'Edit Draft Logbook' : 'Create Digital Logbook')); ?></span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Logbook Harian Trainee OJT</h1>
        <p class="text-xs text-slate-500 mt-1">Form ini menampilkan checklist harian per unit: track unit (DZ/GR), excavator (EXC), dump truck (HDT/LDT), dan semi dump (SDT/ADT).</p>
    </div>
    <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Kembali</a>
    </div>

    <script>
        window.existingSopPayload = <?php echo \Illuminate\Support\Js::from($formPayload)->toHtml() ?>;
        window.logbookFormData = () => ({
            categoryId: <?php echo \Illuminate\Support\Js::from(old('equipment_category_id', $selectedCategoryId))->toHtml() ?>,
            equipmentId: <?php echo \Illuminate\Support\Js::from(old('equipment_id', $isEditing ? $logbook->equipment_id : ''))->toHtml() ?>,
            categoryMap: <?php echo \Illuminate\Support\Js::from($categoryMap)->toHtml() ?>,
            equipmentMap: <?php echo \Illuminate\Support\Js::from($equipmentMap)->toHtml() ?>,
            company: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.company', data_get($formPayload, 'meta.company', $trainee->company ?? '')))->toHtml() ?>,
            certification: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.certification', data_get($formPayload, 'meta.certification', $trainee->certification ?? 'Green')))->toHtml() ?>,
            stickerExpiredAt: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.sticker_expired_at', data_get($formPayload, 'meta.sticker_expired_at', $trainee->sticker_expired_at?->format('Y-m-d') ?? '')))->toHtml() ?>,
            assessmentMode: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.assessment_mode', data_get($formPayload, 'meta.assessment_mode', '')))->toHtml() ?>,
            assessmentStage: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.assessment_stage', data_get($formPayload, 'meta.assessment_stage', '')))->toHtml() ?>,
            assessmentStageDetail: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')))->toHtml() ?>,
            hmStart: <?php echo \Illuminate\Support\Js::from(old('hm_start', $isEditing ? $logbook->hm_start : ''))->toHtml() ?>,
            hmEnd: <?php echo \Illuminate\Support\Js::from(old('hm_end', $isEditing ? $logbook->hm_end : ''))->toHtml() ?>,
            unitType: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.unit_type', data_get($formPayload, 'meta.unit_type', '')))->toHtml() ?>,
            location: <?php echo \Illuminate\Support\Js::from(old('location', $isEditing ? $logbook->location : ''))->toHtml() ?>,
            dailyActivity: <?php echo \Illuminate\Support\Js::from(old('daily_activity', $isEditing ? $logbook->daily_activity : ''))->toHtml() ?>,
            trainerRatings: {},
            selectedTrainerId: '',
            selectedPengawasIds: [],
            selectedOperatorIds: [],
            get selectedCategoryCode() {
                return this.categoryMap[this.categoryId] || '';
            },
            get filteredEquipments() {
                return this.equipmentMap[this.categoryId] || [];
            },
            get unitFamily() {
                if (['DZ', 'MG'].includes(this.selectedCategoryCode)) return 'track';
                if (this.selectedCategoryCode === 'EXC') return 'excavator';
                if (['HDT', 'LDT'].includes(this.selectedCategoryCode)) return 'dumptruck';
                if (['SDT', 'ADT'].includes(this.selectedCategoryCode)) return 'semidump';
                if (this.selectedCategoryCode === 'WL') return 'wheelloader';
                return '';
            },
            get totalHm() {
                let calc = parseFloat(this.hmEnd) - parseFloat(this.hmStart);
                return isNaN(calc) || calc < 0 ? '0.0' : calc.toFixed(1);
            },
            get hmError() {
                const start = parseFloat(this.hmStart);
                const end = parseFloat(this.hmEnd);
                if (this.hmStart === '' || this.hmEnd === '' || isNaN(start) || isNaN(end)) {
                    return '';
                }
                if (end < start) {
                    return 'HM Akhir tidak boleh lebih kecil dari HM Awal.';
                }
                return '';
            },
            fillExistingChecklist() {
                this.$root.querySelectorAll('[name]').forEach((field) => {
                    if (!field.name.startsWith('sop_payload[')) return;
                    const path = Array.from(field.name.matchAll(/\[([^\]]+)\]/g)).map((match) => match[1]);
                    const value = path.reduce((data, key) => data?.[key], window.existingSopPayload);
                    if (value === undefined || value === null || field.type === 'hidden') return;
                    if (field.type === 'radio') field.checked = String(value) === field.value;
                    else if (!field.value) field.value = value;
                });
            },
            getTrainerRating(userId) {
                return this.trainerRatings[userId] || 0;
            },
            setTrainerRating(userId, rating) {
                this.trainerRatings[userId] = rating;
            },
            initTrainerRatings() {
                const raw = <?php echo \Illuminate\Support\Js::from(old('trainer_ratings', $isEditing ? ($logbook->trainer_ratings ?? []) : []))->toHtml() ?>;
                for (const [userId, entry] of Object.entries(raw)) {
                    this.trainerRatings[userId] = entry.rating || 0;
                }
            },
            initSelectedTrainers() {
                const trainerId = <?php echo \Illuminate\Support\Js::from(old('trainer_id', $isEditing ? $logbook->trainer_id : ''))->toHtml() ?>;
                this.selectedTrainerId = trainerId ? Number(trainerId) : '';

                const pengawasIds = <?php echo \Illuminate\Support\Js::from(old('selected_pengawas_ids', $isEditing ? ($logbook->selected_pengawas_ids ?? []) : []))->toHtml() ?>;
                this.selectedPengawasIds = pengawasIds.map(Number);

                const operatorIds = <?php echo \Illuminate\Support\Js::from(old('selected_operator_pendamping_ids', $isEditing ? ($logbook->selected_operator_pendamping_ids ?? []) : []))->toHtml() ?>;
                this.selectedOperatorIds = operatorIds.map(Number);
            },
            toggleOperator(userId, event) {
                if (event) event.stopPropagation();
                const idx = this.selectedOperatorIds.indexOf(userId);
                if (idx > -1) {
                    this.selectedOperatorIds.splice(idx, 1);
                } else {
                    this.selectedOperatorIds.push(userId);
                }
            }
        });
    </script>

    <form action="<?php echo e($isTrainerEditing ? route('trainer.reviews.update', $logbook->id) : ($isEditing ? route('ojt.logbooks.update', $logbook->id) : route('ojt.logbooks.store'))); ?>" method="POST"
      x-data="logbookFormData()"
       x-init="$nextTick(() => { fillExistingChecklist(); initTrainerRatings(); initSelectedTrainers(); })"
       class="space-y-3 pb-10">
    <?php echo csrf_field(); ?>
    <?php if($isEditing): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-xs text-rose-900">
            <p class="font-bold">Submit gagal. Periksa field berikut:</p>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

<input type="hidden" name="sop_payload[meta][category_code]" :value="selectedCategoryCode">
<input type="hidden" name="sop_payload[meta][unit_family]" :value="unitFamily">
<input type="hidden" name="action_type" id="action_type" value="submit">

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][trainee_name]" value="<?php echo e(old('sop_payload.meta.trainee_name', data_get($formPayload, 'meta.trainee_name', $user->name))); ?>" readonly class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="date" name="date" value="<?php echo e(old('date', $isEditing ? optional($logbook->date)->format('Y-m-d') : date('Y-m-d'))); ?>" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="shift" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="day" <?php echo e(old('shift', $isEditing ? $logbook->shift : '') == 'day' ? 'selected' : ''); ?>>Shift Siang</option>
                            <option value="night" <?php echo e(old('shift', $isEditing ? $logbook->shift : '') == 'night' ? 'selected' : ''); ?>>Shift Malam</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="location" x-model="location" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="">Pilih lokasi</option>
                            <option value="BMO 1" <?php echo e(old('location', $isEditing ? $logbook->location : '') == 'BMO 1' ? 'selected' : ''); ?>>BMO 1</option>
                            <option value="BMO 2" <?php echo e(old('location', $isEditing ? $logbook->location : '') == 'BMO 2' ? 'selected' : ''); ?>>BMO 2</option>
                            <option value="BMO 3" <?php echo e(old('location', $isEditing ? $logbook->location : '') == 'BMO 3' ? 'selected' : ''); ?>>BMO 3</option>
                            <option value="GMO" <?php echo e(old('location', $isEditing ? $logbook->location : '') == 'GMO' ? 'selected' : ''); ?>>GMO</option>
                            <option value="LMO" <?php echo e(old('location', $isEditing ? $logbook->location : '') == 'LMO' ? 'selected' : ''); ?>>LMO</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold">SERTIFIKASI</div>
                    <div class="px-3 py-1.5">
                        <select name="sop_payload[meta][certification]" x-model="certification" <?php echo e((!$isEditing && $trainee->certification) || $isEditing ? 'disabled' : ''); ?> class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0 <?php echo e((!$isEditing && $trainee->certification) || $isEditing ? 'appearance-none' : ''); ?>">
                            <option value="Green">Green</option>
                            <option value="Skill-up">Skill-up</option>
                            <option value="Experience">Experience</option>
                        </select>
                        <?php if((!$isEditing && $trainee->certification) || $isEditing): ?>
                            <input type="hidden" name="sop_payload[meta][certification]" value="<?php echo e(old('sop_payload.meta.certification', data_get($formPayload, 'meta.certification', $trainee->certification ?? 'Green'))); ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][company]" x-model="company" placeholder="Contoh: PT Mutiara Tanjung Lestari" <?php echo e((!$isEditing && $trainee->company) || $isEditing ? 'readonly' : ''); ?> class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                        <?php if((!$isEditing && $trainee->company) || $isEditing): ?>
                            <input type="hidden" name="sop_payload[meta][company]" value="<?php echo e(old('sop_payload.meta.company', data_get($formPayload, 'meta.company', $trainee->company ?? ''))); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TIPE ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="equipment_category_id" x-model="categoryId" <?php echo e((!$isEditing && $trainee->equipment_category_id) || $isEditing ? 'disabled' : ''); ?> class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0 <?php echo e((!$isEditing && $trainee->equipment_category_id) || $isEditing ? 'appearance-none' : ''); ?>">
                            <option value="">Pilih unit</option>
                            <option value="<?php echo e($categories->firstWhere('code', 'EXC')?->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'EXC')?->id) ? 'selected' : ''); ?>>Excavator (EX)</option>
                            <option value="<?php echo e($categories->firstWhere('code', 'DZ')?->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'DZ')?->id) ? 'selected' : ''); ?>>Bulldozer (DZ) / Motor Grader (GR)</option>
                            <option value="<?php echo e($categories->firstWhere('code', 'HDT')?->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'HDT')?->id) ? 'selected' : ''); ?>>Heavy Dump Truck (HDT) / Light Dump Truck (LDT)</option>
                            <option value="<?php echo e($categories->firstWhere('code', 'SDT')?->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'SDT')?->id) ? 'selected' : ''); ?>>Semi Dump Trailler (SDT) / Articulated Dump Truck (ADT)</option>
                            <option value="<?php echo e($categories->firstWhere('code', 'WL')?->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'WL')?->id) ? 'selected' : ''); ?>>Wheel Loader (WL)</option>
                        </select>
                        <?php if((!$isEditing && $trainee->equipment_category_id) || $isEditing): ?>
                            <input type="hidden" name="equipment_category_id" value="<?php echo e(old('equipment_category_id', $selectedCategoryId)); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="equipment_number" value="<?php echo e(old('equipment_number', $isEditing ? $logbook->equipment_number : ($trainee->equipment_number ?? ''))); ?>" placeholder="Contoh: DZ-123" <?php echo e((!$isEditing && $trainee->equipment_number) || $isEditing ? 'readonly' : ''); ?> class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM / KM AWAL</div>
                    <div class="px-3 py-1 border-b border-slate-900">
                        <input type="number" step="0.1" name="hm_start" id="hm_start_section_a" value="<?php echo e(old('hm_start', $isEditing ? $logbook->hm_start : '')); ?>" placeholder="Contoh: 4520.5" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold">HM / KM AKHIR</div>
                    <div class="px-3 py-1">
                        <input type="number" step="0.1" name="hm_end" id="hm_end_section_a" value="<?php echo e(old('hm_end', $isEditing ? $logbook->hm_end : '')); ?>" placeholder="Contoh: 4529.0" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-t border-slate-900">EXPIRED DATE STIKER (SKO)</div>
                    <div class="px-3 py-1.5 border-t border-slate-900">
                        <input type="date" name="sop_payload[meta][sticker_expired_at]" x-model="stickerExpiredAt" <?php echo e((!$isEditing && $trainee->sticker_expired_at) || $isEditing ? 'readonly' : ''); ?> class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                        <?php if((!$isEditing && $trainee->sticker_expired_at) || $isEditing): ?>
                            <input type="hidden" name="sop_payload[meta][sticker_expired_at]" value="<?php echo e(old('sop_payload.meta.sticker_expired_at', data_get($formPayload, 'meta.sticker_expired_at', $trainee->sticker_expired_at?->format('Y-m-d') ?? ''))); ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-slate-900">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-3 text-[11px]">
                <div class="font-semibold mb-2">Keterangan:</div>
                <ol class="space-y-1 pl-4 list-decimal">
                    <li>Beri tanda "&#10003;" pada kolom yang sesuai</li>
                    <li>Kolom "Trainee Feedback" memuat penjelasan item evaluasi terkait</li>
                    <li>(K) Kompeten, (BK) Belum Kompeten</li>
                    <li>Knw: Knowledge, Skl: Skill, Atd: Attitude</li>
                </ol>
            </div>
            <div class="p-3 text-[11px]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="font-semibold mb-2">Tahap Penilaian OJT</div>
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_mode]" value="pendampingan" x-model="assessmentMode" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Pendampingan</span>
                        </label>
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_mode]" value="tanpa_pendampingan" x-model="assessmentMode" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Tanpa Pendampingan</span>
                        </label>
                    </div>
                    <div>
                        <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][assessment_stage]" value="bulanan" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                                <span>Bulanan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][assessment_stage]" value="3_bulan_pertama" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                                <span>3 Bulan Pertama</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][assessment_stage]" value="3_bulan_kedua" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                                <span>3 Bulan Kedua</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <div class="font-semibold mb-2">Keterangan</div>
                        <select name="sop_payload[meta][assessment_stage_detail]" x-model="assessmentStageDetail" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="">Pilih bulan</option>
                            <option value="-" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == '-' ? 'selected' : ''); ?>>-</option>
                            <option value="bulan_1" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_1' ? 'selected' : ''); ?>>Bulan ke-1</option>
                            <option value="bulan_2" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_2' ? 'selected' : ''); ?>>Bulan ke-2</option>
                            <option value="bulan_3" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_3' ? 'selected' : ''); ?>>Bulan ke-3</option>
                            <option value="bulan_4" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_4' ? 'selected' : ''); ?>>Bulan ke-4</option>
                            <option value="bulan_5" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_5' ? 'selected' : ''); ?>>Bulan ke-5</option>
                            <option value="bulan_6" <?php echo e(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_6' ? 'selected' : ''); ?>>Bulan ke-6</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#003829]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Penugasan Personil</h2>
        </div>
        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-2">Instruktur <span class="text-rose-500">*</span></label>
                <select name="trainer_id" x-model="selectedTrainerId" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih instruktur</option>
                    <?php $__currentLoopData = $user->assignedInstruktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instruktur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($instruktur->id); ?>" <?php echo e(old('trainer_id', $isEditing ? $logbook->trainer_id : '') == $instruktur->id ? 'selected' : ''); ?>><?php echo e($instruktur->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($user->assignedInstruktur->isEmpty()): ?>
                    <p class="text-[10px] text-slate-400 mt-1">Belum ada instruktur yang ditugaskan.</p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-2">Pengawas <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    <?php $__currentLoopData = $assignedPengawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengawas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="selected_pengawas_ids[]" value="<?php echo e($pengawas->id); ?>" <?php echo e(in_array($pengawas->id, old('selected_pengawas_ids', $isEditing ? ($logbook->selected_pengawas_ids ?? []) : [])) ? 'checked' : ''); ?> x-model="selectedPengawasIds" class="accent-emerald-600">
                            <span class="text-xs"><?php echo e($pengawas->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($assignedPengawas->isEmpty()): ?>
                        <span class="text-[10px] text-slate-400">Belum ada pengawas yang ditugaskan.</span>
                    <?php endif; ?>
                </div>
                <?php $__errorArgs = ['selected_pengawas_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-2">Operator Pendamping <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    <?php $__currentLoopData = $assignedOperators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="selected_operator_pendamping_ids[]" value="<?php echo e($operator->id); ?>" <?php echo e(in_array($operator->id, old('selected_operator_pendamping_ids', $isEditing ? ($logbook->selected_operator_pendamping_ids ?? []) : [])) ? 'checked' : ''); ?> @click="toggleOperator(<?php echo e($operator->id); ?>, $event)" class="accent-emerald-600">
                            <span class="text-xs"><?php echo e($operator->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($assignedOperators->isEmpty()): ?>
                        <span class="text-[10px] text-slate-400">Belum ada operator pendamping yang ditugaskan.</span>
                    <?php endif; ?>
                </div>
                <?php $__errorArgs = ['selected_operator_pendamping_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-rose-600 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    <div class="space-y-3">
        <div x-show="unitFamily === 'track'" x-cloak class="w-full space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Track</span><span>•</span><span>Buldozer / Motor Grader</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi K / BK dan trainee feedback pada setiap item evaluasi.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">DZ / MG</div>
                </div>

<div class="p-4 space-y-3">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#00A859] uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-full bg-[#00A859] text-white flex items-center justify-center text-[10px]">A</span>
                            <span>BAGIAN A: TEKNIK PENGOPERASIAN (DOZING &amp; DIGGING, SPREADING &amp; LEVELING, RIPPING, FINISHING)</span>
                        </div>
                        <div class="flex gap-4" x-show="unitFamily === 'track'" x-cloak>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="DZ" x-model="unitType" class="accent-slate-800"> DZ (Bulldozer)
                            </label>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="GR" x-model="unitType" class="accent-slate-800"> GR (Motor Grader)
                            </label>
                        </div>
                    </div>
                    <?php $__currentLoopData = $trackGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left">Tipe</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                                <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words"><?php echo e($item['label']); ?></td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis trainee feedback"><?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN B: Kepatuhan Terhadap Peraturan Kerja</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Penggunaan APD, keamanan operasional, dan ketentuan parkir unit</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $trackComplianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.track.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.track.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][compliance][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.track.compliance.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN C: Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="unitFamily === 'excavator'" x-cloak class="w-full space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Excavator</span><span>•</span><span>Digging & Loading</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi evaluasi penguji untuk item positioning, loading, digging, dan sloping.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">EXC</div>
                </div>

<div class="p-4 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#00A859] uppercase tracking-wider mb-3">
                        <span class="w-6 h-6 rounded-full bg-[#00A859] text-white flex items-center justify-center text-[10px]">A</span>
                        <span>BAGIAN A: TEKNIK PENGOPERASIAN</span>
                    </div>
                    <?php $__currentLoopData = $excavatorGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                                <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis Trainee feedback"><?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN B: Kepatuhan Terhadap Peraturan Kerja</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Penggunaan APD, keamanan operasional, dan ketentuan parkir unit</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left w-20">Tipe</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php $__currentLoopData = $complianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.excavator.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.excavator.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][compliance][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.excavator.compliance.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN C: Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
         </div>
         </div>

        <div x-show="unitFamily === 'dumptruck'" x-cloak class="w-full space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Heavy Dump Truck</span><span>•</span><span>Light Dump Truck</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi evaluasi penguji untuk item Loading, Hauling, dan Dumping.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">HDT / LDT</div>
                </div>

<div class="p-4 space-y-3">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#00A859] uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-full bg-[#00A859] text-white flex items-center justify-center text-[10px]">A</span>
                            <span>BAGIAN A: TEKNIK PENGOPERASIAN (LOADING, HAULING &amp; DUMPING)</span>
                        </div>
                        <div class="flex gap-4" x-show="unitFamily === 'dumptruck'" x-cloak>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="HDT" x-model="unitType" class="accent-slate-800"> HDT (Heavy Dump Truck)
                            </label>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="LDT" x-model="unitType" class="accent-slate-800"> LDT (Light Dump Truck)
                            </label>
                        </div>
                    </div>
                    <?php $__currentLoopData = $dumptruckGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left">Tipe</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Trainee feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                                <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis Trainee feedback"><?php echo e(old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN B: Kepatuhan Terhadap Peraturan Kerja</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Penggunaan APD, keamanan operasional, dan ketentuan parkir unit</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $dumptruckComplianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.dumptruck.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.dumptruck.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][compliance][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.dumptruck.compliance.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN C: Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.dumptruck.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.dumptruck.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.dumptruck.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="unitFamily === 'semidump'" x-cloak class="w-full space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Semi Dump Trailer</span><span>•</span><span>Articulated Dump Truck</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi evaluasi pengawas untuk item Loading, Hauling, dan Dumping.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">SDT / ADT</div>
                </div>

<div class="p-4 space-y-3">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#00A859] uppercase tracking-wider">
                            <span class="w-6 h-6 rounded-full bg-[#00A859] text-white flex items-center justify-center text-[10px]">A</span>
                            <span>BAGIAN A: TEKNIK PENGOPERASIAN (LOADING, HAULING &amp; DUMPING)</span>
                        </div>
                        <div class="flex gap-4" x-show="unitFamily === 'semidump'" x-cloak>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="SDT" x-model="unitType" class="accent-slate-800"> SDT (Semi Dump Trailer)
                            </label>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                                <input type="radio" name="sop_payload[meta][unit_type]" value="ADT" x-model="unitType" class="accent-slate-800"> ADT (Articulated Dump Truck)
                            </label>
                        </div>
                    </div>
                    <?php $__currentLoopData = $semidumpGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left">Tipe</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Trainee feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100"><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
        <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
        <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
        <td class="px-3 py-3 text-slate-700 leading-relaxed break-words"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis Trainee feedback"><?php echo e(old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN B: Kepatuhan Terhadap Peraturan Kerja</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Penggunaan APD, keamanan operasional, dan ketentuan parkir unit</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100"><?php $__currentLoopData = $semidumpComplianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.semidump.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.semidump.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][compliance][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.semidump.compliance.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN C: Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.semidump.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.semidump.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.semidump.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="unitFamily === 'wheelloader'" x-cloak class="w-full space-y-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Wheel Loader</span><span>•</span><span>Scoping, Loading, Digging &amp; Leveling</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi evaluasi pengawas untuk item Traveling, Scoping & Loading, Digging, dan Leveling.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">WL</div>
                </div>

<div class="p-4 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#00A859] uppercase tracking-wider mb-3">
                        <span class="w-6 h-6 rounded-full bg-[#00A859] text-white flex items-center justify-center text-[10px]">A</span>
                        <span>BAGIAN A: TEKNIK PENGOPERASIAN (TRAVELING, SCOPING &amp; LOADING, DIGGING, LEVELING)</span>
                    </div>
                    <?php $__currentLoopData = $wlGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left">Tipe</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Trainee feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100"><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
        <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
        <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
        <td class="px-3 py-3 text-slate-700 leading-relaxed break-words"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis Trainee feedback"><?php echo e(old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN B: Kepatuhan Terhadap Peraturan Kerja</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Penggunaan APD, keamanan operasional, dan ketentuan parkir unit</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100"><?php $__currentLoopData = $wlComplianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.wheelloader.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.wheelloader.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][compliance][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainee feedback"><?php echo e(old('sop_payload.wheelloader.compliance.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">BAGIAN C: Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Tipe</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Trainee Feedback</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.wheelloader.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.wheelloader.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Trainer feedback"><?php echo e(old('sop_payload.wheelloader.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="!unitFamily" x-cloak class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
            Pilih kategori alat terlebih dahulu untuk menampilkan checklist SOP yang sesuai unit.
        </div>
    </div>
    </div>

    <?php if(!$isTrainerEditing): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">B</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Penilaian Trainer</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Klik bintang untuk memberikan rating</span>
        </div>

        <div class="p-6 space-y-5">
            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Instruktur</p>
                <?php $__currentLoopData = $user->assignedInstruktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instruktur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0 trainer-rating-row" data-type="instruktur" data-user-id="<?php echo e($instruktur->id); ?>" style="display: none;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                <?php echo e(substr($instruktur->name, 0, 1)); ?>

                            </div>
                            <span class="text-xs font-semibold text-slate-700"><?php echo e($instruktur->name); ?></span>
                        </div>
                        <div class="flex items-center gap-1 trainer-rating" data-user-id="<?php echo e($instruktur->id); ?>" data-input-name="trainer_ratings[<?php echo e($instruktur->id); ?>][rating]">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-rating="<?php echo e($i); ?>" class="rating-star relative w-7 h-7 cursor-pointer transition-transform duration-150 hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 rounded">
                                    <svg class="star-icon w-full h-full transition-colors duration-150 pointer-events-none text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            <?php endfor; ?>
                            <input type="hidden" name="trainer_ratings[<?php echo e($instruktur->id); ?>][user_id]" value="<?php echo e($instruktur->id); ?>">
                            <input type="hidden" name="trainer_ratings[<?php echo e($instruktur->id); ?>][role_type]" value="instruktur">
                            <input type="hidden" class="rating-value" name="trainer_ratings[<?php echo e($instruktur->id); ?>][rating]" value="0">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->assignedInstruktur->isEmpty()): ?>
                    <p class="text-[10px] text-slate-400 py-2">Belum ada instruktur yang ditugaskan.</p>
                <?php endif; ?>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pengawas</p>
                <?php $__currentLoopData = $user->assignedPengawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengawas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0 trainer-rating-row" data-type="pengawas" data-user-id="<?php echo e($pengawas->id); ?>" style="display: none;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                                <?php echo e(substr($pengawas->name, 0, 1)); ?>

                            </div>
                            <span class="text-xs font-semibold text-slate-700"><?php echo e($pengawas->name); ?></span>
                        </div>
                        <div class="flex items-center gap-1 trainer-rating" data-user-id="<?php echo e($pengawas->id); ?>" data-input-name="trainer_ratings[<?php echo e($pengawas->id); ?>][rating]">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-rating="<?php echo e($i); ?>" class="rating-star relative w-7 h-7 cursor-pointer transition-transform duration-150 hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 rounded">
                                    <svg class="star-icon w-full h-full transition-colors duration-150 pointer-events-none text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            <?php endfor; ?>
                            <input type="hidden" name="trainer_ratings[<?php echo e($pengawas->id); ?>][user_id]" value="<?php echo e($pengawas->id); ?>">
                            <input type="hidden" name="trainer_ratings[<?php echo e($pengawas->id); ?>][role_type]" value="pengawas">
                            <input type="hidden" class="rating-value" name="trainer_ratings[<?php echo e($pengawas->id); ?>][rating]" value="0">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->assignedPengawas->isEmpty()): ?>
                    <p class="text-[10px] text-slate-400 py-2">Belum ada pengawas yang ditugaskan.</p>
                <?php endif; ?>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Operator Pendamping</p>
                <?php $__currentLoopData = $user->assignedOperatorPendamping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0 trainer-rating-row" data-type="operator" data-user-id="<?php echo e($operator->id); ?>" style="display: none;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                                <?php echo e(substr($operator->name, 0, 1)); ?>

                            </div>
                            <span class="text-xs font-semibold text-slate-700"><?php echo e($operator->name); ?></span>
                        </div>
                        <div class="flex items-center gap-1 trainer-rating" data-user-id="<?php echo e($operator->id); ?>" data-input-name="trainer_ratings[<?php echo e($operator->id); ?>][rating]">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-rating="<?php echo e($i); ?>" class="rating-star relative w-7 h-7 cursor-pointer transition-transform duration-150 hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 rounded">
                                    <svg class="star-icon w-full h-full transition-colors duration-150 pointer-events-none text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292z"/>
                                    </svg>
                                </button>
                            <?php endfor; ?>
                            <input type="hidden" name="trainer_ratings[<?php echo e($operator->id); ?>][user_id]" value="<?php echo e($operator->id); ?>">
                            <input type="hidden" name="trainer_ratings[<?php echo e($operator->id); ?>][role_type]" value="operator_pendamping">
                            <input type="hidden" class="rating-value" name="trainer_ratings[<?php echo e($operator->id); ?>][rating]" value="0">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->assignedOperatorPendamping->isEmpty()): ?>
                    <p class="text-[10px] text-slate-400 py-2">Belum ada operator pendamping yang ditugaskan.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="px-6 py-3 bg-slate-50 border-t border-slate-200">
            <p class="text-[10px] text-slate-500">Rating akan disimpan bersama logbook saat submit.</p>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function syncTrainerRatingsVisibility() {
            const trainerId = document.querySelector('select[name="trainer_id"]')?.value;
            const selectedPengawas = Array.from(document.querySelectorAll('input[name="selected_pengawas_ids[]"]:checked')).map(cb => parseInt(cb.value));
            const selectedOperators = Array.from(document.querySelectorAll('input[name="selected_operator_pendamping_ids[]"]:checked')).map(cb => parseInt(cb.value));

            document.querySelectorAll('.trainer-rating-row').forEach(function(row) {
                const type = row.dataset.type;
                const userId = parseInt(row.dataset.userId);

                if (type === 'instruktur') {
                    row.style.display = (trainerId && userId === parseInt(trainerId)) ? '' : 'none';
                } else if (type === 'pengawas') {
                    row.style.display = selectedPengawas.includes(userId) ? '' : 'none';
                } else if (type === 'operator') {
                    row.style.display = selectedOperators.includes(userId) ? '' : 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            syncTrainerRatingsVisibility();

            const trainerSelect = document.querySelector('select[name="trainer_id"]');
            if (trainerSelect) trainerSelect.addEventListener('change', syncTrainerRatingsVisibility);

            document.querySelectorAll('input[name="selected_pengawas_ids[]"]').forEach(function(cb) {
                cb.addEventListener('change', syncTrainerRatingsVisibility);
            });

            document.querySelectorAll('input[name="selected_operator_pendamping_ids[]"]').forEach(function(cb) {
                cb.addEventListener('change', syncTrainerRatingsVisibility);
            });

            document.querySelectorAll('.trainer-rating').forEach(function(container) {
                const stars = container.querySelectorAll('.rating-star');
                const ratingValue = container.querySelector('.rating-value');
                
                stars.forEach(function(star) {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.rating);
                        if (ratingValue) ratingValue.value = rating;
                        
                        stars.forEach(function(s, index) {
                            const icon = s.querySelector('.star-icon');
                            if (index < rating) {
                                icon.classList.remove('text-slate-200');
                                icon.classList.add('text-amber-400', 'drop-shadow-sm');
                            } else {
                                icon.classList.remove('text-amber-400', 'drop-shadow-sm');
                                icon.classList.add('text-slate-200');
                            }
                        });
                    });
                });
            });
        });
    </script>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">C</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Ringkasan HM</h2>
            </div>
            <span class="text-xs font-bold text-[#00A859] bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Auto Calculate</span>
        </div>

        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">HM Start</span>
                <span class="text-lg font-black text-slate-800" id="section-c-hm-start">-</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">HM End</span>
                <span class="text-lg font-black text-slate-800" id="section-c-hm-end">-</span>
            </div>
            <div class="bg-[#003829] text-white p-3 rounded-xl border border-emerald-900 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider">Total HM</span>
                <div id="section-c-total-hm-wrapper" class="flex items-baseline space-x-1 mt-1">
                    <span class="text-2xl font-black text-[#F5A623]" id="section-c-total-hm">0.0</span>
                    <span class="text-xs text-emerald-200 font-bold">Hours</span>
                </div>
                <div id="section-c-hm-error" class="text-xs text-rose-300 font-medium mt-1" style="display: none;"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#003829]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Kirim Logbook</h2>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-[11px] text-slate-500">Checklist K / BK dan trainee feedback harus sesuai SOP unit yang dipilih sebelum submit.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if (! ($isTrainerEditing)): ?>
                    <button type="submit" value="draft" formnovalidate onclick="document.getElementById('action_type').value='draft'" class="group flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-5 text-left hover:border-slate-300 hover:bg-slate-100 transition">
                        <div class="rounded-lg bg-slate-200 p-2 group-hover:bg-slate-300 transition">
                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-700"><?php echo e($isEditing ? 'Simpan Perubahan Draft' : 'Save Draft'); ?></span>
                            <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">Logbook akan disimpan sebagai draft. Anda bisa melanjutkan pengisian nanti.</p>
                        </div>
                    </button>
                <?php endif; ?>
                <button type="submit" value="submit" onclick="document.getElementById('action_type').value='submit'" class="group flex items-start gap-4 rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-left hover:border-emerald-300 hover:bg-emerald-100 transition">
                    <div class="rounded-lg bg-emerald-200 p-2 group-hover:bg-emerald-300 transition">
                        <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-emerald-800"><?php echo e($isTrainerEditing ? 'Simpan Perubahan' : ($isEditing ? 'Kirim Ulang ke Trainer' : 'Submit Logbook')); ?></span>
                        <p class="mt-1 text-[11px] text-emerald-700 leading-relaxed">Kirim logbook untuk dievaluasi trainer. Pastikan semua checklist dan HM sudah terisi dengan benar.</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
(function() {
    const toggleGroups = ['sop_payload[meta][assessment_mode]', 'sop_payload[meta][assessment_stage]'];
    toggleGroups.forEach(function(name) {
        const radios = document.querySelectorAll('input[name="' + name + '"]');
        radios.forEach(function(radio) {
            radio.addEventListener('click', function() {
                if (this.checked && this.dataset.toggled === 'true') {
                    this.checked = false;
                    this.dataset.toggled = 'false';
                    this.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    radios.forEach(function(r) { r.dataset.toggled = 'false'; });
                    this.dataset.toggled = 'true';
                }
            });
        });
    });

    const checklistRadios = document.querySelectorAll('.checklist-radio');
    checklistRadios.forEach(function(radio) {
        if (radio.checked) radio.dataset.toggled = 'true';
        radio.addEventListener('click', function() {
            if (this.checked && this.dataset.toggled === 'true') {
                this.checked = false;
                this.dataset.toggled = 'false';
                this.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                checklistRadios.forEach(function(r) { r.dataset.toggled = 'false'; });
                this.dataset.toggled = 'true';
            }
        });
    });
})();
</script>
<script>
(function() {
    const hmStartInput = document.getElementById('hm_start_section_a');
    const hmEndInput = document.getElementById('hm_end_section_a');
    const sectionCHmStart = document.getElementById('section-c-hm-start');
    const sectionCHmEnd = document.getElementById('section-c-hm-end');
    const sectionCTotalHm = document.getElementById('section-c-total-hm');
    const sectionCHmError = document.getElementById('section-c-hm-error');

    function updateSectionC() {
        const start = hmStartInput ? hmStartInput.value : '';
        const end = hmEndInput ? hmEndInput.value : '';

        if (sectionCHmStart) sectionCHmStart.textContent = start !== '' ? start : '-';
        if (sectionCHmEnd) sectionCHmEnd.textContent = end !== '' ? end : '-';

        const startNum = parseFloat(start);
        const endNum = parseFloat(end);
        const totalHmWrapper = document.getElementById('section-c-total-hm-wrapper');

        if (sectionCHmError) {
            if (start === '' || end === '' || isNaN(startNum) || isNaN(endNum)) {
                sectionCHmError.textContent = '';
                sectionCHmError.style.display = 'none';
                if (totalHmWrapper) totalHmWrapper.style.display = '';
            } else if (endNum < startNum) {
                sectionCHmError.textContent = 'HM Akhir tidak boleh lebih kecil dari HM Awal.';
                sectionCHmError.style.display = 'block';
                if (totalHmWrapper) totalHmWrapper.style.display = 'none';
            } else {
                sectionCHmError.textContent = '';
                sectionCHmError.style.display = 'none';
                if (totalHmWrapper) totalHmWrapper.style.display = '';
            }
        }

        if (sectionCTotalHm) {
            if (start === '' || end === '' || isNaN(startNum) || isNaN(endNum) || endNum < startNum) {
                sectionCTotalHm.textContent = '0.0';
            } else {
                sectionCTotalHm.textContent = (endNum - startNum).toFixed(1);
            }
        }
    }

    if (hmStartInput) hmStartInput.addEventListener('input', updateSectionC);
    if (hmEndInput) hmEndInput.addEventListener('input', updateSectionC);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateSectionC);
    } else {
        updateSectionC();
    }
})();
</script>

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/create-form.blade.php ENDPATH**/ ?>