<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'first_name' => 'Anya',
            'last_name' => 'Neeze',
            'nickname' => 'neezer',
            'access_pin' => '1234',
        ]);

        Student::create([
            'first_name' => 'Yuri',
            'last_name' => 'Tarded',
            'nickname' => 'yuri',
            'access_pin' => '1234',
        ]);

        Student::create([
            'first_name' => 'Moe',
            'last_name' => 'Lester',
            'nickname' => 'moe',
            'access_pin' => '1234',
        ]);

        Student::create([
            'first_name' => 'Mike',
            'last_name' => 'Hawk',
            'nickname' => 'mikky',
            'access_pin' => '1234',
        ]);
    }
}
