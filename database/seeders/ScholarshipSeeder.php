<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examples = [
            [
                'slug' => 'mext-research-students',
                'title' => 'Beasiswa Pemerintah Jepang MEXT — Research Students',
                'provider' => 'Ministry of Education, Culture, Sports, Science and Technology (MEXT)',
                'country' => 'Jepang', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'none', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => 3.00, 'max_age' => 34, 'open_days' => -7, 'deadline_days' => 165,
                'official_url' => 'https://www.studyinjapan.go.jp/en/planning/scholarships/',
                'description' => 'Pendanaan pemerintah Jepang untuk studi riset pascasarjana di universitas Jepang. Kandidat mendaftar melalui jalur Kedutaan Besar Jepang atau rekomendasi universitas. Sertifikat IELTS/TOEFL tidak selalu diminta, tetapi seleksi dapat mencakup ujian bahasa Jepang atau Inggris.',
            ],
            [
                'slug' => 'australia-awards-indonesia',
                'title' => 'Australia Awards Scholarships — Indonesia',
                'provider' => 'Australian Department of Foreign Affairs and Trade (DFAT)',
                'country' => 'Australia', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => 2.90, 'max_age' => 42, 'open_days' => -7, 'deadline_days' => 135,
                'official_url' => 'https://www.australiaawardsindonesia.org/',
                'description' => 'Beasiswa pemerintah Australia untuk calon pemimpin Indonesia. Pendanaan mencakup biaya studi dan dukungan terkait sesuai ketentuan program. Penerima wajib kembali ke Indonesia sekurang-kurangnya dua tahun setelah menyelesaikan studi.',
            ],
            [
                'slug' => 'chevening-scholarship',
                'title' => 'Chevening Scholarship',
                'provider' => 'UK Foreign, Commonwealth & Development Office',
                'country' => 'Britania Raya', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => null, 'max_age' => null, 'open_days' => 270, 'deadline_days' => 305,
                'official_url' => 'https://www.chevening.org/scholarships/',
                'description' => 'Beasiswa untuk program magister satu tahun di Britania Raya bagi kandidat dengan pengalaman profesional dan potensi kepemimpinan. Penerima wajib kembali ke negara asal setidaknya dua tahun setelah masa beasiswa berakhir. Persyaratan pengalaman dan aplikasi dapat berubah tiap siklus.',
            ],
            [
                'slug' => 'erasmus-mundus-joint-masters',
                'title' => 'Erasmus Mundus Joint Masters',
                'provider' => 'European Commission dan konsorsium universitas Eropa',
                'country' => 'Eropa', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 55,
                'official_url' => 'https://erasmus-plus.ec.europa.eu/opportunities/opportunities-for-individuals/students/erasmus-mundus-joint-masters',
                'description' => 'Program magister bersama yang diselenggarakan oleh konsorsium universitas di beberapa negara Eropa. Beasiswa penuh tersedia pada program dan jalur seleksi tertentu. Pelamar memilih program studi, lalu mengikuti persyaratan dan jadwal konsorsium masing-masing.',
            ],
            [
                'slug' => 'daad-epos',
                'title' => 'DAAD EPOS — Development-Related Postgraduate Courses',
                'provider' => 'German Academic Exchange Service (DAAD)',
                'country' => 'Jerman', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => 2.75, 'max_age' => 36, 'open_days' => -7, 'deadline_days' => 120,
                'official_url' => 'https://www.daad.de/en/studying-in-germany/scholarships/daad-scholarships/',
                'description' => 'Beasiswa DAAD untuk sejumlah program pascasarjana berorientasi pembangunan di Jerman. Seleksi umumnya mempertimbangkan latar pendidikan, pengalaman profesional relevan, dan kontribusi kandidat. Setiap program memiliki batas usia, bukti bahasa, dan tenggat aplikasi tersendiri.',
            ],
            [
                'slug' => 'fulbright-masters-indonesia',
                'title' => 'Fulbright Master’s Degree Program',
                'provider' => 'AMINEF dan Fulbright Foreign Scholarship Board',
                'country' => 'Amerika Serikat', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => 3.00, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 75,
                'official_url' => 'https://www.aminef.or.id/grants-for-indonesians/fulbright-programs/',
                'description' => 'Program Fulbright untuk warga negara Indonesia yang ingin menempuh gelar magister di Amerika Serikat. Penghargaan meliputi dukungan studi sesuai ketentuan program dan kompetisi tahunan. Peserta pertukaran J-1 yang tercakup aturan wajib memenuhi ketentuan residensi atau pengabdian di negara asal.',
            ],
            [
                'slug' => 'fulbright-doctoral-indonesia',
                'title' => 'Fulbright Doctoral Degree Program',
                'provider' => 'AMINEF dan Fulbright Foreign Scholarship Board',
                'country' => 'Amerika Serikat', 'degree_level' => 'S3', 'funding_type' => 'fully',
                'language_test' => 'toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => 3.00, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 90,
                'official_url' => 'https://www.aminef.or.id/grants-for-indonesians/fulbright-programs/',
                'description' => 'Pendanaan kompetitif Fulbright bagi warga negara Indonesia untuk studi doktoral di Amerika Serikat. Pelamar memerlukan rekam akademik yang kuat dan proposal riset yang jelas. Ketentuan program pertukaran J-1 dapat mewajibkan penerima memenuhi aturan tinggal atau pengabdian di negara asal.',
            ],
            [
                'slug' => 'turkiye-burslari',
                'title' => 'Türkiye Scholarships',
                'provider' => 'Presidency for Turks Abroad and Related Communities (YTB)',
                'country' => 'Turki', 'degree_level' => 'S1', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => 3.00, 'max_age' => 21, 'open_days' => -7, 'deadline_days' => 65,
                'official_url' => 'https://www.turkiyeburslari.gov.tr/',
                'description' => 'Program beasiswa pemerintah Türkiye bagi pelamar internasional untuk studi sarjana. Pilihan program, kebutuhan tahun persiapan bahasa, dan syarat umur ditetapkan berdasarkan jenjang dan jurusan. Pendaftaran dilakukan melalui portal resmi Türkiye Scholarships.',
            ],
            [
                'slug' => 'global-korea-scholarship-undergraduate',
                'title' => 'Global Korea Scholarship — Undergraduate',
                'provider' => 'National Institute for International Education (NIIED)',
                'country' => 'Korea Selatan', 'degree_level' => 'S1', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => 2.64, 'max_age' => 25, 'open_days' => 300, 'deadline_days' => 330,
                'official_url' => 'https://www.studyinkorea.go.kr/',
                'description' => 'Beasiswa pemerintah Korea Selatan untuk jenjang sarjana melalui jalur Kedutaan Besar atau universitas. Beasiswa mencakup dukungan pendidikan sesuai panduan NIIED dan ketentuan tahunan. Jadwal, pilihan universitas, dan standar akademik harus diperiksa pada pengumuman siklus yang berlaku.',
            ],
            [
                'slug' => 'global-korea-scholarship-graduate',
                'title' => 'Global Korea Scholarship — Graduate',
                'provider' => 'National Institute for International Education (NIIED)',
                'country' => 'Korea Selatan', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => 2.64, 'max_age' => 40, 'open_days' => 270, 'deadline_days' => 300,
                'official_url' => 'https://www.studyinkorea.go.kr/',
                'description' => 'Program pendanaan pemerintah Korea Selatan untuk studi magister dan doktoral melalui jalur yang tersedia. Kandidat mengikuti seleksi NIIED, Kedutaan Besar, atau universitas sesuai pengumuman tahun berjalan. Persyaratan TOPIK atau bahasa lain bergantung pada program dan tahap studi.',
            ],
            [
                'slug' => 'stipendium-hungaricum',
                'title' => 'Stipendium Hungaricum',
                'provider' => 'Tempus Public Foundation',
                'country' => 'Hungaria', 'degree_level' => 'S1', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 45,
                'official_url' => 'https://stipendiumhungaricum.hu/',
                'description' => 'Beasiswa Pemerintah Hungaria untuk pelamar dari negara mitra, dengan pilihan jenjang dan program berbahasa Inggris atau Hungaria. Dokumen akademik dan pembuktian kemampuan bahasa mengikuti persyaratan universitas dan program studi yang dipilih.',
            ],
            [
                'slug' => 'swiss-government-excellence',
                'title' => 'Swiss Government Excellence Scholarships',
                'provider' => 'Swiss Federal Commission for Scholarships (FCS)',
                'country' => 'Swiss', 'degree_level' => 'S3', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 35,
                'official_url' => 'https://www.sbfi.admin.ch/en/swiss-government-excellence-scholarships',
                'description' => 'Beasiswa riset dan pascasarjana pemerintah Swiss untuk peneliti internasional. Ketersediaan jenis beasiswa dan kelayakan negara asal ditentukan setiap tahun. Pelamar biasanya memerlukan proposal riset dan, untuk jalur terkait, dukungan calon supervisor atau institusi Swiss.',
            ],
            [
                'slug' => 'eiffel-excellence-scholarship',
                'title' => 'France Excellence Eiffel Scholarship',
                'provider' => 'Ministry for Europe and Foreign Affairs, France',
                'country' => 'Prancis', 'degree_level' => 'S2', 'funding_type' => 'partial',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => 29, 'open_days' => -7, 'deadline_days' => 50,
                'official_url' => 'https://www.campusfrance.org/en/the-eiffel-scholarship-program',
                'description' => 'Program beasiswa pemerintah Prancis untuk bidang studi prioritas pada jenjang magister dan doktoral. Institusi pendidikan Prancis mengajukan nominasi pelamar; calon mahasiswa perlu menghubungi universitas yang dituju jauh sebelum batas pengajuan nasional.',
            ],
            [
                'slug' => 'manaaki-new-zealand-scholarships',
                'title' => 'Manaaki New Zealand Scholarships',
                'provider' => 'New Zealand Ministry of Foreign Affairs and Trade (MFAT)',
                'country' => 'Selandia Baru', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 100,
                'official_url' => 'https://www.nzscholarships.govt.nz/',
                'description' => 'Beasiswa pemerintah Selandia Baru untuk warga negara dari negara mitra yang memenuhi kriteria pembangunan dan akademik. Penerima diharapkan pulang ke negara asal setidaknya dua tahun setelah menyelesaikan studi untuk menerapkan keahliannya.',
            ],
            [
                'slug' => 'lpdp-reguler-magister',
                'title' => 'Beasiswa LPDP Reguler — Magister',
                'provider' => 'Lembaga Pengelola Dana Pendidikan (LPDP), Kementerian Keuangan RI',
                'country' => 'Indonesia', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => 3.00, 'max_age' => 35, 'open_days' => -7, 'deadline_days' => 80,
                'official_url' => 'https://lpdp.kemenkeu.go.id/beasiswa/',
                'description' => 'Beasiswa pemerintah Indonesia untuk studi magister di perguruan tinggi dalam atau luar negeri yang memenuhi ketentuan LPDP. Penerima wajib menyelesaikan studi dan menjalankan kewajiban kembali serta kontribusi sesuai perjanjian beasiswa yang berlaku.',
            ],
            [
                'slug' => 'lpdp-reguler-doktor',
                'title' => 'Beasiswa LPDP Reguler — Doktor',
                'provider' => 'Lembaga Pengelola Dana Pendidikan (LPDP), Kementerian Keuangan RI',
                'country' => 'Indonesia', 'degree_level' => 'S3', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => true, 'return_duration_years' => 2,
                'min_gpa' => 3.25, 'max_age' => 40, 'open_days' => -7, 'deadline_days' => 95,
                'official_url' => 'https://lpdp.kemenkeu.go.id/beasiswa/',
                'description' => 'Pendanaan studi doktoral LPDP bagi warga negara Indonesia dengan rekam akademik dan rencana kontribusi yang kuat. Persyaratan usia, kemampuan bahasa, universitas tujuan, serta kewajiban setelah studi mengikuti panduan dan perjanjian pada tahun pendaftaran.',
            ],
            [
                'slug' => 'orange-tulip-scholarship',
                'title' => 'Orange Tulip Scholarship',
                'provider' => 'Nuffic dan institusi pendidikan tinggi Belanda yang berpartisipasi',
                'country' => 'Belanda', 'degree_level' => 'S2', 'funding_type' => 'partial',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 60,
                'official_url' => 'https://www.studyinnl.org/finances/orange-tulip-scholarship-programme',
                'description' => 'Skema beasiswa institusi untuk kandidat dari negara yang memenuhi syarat, termasuk Indonesia pada program tertentu. Nilai bantuan, jenjang, jurusan, dokumen, dan tenggat berbeda menurut institusi peserta; cek daftar program yang masih membuka penerimaan.',
            ],
            [
                'slug' => 'taiwan-scholarship',
                'title' => 'Taiwan Scholarship Program',
                'provider' => 'Ministry of Education, Taiwan',
                'country' => 'Taiwan', 'degree_level' => 'S1', 'funding_type' => 'partial',
                'language_test' => 'none', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => null, 'open_days' => -7, 'deadline_days' => 125,
                'official_url' => 'https://www.roc-taiwan.org/id_en/cat/15.html',
                'description' => 'Beasiswa pemerintah Taiwan untuk pelamar internasional di perguruan tinggi Taiwan. Pada program berbahasa Mandarin, bukti kemampuan bahasa Mandarin dapat menggantikan IELTS/TOEFL; program berbahasa Inggris dapat meminta bukti bahasa Inggris. Periksa persyaratan universitas tujuan.',
            ],
            [
                'slug' => 'chinese-government-scholarship',
                'title' => 'Chinese Government Scholarship (CSC)',
                'provider' => 'China Scholarship Council',
                'country' => 'Tiongkok', 'degree_level' => 'S2', 'funding_type' => 'fully',
                'language_test' => 'waiver', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => 35, 'open_days' => -7, 'deadline_days' => 55,
                'official_url' => 'https://www.campuschina.org/',
                'description' => 'Beasiswa Pemerintah Tiongkok untuk pelajar internasional pada berbagai jenjang dan universitas. Pilihan program dapat menggunakan bahasa Mandarin atau bahasa Inggris. Batas usia, tes bahasa, kebutuhan supervisor, dan mekanisme pendaftaran bergantung pada jalur yang dipilih.',
            ],
            [
                'slug' => 'brunei-darussalam-government-scholarship',
                'title' => 'Brunei Darussalam Government Scholarship',
                'provider' => 'Ministry of Foreign Affairs, Brunei Darussalam',
                'country' => 'Brunei Darussalam', 'degree_level' => 'S1', 'funding_type' => 'fully',
                'language_test' => 'ielts_or_toefl', 'return_obligation' => false, 'return_duration_years' => null,
                'min_gpa' => null, 'max_age' => 25, 'open_days' => -7, 'deadline_days' => 70,
                'official_url' => 'https://www.mfa.gov.bn/Pages/scholarship.aspx',
                'description' => 'Beasiswa pemerintah Brunei Darussalam bagi pelamar internasional untuk studi di universitas yang ditunjuk. Cakupan beasiswa, batas usia, bukti kemampuan bahasa, dan daftar program mengikuti pengumuman resmi untuk siklus pendaftaran terbaru.',
            ],
        ];

        foreach ($examples as $example) {
            $openDays = $example['open_days'];
            $deadlineDays = $example['deadline_days'];
            unset($example['open_days'], $example['deadline_days']);

            Scholarship::updateOrCreate(
                ['slug' => $example['slug']],
                $example + [
                    'open_date' => today()->addDays($openDays),
                    'deadline' => today()->addDays($deadlineDays),
                    'is_active' => true,
                ]
            );
        }
    }
}
