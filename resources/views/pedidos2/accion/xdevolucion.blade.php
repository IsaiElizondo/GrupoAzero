<?php

use App\Reason;
$razones = Reason::get();

?>
<form action="{{ url('pedidos2/set_accion/'.$order_id.'?a=devolucion') }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    
    <div class="Fila">Adjunte la razón y evidencia de la devolución.</div>

    <div class="Fila"><label>Razón</label>

    <select name="razon" class="form-control">
    <option value="">-Elegir una razón-</option>
@foreach ($razones as $ra)
    <option value="{{$ra->id}}">{{ $ra->reason }}</option>
@endforeach
</select>

</div>

    <div class="Fila"><label>Número</label><input type="input" name="number" maxlength="190" class="form-control" /></div>
    
    <div class="Fila"><label>Archivo</label><input type="file" name="archivo" class="form-control" /></div>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Agregar" /> </div>

</aside>


</form>
