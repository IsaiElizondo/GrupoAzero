<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;
use App\Log;
use Illuminate\Support\Facades\Log as LaravelLog;
use App\User;

class Pedidos2 extends Model
{
    public static $total = 0;
    public static $rpp=10;
    public static int $pagina = 1;

    protected $fillable = [
     //   'file', 'order_id', 'created_at'
    ];

    protected $table = 'orders';


    public static function uno(int $order_id=0) : object {
        $q="SELECT o.*,
        s.name AS status_name   
        FROM orders o
        JOIN statuses s ON s.id = o.status_id 
        WHERE o.id = '$order_id'";
        $list = DB::select($q);
        //$list = DB::select(DB::raw($q));
       // $list = DB::table('orders')->where("id",$order_id)->get();
        return !empty($list) ? $list[0] : [];
    }




    public static function Lista(int $pag, string $termino, string $desde, string $hasta, 
    array $status=[], array $subprocesos=[], array $origen=[], array $sucursal=[], array $subpstatus=[], 
    array $recogido=[], array $suborigen=[], int $user_id=0, array $etiquetas = []
    ) : array {

        //LaravelLog::info('RPP recibido en Lista(): ' . self::$rpp);


        $ini= ($pag>1) ? ($pag -1) * self::$rpp : 0;

        $A0order = null;
        $BBorder = null;
        $Createdorder = null;

        $wheres=["o.created_at BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'"];
                
        if(!empty($termino)){
            $wheres[]="(o.office LIKE '%$termino%' OR o.invoice LIKE '%$termino%' 
            OR o.invoice_number LIKE '%$termino%' OR o.client LIKE '%$termino%' 
            OR (SELECT COUNT(*) FROM purchase_orders p WHERE p.order_id = o.id AND p.number LIKE '%$termino%') > 0 
            OR (SELECT COUNT(*) FROM partials par WHERE par.order_id = o.id AND par.invoice LIKE '%$termino%') > 0 
            OR (SELECT COUNT(*) FROM manufacturing_orders mo WHERE mo.order_id = o.id AND mo.number LIKE '%$termino%') > 0 
            OR (SELECT COUNT(*) FROM smaterial sm WHERE sm.order_id = o.id AND sm.code LIKE '%$termino%') > 0 
            OR (SELECT COUNT(*) FROM stockreq s WHERE s.order_id = o.id AND s.number LIKE '%$termino%') > 0 
            OR (SELECT COUNT(*) FROM notes n WHERE n.order_id = o.id AND n.note LIKE '%$termino%') > 0 
            OR q.number LIKE '%$termino%')";
        }
        if(!empty($status)){
            $wheres[]="o.status_id  IN (".implode(",",$status).")";
        }
        if(!empty($subprocesos)){
            if(in_array("devolucion",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM debolutions WHERE debolutions.order_id = o.id) > 0";
            }
            if(in_array("ordenc",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM purchase_orders po WHERE po.order_id = o.id) > 0";
                
                $lookSPO=[];
                foreach($subpstatus as $sps){
                $arr=explode("_",$sps);
                    if($arr[0]=="ordenc"){$lookSPO[]=$arr[1];}
                }
                if(!empty($lookSPO)){
                    $wheres[]="(SELECT COUNT(*) FROM purchase_orders po WHERE po.order_id = o.id AND po.status_id IN(".implode(",",$lookSPO).") ) > 0 ";
                }
                
                
            }
            if(in_array("ordenf",$subprocesos)){     
            $subwheres=[];           
                    foreach($subpstatus as $sps){
                    $arr=explode("_",$sps);
                        if($arr[0]=="ordenf"){$subwheres[]=$arr[1];}
                    }
            $subWhere = !empty($subwheres) ? " AND mof.status_id IN (".implode(",",$subwheres).")" : "";
            $wheres[]="(SELECT COUNT(*) FROM manufacturing_orders mof WHERE mof.order_id = o.id $subWhere) > 0";
            }
            
            if(in_array("parcial",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM partials WHERE partials.order_id = o.id) > 0";

                $lookIN=[];
                foreach($subpstatus as $sps){
                $arr=explode("_",$sps);
                    if($arr[0]=="parcial"){$lookIN[]=$arr[1];}
                }
                if(!empty($lookIN)){
                    $wheres[]="(SELECT COUNT(*) FROM partials pa WHERE pa.order_id = o.id AND pa.status_id IN(".implode(",",$lookIN).") ) > 0 ";
                }

               
            }
            if(in_array("refacturar",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM rebillings WHERE rebillings.order_id = o.id) > 0";
            }
            if(in_array("sm",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM smaterial WHERE smaterial.order_id = o.id) > 0";

                $lookIN=[];
                foreach($subpstatus as $sps){
                $arr=explode("_",$sps);
                    if($arr[0]=="sm"){$lookIN[]=$arr[1];}
                }
                if(!empty($lookIN)){
                    $wheres[]="(SELECT COUNT(*) FROM smaterial sma WHERE sma.order_id = o.id AND sma.status_id IN(".implode(",",$lookIN).") ) > 0 ";
                }
            }

            if(in_array("devolucionp", $subprocesos)){

                $wheres[] = "(SELECT COUNT(*) FROM devoluciones_parciales dp WHERE dp.order_id = o.id AND dp.cancelado = 0) > 0";

                $tipos = [];
                foreach($subpstatus as $sps){
                    if($sps == "devolucionp_parcial"){
                        $tipos[] = "'parcial'";
                    }
                    if($sps == "devolucionp_total"){
                        $tipos[] = "'total'";
                    }
                }

                if(!empty($tipos)){
                    $wheres[] = "(SELECT COUNT(*) FROM devoluciones_parciales dp WHERE dp.order_id = o.id AND dp.cancelado = 0 AND dp.tipo IN (" . implode(",", $tipos) . ")) > 0";
                }

            }

            if (in_array('a0', $subprocesos)) {

                $wheres[] = "o.invoice_number LIKE 'A0%'";
                foreach ($subpstatus as $sp) {
                    if($sp === 'a0_asc')  { $A0order = 'ASC'; }
                    if($sp === 'a0_desc') { $A0order = 'DESC'; }
                }

            }
            

            if(in_array('bb', $subprocesos)){

                $wheres[] = "o.invoice_number LIKE 'BB%'";
                foreach($subpstatus as $sp){
                    if($sp == 'bb_asc') { $BBorder = 'ASC'; }
                    if($sp == 'bb_desc') { $BBorder = 'DESC'; }
                }
                
            }

            if(in_array('created', $subprocesos)){
                $wheres[] = "o.created_at BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'";
                foreach($subpstatus as $sp){
                    if($sp == 'created_at_asc'){$Createdorder = 'ASC';}
                    if($sp == 'created_at_desc'){$Createdorder = 'DESC';}
                }
            }

        }

