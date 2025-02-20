<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectUserSeeder extends Seeder
{
    public function run()
    {
        // Mengaitkan project_id 1 dengan user_id 2
        DB::table('project_user')->insert([
            ['project_id' => 1, 'user_id' => 2],
            ['project_id' => 1, 'user_id' => 1]
        ]);
    }
}
