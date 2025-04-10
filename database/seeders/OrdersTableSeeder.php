<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'invoice' => 'A00001',
            'status_id' => 1,
            'created_at' => now()
        ]);
        DB::table('logs')->insert([
            'status' => 'Recibido',
            'action' => 'Pedido generado',
            'order_id' => 1,
            'user_id' => 1,
            'department_id' => 2,
            'created_at' => now()
        ]);

        DB::table('orders')->insert([
            'invoice' => 'A00002',
            'status_id' => 1,
            'created_at' => now()
        ]);
        DB::table('logs')->insert([
            'status' => 'Recibido',
            'action' => 'Pedido generado',
            'order_id' => 2,
            'user_id' => 2,
            'department_id' => 3,
            'created_at' => now()
        ]);

        DB::table('orders')->insert([
            'invoice' => 'A00003',
            'status_id' => 1,
            'created_at' => now()
        ]);
        DB::table('logs')->insert([
            'status' => 'Recibido',
            'action' => 'Pedido generado',
            'order_id' => 3,
            'user_id' => 3,
            'department_id' => 3,
            'created_at' => now()
        ]);

    }
}
