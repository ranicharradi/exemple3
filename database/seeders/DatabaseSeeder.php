<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@codex.test',
        ], [
            'name' => 'Platform Admin',
            'role' => User::ROLE_ADMIN,
            'password' => 'password',
        ]);

        User::updateOrCreate([
            'email' => 'student@codex.test',
        ], [
            'name' => 'Demo Student',
            'role' => User::ROLE_STUDENT,
            'password' => 'password',
        ]);

        foreach ([
            [
                'title' => 'Laravel Foundations',
                'description' => 'Learn routing, controllers, validation, and Eloquent with a practical project-first approach.',
                'price' => 149.00,
                'video_url' => 'https://videos.codex.test/laravel-foundations',
            ],
            [
                'title' => 'Secure Payment Workflows',
                'description' => 'Design secure manual payment review flows, proof uploads, audit trails, and authorization gates.',
                'price' => 189.00,
                'video_url' => 'https://videos.codex.test/secure-payment-workflows',
            ],
            [
                'title' => 'PostgreSQL for Product Engineers',
                'description' => 'Build stronger schema design habits, indexing strategies, and query patterns for production apps.',
                'price' => 129.00,
                'video_url' => 'https://videos.codex.test/postgresql-for-product-engineers',
            ],
        ] as $course) {
            Course::updateOrCreate([
                'title' => $course['title'],
            ], $course);
        }
    }
}
