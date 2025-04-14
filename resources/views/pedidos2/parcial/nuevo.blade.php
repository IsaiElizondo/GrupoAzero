<?php
$estatuses = [6=>"Entregado"];
    if($user->role_id==1 ||  in_array($user->department_id, [4,8])){
        $estatuses[4]="Generado";
    }
    if($user->role_id==1 || in_array($user->department_id, [8])){
        $estatuses[5]="En Puerta";
    }
    if($user->role_id==1 || in_array($user->department_id, [4])){
        $estatuses[7]="Cancelada";
    }

    ksort($estatuses);

    if($user->department_id == 8){
        $estatuses=[5 => "En Puerta"];
    }
    
?>

@if (!empty($error) )
<div class="ErrorMsg">{{$error}}</div>

@endif


@if ($paso==1)
<form action="{{ url('pedidos2/parcial_crear/'.$order_id.'?paso=1') }}" id="FSetParcial" rel="nuevo" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
<div class="Fila"><label>Folio</label><input type="text" name="invoice" class="form-control" maxlength="24" /></div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        echo "<option value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Continuar" /> </div>

</aside>
</form>


@else

<aside class="AccionForm">    
{{ $estatuses[$partial->status_id] }}
    @if ( in_array($partial->status_id,[5,6]) )
    <div class="Fila"><label>Agregar Imágenes</label></div>
        @if ($partial->status_id == 6)
            <div>Sube foto de salida parcial firmada por el cliente.</div>
        @endif

    <div id='atlSlot'  val="{{$partial->id}}" event="{{ $partial->status_id }}"
        uploadto="{{ url('pedidos2/attachpost') }}" 
        listHref="{{ url('pedidos2/attachlist') }}">
    </div>  
    @endif 
    
    <div class="Fila"><input type="button" name="parcialterminar" class="form-control" value="Terminar" /> </div>

</aside>

@endif
