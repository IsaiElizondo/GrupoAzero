
<div class="FormaAccion">

<label>Agregar Evidencias</label>
<p>Para actualizar el estatus a entregado es requisito subir evidencia.</p>

<section class='attachList form-control' rel='entregar' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&stage=1&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=entregar&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<div class="Fila" id="filaTerminarEntrega" style="display: none;" ><input type="button" class="form-control" value="Terminar" onclick="MiModal.exit()" /></div>
</div>
