<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Zero Infosys ERP',
                'tasks' => ['Database Schema Design', 'Auth Module', 'Inventory Logic']
            ],
            [
                'name' => 'Health App (AdmitDoctors)',
                'tasks' => ['Closed Testing Setup', 'API Integration', 'UI Refinement']
            ],
            [
                'name' => 'MicroAuth System',
                'tasks' => ['Middleware Guard', 'FingerprintJS Integration', 'JWT Setup']
            ],
        ];

        foreach ($projects as $index => $pData) {
            $project = Project::create([
                'name' => $pData['name'],
            ]);

            // foreach ($pData['tasks'] as $taskIndex => $taskName) {
            //     Task::create([
            //         'project_id' => $project->id,
            //         'name'       => $taskName,
            //         'priority'   => $taskIndex + 1, // Default sorting
            //     ]);
            // }
        }
    }
}
