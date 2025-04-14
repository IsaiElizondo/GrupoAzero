<form action="{{ url('pedidos2/set_accion/'.$id.'?a=ordenf') }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    
    <div class="Fila">Adjunte la orden de fabricación.</div>

    <div class="Fila"><label>Número</label><input type="input" name="number" maxlength="190" class="form-control" /></div>
    
    <div class="Fila"><label>Archivo</label><input type="file" name="archivo" class="form-control" /></div>
    
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Agregar" /> </div>

</aside>


</form>
