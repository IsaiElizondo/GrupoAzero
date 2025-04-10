<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;

class Reportes extends Model
{
    protected $fillable = [
     //   'file', 'order_id', 'created_at'
    ];


    
    public static function Ordenes(string $desde, string $hasta) : array {

        $q="SELECT
o.office AS Sucursal,
o.invoice AS Factura,
o.invoice_number AS `Cotizacion`,
s.name AS `Estatus`,
po.number AS `Requisicion`,
(SELECT count(*) FROM shipments sh WHERE sh.order_id = o.id) AS Salidas,
mo.number AS Fabricacion,
o.created_at AS FechaFactura,
po.created_at AS FechaCotizacion,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 0,1) AS `Parcial1`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 1,1) AS `Parcial2`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 2,1) AS `Parcial3`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 3,1) AS `Parcial4`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 4,1) AS `Parcial5`
 FROM orders o
LEFT JOIN statuses s ON s.id = o.status_id
LEFT JOIN purchase_orders po ON po.order_id = o.id
LEFT JOIN manufacturing_orders mo ON mo.order_id = o.id
            
WHERE o.created_at BETWEEN '".$desde."' AND '".$hasta."'"; 
        $list = DB::select($ql);
        $list = json_decode(json_encode($list), true);
        return $list;
    }
    
    public static function Tiempos(string $desde, string $hasta) : array {
        //Folio Factura	Folio Cotizac ion	Folio ReqStock	Creadoen	Estatus Actual	Fecha Este Estatus	Dias Duracion
      $qGeneral = "SELECT *, 
        DATEDIFF(status_at, created_at) AS daynum
        FROM 
        (SELECT 
        o.id,
        o.invoice_number,
        o.invoice,
        rs.number AS rsnumber,
        o.office,
        o.created_at,
        o.status_id, 
        s.name AS status_name,
        (SELECT lo.created_at FROM logs AS lo WHERE lo.order_id = o.id AND lo.status_id = o.status_id ORDER BY lo.created_at DESC LIMIT 1) AS status_at
        FROM orders o
        LEFT JOIN stockreq rs ON rs.order_id = o.id
        LEFT JOIN statuses s ON s.id = o.status_id         
        WHERE o.created_at BETWEEN '$desde' AND '$hasta') sub ";

        $qLog="SELECT *, 
        DATEDIFF(status_at, created_at) AS daynum
        FROM 
        (SELECT 
        o.id,
        o.invoice_number,
        o.invoice,
        rs.number AS rsnumber,
        o.office,
        o.created_at,
        o.status_id, 
        s.name AS status_name,
        (SELECT lo.created_at FROM logs AS lo WHERE lo.order_id = o.id AND lo.status != 'Nota' ORDER BY lo.created_at DESC LIMIT 1) AS status_at
        FROM orders o
        LEFT JOIN stockreq rs ON rs.order_id = o.id
        LEFT JOIN statuses s ON s.id = o.status_id         
        WHERE o.created_at BETWEEN '$desde' AND '$hasta') sub ";
        $list = DB::select($qLog);        

        return $list;
    }



    public static function PedidosPeriodo(string $desde, string $hasta) : array {
        //Folio Factura	Folio Cotizac ion	Folio ReqStock	Creadoen	Estatus Actual	Fecha Este Estatus	Dias Duracion
      $qGeneral = "SELECT 
        o.id,
        o.origin,
        o.invoice_number,
        o.invoice,
        rs.number AS rsnumber,
        o.office,
        o.created_at,
        o.status_id, 
        s.name AS status_name,
        o.updated_at       
        FROM orders o       
        LEFT JOIN stockreq rs ON rs.order_id = o.id
        LEFT JOIN statuses s ON s.id = o.status_id    
        WHERE o.created_at BETWEEN '$desde' AND '$hasta'";
        $list = DB::select($qGeneral);        

        return $list;
    }
    
    

    
    
    

}
