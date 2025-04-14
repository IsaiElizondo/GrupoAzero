<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function borra_archivos(){
       // $map = directory_map('./mydirectory/', 1);
        //$q="SELECT pícture FROM pictures WHERE created_at < '2022-0101 00:00:00'";

        $n = 0;
        $data = DB::table("pictures")->select("id","created_at","picture")
        ->where("created_at",">","2023-01-01")
        ->where("created_at","<","2023-06-30")->get()->toArray();
        $sp = storage_path("app/public");
        foreach($data as $k => $row){
            $cpath = $sp . "/". $row->picture;
          //  echo $cpath;
            $imgExiste = is_file($cpath) ? 1 : 0;
           // var_dump($imgExiste);
           $data[$k]->path="";
           $data[$k]->borrado=0;
           $data[$k]->existe= $imgExiste;

           if($imgExiste && $n < 600){
           unlink($cpath);
           $data[$k]->path=$cpath;
           $data[$k]->borrado=1;
           $n++;
           }
        }

        $tableStr = \App\Libraries\Tools::simpleTable($data);
        echo $tableStr;
    }

    public function borra_archivos2(){
        // $map = directory_map('./mydirectory/', 1);
         //$q="SELECT pícture FROM pictures WHERE created_at < '2022-0101 00:00:00'";
 
         $n = 0;
         $data = DB::table("evidence")->select("id","created_at","file")
         ->where("created_at",">","2021-12-31")
         ->where("created_at","<","2023-01-01")
         ->get()->toArray();
         $sp = storage_path("app/public");
         foreach($data as $k => $row){
             $cpath = $sp . "/". $row->file;
           //  echo $cpath;
             $imgExiste = is_file($cpath) ? 1 : 0;
            // var_dump($imgExiste);
            $data[$k]->path="";
            $data[$k]->borrado=0;
            $data[$k]->existe= $imgExiste;
 
            if($imgExiste && $n < 400){
           // unlink($cpath);
            $data[$k]->path=$cpath;
            $data[$k]->borrado=1;
            $n++;
            }
         }
 
         $tableStr = \App\Libraries\Tools::simpleTable($data);
         echo $tableStr;
     }




    public function respaldo(){
        //Folio cotización	Folio factura	Cliente	Estatus	Fecha de Creación	
        //Parcial #	Orden de Fabricación	Requisición #	Salida Material	Devolucion #	Cancelación #	Archivo

        $q =  "SELECT o.id,

        o.invoice AS `Folio Cotizacion`,
        o.invoice_number AS `Folio Factura`,
        o.client AS `Cliente`,
        s.name AS `Estatus`,
        o.created_at AS `Fecha Creacion`,
        p.id AS `Parcial`,
        m.id AS `Order Fabricacion`,
        po.number AS `Requisicion`,
        sm.code AS `Salida Material`,
        (SELECT reason FROM reasons rea WHERE rea.id = d.reason_id) AS `Devolucion`,
        (SELECT reason FROM reasons rea WHERE rea.id = c.reason_id) AS `Cancelacion`,

        CONCAT('=HIPERVINCULO(\"', po.document,'\")') AS `Factura Doc`,
        CONCAT('=HIPERVINCULO(\"', q.document,'\")') AS `Cotizacion Doc`, 


        CONCAT('=HIPERVINCULO(\"', po.requisition, '\")') AS `Doc Requisicion`,
        CONCAT('=HIPERVINCULO(\"', m.document, '\")') AS `Ord Fabr Doc`,
        CONCAT('=HIPERVINCULO(\"', m.documentc, '\")') AS `Ord Fabr CANCELADA Doc`,

        CONCAT('=HIPERVINCULO(\"', sh.file, '\")') AS `Embarque Doc`,
        CONCAT('=HIPERVINCULO(\"', (SELECT ev.file FROM evidence ev WHERE ev.cancelation_id = c.id LIMIT 1), '\")') AS `Cancelacion Doc`,
        CONCAT('=HIPERVINCULO(\"', (SELECT ev.file FROM evidence ev WHERE ev.rebilling_id = rb.id LIMIT 1), '\")') AS `Refactura Doc`,
        CONCAT('=HIPERVINCULO(\"', (SELECT ev.file FROM evidence ev WHERE ev.debolution_id = d.id LIMIT 1) , '\")')AS `Devolucion Doc`,

        CONCAT('=HIPERVINCULO(\"', (SELECT pic.picture FROM pictures pic WHERE pic.partial_id = p.id LIMIT 1), '\")') AS `Parcial Doc`,
        CONCAT('=HIPERVINCULO(\"', (SELECT pic.picture FROM pictures pic WHERE pic.smaterial_id = sm.id LIMIT 1), '\")') AS `SalidaMaterial Doc`

        FROM orders o 
        LEFT JOIN statuses s ON s.id = o.status_id
        LEFT JOIN shipments sh ON sh.order_id = o.id 
        LEFT JOIN partials p ON p.order_id = o.id 
        LEFT JOIN manufacturing_orders m ON m.order_id = o.id 
        LEFT JOIN purchase_orders po ON po.order_id = o.id 
        LEFT JOIN smaterial sm ON sm.order_id = o.id 
        LEFT JOIN debolutions d ON d.order_id = o.id 
        LEFT JOIN cancelations c ON c.order_id = o.id
        LEFT JOIN rebillings rb ON rb.order_id = o.id  
        LEFT JOIN quotes q ON q.order_id = o.id 

        WHERE o.created_at BETWEEN '2022-01-1 00:00:00' AND '2023-01-01 00:00:00'
        ORDER BY o.id DESC ";


    header("Content-type:text/plain");

        echo $q;
    }

}
