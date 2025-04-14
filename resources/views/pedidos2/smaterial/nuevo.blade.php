<?php
$estatuses = [5 => "En Puerta", 6=>"Entregado"];
    if($user->role_id == 1 || in_array($user->department_id,[4,5])){
        $estatuses[4]="Elaborada";
    }
ksort($estatuses);

    if($user->department_id == 8){
        $estatuses=[5 => "En Puerta"];
    }

    if($user->department_id == 4){
        $estatuses=[4=>"Elaborado", 6 => "Entregado"];
    }

$monTexts =[];
$monTexts[4] ="La salida de material fue generado.";
$monTexts[5] = "Puede agregar imágenes como evidencia";
$monTexts[6] = "Sube evidencia. Puede ser Fotografía o Escaneo de la Hoja Parcial Física firmada por el cliente.";
$monTexts[7] = "Sube una foto de la hoja parcial con el sello de cancelado.";


?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>
@endif


@if ($paso==1)
<form action="{{ url('pedidos2/smaterial_crear/'.$order_id.'?paso=1') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">


    
<div class="Fila"><label>Folio</label><input type="text" name="code" class="form-control" maxlength="24" /></div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        echo "<option value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>

    <p class="hiddenDisclaimer"><b>Es Obligatorio subir evidencia en el siguiente paso. No continúe si no puede subir evidencia.</b></p>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>


</form>


@else

<aside class="AccionForm">    

<div class="monitorSm">{{ $monTexts[$smaterial->status_id] }}</div>

@if ($smaterial->status_id > 4)
<div class="Fila"><label>Agregar Imágenes</label></div>

    <div id='atlSlot'  val="{{$smaterial->id}}" event="{{$smaterial->status_id}}"
        uploadto="{{ url('pedidos2/attachpost') }}" 
        listHref="{{ url('pedidos2/attachlist') }}">
    </div>  
@endif    
    <div class="Fila" id="smTerminar"  ><input type="button" name="parcialterminar" class="form-control" value="Terminar" /> </div>

</aside>

@endif
