    <?php
        $payload = $logbook->sop_payload ?? [];
        $categoryCode = $logbook->equipmentCategory->code ?? 'DZ';
        $categoryName = $logbook->equipmentCategory->name ?? 'Bulldozer & Motor Grader';
        $family = data_get($payload, 'meta.unit_family');
         if (!$family) {
             $family = in_array($categoryCode, ['DZ', 'MG']) ? 'track' : (in_array($categoryCode, ['EXC', 'EX']) ? 'excavator' : (in_array($categoryCode, ['HDT', 'LDT']) ? 'dumptruck' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'semidump' : ($categoryCode === 'WL' ? 'wheelloader' : 'track'))));
         }

        $checklist = $payload[$family] ?? [];
        $certification = data_get($payload, 'meta.certification', 'Green');
        $company = data_get($payload, 'meta.company', 'PT BERAU COAL / PT MTL');
        $stickerExp = data_get($payload, 'meta.sticker_expired_at');
        $assessmentMode = data_get($payload, 'meta.assessment_mode', '');
        $assessmentStage = data_get($payload, 'meta.assessment_stage', '');
        $assessmentStageDetail = data_get($payload, 'meta.assessment_stage_detail', '');

        // Extract Section A Groups
        $groups = data_get($checklist, 'groups', []);
        if (empty($groups)) {
            if ($family === 'excavator') {
                $groups = [
                    [
                        'title' => '1. Positioning',
                        'subtitle' => 'Cara memosisisikan unit, track, dan upper structure di front loading',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan unit di front loading', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Cara membuat landasan', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara mengatur track dan upper structure', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Loading & Dumping',
                        'subtitle' => 'Cara swing, memuat, dan dumping yang aman',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Skl', 'label' => 'Cara swing muatan', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Skl', 'label' => 'Cara swing kosongan', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Knw', 'label' => 'Kombinasi gerakan', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Cara dumping dan kerapihan muatan', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Knw', 'label' => 'Sudut swing', 'status' => 'K'],
                            ['code' => '2.6', 'kind' => 'Knw', 'label' => 'Cycle time', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Digging',
                        'subtitle' => 'Teknik digging dan pengaturan kerja bucket',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Teknik digging (urutan pengambilan)', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Skl', 'label' => 'Sudut pengambilan (digging)', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Gerakan kombinasi pada saat digging', 'status' => 'K'],
                            ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Volume bucket', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '4. Sloping',
                        'subtitle' => 'Teknik pembuatan slope dan kerapihan permukaan',
                        'items' => [
                            ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Teknik pembuatan slope', 'status' => 'K'],
                            ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Kerapihan slope', 'status' => 'K'],
                        ]
                    ],
                ];
            } elseif ($family === 'dumptruck') {
                $groups = [
                    [
                        'title' => '1. Loading',
                        'subtitle' => 'Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi/brake saat loading',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk loading/ posisi antri/', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Posisi terhadap Alat muat (rata, aman & keras)', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Tranmission "N" & Penggunaan brake saat loading', 'status' => 'K'],
                            ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/payload meter (Khusus untuk Unit HDT)', 'status' => 'K'],
                            ['code' => '1.5', 'kind' => 'Knw', 'label' => 'Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)', 'status' => 'K'],
                            ['code' => '1.6', 'kind' => 'Knw', 'label' => 'Perhatian terhadap standart muatan (Khusus untuk Unit LDT)', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Hauling',
                        'subtitle' => 'Penggunaan speed/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Clutch (Khusus untuk Unit LDT)', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Knw', 'label' => 'Penggunaan Shift Limit & Power/ Eco Mode (Khusus untuk Unit HDT)', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)', 'status' => 'K'],
                            ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)', 'status' => 'K'],
                            ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ di tikungan', 'status' => 'K'],
                            ['code' => '2.8', 'kind' => 'Skl', 'label' => 'Ketrampilan/ kelembutan mengemudi', 'status' => 'K'],
                            ['code' => '2.9', 'kind' => 'Knw', 'label' => 'Cycle time', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Dumping',
                        'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping', 'status' => 'K'],
                            ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)', 'status' => 'K'],
                            ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel', 'status' => 'K'],
                            ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)', 'status' => 'K'],
                            ['code' => '3.7', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan vesel di hopper/ stock pile (Khusus untuk Unit LDT)', 'status' => 'K'],
                        ]
                    ],
                ];
            } elseif ($family === 'semidump') {
                $groups = [
                    [
                        'title' => '1. Loading',
                        'subtitle' => 'Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi/brake saat loading',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Penempatan posisi untuk loading/ posisi antri', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Posisi Trailer terhadap Alat muat (rata & aman)', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Transmission "N" & Penggunaan Parking Brake saat loading', 'status' => 'K'],
                            ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/ vessel penuh', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Hauling',
                        'subtitle' => 'Penggunaan speed/transmissi, power/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Power/ Offroad Mode', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penggunaan Trailer Brake', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ditikungan', 'status' => 'K'],
                            ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Ketrampilan/kelembutan mengemudi', 'status' => 'K'],
                            ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Cycle time', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Dumping',
                        'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping', 'status' => 'K'],
                            ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)', 'status' => 'K'],
                            ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel', 'status' => 'K'],
                            ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di Hopper/Stock Pile', 'status' => 'K'],
                        ]
                    ],
                ];
            } elseif ($family === 'wheelloader') {
                $groups = [
                    [
                        'title' => '1. Traveling',
                        'subtitle' => 'Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Memposisikan attachment dengan benar', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Penyesuaian speed dengan kondisi medan', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara manuver & membelok di tikungan', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Scoping & Loading',
                        'subtitle' => 'Teknik scopping, loading, dan load & carry ke hauling truck',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan bucket pada saat scopping', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Skl', 'label' => 'Cara manuver/ pengoperasian load & carry', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara loading ke hauling truck', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Knw', 'label' => 'Cycle Time', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Digging',
                        'subtitle' => 'Teknik digging: posisi unit, penetrasi bucket, dan kapasitas',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Position unit', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik penetrasi bucket', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi lift arm', 'status' => 'K'],
                            ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Kapasitas bucket', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '4. Leveling',
                        'subtitle' => 'Teknik leveling: speed, tilt, steering, dan penempatan material',
                        'items' => [
                            ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Penggunaan speed/transmisi saat bergerak', 'status' => 'K'],
                            ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Cara leveling menggunakan tilt', 'status' => 'K'],
                            ['code' => '4.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll', 'status' => 'K'],
                            ['code' => '4.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja', 'status' => 'K'],
                            ['code' => '4.5', 'kind' => 'Skl', 'label' => 'Penggunaan steering', 'status' => 'K'],
                        ]
                    ],
                ];
            } else {
                $groups = [
                    [
                        'title' => '1. Dozing & Digging untuk Unit (DZ) / Grading & Digging untuk Unit (GR)',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memosisisikan unit, track, dan upper structure di front loading', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Penggunaan Tilt Blade', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk mendorong/ ditching', 'status' => 'K'],
                            ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk menggali/ sloping', 'status' => 'K'],
                            ['code' => '1.5', 'kind' => 'Skl', 'label' => 'Penyesuaian beban dengan rpm/posisi transmissi', 'status' => 'K'],
                            ['code' => '1.6', 'kind' => 'Skl', 'label' => 'Teknik dozing/grading/digging', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Spreading & Leveling',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Cara leveling menggunakan tilt', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Steering', 'status' => 'K'],
                            ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan Articulated (Khusus untuk unit GR)', 'status' => 'K'],
                            ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Teknik spreading/levelling', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Ripping',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Cara memosisisikan Ripper', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik Penetrasi Ripping', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '4. Finishing',
                        'items' => [
                            ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Kesesuaian penggunaan speed', 'status' => 'K'],
                            ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Hasil akhir pendorungan (hasil pekerjaan)', 'status' => 'K'],
                        ]
                    ],
                ];
            }
        }

        // Section B: Compliance
        if (in_array($family, ['dumptruck', 'semidump'])) {
            $complianceItems = [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni unit (Three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk dan steering wheel'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Atd', 'label' => 'Keselamatan saat loading/harus didalam kabin'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap Rambu lalu lintas'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Keselamatan saat dumping'],
                ['code' => '10', 'kind' => 'Atd', 'label' => 'Sopan santun mengemudi'],
                ['code' => '11', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)'],
            ];
        } elseif ($family === 'wheelloader') {
            $complianceItems = [
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
            ];
        } else {
            $complianceItems = [
                ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
                ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni Unit (Three point contact)'],
                ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk'],
                ['code' => '4', 'kind' => 'Atd', 'label' => 'Penggunaan sabuk pengaman/safety belt'],
                ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
                ['code' => '6', 'kind' => 'Skl', 'label' => $family === 'track' ? 'Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling' : 'Keselamatan saat loading, unloading, positioning, traveling, dan digging'],
                ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan'],
                ['code' => '8', 'kind' => 'Atd', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu'],
                ['code' => '9', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)'],
                ['code' => '10', 'kind' => 'Knw', 'label' => 'Keselamatan selama operasi'],
            ];
        }

        // Merge saved compliance statuses if exists
        $savedCompliance = data_get($checklist, 'compliance', []);
        foreach ($complianceItems as $i => $item) {
            if (isset($savedCompliance[$i])) {
                $complianceItems[$i]['status'] = $savedCompliance[$i]['status'] ?? 'K';
                $complianceItems[$i]['note'] = $savedCompliance[$i]['note'] ?? '';
            } else {
                $complianceItems[$i]['status'] = 'K';
                $complianceItems[$i]['note'] = '';
            }
        }

        // Section C: Discipline & Communication
        $savedBehavior = data_get($checklist, 'behavior', []);
        $disciplineItems = [
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

        // Merge saved behavior statuses if exists
        foreach ($disciplineItems as $i => $item) {
            if (isset($savedBehavior[$i])) {
                $disciplineItems[$i]['status'] = $savedBehavior[$i]['status'] ?? 'K';
                $disciplineItems[$i]['note'] = $savedBehavior[$i]['note'] ?? '';
            } else {
                $disciplineItems[$i]['status'] = 'K';
                $disciplineItems[$i]['note'] = '';
            }
        }
    ?>

    <div class="form-container">

        <!-- Official Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div style="text-align: center; padding: 1px 2px;">
                        <img src="<?php echo e(asset('images/berau_coal_logo.svg')); ?>" alt="Berau Coal Logo" class="logo-img">
                    </div>
                </td>
                <td>
                    <div class="title-main">BERAU COAL GREEN MINING SYSTEM</div>
                    <div class="title-sub">FORMULIR</div>
                     <div class="title-desc">Pelaksanaan On Job Training (OJT) Unit <?php echo e(in_array($categoryCode, ['HDT', 'LDT']) ? 'Heavy Dump Truck (HDT) / Light Dump Truck (LDT)' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'Semi Dump Trailer (SDT) / Articulated Dump Truck (ADT)' : ($categoryCode === 'WL' ? 'Wheel Loader (WL)' : ($categoryName . ' (' . $categoryCode . ')')))); ?></div>
                </td>
            </tr>
        </table>

        <!-- Metadata Grid -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">NAMA</td><td>: <?php echo e($logbook->trainee->name ?? 'Ahmad Rian Syahputra'); ?></td></tr>
                        <tr><td class="meta-label">HARI/ TANGGAL</td><td>: <?php echo e(\Carbon\Carbon::parse($logbook->date)->translatedFormat('l, d F Y')); ?></td></tr>
                        <tr><td class="meta-label">SHIFT</td><td>: Shift <?php echo e(ucfirst($logbook->shift)); ?> (<?php echo e($logbook->shift === 'day' ? 'Siang: 07.00 - 17.00' : 'Malam: 19.00 - 05.00'); ?>)</td></tr>
                        <tr><td class="meta-label">LOKASI (OJT)</td><td>: <?php echo e($logbook->location); ?></td></tr>
                        <tr><td class="meta-label">SERTIFIKASI</td><td>: 
                            <span style="<?php echo e($certification === 'Green' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Green</span> / 
                            <span style="<?php echo e($certification === 'Skill-up' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Skill-up</span> / 
                            <span style="<?php echo e($certification === 'Experience' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Experience</span> 
                            <span style="font-size: 7.5px; font-style: italic;">(Coret yang tidak sesuai)</span>
                        </td></tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">PERUSAHAAN</td><td>: <?php echo e($company); ?></td></tr>
                         <tr><td class="meta-label">TIPE ALAT</td><td>: <?php echo e($categoryName); ?> (<?php echo e($categoryCode); ?>)</td></tr>
                         <?php if(in_array($family, ['track', 'dumptruck', 'semidump'], true)): ?>
                             <?php
                                 $unitType = data_get($payload, 'meta.unit_type', $family === 'track' ? 'DZ' : ($family === 'semidump' ? 'SDT' : 'HDT'));
                              $unitTypeLabel = $family === 'track' ? ($unitType === 'GR' ? 'GR (Motor Grader)' : 'DZ (Bulldozer)') : ($family === 'semidump' ? ($unitType === 'ADT' ? 'ADT (Articulated Dump Truck)' : 'SDT (Semi Dump Trailer)') : ($unitType === 'LDT' ? 'LDT (Light Dump Truck)' : 'HDT (Heavy Dump Truck)'));
                          ?>
                         <tr><td class="meta-label">UNIT TYPE</td><td>: <?php echo e($unitTypeLabel); ?></td></tr>
                         <?php endif; ?>
                        <tr><td class="meta-label">NO ALAT</td><td>: <?php echo e($logbook->unit_code); ?></td></tr>
                         <tr><td class="meta-label">HM/ KM AWAL</td><td>: <?php echo e(number_format($logbook->hm_start, 1)); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>HM/ KM AKHIR:</b> <?php echo e(number_format($logbook->hm_end, 1)); ?></td></tr>
                         <tr><td class="meta-label">EXPIRED DATE STIKER (SKO)</td><td>: <?php echo e($stickerExp ? \Carbon\Carbon::parse($stickerExp)->format('d/m/Y') : '......................20....'); ?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;" class="keterangan-box">
                    <b>Keterangan:</b><br>
                    1. Beri tanda "✓" pada kolom yang sesuai<br>
                    2. Kolom "Catatan Penguji" memuat penjelasan item evaluasi terkait<br>
                    3. (K) Kompeten, (BK) Belum Kompeten<br>
                    4. Knw: Knowledge, Skl: Skill, Att: Attitude<br>
                    5. (*) Coret yang tidak sesuai
                </td>
                <td style="width: 50%;" class="checkbox-box">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%; vertical-align: top;">
                                <b>Tahap Penilaian OJT</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentMode === 'pendampingan' ? '✓' : '&nbsp;'; ?></span> Pendampingan</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentMode === 'tanpa_pendampingan' ? '✓' : '&nbsp;'; ?></span> Tanpa Pendampingan</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Tahap Tanpa Pendampingan Lanjutan</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === 'bulanan' ? '✓' : '&nbsp;'; ?></span> Bulanan</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_pertama' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Pertama</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_kedua' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Kedua</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Keterangan</b><br>
                                <?php
                                    $detailValue = $assessmentStageDetail ?: $assessmentStage;
                                ?>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_1' ? '✓' : '&nbsp;'; ?></span> Bulan ke-1</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_2' ? '✓' : '&nbsp;'; ?></span> Bulan ke-2</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_3' ? '✓' : '&nbsp;'; ?></span> Bulan ke-3</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_4' ? '✓' : '&nbsp;'; ?></span> Bulan ke-4</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_5' ? '✓' : '&nbsp;'; ?></span> Bulan ke-5</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_6' ? '✓' : '&nbsp;'; ?></span> Bulan ke-6</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === '-' ? '✓' : '&nbsp;'; ?></span> -</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Banner Code -->
        <div class="unit-banner">
            UNIT*: <?php echo e($categoryCode); ?>

        </div>

        <!-- Main Evaluation Table -->
        <table class="eval-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-aspek">Tipe</th>
                    <th class="col-item">Item Evaluasi</th>
                    <th class="col-kbk">K</th>
                    <th class="col-kbk">BK</th>
                    <th class="col-catatan">Catatan Penguji</th>
                </tr>
            </thead>
            <tbody>
                <!-- SECTION A -->
                <tr>
                    <td class="col-no">A</td>
                    <td colspan="5" class="section-header">Teknik Pengoperasian</td>
                </tr>
                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($group['title'])): ?>
                        <tr>
                            <td class="col-no"><?php echo e($groupIndex + 1); ?></td>
                            <td colspan="5" class="sub-header"><?php echo e($group['title']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php $__currentLoopData = $group['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $status = $item['status'] ?? 'K';
                            $note = trim((string)($item['note'] ?? ''));
                        ?>
                        <tr>
                            <td class="col-no"><?php echo e($item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1)); ?></td>
                            <td class="col-aspek"><?php echo e($item['kind'] ?? 'Skl'); ?></td>
                            <td class="col-item"><?php echo e($item['label'] ?? ''); ?></td>
                            <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'K') ? '✓' : ''; ?></td>
                            <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'BK') ? '✓' : ''; ?></td>
                            <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- SECTION B -->
                <tr>
                    <td class="col-no">B</td>
                    <td colspan="5" class="section-header">Kepatuhan Terhadap Peraturan Kerja</td>
                </tr>
                <?php $__currentLoopData = $complianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['note'] ?? ''));
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'K') ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'BK') ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- SECTION C -->
                <tr>
                    <td class="col-no">C</td>
                    <td colspan="5" class="section-header">Kedisiplinan dan Komunikasi</td>
                </tr>
                <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['note'] ?? ''));
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'K') ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && $status === 'BK') ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Declaration Statement -->
        <div class="statement-box">
            Pengisian form Pelaksanaan OJT ini saya lakukan dengan benar dan item Evaluasi yang saya beri rekomendasi (K) / (BK) dapat saya pertanggungjawabkan.
        </div>

        <!-- Instructor Notes & Conclusion Box -->
        <table class="footer-grid">
            <tr>
                <td style="width: 65%;">
                    <div style="font-weight: bold; text-transform: uppercase; margin-bottom: 4px;">Catatan Instruktur:</div>
                    <div style="font-size: 9.5px; line-height: 1.5; min-height: 60px; white-space: pre-line;">
