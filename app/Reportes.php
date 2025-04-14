<?php

namespace App;

use App\Libraries\WhereBuilder;
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
    
    

    public function Participaciones(array $filtros, int $userId=0, string $version="admin"): array {

      $W = new WhereBuilder();
      $W->Received($filtros);
      $W->Customize("desde","o.created_at >='%s'");
      $W->Customize("hasta","o.created_at <='%s'");
      $W->Customize("termino","IF( '%s' IN (o.invoice_number, o.invoice,po.number), 1 , 0) = 1");
      $W->Customize("origen","o.origin = '%s'");
        if(isset($filtros["subprocesos"]) && is_array($filtros["subprocesos"]) && !empty($filtros["subprocesos"])){
          $ors =[];
          if(in_array("partial",$filtros["subprocesos"])){
            $ors []= "(SELECT COUNT(*) FROM partials subp WHERE subp.order_id = o.id) > 0";
          }
          if(in_array("ordenf",$filtros["subprocesos"])){
            $ors []="(SELECT COUNT(*) FROM manufacturing_orders submo WHERE submo.order_id = o.id) > 0";
          }
          if(in_array("requisicion",$filtros["subprocesos"])){
            $ors []="(SELECT COUNT(*) FROM purchase_orders subpo WHERE subpo.order_id = o.id) > 0";
          }
          if(in_array("smaterial",$filtros["subprocesos"])){
            $ors []="(SELECT COUNT(*) FROM smaterial subsm WHERE subsm.order_id = o.id) > 0";
          }
          if(in_array("debolution",$filtros["subprocesos"])){
            $ors []="(SELECT COUNT(*) FROM debolutions subdeb WHERE subdeb.order_id = o.id) > 0";
          }
        $W->CustomValue("subprocesos","(". implode(" OR ",$ors) .")");
        }

      if(!empty($userId)){
      $W->Add("(SELECT logs.user_id FROM logs WHERE logs.order_id = o.id ORDER BY logs.created_at ASC LIMIT 1) = '$userId'");
      }

      $ws = $W->String();

      $q="SELECT 
      o.office AS SUCURSAL,
DATE_FORMAT(o.created_at, '%d/%m/%Y %H:%i') AS  `FECHA DE CREACIÓN`,";

  if($version=="admin"){
    //u.name AS `USUARIO CREADOR`,
  $q.="
  (SELECT CONCAT(uu.name,' (',uu.office,')') FROM users uu JOIN logs lo ON lo.user_id = uu.id WHERE lo.order_id = o.id ORDER BY lo.created_at ASC LIMIT 1) AS `USUARIO CREADOR LOGS`,
  ";
  }

$q.="o.invoice_number AS `# FACTURA`,
o.invoice AS `# PEDIDO`,
o.client AS `CLIENTE`,
ss.name AS `ESTATUS GENERAL`, 
DATE_FORMAT(o.updated_at, '%d/%m/%Y %H:%i') AS `ULTIMO CAMBIO GENERAL`,
GREATEST(
  DATEDIFF(o.updated_at,o.created_at),
  COALESCE(DATEDIFF(p.updated_at,o.created_at),0),
  COALESCE(DATEDIFF(sm.updated_at,o.created_at),0),
  COALESCE(DATEDIFF(mo.updated_at,o.created_at),0),
  COALESCE(DATEDIFF(po.updated_at,o.created_at),0) 
  ) AS `CONTEO DÍAS`,
(SELECT ue.office FROM users ue WHERE ue.id = o.embarques_by)  AS `EMBARQUES SUCURSAL`,
p.invoice AS `# PARCIAL`,
(SELECT pss.name FROM statuses pss WHERE pss.id = p.status_id) AS `ESTATUS PARCIAL`,  
DATE_FORMAT(p.updated_at, '%d/%m/%Y %H:%i') AS `ULTIMO CAMBIO PARCIAL`,
sm.code AS `# SALIDA DE MATERIALES`,
(SELECT sms.name FROM statuses sms WHERE sms.id = sm.status_id) AS `ESTATUS SALIDA DE MATERIALES`,
DATE_FORMAT(sm.updated_at, '%d/%m/%Y %H:%i') AS `ULTIMO CAMBIO SALIDA DE MATERIALES`,
'' AS `SUCURSAL DE FABRICACION`,
mo.number AS `#ORDEN DE FABRICACION`,
(SELECT mos.name FROM statuses mos WHERE mos.id = mo.status_id) AS `ESTATUS DE FABRICACION`,
DATE_FORMAT(mo.updated_at, '%d/%m/%Y %H:%i') AS `ULTIMO CAMBIO ORDEN DE FABRICACION`,
po.number AS `REQUISICION`,
(SELECT pos.name FROM statuses pos WHERE pos.id = po.status_id) AS `REQUISICION ESTATUS`,
DATE_FORMAT(po.updated_at, '%d/%m/%Y %H:%i') AS `REQUISICION ULTIMA MODIFICACION`,
DATE_FORMAT(deb.created_at, '%d/%m/%Y %H:%i') AS `DEVOLUCION`,
(SELECT r.reason FROM reasons r WHERE r.id = deb.reason_id) AS `DEVOLUCION RAZON`,
re.number AS `REFACTURACION` 
      FROM orders o 
      JOIN statuses ss ON ss.id = o.status_id 
      LEFT JOIN partials p ON p.order_id = o.id 
      LEFT JOIN smaterial sm ON sm.order_id = o.id 
      LEFT JOIN purchase_orders po ON po.order_id = o.id 
      LEFT JOIN manufacturing_orders mo ON mo.order_id = o.id 
      LEFT JOIN debolutions deb ON deb.order_id = o.id 
      LEFT JOIN rebillings re ON re.order_id = o.id 
      LEFT JOIN users u ON u.id = o.created_by
      $ws";

//echo $q;
//die();

      $this->query = $q;

     $list = DB::select($q);        

        return $list;
        //return[];
    }
    
    
    

}