        if(!empty($origen)){
            $orarr=[];
            foreach($origen as $ori){$orarr[]="'$ori'";}
            $wheres[]="o.origin IN (".implode(",",$orarr).")";
        }
        if(!empty($sucursal)){
            $suarr=[];
            foreach($sucursal as $su){$suarr[]="'$su'";}
            $wheres[]="o.office IN (".implode(",",$suarr).")";
        }

        if(!empty($recogido)){
            $rearr=[];
            foreach($recogido as $reco){
                $rearr[]="'".$reco."'";
            }      
        $wheres[]="(SELECT COUNT(*) FROM shipments sh WHERE sh.order_id = o.id AND sh.type  IN (".implode(",",$rearr).") ) > 0";          
        }

        if (!empty($etiquetas)) {
            $wheres[] = "(SELECT COUNT(*) FROM etiqueta_pedido ep WHERE ep.pedido_id = o.id AND ep.etiqueta_id IN (" . implode(',', $etiquetas) . ")) > 0";
        }
        

        if(!empty($suborigen)){            
            foreach($suborigen as $subor){
                $valpar = explode("_",$subor);
                if($valpar[0]=="C" && $valpar[1]==0){$wheres[] = "(o.invoice_number='' OR o.invoice_number IS NULL)"; }
                elseif($valpar[0]=="C" && $valpar[1]==1){$wheres[] = "(o.invoice_number IS NOT NULL AND o.invoice_number !='')"; }
            }
           
        }

        $wherestring = implode(" AND ",$wheres);

