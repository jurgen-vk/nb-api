<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = database_path('/data/Bears.sql');
        DB::unprepared(
            file_get_contents($file_path)
        );

        DB::table('bears')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
