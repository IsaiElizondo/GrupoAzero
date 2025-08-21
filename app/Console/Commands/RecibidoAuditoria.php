<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

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

            'BB172515',
            'BB172603',
            'A0496789',
            'A0498471',
            'A0494107',
            'BB169376',
            'BB169350',
            'A0503093',
            'A0487771',
            'BB177469',
            'BB173656',
            'BB177044',
            'BB169445',
            'A0502455',
            'A0502991',
            'A0499506',
            'BB180634',
            'BB170402',
            'A0502995',
            'A0496131',
            'A0487848',
            'A0494939',
            'A0486002',
            'A0488485',
            'A0494548',
            'A0488191',
            'A0502876',
            'A0502540',
            'BB173785',
            'A0503444',
            'BB182070',
            'A0503865',
            'A0489279',
            'A0502978',
            'A0503314',
            'BB179116',
            'A0486863',
            'BB174816',
            'A0487589',
            'A0489885',
            'A0485950',
            'A0500327',
            'BB169993',
            'A0484498',
            'BB169664',
            'A0496927',
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
            'BB174798',
            'BB174816',
            'BB174886',
            'BB175778',
            'BB177044',
            'BB177469',
            'BB178347',
            'BB179116',
            'BB179889',
            'BB180285',
            'BB180434',
            'BB180634',
            'BB181053',
            'BB181255',
            'BB181296',
            'BB181659',
            'BB181865',
            'BB181897',
            'BB182070',
            'BB182311',
            'BB182398',
            'BB182399',
            'BB182481',
            'BB182482',
            'BB182516',
            'BB182614',
            'BB182651',
            'BB182700'


        ];

        $afectados = DB::table('orders')
            ->where('office', ['La Noria', 'San Pablo'])
            ->whereBetween('created_at', ['2025-01-01 00:00:00', '2025-07-31 23:59:59'])
            ->whereIn('origin', ['C', 'F'])
            ->whereIn('status_id', [6, 7, 8, 9])
            ->whereNotIn('invoice_number', $excluirPedidos)
            ->update(['status_id' => 10]);

        $this->info("Pedidos actualizados: {$afectados}");

    }

}