        //QUERY TOTAL
        $listt = DB::select("SELECT
        COUNT(*) AS tot 
        FROM orders o 
        LEFT JOIN quotes q ON q.order_id = o.id 
        LEFT JOIN stockreq r ON r.order_id = o.id 
        WHERE ". $wherestring);

        self::$total = !empty($listt) ? $listt[0]->tot : 0 ;

        $orderBy ="updated_at DESC";

        if($A0order){
            $orderBy = "o.invoice_number $A0order";
        }
        if($BBorder){
            $orderBy = "o.invoice_number $BBorder";
        }
        if(!empty($Createdorder)){
            $orderBy = "o.created_at $Createdorder";
        }
        

        //QUERY MAIN***********
        $q = "SELECT 
        o.*,
        (SELECT l.user_id FROM logs l WHERE l.order_id = o.id AND l.action LIKE '%crea%' ORDER BY l.created_at ASC LIMIT 1) AS user_id,

        (SELECT GROUP_CONCAT(DISTINCT CONCAT(e.nombre, '|', e.color) SEPARATOR ', ')
        FROM etiqueta_pedido ep
        JOIN etiquetas e ON e.id = ep.etiqueta_id
        WHERE ep.pedido_id = o.id
        )AS etiquetas_coloreadas,

        (
            SELECT GROUP_CONCAT(CONCAT(dp.tipo, '|', dp.folio) SEPARATOR',')
            FROM devoluciones_parciales dp
            WHERE dp.order_id = o.id AND dp.cancelado = 0
        )AS devoluciones_info,

        (SELECT p.number FROM purchase_orders p wHERE p.order_id = o.id LIMIT 1) AS requisition_code,      
        (SELECT p.document FROM purchase_orders p wHERE p.order_id = o.id LIMIT 1) AS document,
        (SELECT p.requisition FROM purchase_orders p wHERE p.order_id = o.id LIMIT 1) AS requisition_document,
        (SELECT COUNT(*) FROM follows WHERE follows.user_id = '$user_id' AND follows.order_id= o.id) AS follows, 
        q.number AS quote, 
        q.document quote_document, 
        r.id AS stockreq_id,
        r.number AS stockreq_number,
        r.document AS stockreq_document, 
        (SELECT m.number FROM manufacturing_orders m WHERE m.order_id = o.id ORDER BY id DESC LIMIT 1) AS ordenf_number,
        (SELECT m.status_id FROM manufacturing_orders m WHERE m.order_id = o.id ORDER BY id DESC LIMIT 1) AS ordenf_status_id,
        (SELECT pa.invoice FROM partials pa WHERE pa.order_id = o.id ORDER BY id DESC LIMIT 1) AS parcial_number,
        (SELECT pa.status_id FROM partials pa WHERE pa.order_id = o.id ORDER BY id DESC LIMIT 1) AS parcial_status_id,
        (SELECT ue.office FROM users ue WHERE ue.id = o.embarques_by) AS embarques_office, 
        u.name AS creator  
        FROM orders o 
        LEFT JOIN quotes q ON q.order_id = o.id 
        LEFT JOIN stockreq r ON r.order_id = o.id 
        LEFT JOIN users u ON u.id = o.created_by 
        WHERE 
        ". $wherestring  ." ORDER BY $orderBy LIMIT ".$ini.", ". self::$rpp;
     //echo $q;

        //LaravelLog::info("Consulta generada: $q");
    
        $list = DB::select($q);
/*
        foreach ($list as $pedido) {
    LaravelLog::info("Pedido recibido: ID={$pedido->id}, Origen={$pedido->origin}, Status={$pedido->status_id}");
}
    */
        
    return $list;
    }


    public static function Statuses() : array {
         return DB::table('statuses')->get()->toArray();
    }
    public static function StatusesCat(string $default="") : array{
        $arr=[];
        if(!empty($default)){$arr[""]=$default;}
        $list = self::Statuses();
        foreach($list as $li){
            $arr[$li->id]=$li->name;
        }
    return $arr;
    }

    
    public static function StatusCodes() : array
{
    return [
        1 => "GEN",
        2 => "EMB",
        3 => "FAB",
        4 => "FAB",
        5 => "PUE",
        6 => "ENT",
        7 => "CNC",
        8 => "REF",
        9 => "DEV",
        10 => "AUD",
    ];
}

// Devuelve estatus para salidas de materiales (modifica el estatus 4)
public static function StatusesSmaterial() : array
{
    $estatuses = self::StatusesCat();
    if (isset($estatuses[4])) {
        $estatuses[4] = "Elaborado";
    }
    return $estatuses;
}

// Devuelve estatus para parciales (modifica el estatus 4)
public static function StatusesPartial() : array
{
    $estatuses = self::StatusesCat();
    if (isset($estatuses[4])) {
        $estatuses[4] = "Generado";
    }
    return $estatuses;
}

// Devuelve los tipos de origen de pedidos
public static function OrigenesCat() : array
{
    return [
        "" => "",
        "C" => "Cotización",
        "F" => "Factura",
        "R" => "Requisición",
    ];
}

