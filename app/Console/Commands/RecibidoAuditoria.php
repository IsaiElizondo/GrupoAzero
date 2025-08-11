<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RecibidoAuditoria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recibido-auditoria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para darle recibido por auditoria, se puede volver a usar si vuelven a juntarse muchos pedidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $excluirPedidos = [

            'BB169350', 
            'BB169376',
            'BB169445',
            'BB169664',
            'BB169993',
            'BB170402', 
            'BB173785', 
            'BB172515', 
            'BB172603', 
            'BB173137',
            'BB173656', 
            'BB174122', 
            'BB174712', 
            'BB174791', 
            'BB174798',
            'BB174800', 
            'BB174816', 
            'BB174886', 
            'BB175778', 
            'BB177044',
            'BB177469', 
            'BB178103', 
            'BB178347', 
            'BB178548', 
            'BB179081',
            'BB179116', 
            'BB179593', 
            'BB179749', 
            'BB179889', 
            'BB180011',
            'BB180244', 
            'BB180285', 
            'BB180420', 
            'BB180434', 
            'BB180448',
            'BB180450', 
            'BB180560', 
            'BB180585', 
            'BB180592'

        ];

        $afectados = DB::table('orders')
            ->where('office', 'La Noria')
            ->whereBetween('created_at', ['2025-01-01 00:00:00', '2025-06-30 23:59:59'])
            ->whereIn('origin', ['C', 'F'])
            ->whereIn('status_id', [6, 7, 8, 9])
            ->whereNotIn('invoice_number', $excluirPedidos)
            ->count();

        $this->info("Pedidos actualizados: {$afectados}");

    }

}

