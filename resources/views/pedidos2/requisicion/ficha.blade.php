<?php
$estatuses = [1=>"En Proceso", 5=>"En Puerta",6=>"Entregada",7=>"Cancelada"];
//var_dump($ob->status_id);
?>
<aside class="Subproceso">

    <span rel='inv'><strong>  {{ !empty($ob->number) ? "Requisición # ".$ob->number : "Requisición" }}</strong></span>

    <div rel='st'>
    <label><strong> </strong></label>
    <span>
        @if ( isset($estatuses[$ob->status_id]) )
        <div class="MiniEstatus E{{ $ob->status_id }}">{{ $estatuses[$ob->status_id] }}</div>
        @endif
 
    </span>
    </div>    
    


    <div rel='ed'>
    @if ($user->role_id == 1 || in_array($user->department_id,[4,7]))
    <a class="btn editarrequisicion" href="{{ url('pedidos2/requisicion_edit/'.$ob->id) }}">Editar</a>
    @endif
    </div>



    <div rel='fi' class="">
        <div class="space-around flex-wrap">

        @if (!empty($ob->code_smaterial))
        <div><b>{{$ob->code_smaterial}}</b><br/><center>Folio Salida de Material</center></div>
        @endif
        
        @if (isset($ob->document) && !empty($ob->document))
        <span class="statusItem">       
            
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
            <br/><center>Factura</center> 
        
        </span>
        @endif

        <span class="statusItem">
        @if (isset($ob->requisition))
            <center>  
            <div class="MiniEstatus E1">{{ $estatuses[1] }}</div>
            </center>  
            <br/>
            {{ view('pedidos2/view_storage_item',['path'=>$ob->requisition]) }}
            <div>{{ \App\Libraries\Tools::fechaMedioLargo($ob->status_1) }}</div>
        @endif
        </span>


        
        @if (!empty($ob->document_5))
        <span class="statusItem">  
            <center>  
            <div class="MiniEstatus E5">{{ $estatuses[5] }}</div>
            </center>
            <br/>
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document_5]) }}

            <div>{{ \App\Libraries\Tools::fechaMedioLargo($ob->status_5) }}</div>
        </span>
        @endif

        @if (!empty($ob->document_6))
        <span class="statusItem">    
            <center>  
            <div class="MiniEstatus E6">{{ $estatuses[6] }}</div>
            </center>  
            <br/>
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document_6]) }}

            <div>{{ \App\Libraries\Tools::fechaMedioLargo($ob->status_6) }}</div>
        </span>
        @endif

        @if (!empty($ob->document_7))
        <span class="statusItem">    
            <center>  
            <div class="MiniEstatus E7">{{ $estatuses[7] }}</div>
            </center>  
            <br/>
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document_7]) }}

            <div>{{ \App\Libraries\Tools::fechaMedioLargo($ob->status_7) }}</div>
        </span>
        @endif
        



 

        </div>
    </div>

</aside>