    public static function Events() : array {
        return DB::table('events')->get()->toArray();
   }
   public static function EventsCat(string $default="") : array{
       $arr=[];
       if(!empty($default)){$arr[""]=$default;}
       $list = self::Events();
       foreach($list as $li){
           $arr[$li->id]=$li->name;
       }
   return $arr;
   }

   public static function OrderHasDirect(int $order_id) : array{
    $q="SELECT p.*, 
    e.name AS `event_name`,
    pa.invoice AS `partial`, 
    o.invoice AS invoice 
    FROM pictures p 
    LEFT JOIN partials pa ON pa.id = p.partial_id
    LEFT JOIN events e ON e.id = p.`event`
    LEFT JOIN shipments s ON s.id = p.shipment_id 
    JOIN orders o ON o.id IN( p.order_id, pa.order_id, s.order_id)  
    WHERE o.id = '$order_id'";

    return DB::select($q);
}



   public static function LogsDe(int $id) : object{
    return DB::table("logs")
    ->select(["logs.*","users.name AS user"])
    ->join("users","users.id","=","logs.user_id")->where("logs.order_id",$id)
    ->orderBy("logs.created_at","DESC")->get();
   }



   public static function mimeExtensions() : array{
    return [
        "image/jpg" =>"jpg",
        "image/jpeg" =>"jpg",
        "image/gif"=>"gif",
        "image/png"=>"png",
        "application/pdf"=>"pdf",
        "application/x-pdf"=>"pdf",
        "x-pdf"=>"pdf"
       // "application/msword"=>"docx",
       // "application/ms-word"=>"docx",
       // "text/rtf"=>"rtf",
       // "application/rtf"=>"rtf",
       // "application/ms-excel"=>"xlsx",
       // "application/msexcel"=>"xlsx",
       // "application/vnd.ms-excel"=>"xlsx",
       // "application/vnd.ms-word"=>"docx"
        ];
   }



   public static function Log(int $order_id, string $statusStr, string $action, int $statusId, object $user) : int {
    
    $nid = Log::create([
        "status"=>$statusStr,
        "action"=> $action,
        "order_id"=>$order_id,
        "user_id" => $user->id,
        "department_id"=>$user->department->id,
        "status_id"=>$statusId,
        "created_at" => date("Y-m-d H:i:s"),
        "updated_at" => date("Y-m-d H:i:s")
    ])->id;
        return $nid;
   }


