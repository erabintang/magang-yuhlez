<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'section_key' => 'hero',
                'title' => 'Hero',
                'sort_order' => 0,
                'content' => [
                    'title' => 'From Useless to YUHLEZ',
                    'subtitle' => 'The Best Solution for Website & Web Apps',
                    'description' => 'Membangun solusi digital untuk membantu bisnis dan organisasi beradaptasi dengan perkembangan teknologi, dari Tegal, sejak 2021.',
                    'cta_primary_text' => 'Lihat Program Magang',
                    'cta_primary_url' => '/magang',
                    'cta_secondary_text' => 'Masuk dengan Google',
                    'cta_secondary_url' => '/login',
                ],
            ],
            [
                'section_key' => 'about',
                'title' => 'Tentang',
                'sort_order' => 1,
                'content' => [
                    'subtitle' => 'Tentang',
                    'heading' => 'Software house dari Tegal untuk transformasi digital',
                    'description' => 'CV Talang Digital Indonesia atau YUHLEZ Software House melahirkan karya-karya inovatif di bidang sistem informasi manajemen, bisnis, dan pemerintah.',
                    'vision' => 'Menjadi roda penggerak sekaligus perantara transformasi digital di Indonesia.',
                    'mission_items' => [
                        'Menyiapkan dan menyediakan infrastruktur transformasi digital',
                        'Menerapkan ekosistem dan platform digital',
                        'Edukasi transformasi digital',
                    ],
                ],
            ],
            [
                'section_key' => 'team',
                'title' => 'Tim',
                'sort_order' => 2,
                'content' => [
                    'items' => [
                        ['name' => 'Adjiemas Matijevic', 'role' => 'CEO', 'focus' => 'Web Developer', 'photo' => 'brand/team/adjiemas.png'],
                        ['name' => 'Ratono', 'role' => 'CTO', 'focus' => 'Programmer Analyst', 'photo' => 'brand/team/ratono.png'],
                        ['name' => 'Cabit Amrulloh', 'role' => 'Graphic Designer', 'focus' => 'Design & Branding', 'photo' => 'brand/team/cabit.png'],
                    ],
                ],
            ],
            [
                'section_key' => 'services',
                'title' => 'Layanan',
                'sort_order' => 3,
                'content' => [
                    'items' => [
                        ['title' => 'Web Design', 'description' => 'Membuat dan menyesuaikan website sesuai keinginan dan kebutuhan Anda.'],
                        ['title' => 'Web Apps', 'description' => 'Membangun sistem aplikasi berbasis website untuk kebutuhan bisnis Anda.'],
                        ['title' => 'IT Consultant', 'description' => 'Menyerahkan masalah IT kepada YUHLEZ dengan dukungan 24/7.'],
                        ['title' => 'Extend Project', 'description' => 'Proyek skala besar: IoT, aplikasi mobile, dan integrasi sistem.'],
                        ['title' => 'Mini ERP · POS · Inventory', 'description' => 'Sistem manajemen bisnis: kasir digital, stok, pembukuan, dan laporan.'],
                        ['title' => 'Database & Infrastruktur', 'description' => 'Perancangan database, pengadaan hardware, dan dukungan infrastruktur IT.'],
                    ],
                ],
            ],
            [
                'section_key' => 'process',
                'title' => 'Cara Kerja',
                'sort_order' => 6,
                'content' => [
                    'subtitle' => 'Cara kerja',
                    'heading' => 'Dari konsultasi hingga peluncuran',
                    'description' => 'Proses yang jelas dan terukur untuk setiap project.',
                    'steps' => [
                        ['step' => '01', 'title' => 'Konsultasi', 'description' => 'Ceritakan kebutuhan dan tujuan bisnis Anda, kami bantu rumuskan solusinya.'],
                        ['step' => '02', 'title' => 'Perancangan', 'description' => 'Desain UI/UX dan arsitektur sistem disusun sesuai kebutuhan.'],
                        ['step' => '03', 'title' => 'Pengembangan', 'description' => 'Tim mengembangkan front-end dan back-end dengan teknologi terkini.'],
                        ['step' => '04', 'title' => 'Peluncuran & Dukungan', 'description' => 'Pengujian, peluncuran, dan dukungan purna jual.'],
                    ],
                ],
            ],
            [
                'section_key' => 'contributors',
                'title' => 'Kontributor',
                'sort_order' => 5,
                'content' => [
                    'items' => [
                        ['name' => 'SPASI Creative Space', 'description' => 'Ruang kreatif & ekosistem seni budaya di Kota Tegal', 'url' => 'https://spasicreative.space/', 'logo' => 'brand/contributors/spasi-creative-space.png'],
                        ['name' => 'Sinema Pantura', 'description' => 'Komunitas sineas & film-maker dari kawasan Pantura Tegal', 'url' => 'https://www.instagram.com/sinemapantura/', 'logo' => 'brand/contributors/sinema-pantura.png'],
                        ['name' => 'Politeknik Harapan Bersama', 'description' => 'Institusi pendidikan vokasi di Tegal, mitra program magang', 'url' => 'https://www.harapan-bersama.ac.id/', 'logo' => 'brand/contributors/politeknik-harapan-bersama.png'],
                    ],
                ],
            ],
            [
                'section_key' => 'cta',
                'title' => 'Call to Action',
                'sort_order' => 7,
                'content' => [
                    'heading' => 'Sudah siap untuk go digital?',
                    'description' => 'Konsultasikan kebutuhan platform digital Anda, dari website, aplikasi, hingga sistem manajemen bisnis.',
                    'email' => 'admin@yuhlez.com',
                    'whatsapp' => '6282125126584',
                ],
            ],
        ];

        foreach ($sections as $data) {
            HomepageSection::updateOrCreate(
                ['section_key' => $data['section_key']],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'is_active' => true,
                    'sort_order' => $data['sort_order'],
                ]
            );
        }

        $this->command->info('Homepage sections seeded: ' . count($sections) . ' sections');
    }
}
