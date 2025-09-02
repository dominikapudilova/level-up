<?php

namespace Database\Seeders;

use App\Models\KnowledgeLevel;
use Illuminate\Database\Seeder;

class KnowledgeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KnowledgeLevel::create([
            'name' => 'Viděl jsem',
            'description' => 'Žák dostal přednášku na dané téma.',
            'weight' => '1',
            'icon' => 'eye.png',
        ]);

        KnowledgeLevel::create([
            'name' => 'Slovní úkol',
            'description' => 'Žák dostal zadaný úkol na vyzkoušení znalosti.',
            'weight' => '1',
            'icon' => 'ear.png',
        ]);

        KnowledgeLevel::create([
            'name' => 'Zkusil jsem',
            'description' => 'Žák si danou dovednost prakticky vyzkoušel.',
            'weight' => '2',
            'icon' => 'hand.png',
        ]);

        KnowledgeLevel::create([
            'name' => 'Umím',
            'description' => 'Žák danou dovednost zvládá bez pomoci.',
            'weight' => '10',
            'icon' => 'medal-3.png',
        ]);

        KnowledgeLevel::create([
            'name' => 'Větší dílo',
            'description' => 'Žák se dovednosti věnoval např. v rámci většího projektu.',
            'weight' => '15',
            'icon' => 'medal-2.png',
        ]);

        KnowledgeLevel::create([
            'name' => 'Mistr',
            'description' => 'Žák dovednost zvládá na takové úrovni, že může ostatní učit.',
            'weight' => '100',
            'icon' => 'medal-1.png',
        ]);
    }
}
