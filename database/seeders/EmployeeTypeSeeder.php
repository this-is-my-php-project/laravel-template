<?php

namespace Database\Seeders;

use App\Constants\EmployeeTypeConstant;
use App\Models\EmployeeType;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'id' => 1,
                'name' => EmployeeTypeConstant::FULL_TIME['name'],
                'description' => 'Full Time Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => EmployeeTypeConstant::PART_TIME['name'],
                'description' => 'Part Time Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => EmployeeTypeConstant::CONTRACT['name'],
                'description' => 'Contract Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => EmployeeTypeConstant::INTERN['name'],
                'description' => 'Intern Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => EmployeeTypeConstant::TEMPORARY['name'],
                'description' => 'Temporary Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => EmployeeTypeConstant::CASUAL['name'],
                'description' => 'Casual Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => EmployeeTypeConstant::SEASONAL['name'],
                'description' => 'Seasonal Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => EmployeeTypeConstant::VOLUNTEER['name'],
                'description' => 'Volunteer Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => EmployeeTypeConstant::APPRENTICE['name'],
                'description' => 'Apprentice Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => EmployeeTypeConstant::TRAINEE['name'],
                'description' => 'Trainee Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'name' => EmployeeTypeConstant::PROBATION['name'],
                'description' => 'Probation Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'name' => EmployeeTypeConstant::PERMANENT['name'],
                'description' => 'Permanent Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'name' => EmployeeTypeConstant::NORMAL['name'],
                'description' => 'NORMAL Employee',
                'is_active' => true,
                'is_global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        EmployeeType::insert($employees);
    }
}
