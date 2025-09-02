<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // create default administrator user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Istrator',
            'username' => 'admin1796',
            'is_admin' => true,
            'email' => '',
            'password' => bcrypt(config('app.default_admin_pass', 'u3$tZ1IJIFHahInm3TK@')),
        ]);

        $this->call([
            KnowledgeLevelSeeder::class
        ]);
    }
}
