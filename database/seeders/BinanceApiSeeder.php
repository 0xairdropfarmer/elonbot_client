<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiKey;
class BinanceApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
           \App\Models\ApiKey::factory(1)->create();
    }
}