   public static function CodigoDe(object $order) : string {
    $invn = isset($order->invoice_number) ? $order->invoice_number : "";
    $inv = isset($order->invoice) ? $order->invoice : "";

        if(!empty($invn)){return $invn;}
        elseif(!empty($inv)){return $inv;}
        else{
            $sr = Stockreq::where("order_id",$order->id)->first();
            $str = !empty($sr) ? $sr->number : " ";
            return $str;
        }

    }

public static function ListaDashboard(int $pag, $user, array $filtros): array{

    $ini = ($pag > 1) ? ($pag - 1) * self::$rpp : 0;

    $termino = addslashes($filtros['termino'] ?? '');
    $desde = $filtros['desde'] ?? '2025-01-01';
    $hasta = $filtros['hasta'] ?? now()->format("Y-m-d");
    $etiquetas = $filtros['etiquetas'] ?? [];
    $status = $filtros['st'] ?? [];
    $subprocesos = $filtros['sp'] ?? [];
    $subpstatus = $filtros['spsub'] ?? [];
    $origen = $filtros['or'] ?? [];
    $sucursal = $filtros['suc'] ?? [];
    $recogido = $filtros['rec'] ?? [];
    $suborigen = $filtros['orsob'] ?? [];
    $ordenRecibido = $filtros['orden_recibido'] ?? 'DESC';

    $A0order = null;
    $BBorder = null;
    $Createdorder = null;

    $where = ["o.created_at BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'"];

    if (!empty($termino)) {
        $where[] = "( o.office LIKE '%$termino%' OR o.invoice LIKE '%$termino%' OR o.invoice_number LIKE '%$termino%' OR o.client LIKE '%$termino%' OR q.number LIKE '%$termino%' )";
    }

    if (!empty($etiquetas)) {
        $where[] = "ep.etiqueta_id IN (" . implode(',', $etiquetas) . ")";
    }

    if (!empty($status)) {
        $where[] = "o.status_id IN (" . implode(',', $status) . ")";
    }

    if (!empty($origen)) {
        $arr = array_map(fn($v) => "'" . addslashes($v) . "'", $origen);
        $where[] = "o.origin IN (" . implode(',', $arr) . ")";
    }

    if (!empty($sucursal)) {
        $arr = array_map(fn($v) => "'" . addslashes($v) . "'", $sucursal);
        $where[] = "o.office IN (" . implode(',', $arr) . ")";
    }

    if (!empty($recogido)) {
        $where[] = "(SELECT COUNT(*) FROM shipments sh WHERE sh.order_id = o.id AND sh.type IN (" . implode(',', $recogido) . ")) > 0";
    }

    if (!empty($suborigen)) {
        foreach ($suborigen as $subor) {
            $valpar = explode("_", $subor);
            if ($valpar[0] == "C" && $valpar[1] == 0) {
                $where[] = "(o.invoice_number = '' OR o.invoice_number IS NULL)";
            } elseif ($valpar[0] == "C" && $valpar[1] == 1) {
                $where[] = "(o.invoice_number IS NOT NULL AND o.invoice_number != '')";
            }
        }
    }

    // Filtros de subprocesos y subestatus
    if (!empty($subprocesos)) {
        if (in_array("devolucion", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM debolutions WHERE debolutions.order_id = o.id) > 0";
        }

        if (in_array("ordenc", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM purchase_orders po WHERE po.order_id = o.id) > 0";
            $subpo = [];
            foreach ($subpstatus as $sp) {
                $arr = explode("_", $sp);
                if ($arr[0] == "ordenc") $subpo[] = $arr[1];
            }
            if (!empty($subpo)) {
                $where[] = "(SELECT COUNT(*) FROM purchase_orders po WHERE po.order_id = o.id AND po.status_id IN (" . implode(',', $subpo) . ")) > 0";
            }
        }

        if (in_array("ordenf", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM manufacturing_orders mof WHERE mof.order_id = o.id) > 0";
            $submo = [];
            foreach ($subpstatus as $sp) {
                $arr = explode("_", $sp);
                if ($arr[0] == "ordenf") $submo[] = $arr[1];
            }
            if (!empty($submo)) {
                $where[] = "(SELECT COUNT(*) FROM manufacturing_orders mof WHERE mof.order_id = o.id AND mof.status_id IN (" . implode(',', $submo) . ")) > 0";
            }
        }

        if (in_array("parcial", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM partials WHERE partials.order_id = o.id) > 0";
            $subpa = [];
            foreach ($subpstatus as $sp) {
                $arr = explode("_", $sp);
                if ($arr[0] == "parcial") $subpa[] = $arr[1];
            }
            if (!empty($subpa)) {
                $where[] = "(SELECT COUNT(*) FROM partials pa WHERE pa.order_id = o.id AND pa.status_id IN (" . implode(',', $subpa) . ")) > 0";
            }
        }

        if (in_array("refacturar", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM rebillings WHERE rebillings.order_id = o.id) > 0";
        }

        if (in_array("sm", $subprocesos)) {
            $where[] = "(SELECT COUNT(*) FROM smaterial WHERE smaterial.order_id = o.id) > 0";
            $subsm = [];
            foreach ($subpstatus as $sp) {
                $arr = explode("_", $sp);
                if ($arr[0] == "sm") $subsm[] = $arr[1];
            }
            if (!empty($subsm)) {
                $where[] = "(SELECT COUNT(*) FROM smaterial sma WHERE sma.order_id = o.id AND sma.status_id IN (" . implode(',', $subsm) . ")) > 0";
            }
        }

        if(in_array("devolucionp", $subprocesos)){

            $where[] = "(SELECT COUNT(*) FROM devoluciones_parciales dp WHERE dp.order_id = o.id AND dp.cancelado = 0) > 0";

            foreach($subpstatus as $sp){
                if($sp == 'devolucionp_parcial'){
                    $where[] = "(SELECT COUNT(*) FROM devoluciones_parciales dp WHERE dp.order_id = o.id AND dp.cancelado = 0 AND dp.tipo = 'parcial') > 0";
                }
                if($sp == 'devolucionp_total'){
                    $where[] = "(SELECT COUNT(*) FROM devoluciones_parciales dp WHERE dp.order_id = o.id AND dp.cancelado = 0 AND dp.tipo = 'total) > 0";
                }
            }

        }


        if (in_array('a0', $subprocesos)) {

            $where[] = "o.invoice_number LIKE 'A0%'";
            foreach ($subpstatus as $sp) {
                if($sp === 'a0_asc')  { $A0order = 'ASC'; }
                if($sp === 'a0_desc') { $A0order = 'DESC'; }
            }

        }
        

        if(in_array('bb', $subprocesos)){

            $where[] = "o.invoice_number LIKE 'BB%'";
            foreach($subpstatus as $sp){
                if($sp == 'bb_asc') { $BBorder = 'ASC'; }
                if($sp == 'bb_desc') { $BBorder = 'DESC'; }
            }
            
        }

        if(in_array('created', $subprocesos)){
                $wheres[] = "o.created_at BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'";
                foreach($subpstatus as $sp){
                    if($sp == 'created_at_asc'){$Createdorder = 'ASC';}
                    if($sp == 'created_at_desc'){$Createdorder = 'DESC';}
                }
            }

    }

    // Filtros por rol y departamento
    if ($user->role_id == 2 && $user->department_id == 3) {
        $where[] = "log_creador.user_id = {$user->id}";
        $where[] = "o.status_id IN (1,2,3,4,5)";
    }elseif ($user->role_id == 2 && $user->department_id == 4) {
        $where[] = "o.status_id IN (2,5)";
        $where[] = "EXISTS (
            SELECT 1
            FROM logs l_emb
            JOIN users ul_emb ON ul_emb.id = l_emb.user_id
            WHERE l_emb.order_id = o.id
            AND l_emb.status LIKE '%Recibido por embarques%'
            AND ul_emb.office = '" . addslashes($user->office) . "'
        )";
    }elseif ($user->role_id == 2 && $user->department_id == 5) {
        $where[] = "o.status_id NOT IN (6,7,8,9,10)";
        $where[] = "EXISTS (
            SELECT 1
            FROM manufacturing_orders mo
            JOIN users u_mo ON u_mo.id = mo.created_by
            WHERE mo.order_id = o.id
            AND mo.status_id IN (1,3)
            AND u_mo.office = '" . addslashes($user->office) . "'
        )";
    }
    elseif ($user->role_id == 1 || $user->department_id == 2) {
        $where[] = "o.status_id NOT IN (6,7,8,9,10)";
    } elseif (in_array($user->role_id, [1,2]) && $user->department_id == 9) {
        $where[] = "o.status_id IN (6,7,8,9)";
        $where[] = "o.origin IN ('F', 'C')";
    }

    $whereStr = implode(" AND ", $where);

    // Ordenamiento por recibido_embarques_at
    $sinfiltros = 
        empty($termino)
        && empty($etiquetas)
        && empty($status)
        && empty($subprocesos)
        && empty($subpstatus)
        && empty($origen)
        && empty($sucursal)
        && empty($recogido)
        && empty($suborigen);

    if($A0order !== null){
        $orderBy ="a0_num " . ($A0order === 'ASC' ? 'ASC' : 'DESC') . ", MAX(o.created_at) DESC";
    }elseif($BBorder !== null){
        $orderBy ="bb_num " . ($BBorder === 'ASC' ? 'ASC' : 'DESC') . ", MAX(o.created_at) DESC";
    }elseif($sinfiltros){
        $orderBy = "MAX(o.updated_at) DESC";
    }elseif($Createdorder !== null){
        $orderBy = "o.created_at $Createdorder";
    }else{
        $orderBy = "MAX(o.recibido_embarques_at)" . (strtoupper($ordenRecibido) === 'ASC' ? 'ASC' : 'DESC');
    }




    $totalQ = DB::select("
        SELECT COUNT(DISTINCT o.id) AS tot
        FROM orders o
        LEFT JOIN quotes q ON q.order_id = o.id
        LEFT JOIN etiqueta_pedido ep ON ep.pedido_id = o.id
        LEFT JOIN etiquetas e ON e.id = ep.etiqueta_id
        LEFT JOIN logs log_creador ON log_creador.order_id = o.id AND log_creador.action LIKE '%crea%'
        LEFT JOIN users ul_emb ON ul_emb.id = log_creador.user_id
        LEFT JOIN manufacturing_orders mo ON mo.order_id = o.id
        LEFT JOIN users u_mo ON u_mo.id = mo.created_by
        WHERE $whereStr
    ");

    $query = "
        SELECT 
            o.id,
            MAX(o.office) AS office,
            MAX(o.invoice) AS invoice,
            MAX(o.invoice_number) AS invoice_number,
            CASE
                WHEN MAX(o.invoice_number) LIKE 'A0%' THEN CAST(SUBSTRING(MAX(o.invoice_number), 3) AS UNSIGNED)
                ELSE NULL
            END AS a0_num,
            CASE
                WHEN MAX(o.invoice_number) LIKE 'BB%' THEN CAST(SUBSTRING(MAX(o.invoice_number), 3) AS UNSIGNED)
                ELSE NULL
            END AS bb_num,
            MAX(o.client) AS client,
            MAX(o.status_id) AS status_id,
            MAX(o.created_at) AS created_at,
            MAX(o.updated_at) AS updated_at,
            MAX(o.origin) AS origin,
            MAX(o.recibido_embarques_at) AS recibido_embarques_at,
            MAX(o.entrega_programada_at) AS entrega_programada_at,
            MAX(log_creador.user_id) AS user_id,
            GROUP_CONCAT(DISTINCT CONCAT(e.nombre, '|', e.color) SEPARATOR ', ') AS etiquetas_coloreadas,
            MAX(p.number) AS requisition_code,
            MAX(p.document) AS document,
            MAX(p.requisition) AS requisition_document,
            IF(MAX(f.user_id) IS NULL, 0, 1) AS follows,
            MAX(q.number) AS quote,
            MAX(q.document) AS quote_document,
            MAX(r.id) AS stockreq_id,
            MAX(r.number) AS stockreq_number,
            MAX(r.document) AS stockreq_document,
            MAX(mo.number) AS ordenf_number,
            MAX(mo.status_id) AS ordenf_status_id,
            MAX(pa.invoice) AS parcial_number,
            MAX(pa.status_id) AS parcial_status_id,
            MAX(ue.office) AS embarques_office,
            MAX(u.name) AS creator,
            (
                SELECT GROUP_CONCAT(CONCAT(dp.tipo, '|', dp.folio) SEPARATOR',')
                FROM devoluciones_parciales dp
                WHERE dp.order_id = o.id AND dp.cancelado = 0
            )AS devoluciones_info

        FROM orders o
        LEFT JOIN quotes q ON q.order_id = o.id
        LEFT JOIN stockreq r ON r.order_id = o.id
        LEFT JOIN users u ON u.id = o.created_by
        LEFT JOIN logs log_creador ON log_creador.order_id = o.id AND log_creador.action LIKE '%crea%'
        LEFT JOIN etiqueta_pedido ep ON ep.pedido_id = o.id
        LEFT JOIN etiquetas e ON e.id = ep.etiqueta_id
        LEFT JOIN follows f ON f.order_id = o.id AND f.user_id = {$user->id}
        LEFT JOIN purchase_orders p ON p.order_id = o.id
        LEFT JOIN manufacturing_orders mo ON mo.order_id = o.id
        LEFT JOIN partials pa ON pa.order_id = o.id
        LEFT JOIN users ue ON ue.id = o.embarques_by
        LEFT JOIN users ul_emb ON ul_emb.id = log_creador.user_id
        LEFT JOIN users u_mo ON u_mo.id = mo.created_by
        WHERE $whereStr
        GROUP BY o.id
        ORDER BY $orderBy
        LIMIT " . self::$rpp . " OFFSET $ini
    ";

    $resultados = DB::select($query);

    return [
        'total' => $totalQ[0]->tot ?? 0,
        'data' => $resultados,
    ];
}



}
