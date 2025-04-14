<?php

namespace App\Http\Controllers;

#use App\Log;
use App\Reportes;
use App\Stockreq;
use App\User;
use App\Libraries\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Ellumilel\ExcelWriter;



class ReportesController extends Controller
{
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $user = User::find(auth()->user()->id);

        $hoy = new \DateTime();
        $hastaDef = $hoy->format("Y-m-d");
        $hoy->modify("-7 day");
        $desdeDef = $hoy->format("Y-m-d");

        $action = 'Reportes de Azero';
        
        return view('reportes.index', compact('user', 'action', 'desdeDef','hastaDef'));
    }
    
    
    
    /*
    public function reporte(Request $request)
    {        
        $tipo = $request->post("tipo");
        //$desde = $request->post("desde");
        //$hasta = $request->post("hasta");
        $fechas = $request->post("fechas");

        $partes = explode(" - ",$fechas);
            if(count($partes) < 2){
                return "f";
            }
        $desde = $partes[0];
        $desde = Tools::fechaIso($desde);
        $hasta = $partes[1];
        $hasta = Tools::fechaIso($hasta);
        
        $user = User::find(auth()->user()->id);


        if($tipo == "Tiempossub"){
            $this->tiemposSub($desde,$hasta);
        }
        else if($tipo == "Tiempos") {
            $this->tiemposGeneral($desde,$hasta);
        }

    return;
    }
*/


    function reporte_subprocesos(Request $request){
    
        $user = User::find(auth()->user()->id);


        $fechas = $request->post("fechas");

        $partes = explode(" - ",$fechas);
            if(count($partes) < 2){
                return "f";
            }
        $desde = $partes[0];
        $desde = Tools::fechaIso($desde);
        $hasta = $partes[1];
        $hasta = Tools::fechaIso($hasta);
        


    //********************************   CATALOGOS    ******************** */
    $statuses = \App\Status::all();
    $estatuses = [];
        foreach($statuses as $st){
            $estatuses[$st->id]=$st->name;
        }
    $estatuses[1]="Generado";    
       
    $origenes=["C"=>"Cotización", "F"=>"Factura", "R"=>"Requisición Stock"];
       
    $reasonsList = \App\Reason::all();
    $reasons=[];
    foreach($reasonsList as $rea){
                   $reasons[$rea->id] = $rea->reason;
    }

    $desdeOb = new \DateTime($desde);
    $hastaOb = new \DateTime($hasta);

    $desde = $desdeOb->format("Y-m-d 00:00:00");
    $hasta = $hastaOb->format("Y-m-d 23:59:59");

    $lista =Reportes::PedidosPeriodo($desde,$hasta);
        //Folio Factura	Folio Cotizacion	Folio ReqStock	Creadoen	Estatus Actual	Fecha Este Estatus	Dias Duracion
                   $columnas = [
                       'ID'=>'string',
                       'Origen'=>'string',
                       'Folio Factura' => 'string',
                       'Folio Cotización'=>'string',
                       'Folio Requisición Stock' => 'string',
                       'Sucursal Original' => 'string',
                       'Creado general en' => 'DD/MM/YYYY',

                       'Subproceso' =>'string',
                       'Folio/Numero' => 'string',
                       'Estatus Actual'=>'string',
                       'Creado en' => 'DD/MM/YYYY HH:MM',
                       'Creado por' => 'string',
                       'Sucursal' => 'string',

                       'Final en' => 'DD/MM/YYYY',
                       'Dias' => 'integer'];
                   $wExcel = new ExcelWriter();
                   $wExcel->writeSheetHeader('Sheet1', $columnas);
                   $wExcel->setAuthor('Sistema Evidenciasmars');      
       
                   foreach($lista as $li){
                       $row=[
                           $li->id,
                           isset($origenes[$li->origin]) ? $origenes[$li->origin] : '',
                           $li->invoice_number,
                           $li->invoice,
                           $li->rsnumber,
                           $li->office,
                           $li->created_at     
                       ];
                       
                       //$row=$rowIni;

                       $partials = \App\Partial::where("order_id",$li->id)->get();
                       foreach($partials as $partial){
                           $row['Subproceso'] = 'Salida Parcial';
                           $row['Folio/Numero'] = $partial->invoice;
                           $row['Estatus Actual'] = isset($estatuses[$partial->status_id])?$estatuses[$partial->status_id]:'';
                           $row['Creado en'] = strval($partial->created_at);

                           $row['Creado por']='';
                           $row['Sucursal']=''; 

                           $row['Final en'] = strval($partial->end_at);
                           $caDate = new \DateTime($partial->created_at);
                           $upDate = new \DateTime($partial->end_at);
                           $diffDate = $caDate->diff($upDate);
                           $row['Dias'] =  $diffDate->days;
                           $wExcel->writeSheetRow('Sheet1', $row );
                       }


                       $ordenesf =\App\ManufacturingOrder::where("order_id",$li->id)->get();
                        foreach($ordenesf as $ord){
                            $row['Subproceso'] = 'Orden Fabricación';
                            $row['Folio/Numero'] = $ord->number;
                            $row['Estatus Actual'] = isset($estatuses[$ord->status_id]) ? $estatuses[$ord->status_id] :'';
                            $row['Creado en'] = strval($ord->created_at);
                           
                            $usr = \App\User::where("id",$ord->created_by)->first();
                           $row['Creado por']= !empty($usr) ? $usr->name : "";
                           $row['Sucursal']= !empty($usr)  ? $usr->office : ""; 

                            $row['Final en'] = strval($ord->end_at);
                            $caDate = new \DateTime($ord->created_at);
                            $upDate = new \DateTime($ord->end_at);
                            $diffDate = $caDate->diff($upDate);
                            $row['Dias'] =  $diffDate->days;
                            $wExcel->writeSheetRow('Sheet1', $row );
                        }


                        $requisiciones = \App\PurchaseOrder::where("order_id",$li->id)->get();
                        foreach($requisiciones as $req){            
                            $row['Subproceso'] = 'Requisición';
                            $row['Folio/Numero'] = $req->number;
                            $row['Estatus Actual'] = isset($estatuses[$req->status_id]) ? $estatuses[$req->status_id] : '';
                            $row['Creado en'] = strval($req->created_at);

                            $usr = \App\User::where("id",$req->created_by)->first();
                            $row['Creado por']= !empty($usr) ? $usr->name : "";
                            $row['Sucursal']= !empty($usr)  ? $usr->office : ""; 

                            $row['Final en'] = strval($req->end_at);
                            $caDate = new \DateTime($req->created_at);
                            $upDate = new \DateTime($req->end_at);
                            $diffDate = $caDate->diff($upDate);
                            $row['Dias'] =  $diffDate->days;
                            $wExcel->writeSheetRow('Sheet1', $row );
                        }


                        $salidasm = \App\Smaterial::where("order_id", $li->id)->get();
                        foreach($salidasm as $salm){     

                            $row['Subproceso'] = 'Salida de Material';
                            $row['Folio/Numero'] = $salm->code;
                            $row['Estatus Actual'] = isset($estatuses[$salm->status_id]) ? $estatuses[$salm->status_id] : '';
                            $row['Creado en'] = strval($salm->created_at);

                            $row['Creado por']='';
                            $row['Sucursal']=''; 

                            $row['Final en'] = strval($salm->end_at);
                            $caDate = new \DateTime($salm->created_at);
                            $upDate = new \DateTime($salm->end_at);
                            $diffDate = $caDate->diff($upDate);
                            $row['Dias'] =  $diffDate->days;

                            $wExcel->writeSheetRow('Sheet1', $row );
                        }


                        $devoluciones = \App\Debolution::where("order_id",$li->id)->get();
                        foreach($devoluciones as $devol){

                            $row['Subproceso'] = 'Devolución';
                            $row['Folio/Numero'] = isset($reasons[$devol->reason_id]) ? $reasons[$devol->reason_id] : '' ;
                            $row['Estatus Actual'] = $estatuses[1];
                            $row['Creado en'] = strval($devol->created_at);

                            $row['Creado por']='';
                            $row['Sucursal']=''; 

                            $row['Final en'] = strval($devol->updated_at);
                            $caDate = new \DateTime($devol->created_at);
                            $upDate = new \DateTime($devol->updated_at);
                            $diffDate = $caDate->diff($upDate);
                            $row['Dias'] =  $diffDate->days;

                            $wExcel->writeSheetRow('Sheet1', $row );
                        }


                        $refacturaciones = \App\Rebilling::where("order_id", $li->id)->get();
                        foreach($refacturaciones as $ref){
                            $row['Subproceso'] = 'Refacturación';
                            $row['Folio/Numero'] = $ref->number;
                            $row['Estatus Actual'] = $estatuses[1];
                            $row['Creado en'] = strval($ref->created_at);

                            $row['Creado por']='';
                            $row['Sucursal']=''; 

                            $row['Final en'] = strval($ref->updated_at);
                            $caDate = new \DateTime($ref->created_at);
                            $upDate = new \DateTime($ref->updated_at);
                            $diffDate = $caDate->diff($upDate);
                            $row['Dias'] =  $diffDate->days;

                            $wExcel->writeSheetRow('Sheet1', $row );
                        }


                   //$wExcel->writeSheetRow('Sheet1', $row );
                   }
                   
                   //$tempPath= public_path("temp")."/".$csvFileName;
                   $xlsFileName = "TiemposSub_".date("d-m-Y H\hi\m").".xlsx";
                   $this->MandaReporte($wExcel, $xlsFileName);
                   return;            
    }



    public function participaciones(Request $request){
    
        $user = User::find(auth()->user()->id);

        $hoy = new \DateTime();
        $hastaDef = $hoy->format("Y-m-d");
        $hoy->modify("-7 day");
        $desdeDef = $hoy->format("Y-m-d");
        

        $action = 'Reporte de Participaciones';
        
        return view('reportes.participaciones', compact('user', 'action', 'desdeDef','hastaDef'));
    }





    function reporte_tiemposgeneral(string $desde, string $hasta){
        $lista =Reportes::Tiempos($desde,$hasta);
        //Folio Factura	Folio Cotizacion	Folio ReqStock	Creadoen	Estatus Actual	Fecha Este Estatus	Dias Duracion
                   $columnas = [
                       'ID'=>'string',
                       'Folio Factura' => 'string',
                       'Folio Cotización'=>'string',
                       'Folio Requisición Stock' => 'string',
                       'Sucursal' => 'string',
                       'Creado en' => 'DD/MM/YYYY',
                       'Estatus Actual'=>'string',
                       'Ultimo Cambio' => 'DD/MM/YYYY',
                       'Dias' => 'integer'];
                   $wExcel = new ExcelWriter();
                   $wExcel->writeSheetHeader('Sheet1', $columnas);
                   $wExcel->setAuthor('Sistema Evidenciasmars');      
       
                   foreach($lista as $li){
                       $row=[
                           $li->id,
                           $li->invoice_number,
                           $li->invoice,
                           $li->rsnumber,
                           $li->office,
                           $li->created_at,
                           $li->status_name,
                           $li->status_at,
                           $li->daynum
                       ];
                   $wExcel->writeSheetRow('Sheet1', $row );
                   }
                   
                   //$tempPath= public_path("temp")."/".$csvFileName;
                   $xlsFileName = "Tiempos_".date("d-m-Y H\hi\m").".xlsx";
                   $this->MandaReporte($wExcel, $xlsFileName);
                   return;    
    }
    


    public function general( array $lista ){
        //ID	Sucursal	Origen	Folio cotización	Folio Factura	Cliente	Estatus	Fecha Creación	Fecha Cambio	
        //Parcial Numero	Parcial Estatus	Parcial Creado	Numero_ReqStock	Orden Manufactura Numero	
        //Estatus	Requisicion Numero	Req Status	Req Salida Material	Salida Material ID	Salida Mat Estatus	
        //Salida Material Creado	Devolución Razon	Devolución Creada	
        //Refacturación numero	Refacturación razón	Refacturación Url
        

        $header = [
            'ID'=>'string',
            'Sucursal' => 'string',
            'Origen'=>'string',
            'Folio cotización' => 'string',
            'Folio factura' => 'string',
            'Req Stock #'=>'string',
            'Embarques Sucursal' => 'string',
            'Cliente' => 'string',
            'Estatus' => 'string',
            'Fecha de Creación' => 'DD/MM/YYYY',
            'Creado por' => 'string',
            'Último cambio' => 'DD/MM/YYYY',
            'Días' => 'string',

            'Parcial #' => 'string',
            'SP Estatus' => 'string',
            'SP Creado'=>'DD/MM/YYYY HH:MM',

            'Orden de Fabricación'=>'string',
            'OF Status'=>'string',
            'OF Creada'=>'DD/MM/YYYY HH:MM',
            'OF Sucursal'=>'string',

            'Requisición #'=>'string',
            'Req Status'=>'string',
            'Req Creada'=>'DD/MM/YYYY HH:MM',
            'Req Sucursal'=>'string',
            
            'Salida Material'=>'string',
            'SM Estatus'=>'string',
            'SM Creado'=>'DD/MM/YYYY HH::MM',

            'Devolución Razón'=>'string',
            'Devolución Creada'=>'DD/MM/YYYY HH::MM',   

            'Refacturación #' => 'string',
            'Ref Razón' => 'string',
            'Ref URL' => 'string'            
        ];

        //string,money, YYYY-MM-DD HH:MM:SS

        //********************************   CATALOGOS    ******************** */
        $statuses = \App\Status::all();
         $estatuses = [];
         foreach($statuses as $st){
             $estatuses[$st->id]=$st->name;
         }

        $origenes=["C"=>"Cotización", "F"=>"Factura", "R"=>"Requisición Stock"];

        $reasonsList = \App\Reason::all();
        $reasons=[];
        foreach($reasonsList as $rea){
            $reasons[$rea->id] = $rea->reason;
        }

        /*
        $officesList = \App\Office::all();
        $offices=[];
        foreach($officesList as $offi){
            $offices[$offi->name] = $offi->name;
        }
        */
       // $O = new \App\Office();
       // $offices = $O->catalog();

        //*******************************    EXCEL    ************************* */ 
        $maxOrders=4000;
        $o=0;
        
        $wExcel = new ExcelWriter();
        $wExcel->writeSheetHeader('Sheet1', $header);
        $wExcel->setAuthor('Sistema Aceros2000');      
        
        
        

        foreach($lista as $li){
        $o++;
            if($o>$maxOrders){break;}

            //************************************ DATA ************************/

            $stockReq = Stockreq::where("order_id",$li->id)->first();

            $feCat = new \DateTime($li->created_at);
            $feUC = new \DateTime($li->end_at);
            $feDiff = $feUC->diff($feCat);


            //----------------------------------------
            
            $row = [
                'ID'=>$li->id,
                'Sucursal' => $li->office,
                'Origen'=> !empty($origenes[$li->origin]) ? $origenes[$li->origin] : "",
                'Folio cotizacion' => $li->invoice,
                'Folio factura' => $li->invoice_number,

                
                'Req Stock #'=> !empty($stockReq) ? $stockReq->number : '',

                'Embarques Sucursal' => $li->embarques_office,
                
                'Cliente' => $li->client,
                'Estatus' => $estatuses[$li->status_id],
                'Fecha de Creacion' => $li->created_at,
                'Creado por' => $li->creator,
                'Ultimo cambio' => $li->end_at,
                'Días' =>  $feDiff->days
            ];  




            $wExcel->writeSheetRow('Sheet1', $row );


            $partials = \App\Partial::where("order_id",$li->id)->get();
            foreach($partials as $partial){
                $row["Parcial #"] = $partial->invoice;
                $row["SP Estatus"] = $estatuses[$partial->status_id];
                $row["SP Creado"] = strval($partial->created_at);
             
                $wExcel->writeSheetRow('Sheet1', $row );
            }

            $row["Parcial #"] = '';
            $row["SP Estatus"] = '';
            $row["SP Creado"] =null;


            $ordenesf =\App\ManufacturingOrder::where("order_id",$li->id)->get();
            foreach($ordenesf as $ord){
                $row['Orden de Fabricación']=$ord->number;
                $row['OF Status']= isset($estatuses[$ord->status_id]) ? $estatuses[$ord->status_id] : "";
                $row["OF Creada"] = strval($ord->created_at);
                $row['OF Sucursal'] = strval($ord->office());

                $wExcel->writeSheetRow('Sheet1', $row );
            }

            $row['Orden de Fabricación']= '';
            $row['OF Status']= '';
            $row['OF Creada']= '';
            $row['OF Sucursal']= '';


            $requisiciones = \App\PurchaseOrder::where("order_id",$li->id)->get();
            foreach($requisiciones as $req){
                $row['Requisición #']= $req->number;
                $row['Req Status']=isset($estatuses[$req->status_id]) ? $estatuses[$req->status_id] : '';
                $row["Req Creada"] = strval($req->created_at);
                $row['Req Sucursal']=strval($req->office());

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Requisición #']= '';
            $row['Req Status']='';
            $row['Req Creada']='';
            $row['Req Sucursal']='';


            $salidasm = \App\Smaterial::where("order_id", $li->id)->get();
            foreach($salidasm as $salm){
                $row['Salida Material']= $salm->code;
                $row['SM Estatus'] = isset($estatuses[$salm->status_id]) ? $estatuses[$salm->status_id] : '';
                $row['SM Creado'] = strval($salm->created_at);

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Salida Material']= '';
            $row['SM Estatus'] = '';
            $row['SM Creado'] = '';
            

            $devoluciones = \App\Debolution::where("order_id",$li->id)->get();
            foreach($devoluciones as $devol){
                $row['Devolución Razón'] = isset($reasons[$devol->reason_id]) ? $reasons[$devol->reason_id] : '' ;
                $row['Devolución Creada'] = strval($devol->created_at);

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Devolución Razón'] = '' ;
            $row['Devolución Creada'] = '';
            
            $refacturaciones = \App\Rebilling::where("order_id", $li->id)->get();
            foreach($refacturaciones as $ref){
                $row['Refacturación #']= $ref->number;
                $row['Ref Razón'] = isset($reasons[$ref->reason_id]) ? $reasons[$ref->reason_id] : '';
                $row['Ref URL'] = $ref->url;

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Refacturación #']= '';
            $row['Ref Razón'] = '';
            $row['Ref URL'] = '';

        }
        if(count($lista) > $maxOrders){
            $row=[];
            $row['ID']="Lista truncada. Máximo $maxOrders pedidos se pueden mostrar en este reporte. Haga la consulta con un rango de fechas o filtros más restrictivos.";
            $wExcel->writeSheetRow('Sheet1', $row );
        }


        $xlsFileName = "Busqueda_".date("d-m-Y H\hi\m").".xlsx";
        $this->MandaReporte($wExcel,$xlsFileName);
        return;
    }
    

    function MandaReporte(ExcelWriter $wExcel, string $xlsFileName){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($xlsFileName).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        

        
        $str = $wExcel->writeToString();
        header('Content-Length: '.strlen($str));
        echo $str;
        return;
    }
    


    public function subprocesos(){

        $user = User::find(auth()->user()->id);

        $hoy = new \DateTime();
        $hastaDef = $hoy->format("Y-m-d");
        $hoy->modify("-7 day");
        $desdeDef = $hoy->format("Y-m-d");
        

        $action = 'Reporte de Tiempos en Subprocesos';
        
        return view('reportes.subprocesos', compact('user', 'action', 'desdeDef','hastaDef'));
    }


    function reporte_participaciones(Request $request){
    
        $user = User::find(auth()->user()->id);

        // ********** FECHAS *************
        $fechas = $request->post("fechas");

        $partes = explode(" - ",$fechas);
            if(count($partes) < 2){
                return "f";
            }
        $desde = $partes[0];
        $desde = Tools::fechaIso($desde);
        $hasta = $partes[1];
        $hasta = Tools::fechaIso($hasta);

        $desdeOb = new \DateTime($desde);
        $hastaOb = new \DateTime($hasta);
    
        $desde = $desdeOb->format("Y-m-d 00:00:00");
        $hasta = $hastaOb->format("Y-m-d 23:59:59");


        $origen = $request->post("origen");
        $origen = Tools::_string($origen,1);
        $origenes=["C"=>"Cotización", "F"=>"Factura", "R"=>"Requisición Stock"];
        $origenStr = isset($origenes[$origen]) ? $origenes[$origen] : "" ;
           
    
        $subprocesos = $request->post("subprocesos");
        $subprocesos = is_array($subprocesos) ? $subprocesos : [];
    
        $termino = $request->post("termino");
        $termino = Tools::_string($termino,20);
        
        $userId = $request->post("user_id");
        $userId = intval($userId);


    //********************************   CATALOGOS    ******************** */
    $statuses = \App\Status::all();
    $estatuses = [];
        foreach($statuses as $st){
            $estatuses[$st->id]=$st->name;
        }
    $estatuses[1]="Generado";    
       


    $filtros = ["desde"=>$desde,"hasta"=>$hasta,"origen"=>$origen,"subprocesos"=>$subprocesos,"termino"=>$termino];

        if($user->role_id != 1){
            $userId = $user->id;
        }


    $version = "admin";
        if($user->role_id != 1){
            $version="otro";
        }


    $R = new Reportes();
    $lista = $R->Participaciones($filtros, $userId, $version);
    


    $modoPrueba = false;

    if($modoPrueba){
    echo $R->query;
    echo Tools::simpleTable($lista);
    }
    else{
        if(!empty($lista)){
            $columnasKeys = !empty($lista) ? array_keys( (array)$lista[0]) : [] ;
            $columnas = [];
                foreach($columnasKeys as $c){
                    $columnas[$c]="string";
                }
        
            $wExcel = new ExcelWriter();
            $wExcel->writeSheetHeader('Resultados', $columnas);
            $wExcel->setAuthor('Sistema Aceros2000');   
            
            foreach($lista as $row){
                $wExcel->writeSheetRow('Resultados', (array)$row );
            }    
        
            $tit = "Reporte Participaciones ".date("d_m_Y_H_i").".xlsx";
            $this->MandaReporte($wExcel,$tit);
        }else{
            //return redirect("reportes/participaciones");
            
            $action = 'Reportes Vacío';
            $regresara = url("reportes/participaciones");
            return view('reportes.vacio', compact('user', 'regresara'));
        }
    }



    }



    public function feed_usuarios(Request $request){
    $user = User::find(auth()->user()->id);

    $term = $request->post("term");

    $U = new User();
    $list = $U->where('name', 'like', '%'. $term.'%')->get();
    
    $arr=[];
        foreach($list as $li){
            $item = ["value"=>$li["id"], "label"=> $li["name"]." (".$li["office"].")"  ];
            $arr[]=(object)$item;
        }
    echo json_encode($arr);
    }


    
}
