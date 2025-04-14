<form action="{{ url('pedidos2/set_accion/'.$id.'?a=refacturar') }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    
    <div class="Fila">Adjunte la evidencia de la refacturación.</div>

    <div class="Fila"><label>Número</label><input type="input" name="number" class="form-control" maxlength="190" /></div>
    
    <div class="Fila"><label>Archivo</label><input type="file" name="archivo" class="form-control" /></div>
    
    <div class="Fila"><input type="submit" name="sb" value="Agregar" class="form-control" /> </div>

</aside>


</form>
