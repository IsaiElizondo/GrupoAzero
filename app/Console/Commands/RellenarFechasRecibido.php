<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RellenarFechasRecibido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rellenar-fechas-recibido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $logs = DB::table('logs')
            ->select('order_id', DB::raw('MIN(created_at) as fecha'))
            ->where('status', 'Recibido por embarques')
            ->groupBy('order_id')
            ->get();

        $count = 0;

        foreach($logs as $log){

            $updated = DB::table('orders')
                ->where('id', $log->order_id)
                ->whereNull('recibido_embarques_at')
                ->update(['recibido_embarques_at' =>$log->fecha]);

            if($updated){

                $count++;

            }

        }

        $this->info("Actualizados $count pedidos con recibido_embarques_at desde logs.");

    }
}
