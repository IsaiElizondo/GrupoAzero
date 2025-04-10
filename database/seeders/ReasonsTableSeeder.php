<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reasons')->insert([
            'reason' => 'Error externo'
        ]);
        DB::table('reasons')->insert([
            'reason' => 'Error interno'
        ]);
    }
}
