<div class="FormaAccion">
    <div class="Fila">
        <label>El pedido fue entregado</label>
        <div>
         @if ( ($user->role_id == 1 || in_array($user->department_id,[4])) && ($order->status_id == 6 || $order->status_6 ==1) )
        <a class="deshacerEntregado" 
        href="{{ url('pedidos2/unset_entregado/'.$order->id) }}" title="Revertir Entrega y regresar al estado más reciente. Esta acción será guardada.">Deshacer entregado</a>
        
        @elseif ($user->role_id == 1)
        <a class="rehacerEntregado" 
        href="{{ url('pedidos2/set_accion_entregar/'.$order->id) }}" title="¿Confirma que el pedido fue entregado?">Marcar como entregado</a>
        @endif
        </div>
    </div>
    
<label>Agregar Evidencias</label>
<section class='attachList form-control' rel='entregar' 
uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=entregar&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<div id="filaTerminarEntrega" style="display: none;" ><input type="button" class="btn" value="Terminar" onclick="MiModal.exit()" /></div>
</div>