<?php echo e($logbook->evaluation?->trainer_comment ?? $logbook->daily_activity); ?>

                    </div>
                </td>
                <td style="width: 35%;">
                    <div class="conclusion-title">KESIMPULAN</div>
                    <?php
                        $hasAnyBk = false;

                        foreach ($groups as $group) {
                            foreach ($group['items'] ?? [] as $item) {
                                if (($item['status'] ?? 'K') === 'BK') {
                                    $hasAnyBk = true;
                                    break 2;
                                }
                            }
                        }

                        if (!$hasAnyBk) {
                            foreach ($complianceItems as $item) {
                                if (($item['status'] ?? 'K') === 'BK') {
                                    $hasAnyBk = true;
                                    break;
                                }
                            }
                        }

                        if (!$hasAnyBk) {
                            foreach ($disciplineItems as $item) {
                                if (($item['status'] ?? 'K') === 'BK') {
                                    $hasAnyBk = true;
                                    break;
                                }
                            }
                        }

                        $storedStatus = $logbook->evaluation?->competency_status;
                        if ($storedStatus === 'not_yet_competent') {
                            $hasAnyBk = true;
                        }

                        $isKompeten = !$hasAnyBk;
                    ?>
                    <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect"><?php echo $isKompeten ? '✓' : '&nbsp;'; ?></span> &nbsp;KOMPETEN (K)
                    </div>
                    <div style="margin-bottom: 6px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect"><?php echo !$isKompeten ? '✓' : '&nbsp;'; ?></span> &nbsp;BELUM KOMPETEN (BK)
                    </div>
                    <div style="font-size: 7.5px; font-weight: bold; border-top: 1px solid #000; padding-top: 3px; line-height: 1.2;">
                        NOTE:<br>
                        WAJIB SEMUA ITEM EVALUASI (K), ADA YANG (BK), BERARTI SECARA KESIMPULAN (BK)
                    </div>
                </td>
            </tr>
        </table>

        <!-- Signatures Grid -->
        <table class="sig-grid">
            <tr>
                <td colspan="2" class="sig-header">Tanda Tangan</td>
                <td class="sig-header">Disetujui</td>
            </tr>
            <tr>
                <td style="width: 33.33%;" class="sig-title">Peserta/ Trainee</td>
                <td style="width: 33.33%;" class="sig-title">
                    <?php
                        $approverTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
                        $approverName = $logbook->trainer->name ?? $logbook->supervisor->name ?? '-';
                        $approverSid = $logbook->trainer->sid ?? $logbook->supervisor->sid ?? '-';
                        $approverSig = null;

                        if ($logbook->evaluation?->trainer_signature_path) {
                            $approver = $logbook->evaluation->trainer;
                            $approverTitle = match($approver->trainer_type) {
                                'pengawas' => 'Pengawas',
                                'operator_pendamping' => 'Operator Pendamping',
                                default => 'Instruktur',
                            };
                            $approverName = $approver->name;
                            $approverSid = $approver->sid;
                            $approverSig = $logbook->evaluation->trainer_signature_path;
                        } elseif ($logbook->pjo_signature_path) {
                            $approverTitle = 'Pengawas';
                            $approverName = $logbook->pengawasTrainer->name ?? $approverName;
                            $approverSid = $logbook->pengawasTrainer->sid ?? $approverSid;
                            $approverSig = $logbook->pjo_signature_path;
                        }
                    ?>
                    <?php echo e($approverTitle); ?>

                </td>
                <td style="width: 33.33%;" class="sig-title">Kabag OTDI/ LC</td>
            </tr>
            <tr>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->trainee?->signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->trainee->signature_path)); ?>" alt="Trainee signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainee->name ?? 'Ahmad Rian Syahputra'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainee->sid ?? '-'); ?></div>
                </td>
                <td>
                    <div class="sig-space">
                        <?php if($approverSig ?? null): ?>
                            <img src="<?php echo e(asset('storage/'.$approverSig)); ?>" alt="Approver signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($approverName); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($approverSid); ?></div>
                </td>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->training_centre_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->training_centre_signature_path)); ?>" alt="Kabag signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainingCentre->name ?? 'Kabag Training Centre'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainingCentre->sid ?? '-'); ?></div>
                </td>
            </tr>
        </table>

    </div>


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/print-body.blade.php ENDPATH**/ ?>