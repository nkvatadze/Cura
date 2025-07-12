<?php

namespace Database\Seeders;

use App\Models\Complex;
use Illuminate\Database\Seeder;

class ComplexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 sample complexes
        Complex::factory(20)->create();

        // Create some specific examples
        Complex::create([
            'name' => 'Sunset Towers',
            'location' => 'Downtown, New York',
            'block_quantity' => 5,
            'status' => true,
            'construction_date' => '2020-01-15',
            'completion_date' => '2022-06-30',
        ]);

        Complex::create([
            'name' => 'Green Valley Residences',
            'location' => 'Suburban District, California',
            'block_quantity' => 3,
            'status' => true,
            'construction_date' => '2019-03-10',
            'completion_date' => '2021-12-15',
        ]);

        Complex::create([
            'name' => 'Riverside Complex',
            'location' => 'Riverside District, Texas',
            'block_quantity' => 8,
            'status' => false,
            'construction_date' => '2023-06-01',
            'completion_date' => null,
        ]);
    }
}
