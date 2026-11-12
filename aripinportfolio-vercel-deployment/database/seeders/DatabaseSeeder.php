<?php

namespace Database\Seeders;

use App\Models\PortfolioContent;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@portfolio.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $defaults = [
            'home_name'         => 'Aripin',
            'home_title'        => 'A Design UI/UX.',
            'home_description'  => 'Saya berfokus menciptakan desain antarmuka yang modern, menarik secara visual, dan mudah digunakan pengguna.',
            'about_description' => 'Saya adalah seorang UI/UX Designer yang memiliki semangat dalam menciptakan tampilan visual yang modern, menarik, dan nyaman digunakan pengguna.',
            'about_quote'       => 'Bagi saya, desain bukan hanya tentang tampilan, tetapi juga bagaimana pengguna dapat merasakan kenyamanan saat menggunakan sebuah produk digital.',
            'about_image'       => 'gambar/about.jpg',
            'contact_email'     => 'bakantan696@gmail.com',
            'contact_phone'     => '+62 851 2423 8917',
        ];

        foreach ($defaults as $key => $value) {
            PortfolioContent::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        if (Project::count() === 0) {
            Project::insert([
                ['title' => 'Project Pertama', 'description' => 'Deskripsi project pertama.', 'image' => null, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Project Kedua',   'description' => 'Deskripsi project kedua.',   'image' => null, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Project Ketiga',  'description' => 'Deskripsi project ketiga.',  'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Skill::count() === 0) {
            Skill::insert([
                ['category' => 'Frontend', 'icon' => 'ri-code-s-slash-line', 'items' => 'HTML,CSS,JavaScript',  'created_at' => now(), 'updated_at' => now()],
                ['category' => 'Backend',  'icon' => 'ri-database-2-line',   'items' => 'PHP,MySQL,Laravel',     'created_at' => now(), 'updated_at' => now()],
                ['category' => 'Tools',    'icon' => 'ri-tools-line',         'items' => 'Figma,VS Code',         'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
