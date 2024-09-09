<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'task_id' => '1',
            'title' => 'Create the Project',
            'description' => 'Create all needed files to start working on the project',
            'priority' => 'high',
            'due_date' => '2024-09-08',
            'status' => 'completed',
            'assigned_to' => '1',
            'created_on' => '2024-09-07',
            'updated_on' => '2024-09-07',
        ]);
        Task::create([
            'task_id' => '2',
            'title' => 'Create Migrations',
            'description' => 'Create all needed migrations.',
            'priority' => 'high',
            'due_date' => '2024-09-09',
            'status' => 'in_progress',
            'assigned_to' => '1',
            'created_on' => '2024-09-07',
            'updated_on' => '2024-09-07',
        ]);
        Task::create([
            'task_id' => '3',
            'title' => 'Create Models',
            'description' => 'Create all needed models.',
            'priority' => 'high',
            'due_date' => '2024-09-10',
            'status' => 'in_progress',
            'assigned_to' => null,
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '4',
            'title' => 'Create Seeders',
            'description' => 'Create needed seeders.',
            'priority' => 'medium',
            'due_date' => '2024-09-11',
            'status' => 'pending',
            'assigned_to' => null,
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '5',
            'title' => 'Create controllers1',
            'description' => 'Implement user controller',
            'priority' => 'medium',
            'due_date' => '2024-09-12',
            'status' => 'pending',
            'assigned_to' => '1',
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '6',
            'title' => 'Create Controllers2',
            'description' => 'Implement task controller',
            'priority' => 'medium',
            'due_date' => '2024-09-13',
            'status' => 'pending',
            'assigned_to' => '2',
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '7',
            'title' => 'Testing1',
            'description' => 'Testingthe project1',
            'priority' => 'low',
            'due_date' => '2024-09-14',
            'status' => 'pending',
            'assigned_to' => '3',
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '8',
            'title' => 'Testing2',
            'description' => 'Testingthe project2',
            'priority' => 'low',
            'due_date' => '2024-09-14',
            'status' => 'pending',
            'assigned_to' => '3',
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '9',
            'title' => 'Testing3',
            'description' => 'Testingthe project3',
            'priority' => 'low',
            'due_date' => '2024-09-14',
            'status' => 'pending',
            'assigned_to' => null,
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
        Task::create([
            'task_id' => '10',
            'title' => 'Testing4',
            'description' => 'Testingthe project4',
            'priority' => 'low',
            'due_date' => '2024-09-14',
            'status' => 'pending',
            'assigned_to' => null,
            'created_on' => '2024-09-07',
            'updated_on' => null,
        ]);
    }
}
