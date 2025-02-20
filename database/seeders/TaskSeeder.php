<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run()
    {
        DB::table('tasks')->insert([
            [
                'project_id' => 1,
                'title' => 'Setup Laravel',
                'description' => 'Menginstal Laravel dan membuat struktur proyek',
                'status' => 'todo',
                'due_date' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
