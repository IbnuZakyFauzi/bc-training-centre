<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use App\Models\Evaluasi1\Eval1Attendance;
use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1Exam;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi1\Eval1Material;
use App\Models\Evaluasi1\Eval1MaterialProgress;
use App\Models\Evaluasi1\Eval1ProctoringLog;
use App\Models\Evaluasi1\Eval1Question;
use App\Models\Evaluasi2\Eval2Certificate;
use App\Models\Evaluasi2\Eval2GerdasAssessment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Evaluasi1Dan2Seeder extends Seeder
{
    public function run(): void
    {
        $trainer = User::where('role', 'trainer')->first() ?? User::where('role', 'admin')->first();
        $trainees = User::where('role', 'trainee')->get();
        $firstTrainee = $trainees->first();

        // 1. EVALUASI 1: Materi LMS & Video Pembelajaran
        $m1 = Eval1Material::create([
            'title' => 'Pengenalan K3LH, Golden Rules & Rambu Tambang PT Berau Coal',
            'description' => 'Materi fundamental keselamatan kerja pertambangan, Golden Rules PT Berau Coal, dan pemahaman rambu-rambu operasional tambang.',
            'type' => 'video',
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'order' => 1,
            'min_duration_seconds' => 120,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $m2 = Eval1Material::create([
            'title' => 'Prosedur Baku P2H (Pemeriksaan & Perawatan Harian) Unit A2B',
            'description' => 'Langkah-langkah sistematis inspeksi keliling (walk around inspection) sebelum menyalakan mesin, pengecekan fluida, indikator cabin, dan pelaporan kerusakan (K3/Hazard).',
            'type' => 'video',
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'order' => 2,
            'min_duration_seconds' => 180,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $m3 = Eval1Material::create([
            'title' => 'Teknik Kontrol Dasar & Mekanisme Pengoperasian Aman Unit Alat Berat',
            'description' => 'Fungsi tuas kendali, pedal rem/akselerasi, retarder, transmisi otomatis/manual, dan titik buta (blind spots) di sekitar unit.',
            'type' => 'slide',
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'order' => 3,
            'min_duration_seconds' => 150,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $m4 = Eval1Material::create([
            'title' => 'Prosedur Tanggap Darurat & Etika Komunikasi Radio Dua Arah di Hauling Road',
            'description' => 'Tata cara evakuasi darurat, aktivasi fire suppression system, serta standar protokol radio dua arah di jalan tambang.',
            'type' => 'video',
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'order' => 4,
            'min_duration_seconds' => 120,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        // 2. EVALUASI 1: Post-Test Setiap Materi (Passing Score: Min. 80, Remedial jika < 80)
        // Modul 1 Post-Test
        $postTest1 = Eval1Exam::create([
            'title' => 'Post-Test Modul 1: K3LH, Golden Rules & Rambu Tambang',
            'type' => 'post_test',
            'material_id' => $m1->id,
            'description' => 'Evaluasi pemahaman K3LH, 10 Golden Rules PT Berau Coal, dan rambu keselamatan tambang. Syarat kelulusan minimal nilai 80. Jika < 80 wajib remedial.',
            'duration_minutes' => 15,
            'passing_score' => 80.00,
            'proctoring_enabled' => false,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $qModul1 = [
            [
                'question_text' => 'Siapa yang memiliki hak untuk melakukan STOP WORK AUTHORITY jika melihat kondisi berbahaya di area tambang PT Berau Coal?',
                'options' => [
                    ['key' => 'A', 'text' => 'Hanya Kepala Teknik Tambang (KTT)'],
                    ['key' => 'B', 'text' => 'Hanya Pengawas dan Instruktur Safety'],
                    ['key' => 'C', 'text' => 'Setiap orang / karyawan yang melihat potensi bahaya'],
                    ['key' => 'D', 'text' => 'Hanya Operator senior'],
                ],
                'correct_answer' => 'C',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Di bawah Golden Rules PT Berau Coal, apa konsekuensi dari pelanggaran isolasi energi (LOTO) atau bekerja tanpa APD wajib di zona merah?',
                'options' => [
                    ['key' => 'A', 'text' => 'Teguran lisan tanpa catatan'],
                    ['key' => 'B', 'text' => 'Sanksi tegas hingga pencabutan SIMPER dan pemutusan hubungan kerja'],
                    ['key' => 'C', 'text' => 'Membayar denda materi ke departemen safety'],
                    ['key' => 'D', 'text' => 'Pengurangan jam lembur selama 1 minggu'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Arti dari rambu segitiga berwarna kuning dengan simbol tanda seru di area tambang adalah:',
                'options' => [
                    ['key' => 'A', 'text' => 'Perintah wajib menggunakan APD'],
                    ['key' => 'B', 'text' => 'Larangan melintas bagi kendaraan umum'],
                    ['key' => 'C', 'text' => 'Peringatan waspada terhadap potensi bahaya di depan'],
                    ['key' => 'D', 'text' => 'Petunjuk arah menuju pit office'],
                ],
                'correct_answer' => 'C',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Berapa batas kecepatan maksimal (speed limit) yang diizinkan untuk unit alat berat di area disposal/dumping area?',
                'options' => [
                    ['key' => 'A', 'text' => '10 - 15 km/jam'],
                    ['key' => 'B', 'text' => '25 - 30 km/jam'],
                    ['key' => 'C', 'text' => '40 km/jam'],
                    ['key' => 'D', 'text' => 'Tidak ada batasan selama aman'],
                ],
                'correct_answer' => 'A',
                'score_weight' => 25.00,
            ],
        ];

        foreach ($qModul1 as $i => $q) {
            Eval1Question::create([
                'exam_id' => $postTest1->id,
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'score_weight' => $q['score_weight'],
                'order' => $i + 1,
            ]);
        }

        // Modul 2 Post-Test
        $postTest2 = Eval1Exam::create([
            'title' => 'Post-Test Modul 2: Prosedur Baku P2H Unit A2B',
            'type' => 'post_test',
            'material_id' => $m2->id,
            'description' => 'Evaluasi inspeksi harian keliling (walk around inspection) unit A2B. Syarat kelulusan minimal nilai 80. Jika < 80 wajib remedial.',
            'duration_minutes' => 15,
            'passing_score' => 80.00,
            'proctoring_enabled' => false,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $qModul2 = [
            [
                'question_text' => 'Apa tindakan pertama yang wajib dilakukan operator sebelum melakukan pemeriksaan keliling (P2H) di bawah unit?',
                'options' => [
                    ['key' => 'A', 'text' => 'Menyalakan mesin agar oli bersirkulasi'],
                    ['key' => 'B', 'text' => 'Memasang ganjal ban (wheel chock) dan memastikan parking brake aktif'],
                    ['key' => 'C', 'text' => 'Membunyikan klakson 3 kali'],
                    ['key' => 'D', 'text' => 'Mengosongkan muatan unit'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Kapan waktu pengecekan level oli mesin (engine oil dipstick) yang paling akurat?',
                'options' => [
                    ['key' => 'A', 'text' => 'Saat mesin sedang running pada RPM tinggi'],
                    ['key' => 'B', 'text' => 'Kondisi mesin mati, di permukaan rata, sebelum mesin pertama kali distart'],
                    ['key' => 'C', 'text' => 'Saat unit sedang menuruni tanjakan hauling'],
                    ['key' => 'D', 'text' => 'Tepat setelah mematikan mesin tanpa menunggu oli turun'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Jika ditemukan kebocoran fluida bertekanan tinggi pada selang hidrolik saat P2H, tindakan yang benar adalah:',
                'options' => [
                    ['key' => 'A', 'text' => 'Menyentuh sambungan selang dengan jari tangan untuk mendeteksi sumber kebocoran'],
                    ['key' => 'B', 'text' => 'Mengikat selang dengan kain/kawat dan tetap mengoperasikan unit'],
                    ['key' => 'C', 'text' => 'Jangan sentuh, pasang tag Out of Service/Danger Tag, laporkan pada form P2H dan pengawas'],
                    ['key' => 'D', 'text' => 'Menambah oli hidrolik terus menerus selama shift'],
                ],
                'correct_answer' => 'C',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Komponen apa saja yang wajib diperiksa operator saat inspeksi kabin dalam tahapan P2H?',
                'options' => [
                    ['key' => 'A', 'text' => 'Kaca spion, sabuk pengaman, panel indikator, radio komunikasi, dan wiper'],
                    ['key' => 'B', 'text' => 'Hanya sound system dan AC kabin'],
                    ['key' => 'C', 'text' => 'Hanya jok tempat duduk operator'],
                    ['key' => 'D', 'text' => 'Kelengkapan stiker sponsor'],
                ],
                'correct_answer' => 'A',
                'score_weight' => 25.00,
            ],
        ];

        foreach ($qModul2 as $i => $q) {
            Eval1Question::create([
                'exam_id' => $postTest2->id,
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'score_weight' => $q['score_weight'],
                'order' => $i + 1,
            ]);
        }

        // Modul 3 Post-Test
        $postTest3 = Eval1Exam::create([
            'title' => 'Post-Test Modul 3: Kontrol Dasar & Mekanisme Pengoperasian Aman',
            'type' => 'post_test',
            'material_id' => $m3->id,
            'description' => 'Evaluasi fungsi kontrol kendali, transmisi, retarder, dan blind spot unit A2B. Syarat kelulusan minimal nilai 80. Jika < 80 wajib remedial.',
            'duration_minutes' => 15,
            'passing_score' => 80.00,
            'proctoring_enabled' => false,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $qModul3 = [
            [
                'question_text' => 'Berapa kali bunyi isyarat klakson yang benar sebelum menyalakan mesin unit di tambang PT Berau Coal?',
                'options' => [
                    ['key' => 'A', 'text' => '1 kali panjang'],
                    ['key' => 'B', 'text' => '1 kali pendek'],
                    ['key' => 'C', 'text' => '2 kali pendek'],
                    ['key' => 'D', 'text' => '3 kali pendek'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Isyarat klakson 2 kali pendek pada unit A2B digunakan untuk menandakan bahwa unit akan:',
                'options' => [
                    ['key' => 'A', 'text' => 'Maju'],
                    ['key' => 'B', 'text' => 'Mundur'],
                    ['key' => 'C', 'text' => 'Mematikan mesin'],
                    ['key' => 'D', 'text' => 'Meminta jalan darurat'],
                ],
                'correct_answer' => 'A',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Fungsi utama dari retarder brake pada unit dump truck tambang adalah:',
                'options' => [
                    ['key' => 'A', 'text' => 'Mengunci roda saat parkir semalaman'],
                    ['key' => 'B', 'text' => 'Memperlambat laju unit secara konstan di turunan tanpa membuat brake pad panas berlebih'],
                    ['key' => 'C', 'text' => 'Meningkatkan akselerasi saat muatan penuh'],
                    ['key' => 'D', 'text' => 'Mengganti fungsi kemudi roda depan'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Di mana letak titik buta (blind spot) terbesar pada unit haul truck kategori CAT 777 atau Komatsu HD785?',
                'options' => [
                    ['key' => 'A', 'text' => 'Hanya di sisi kiri kabin'],
                    ['key' => 'B', 'text' => 'Sisi depan bawah kabin dan sisi kanan belakang unit'],
                    ['key' => 'C', 'text' => 'Hanya di atas atap kabin'],
                    ['key' => 'D', 'text' => 'Tidak ada blind spot karena kaca spion lengkap'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
        ];

        foreach ($qModul3 as $i => $q) {
            Eval1Question::create([
                'exam_id' => $postTest3->id,
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'score_weight' => $q['score_weight'],
                'order' => $i + 1,
            ]);
        }

        // Modul 4 Post-Test
        $postTest4 = Eval1Exam::create([
            'title' => 'Post-Test Modul 4: Tanggap Darurat & Komunikasi Radio Hauling',
            'type' => 'post_test',
            'material_id' => $m4->id,
            'description' => 'Evaluasi prosedur tanggap darurat, aktivasi fire suppression, dan komunikasi radio. Syarat kelulusan minimal nilai 80. Jika < 80 wajib remedial.',
            'duration_minutes' => 15,
            'passing_score' => 80.00,
            'proctoring_enabled' => false,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $qModul4 = [
            [
                'question_text' => 'Jika terjadi kebakaran di kompartemen mesin saat beroperasi, langkah pertama aktivasi fire suppression manual adalah:',
                'options' => [
                    ['key' => 'A', 'text' => 'Langsung melompat dari cabin unit'],
                    ['key' => 'B', 'text' => 'Matikan mesin, aktifkan rem parkir, tarik pin pengaman lalu tekan tuas aktuator fire suppression'],
                    ['key' => 'C', 'text' => 'Menyiram air radiator ke mesin'],
                    ['key' => 'D', 'text' => 'Maju dengan kecepatan tinggi menuju workshop'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Berapa jarak aman beriringan minimal antar unit alat berat di jalan hauling dalam kondisi berdebu atau hujan?',
                'options' => [
                    ['key' => 'A', 'text' => '20 meter'],
                    ['key' => 'B', 'text' => '30 meter'],
                    ['key' => 'C', 'text' => '50 meter'],
                    ['key' => 'D', 'text' => '70 meter'],
                ],
                'correct_answer' => 'C',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Panggilan radio darurat standar internasional dan operasional tambang Berau Coal untuk kondisi emergency jiwa/kebakaran adalah:',
                'options' => [
                    ['key' => 'A', 'text' => 'Panggilan Halo-Halo 3 kali'],
                    ['key' => 'B', 'text' => 'EMERGENCY... EMERGENCY... EMERGENCY...'],
                    ['key' => 'C', 'text' => 'BREAK... BREAK...'],
                    ['key' => 'D', 'text' => 'INFO PIT... INFO PIT...'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
            [
                'question_text' => 'Saat radio dua arah diumumkan kondisi EMERGENCY di channel tambang, seluruh pengguna radio lain wajib:',
                'options' => [
                    ['key' => 'A', 'text' => 'Menjawab secara bersamaan untuk konfirmasi'],
                    ['key' => 'B', 'text' => 'Segera RADIO SILENCE (diam/tidak menggunakan frekuensi radio) sampai penanganan selesai'],
                    ['key' => 'C', 'text' => 'Mematikan radio komunikasi'],
                    ['key' => 'D', 'text' => 'Mengganti frekuensi radio tanpa izin dispatcher'],
                ],
                'correct_answer' => 'B',
                'score_weight' => 25.00,
            ],
        ];

        foreach ($qModul4 as $i => $q) {
            Eval1Question::create([
                'exam_id' => $postTest4->id,
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'score_weight' => $q['score_weight'],
                'order' => $i + 1,
            ]);
        }

        // 3. EVALUASI 1: Pre-Test & Ujian Akhir CBT (SIMPER L1)
        $preTest = Eval1Exam::create([
            'title' => 'Pre-Test In-Class: Teori Dasar Keselamatan & Pengoperasian A2B',
            'type' => 'pre_test',
            'material_id' => null,
            'description' => 'Evaluasi awal untuk mengukur pemahaman dasar trainee sebelum mengikuti materi pembelajaran in-class.',
            'duration_minutes' => 30,
            'passing_score' => 70.00,
            'proctoring_enabled' => false,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        $examPost = Eval1Exam::create([
            'title' => 'Ujian Evaluasi 1 (CBT): Kompetensi In-Class & Kelayakan SIMPER L1',
            'type' => 'evaluasi_1',
            'material_id' => null,
            'description' => 'Ujian akhir teori in-class berstandar PT MTL / PT Berau Coal. Terkunci hingga seluruh Post-Test materi lulus minimal 80. Dilengkapi pengawasan Camera Proctoring real-time.',
            'duration_minutes' => 45,
            'passing_score' => 80.00, // Standar SOP Evaluasi 1: Skor minimal 80
            'proctoring_enabled' => true,
            'is_active' => true,
            'created_by' => $trainer?->id,
        ]);

        // Soal Ujian Akhir CBT Gabungan
        $finalQuestions = array_merge($qModul1, $qModul2, $qModul3, $qModul4);
        foreach ($finalQuestions as $i => $q) {
            Eval1Question::create([
                'exam_id' => $examPost->id,
                'question_text' => $q['question_text'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'score_weight' => 100.0 / count($finalQuestions),
                'order' => $i + 1,
            ]);

            if ($i < 10) {
                Eval1Question::create([
                    'exam_id' => $preTest->id,
                    'question_text' => $q['question_text'],
                    'options' => $q['options'],
                    'correct_answer' => $q['correct_answer'],
                    'score_weight' => 10.00,
                    'order' => $i + 1,
                ]);
            }
        }

        // Sample progress & exam attempt untuk trainee pertama (Lulus Evaluasi 1)
        if ($firstTrainee) {
            foreach ([$m1, $m2, $m3, $m4] as $mat) {
                Eval1MaterialProgress::create([
                    'user_id' => $firstTrainee->id,
                    'material_id' => $mat->id,
                    'watch_time_seconds' => $mat->min_duration_seconds,
                    'is_completed' => true,
                    'completed_at' => Carbon::now()->subDays(2),
                ]);
            }

            // Post-test attempts untuk Trainee 1 (Semua Lulus >= 80)
            foreach ([$postTest1, $postTest2, $postTest3, $postTest4] as $idx => $pt) {
                Eval1ExamAttempt::create([
                    'exam_id' => $pt->id,
                    'user_id' => $firstTrainee->id,
                    'started_at' => Carbon::now()->subDays(2)->addHours($idx * 2),
                    'submitted_at' => Carbon::now()->subDays(2)->addHours($idx * 2)->addMinutes(12),
                    'answers' => [1 => 'C', 2 => 'B', 3 => 'C', 4 => 'A'],
                    'total_score' => 100.00,
                    'is_passed' => true,
                    'status' => 'completed',
                    'suspicious_flags_count' => 0,
                ]);
            }

            // Sample Presensi Online In-Class LMS Trainee
            Eval1Attendance::create([
                'user_id' => $firstTrainee->id,
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'clock_in' => '07:55:00',
                'clock_out' => '16:05:00',
                'total_hours' => 8.00,
                'topic' => '1. Kebijakan dan Pengetahuan K3L',
                'status' => 'hadir',
                'location' => 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => 'Materi regulasi K3 pertambangan & Golden Rules Berau Coal.',
                'verified_by' => $trainer?->id,
                'verified_at' => Carbon::now()->subDays(3)->setTime(16, 30),
            ]);

            Eval1Attendance::create([
                'user_id' => $firstTrainee->id,
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'clock_in' => '07:58:00',
                'clock_out' => '16:00:00',
                'total_hours' => 8.00,
                'topic' => '2. Teknis Pengetahuan Alat',
                'status' => 'hadir',
                'location' => 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => 'Walk around inspection P2H dan fungsi tuas kendali alat berat.',
                'verified_by' => $trainer?->id,
                'verified_at' => Carbon::now()->subDays(2)->setTime(16, 30),
            ]);

            Eval1Attendance::create([
                'user_id' => $firstTrainee->id,
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'clock_in' => '07:50:00',
                'clock_out' => '16:10:00',
                'total_hours' => 8.00,
                'topic' => '3. Defensive Driving termasuk pengendalian unit dalam kondisi emergency',
                'status' => 'hadir',
                'location' => 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => 'Prosedur evakuasi darurat, blind spot, dan komunikasi radio tambang.',
                'verified_by' => $trainer?->id,
                'verified_at' => Carbon::now()->subDays(1)->setTime(16, 30),
            ]);

            $attempt = Eval1ExamAttempt::create([
                'exam_id' => $examPost->id,
                'user_id' => $firstTrainee->id,
                'started_at' => Carbon::now()->subDays(1)->setTime(9, 0),
                'submitted_at' => Carbon::now()->subDays(1)->setTime(9, 35),
                'answers' => [1 => 'C', 2 => 'B', 3 => 'B', 4 => 'B', 5 => 'C'],
                'total_score' => 100.00,
                'is_passed' => true,
                'status' => 'completed',
                'suspicious_flags_count' => 0,
            ]);

            Eval1ProctoringLog::create([
                'attempt_id' => $attempt->id,
                'user_id' => $firstTrainee->id,
                'event_type' => 'periodic_capture',
                'event_details' => 'Webcam snapshot: Trainee fokus pada layar ujian.',
                'created_at' => Carbon::now()->subDays(1)->setTime(9, 10),
            ]);

            Eval1ProctoringLog::create([
                'attempt_id' => $attempt->id,
                'user_id' => $firstTrainee->id,
                'event_type' => 'periodic_capture',
                'event_details' => 'Webcam snapshot: Kondisi pencahayaan dan wajah valid.',
                'created_at' => Carbon::now()->subDays(1)->setTime(9, 20),
            ]);

            // Sertifikat SIMPER L1
            Eval1Certificate::create([
                'user_id' => $firstTrainee->id,
                'exam_attempt_id' => $attempt->id,
                'certificate_number' => Eval1Certificate::generateCertificateNumber($firstTrainee),
                'license_type' => 'SIMPER L1 (Trainee In-Class LMS & CBT)',
                'issue_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'valid_until' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'score' => 100.00,
                'status' => 'valid',
                'notes' => 'Lulus Ujian Evaluasi 1 Berbasis Camera Proctoring tanpa pelanggaran.',
            ]);

            // EVALUASI 2: Penilaian Gerakan Dasar (Gerdas)
            $cat = EquipmentCategory::first();
            $gerdas = Eval2GerdasAssessment::create([
                'trainee_id' => $firstTrainee->id,
                'trainer_id' => $trainer?->id ?? 1,
                'equipment_category_id' => $cat?->id,
                'unit_model' => 'Komatsu PC2000-8 / CAT 777F',
                'unit_number' => 'EX-2001',
                'lokasi_kerja' => 'Training Ground Pit BMO 1',
                'tanggal_penilaian' => Carbon::now()->format('Y-m-d'),
                'shift' => 'day',
                'criteria_scores' => [
                    [
                        'aspek' => 'Pemeriksaan Keliling & Prosedur P2H Awal',
                        'kriteria' => 'Pemeriksaan oli, hidrolik, kebocoran, kelayakan tire/track, pin ganjal ban',
                        'bobot' => 20,
                        'nilai' => 88,
                        'status' => 'K',
                    ],
                    [
                        'aspek' => 'Kontrol Dasar & Penggunaan Sabuk Pengaman',
                        'kriteria' => 'Pengaturan kursi, spion, klakson isyarat, start engine aman & transmisi',
                        'bobot' => 20,
                        'nilai' => 90,
                        'status' => 'K',
                    ],
                    [
                        'aspek' => 'Manuver Gerakan Dasar (Maju, Mundur, Belok Sempit)',
                        'kriteria' => 'Kelancaran akselerasi, belokan radius aman, pengamatan blind spot, dan perataan posisi',
                        'bobot' => 30,
                        'nilai' => 85,
                        'status' => 'K',
                    ],
                    [
                        'aspek' => 'Prosedur Pengereman Darurat & Parkir Aman',
                        'kriteria' => 'Aplikasi service brake, retarder, parking brake, penempatan ground bucket/blade, dan shutdown',
                        'bobot' => 15,
                        'nilai' => 87,
                        'status' => 'K',
                    ],
                    [
                        'aspek' => 'Kepatuhan K3LH & Disiplin Lingkungan Tambang',
                        'kriteria' => 'Pemakaian APD lengkap, rambu batas kecepatan gerak dasar (max 10-15 km/h)',
                        'bobot' => 15,
                        'nilai' => 92,
                        'status' => 'K',
                    ],
                ],
                'score_p2h' => 88.00,
                'score_basic_control' => 90.00,
                'score_maneuver' => 85.00,
                'score_safety_k3' => 92.00,
                'total_score' => 87.80,
                'kesimpulan' => 'kompeten',
                'catatan_instruktur' => 'Trainee menguasai gerakan dasar unit dengan tenang, koordinasi tangan dan kaki sangat baik, serta patuh pada SOP K3LH.',
                'instruktur_signature_path' => $trainer?->signature_path,
                'status' => 'completed',
            ]);

            // Sertifikat SIMPER L2
            Eval2Certificate::create([
                'trainee_id' => $firstTrainee->id,
                'assessment_id' => $gerdas->id,
                'certificate_number' => Eval2Certificate::generateCertificateNumber($firstTrainee),
                'license_type' => 'SIMPER L2 (Praktik Gerakan Dasar / Gerdas)',
                'issue_date' => Carbon::now()->format('Y-m-d'),
                'valid_until' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'total_score' => 87.80,
                'status' => 'valid',
                'notes' => 'Kompeten dalam Uji Gerakan Dasar A2B di bawah pengawasan Instruktur TC.',
            ]);
        }

        // Sample Trainee kedua (Simulasi Remedial Post-Test & Evaluasi Terkunci)
        $secondTrainee = $trainees->skip(1)->first();
        if ($secondTrainee) {
            // Modul 1: Selesai & Lulus Post-Test (Skor: 100)
            Eval1MaterialProgress::create([
                'user_id' => $secondTrainee->id,
                'material_id' => $m1->id,
                'watch_time_seconds' => $m1->min_duration_seconds,
                'is_completed' => true,
                'completed_at' => Carbon::now()->subDays(1),
            ]);
            Eval1ExamAttempt::create([
                'exam_id' => $postTest1->id,
                'user_id' => $secondTrainee->id,
                'started_at' => Carbon::now()->subDays(1)->setTime(10, 0),
                'submitted_at' => Carbon::now()->subDays(1)->setTime(10, 12),
                'answers' => [1 => 'C', 2 => 'B', 3 => 'C', 4 => 'A'],
                'total_score' => 100.00,
                'is_passed' => true,
                'status' => 'completed',
            ]);

            // Modul 2: Selesai, tapi Post-Test GAGAL / REMEDIAL (Skor: 50.00 < 80)
            Eval1MaterialProgress::create([
                'user_id' => $secondTrainee->id,
                'material_id' => $m2->id,
                'watch_time_seconds' => $m2->min_duration_seconds,
                'is_completed' => true,
                'completed_at' => Carbon::now()->subHours(6),
            ]);
            Eval1ExamAttempt::create([
                'exam_id' => $postTest2->id,
                'user_id' => $secondTrainee->id,
                'started_at' => Carbon::now()->subHours(5),
                'submitted_at' => Carbon::now()->subHours(5)->addMinutes(10),
                'answers' => [1 => 'B', 2 => 'A', 3 => 'A', 4 => 'A'], // hanya benar nomor 1 & 4 -> 50%
                'total_score' => 50.00,
                'is_passed' => false, // REMEDIAL!
                'status' => 'completed',
            ]);

            // Presensi untuk Trainee 2
            Eval1Attendance::create([
                'user_id' => $secondTrainee->id,
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'clock_in' => '08:00:00',
                'clock_out' => '16:00:00',
                'total_hours' => 8.00,
                'topic' => '1. Kebijakan dan Pengetahuan K3L',
                'status' => 'hadir',
                'location' => 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => 'Trainee aktif menyimak.',
                'verified_by' => $trainer?->id,
                'verified_at' => Carbon::now()->subDays(1)->setTime(16, 30),
            ]);
        }
    }
}
