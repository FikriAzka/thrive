<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectManagementSeeder extends Seeder
{
    public function run()
    {
        DB::table('project_management')->insert([
            [
                'nama_proyek' => 'Project Laravel',
                'description' => 'Membuat aplikasi Project Management dengan Laravel',
                'status' => 'in_progress',
                'deadline' => null,  // Sesuaikan jika deadline tidak digunakan
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
