<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectManagement;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class ProjectManagementSeeder extends Seeder
{
    public function run(): void
    {
        // Create some sample users first if not exists
        if (User::count() == 0) {
            User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password123')
            ]);
            
            User::create([
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password123')
            ]);
        }

        $users = User::all();

        // Create Projects
        $projects = [
            [
                'nama_proyek' => 'Website E-Commerce Development',
                'description' => 'Pengembangan website e-commerce untuk toko online',
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addMonths(3),
            ],
            [
                'nama_proyek' => 'Mobile App Development',
                'description' => 'Pengembangan aplikasi mobile untuk manajemen inventori',
                'status' => 'pending',
                'deadline' => Carbon::now()->addMonths(2),
            ],
            [
                'nama_proyek' => 'System Integration Project',
                'description' => 'Integrasi sistem ERP dengan sistem existing',
                'status' => 'completed',
                'deadline' => Carbon::now()->addWeek(),
            ]
        ];

        foreach ($projects as $project) {
            $projectModel = ProjectManagement::create($project);
            
            // Attach random users to project
            $projectModel->users()->attach(
                $users->random(rand(1, 2))->pluck('id')->toArray()
            );

            // Create tasks for each project
            $tasks = [];
            
            if ($project['nama_proyek'] === 'Website E-Commerce Development') {
                $tasks = [
                    [
                        'title' => 'Database Design',
                        'description' => 'Create database schema and relationships',
                        'status' => 'done',
                        'due_date' => Carbon::now()->addWeek(),
                    ],
                    [
                        'title' => 'Frontend Development',
                        'description' => 'Develop user interface using React',
                        'status' => 'in_progress',
                        'due_date' => Carbon::now()->addWeeks(2),
                    ],
                    [
                        'title' => 'Payment Integration',
                        'description' => 'Integrate payment gateway',
                        'status' => 'todo',
                        'due_date' => Carbon::now()->addWeeks(3),
                    ]
                ];
            } 
            elseif ($project['nama_proyek'] === 'Mobile App Development') {
                $tasks = [
                    [
                        'title' => 'UI/UX Design',
                        'description' => 'Design user interface for mobile app',
                        'status' => 'in_progress',
                        'due_date' => Carbon::now()->addDays(10),
                    ],
                    [
                        'title' => 'API Development',
                        'description' => 'Create RESTful API endpoints',
                        'status' => 'todo',
                        'due_date' => Carbon::now()->addWeeks(2),
                    ],
                    [
                        'title' => 'Testing',
                        'description' => 'Perform unit and integration testing',
                        'status' => 'todo',
                        'due_date' => Carbon::now()->addWeeks(3),
                    ],
                    [
                        'title' => 'App Store Deployment',
                        'description' => 'Prepare and submit app to stores',
                        'status' => 'todo',
                        'due_date' => Carbon::now()->addWeeks(4),
                    ]
                ];
            }
            else {
                $tasks = [
                    [
                        'title' => 'System Analysis',
                        'description' => 'Analyze current system architecture',
                        'status' => 'done',
                        'due_date' => Carbon::now()->addDays(5),
                    ],
                    [
                        'title' => 'Data Migration',
                        'description' => 'Migrate existing data to new system',
                        'status' => 'done',
                        'due_date' => Carbon::now()->addDays(7),
                    ],
                    [
                        'title' => 'System Testing',
                        'description' => 'Perform system integration testing',
                        'status' => 'done',
                        'due_date' => Carbon::now()->addDays(10),
                    ]
                ];
            }

            // Create tasks for the project
            foreach ($tasks as $task) {
                $projectModel->tasks()->create($task);
            }
        }
    }
}