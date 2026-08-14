@php
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
@endphp

<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
            <a href="{{ route('ojt.logbooks.index') }}" class="hover:underline">My Logbook</a>
            <span>/</span>
            <span class="text-slate-500">{{ $isTrainerEditing ? 'Edit Logbook Trainer' : ($isEditing ? 'Edit Draft Logbook' : 'Create Digital Logbook') }}</span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Logbook Harian Trainee OJT</h1>
        <p class="text-xs text-slate-500 mt-1">Form ini menampilkan checklist harian per unit: track unit (DZ/GR), excavator (EXC), dump truck (HDT/LDT), dan semi dump (SDT/ADT).</p>
    </div>
    <a href="{{ route('ojt.logbooks.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Kembali</a>
</div>

<form action="{{ $isTrainerEditing ? route('trainer.reviews.update', $logbook->id) : ($isEditing ? route('ojt.logbooks.update', $logbook->id) : route('ojt.logbooks.store')) }}" method="POST"
      x-data="{
           categoryId: @js(old('equipment_category_id', $selectedCategoryId)),
           equipmentId: @js(old('equipment_id', $isEditing ? $logbook->equipment_id : '')),
          categoryMap: @js($categoryMap),
          equipmentMap: @js($equipmentMap),
            company: @js(old('sop_payload.meta.company', data_get($formPayload, 'meta.company', $trainee->company ?? ''))),
            certification: @js(old('sop_payload.meta.certification', data_get($formPayload, 'meta.certification', $trainee->certification ?? 'Green'))),
            stickerExpiredAt: @js(old('sop_payload.meta.sticker_expired_at', data_get($formPayload, 'meta.sticker_expired_at', $trainee->sticker_expired_at?->format('Y-m-d') ?? ''))),
            assessmentMode: @js(old('sop_payload.meta.assessment_mode', data_get($formPayload, 'meta.assessment_mode', ''))),
            assessmentStage: @js(old('sop_payload.meta.assessment_stage', data_get($formPayload, 'meta.assessment_stage', ''))),
            assessmentStageDetail: @js(old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', ''))),
            hmStart: @js(old('hm_start', $isEditing ? $logbook->hm_start : '')),
            hmEnd: @js(old('hm_end', $isEditing ? $logbook->hm_end : '')),
            unitType: @js(old('sop_payload.meta.unit_type', data_get($formPayload, 'meta.unit_type', ''))),
            location: @js(old('location', $isEditing ? $logbook->location : '')),
            dailyActivity: @js(old('daily_activity', $isEditing ? $logbook->daily_activity : '')),
            existingSopPayload: @js($formPayload),
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
                  const value = path.reduce((data, key) => data?.[key], this.existingSopPayload);
                  if (value === undefined || value === null || field.type === 'hidden') return;
                  if (field.type === 'radio') field.checked = String(value) === field.value;
                  else if (!field.value) field.value = value;
              });
          }
      }"
      x-init="$nextTick(() => fillExistingChecklist())"
       class="space-y-3 pb-10">
    @csrf
    @if($isEditing)
        @method('PUT')
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-xs text-rose-900">
            <p class="font-bold">Submit gagal. Periksa field berikut:</p>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<input type="hidden" name="sop_payload[meta][category_code]" :value="selectedCategoryCode">
