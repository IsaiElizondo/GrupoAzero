<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->insert([
            'note' => 'Nota de prueba',
            'order_id' => 1,
            'user_id' => 1,
            'created_at' => now()
        ]);
    }
}
