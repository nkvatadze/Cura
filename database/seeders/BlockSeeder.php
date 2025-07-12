<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Complex;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing complexes
        $complexes = Complex::all();

        if ($complexes->isEmpty()) {
            // If no complexes exist, create some first
            $complexes = Complex::factory(5)->create();
        }

        // Create 30 sample blocks distributed across complexes
        foreach ($complexes as $complex) {
            // Create 2-6 blocks per complex
            $blockCount = rand(2, 6);

            for ($i = 0; $i < $blockCount; $i++) {
                Block::factory()->create([
                    'complex_id' => $complex->id,
                ]);
            }
        }

        // Create some specific examples
        $sunsetTowers = Complex::where('name', 'Sunset Towers')->first();
        if ($sunsetTowers) {
            Block::create([
                'complex_id' => $sunsetTowers->id,
                'name' => 'Block A',
                'flat_quantity' => 75,
                'commercial_space_quantity' => 5,
                'status' => true,
                'construction_date' => '2020-01-15',
                'completion_date' => '2022-06-30',
            ]);

            Block::create([
                'complex_id' => $sunsetTowers->id,
                'name' => 'Block B',
                'flat_quantity' => 60,
                'commercial_space_quantity' => 8,
                'status' => true,
                'construction_date' => '2021-03-01',
                'completion_date' => '2023-03-15',
            ]);
        }

        $greenValley = Complex::where('name', 'Green Valley Residences')->first();
        if ($greenValley) {
            Block::create([
                'complex_id' => $greenValley->id,
                'name' => 'Block A',
                'flat_quantity' => 40,
                'commercial_space_quantity' => 2,
                'status' => true,
                'construction_date' => '2019-03-10',
                'completion_date' => '2021-12-15',
            ]);

            Block::create([
                'complex_id' => $greenValley->id,
                'name' => 'Block B',
                'flat_quantity' => 30,
                'commercial_space_quantity' => 1,
                'status' => true,
                'construction_date' => '2020-06-01',
                'completion_date' => '2022-08-20',
            ]);
        }
    }
}