<input type="hidden" name="sop_payload[meta][unit_family]" :value="unitFamily">
<input type="hidden" name="action_type" id="action_type" value="submit">

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][trainee_name]" value="{{ old('sop_payload.meta.trainee_name', data_get($formPayload, 'meta.trainee_name', $user->name)) }}" readonly class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="date" name="date" value="{{ old('date', $isEditing ? optional($logbook->date)->format('Y-m-d') : date('Y-m-d')) }}" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="shift" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="day" {{ old('shift', $isEditing ? $logbook->shift : '') == 'day' ? 'selected' : '' }}>Shift Siang</option>
                            <option value="night" {{ old('shift', $isEditing ? $logbook->shift : '') == 'night' ? 'selected' : '' }}>Shift Malam</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="location" x-model="location" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="">Pilih lokasi</option>
                            <option value="BMO 1" {{ old('location', $isEditing ? $logbook->location : '') == 'BMO 1' ? 'selected' : '' }}>BMO 1</option>
                            <option value="BMO 2" {{ old('location', $isEditing ? $logbook->location : '') == 'BMO 2' ? 'selected' : '' }}>BMO 2</option>
                            <option value="BMO 3" {{ old('location', $isEditing ? $logbook->location : '') == 'BMO 3' ? 'selected' : '' }}>BMO 3</option>
                            <option value="GMO" {{ old('location', $isEditing ? $logbook->location : '') == 'GMO' ? 'selected' : '' }}>GMO</option>
                            <option value="LMO" {{ old('location', $isEditing ? $logbook->location : '') == 'LMO' ? 'selected' : '' }}>LMO</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold">SERTIFIKASI</div>
                    <div class="px-3 py-1.5">
                        <select name="sop_payload[meta][certification]" x-model="certification" {{ !$isEditing && $trainee->certification ? 'disabled' : '' }} class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0 {{ !$isEditing && $trainee->certification ? 'appearance-none' : '' }}">
                            <option value="Green">Green</option>
                            <option value="Skill-up">Skill-up</option>
                            <option value="Experience">Experience</option>
                        </select>
                        @if(!$isEditing && $trainee->certification)
                            <input type="hidden" name="sop_payload[meta][certification]" value="{{ $trainee->certification }}">
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][company]" x-model="company" placeholder="Contoh: PT Mutiara Tanjung Lestari" {{ !$isEditing && $trainee->company ? 'readonly' : '' }} class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TIPE ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="equipment_category_id" x-model="categoryId" {{ !$isEditing && $trainee->equipment_category_id ? 'disabled' : '' }} class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0 {{ !$isEditing && $trainee->equipment_category_id ? 'appearance-none' : '' }}">
                            <option value="">Pilih unit</option>
                            <option value="{{ $categories->firstWhere('code', 'EXC')?->id }}" {{ old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'EXC')?->id) ? 'selected' : '' }}>Excavator (EX)</option>
                            <option value="{{ $categories->firstWhere('code', 'DZ')?->id }}" {{ old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'DZ')?->id) ? 'selected' : '' }}>Bulldozer (DZ) / Motor Grader (GR)</option>
                            <option value="{{ $categories->firstWhere('code', 'HDT')?->id }}" {{ old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'HDT')?->id) ? 'selected' : '' }}>Heavy Dump Truck (HDT) / Light Dump Truck (LDT)</option>
                            <option value="{{ $categories->firstWhere('code', 'SDT')?->id }}" {{ old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'SDT')?->id) ? 'selected' : '' }}>Semi Dump Trailler (SDT) / Articulated Dump Truck (ADT)</option>
                            <option value="{{ $categories->firstWhere('code', 'WL')?->id }}" {{ old('equipment_category_id', $selectedCategoryId) == ($categories->firstWhere('code', 'WL')?->id) ? 'selected' : '' }}>Wheel Loader (WL)</option>
                        </select>
                        @if(!$isEditing && $trainee->equipment_category_id)
                            <input type="hidden" name="equipment_category_id" value="{{ $trainee->equipment_category_id }}">
                        @endif
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="equipment_number" value="{{ old('equipment_number', $isEditing ? $logbook->equipment_number : ($trainee->equipment_number ?? '')) }}" placeholder="Contoh: DZ-123" {{ !$isEditing && $trainee->equipment_number ? 'readonly' : '' }} class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM / KM AWAL</div>
                    <div class="px-3 py-1 border-b border-slate-900">
                        <input type="number" step="0.1" name="hm_start" id="hm_start_section_a" value="{{ old('hm_start', $isEditing ? $logbook->hm_start : '') }}" placeholder="Contoh: 4520.5" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold">HM / KM AKHIR</div>
                    <div class="px-3 py-1">
                        <input type="number" step="0.1" name="hm_end" id="hm_end_section_a" value="{{ old('hm_end', $isEditing ? $logbook->hm_end : '') }}" placeholder="Contoh: 4529.0" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-t border-slate-900">EXPIRED DATE STIKER (SKO)</div>
                    <div class="px-3 py-1.5 border-t border-slate-900">
                        <input type="date" name="sop_payload[meta][sticker_expired_at]" x-model="stickerExpiredAt" {{ !$isEditing && $trainee->sticker_expired_at ? 'readonly' : '' }} class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-slate-900">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-3 text-[11px]">
                <div class="font-semibold mb-2">Keterangan:</div>
                <ol class="space-y-1 pl-4 list-decimal">
                    <li>Beri tanda "&#10003;" pada kolom yang sesuai</li>
                    <li>Kolom "Catatan Penguji" memuat penjelasan item evaluasi terkait</li>
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
                            <option value="-" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == '-' ? 'selected' : '' }}>-</option>
                            <option value="bulan_1" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_1' ? 'selected' : '' }}>Bulan ke-1</option>
                            <option value="bulan_2" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_2' ? 'selected' : '' }}>Bulan ke-2</option>
                            <option value="bulan_3" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_3' ? 'selected' : '' }}>Bulan ke-3</option>
                            <option value="bulan_4" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_4' ? 'selected' : '' }}>Bulan ke-4</option>
                            <option value="bulan_5" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_5' ? 'selected' : '' }}>Bulan ke-5</option>
                            <option value="bulan_6" {{ old('sop_payload.meta.assessment_stage_detail', data_get($formPayload, 'meta.assessment_stage_detail', '')) == 'bulan_6' ? 'selected' : '' }}>Bulan ke-6</option>
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
                <select name="trainer_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih instruktur</option>
                    @foreach($user->assignedInstruktur as $instruktur)
                        <option value="{{ $instruktur->id }}" {{ old('trainer_id', $isEditing ? $logbook->trainer_id : '') == $instruktur->id ? 'selected' : '' }}>{{ $instruktur->name }}</option>
                    @endforeach
                </select>
                @if($user->assignedInstruktur->isEmpty())
                    <p class="text-[10px] text-slate-400 mt-1">Belum ada instruktur yang ditugaskan.</p>
                @endif
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-2">Pengawas <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    @foreach($assignedPengawas as $pengawas)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="selected_pengawas_ids[]" value="{{ $pengawas->id }}" {{ in_array($pengawas->id, old('selected_pengawas_ids', $isEditing ? ($logbook->selected_pengawas_ids ?? []) : [])) ? 'checked' : '' }} class="accent-emerald-600">
                            <span class="text-xs">{{ $pengawas->name }}</span>
                        </label>
                    @endforeach
                    @if($assignedPengawas->isEmpty())
                        <span class="text-[10px] text-slate-400">Belum ada pengawas yang ditugaskan.</span>
                    @endif
                </div>
                @error('selected_pengawas_ids')
                    <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-2">Operator Pendamping <span class="text-rose-500">*</span></label>
                <div class="space-y-2">
                    @foreach($assignedOperators as $operator)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="selected_operator_pendamping_ids[]" value="{{ $operator->id }}" {{ in_array($operator->id, old('selected_operator_pendamping_ids', $isEditing ? ($logbook->selected_operator_pendamping_ids ?? []) : [])) ? 'checked' : '' }} class="accent-emerald-600">
                            <span class="text-xs">{{ $operator->name }}</span>
                        </label>
                    @endforeach
                    @if($assignedOperators->isEmpty())
                        <span class="text-[10px] text-slate-400">Belum ada operator pendamping yang ditugaskan.</span>
                    @endif
                </div>
                @error('selected_operator_pendamping_ids')
                    <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
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
                        <p class="text-[11px] text-slate-500 mt-1">Isi K / BK dan catatan penguji pada setiap item evaluasi.</p>
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
                    @foreach($trackGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
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
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                                <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words">{{ $item['label'] }}</td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($trackComplianceItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][compliance][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[track][compliance][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[track][compliance][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][compliance][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.track.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][compliance][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.track.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][compliance][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.track.compliance.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[track][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[track][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.track.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                    @foreach($excavatorGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
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
                                    @foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                                <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

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
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($complianceItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][compliance][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[excavator][compliance][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[excavator][compliance][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][compliance][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.excavator.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][compliance][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.excavator.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][compliance][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.excavator.compliance.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[excavator][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[excavator][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.excavator.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                    @foreach($dumptruckGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
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
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                                <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.dumptruck.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($dumptruckComplianceItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.dumptruck.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.dumptruck.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][compliance][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.dumptruck.compliance.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.dumptruck.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.dumptruck.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[dumptruck][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.dumptruck.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                    @foreach($semidumpGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
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
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">@foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
        <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
        <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
        <td class="px-3 py-3 text-slate-700 leading-relaxed break-words">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[semidump][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.semidump.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">@foreach($semidumpComplianceItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][compliance][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[semidump][compliance][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[semidump][compliance][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][compliance][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.semidump.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][compliance][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.semidump.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][compliance][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.semidump.compliance.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[semidump][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[semidump][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[semidump][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[semidump][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.semidump.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[semidump][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.semidump.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[semidump][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.semidump.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                    @foreach($wlGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
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
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">@foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
        <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
        <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
        <td class="px-3 py-3 text-slate-700 leading-relaxed break-words">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.wheelloader.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">@foreach($wlComplianceItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.wheelloader.compliance.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.wheelloader.compliance.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][compliance][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.wheelloader.compliance.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
<td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600 checklist-radio" name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.wheelloader.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
<td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600 checklist-radio" name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.wheelloader.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
<td class="px-3 py-3"><textarea name="sop_payload[wheelloader][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.wheelloader.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">B</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Trainer Feedback</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Ringkasan aktivitas shift</span>
        </div>

        <div class="p-4">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan kegiatan / pekerjaan harian <span class="text-rose-500">*</span></label>
            <textarea id="daily_activity_field" name="daily_activity" rows="3" placeholder="Tuliskan ringkasan aktivitas harian, kondisi unit, dan poin penting pekerjaan shift ini..." class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">{{ old('daily_activity', $isEditing ? $logbook->daily_activity : '') }}</textarea>
            <input type="hidden" name="daily_activity_backup" id="daily_activity_backup">
        </div>
    </div>

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
            <p class="text-[11px] text-slate-500">Checklist K / BK dan catatan penguji harus sesuai SOP unit yang dipilih sebelum submit.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @unless($isTrainerEditing)
                    <button type="submit" value="draft" formnovalidate onclick="document.getElementById('action_type').value='draft'" class="group flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-5 text-left hover:border-slate-300 hover:bg-slate-100 transition">
                        <div class="rounded-lg bg-slate-200 p-2 group-hover:bg-slate-300 transition">
                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-700">{{ $isEditing ? 'Simpan Perubahan Draft' : 'Save Draft' }}</span>
                            <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">Logbook akan disimpan sebagai draft. Anda bisa melanjutkan pengisian nanti.</p>
                        </div>
                    </button>
                @endunless
                <button type="submit" value="submit" onclick="document.getElementById('action_type').value='submit'" class="group flex items-start gap-4 rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-left hover:border-emerald-300 hover:bg-emerald-100 transition">
                    <div class="rounded-lg bg-emerald-200 p-2 group-hover:bg-emerald-300 transition">
                        <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-emerald-800">{{ $isTrainerEditing ? 'Simpan Perubahan' : ($isEditing ? 'Kirim Ulang ke Trainer' : 'Submit Logbook') }}</span>
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

    const dailyActivityTextarea = document.querySelector('textarea[name="daily_activity"]');
    const dailyActivityBackup = document.getElementById('daily_activity_backup');
    if (dailyActivityTextarea && dailyActivityBackup) {
        dailyActivityTextarea.addEventListener('input', function() {
            dailyActivityBackup.value = this.value;
            try { localStorage.setItem('daily_activity_backup', this.value); } catch (e) {}
        });

        const saved = (function() {
            try { return localStorage.getItem('daily_activity_backup'); } catch (e) { return ''; }
        })();
        if (saved && !dailyActivityTextarea.value.trim()) {
            dailyActivityTextarea.value = saved;
            dailyActivityBackup.value = saved;
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (!dailyActivityTextarea.value.trim() && dailyActivityBackup.value.trim()) {
                    dailyActivityTextarea.value = dailyActivityBackup.value;
                }
                try { localStorage.removeItem('daily_activity_backup'); } catch (e) {}
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateSectionC);
    } else {
        updateSectionC();
    }
})();
</script>
