<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([DepartmenTableSeeder::class]);
        $this->call([RoleTableSeeder::class]);
        $this->call([UsersTableSeeder::class]);
        $this->call([StatusTableSeeder::class]);
        $this->call([ReasonsTableSeeder::class]);
        // $this->call([OrdersTableSeeder::class]);
        // $this->call([NotesTableSeeder::class]);
    }
}
