<?php use App\Pedidos2; ?>

<h3> Confirmar entrega no exitosa - {{Pedidos2::Codigode($order)}}</h3>

<form id="FSetAccion" action="{{url('pedidos2/set_accion_noexitosa/'.$order->id) }}" method="post">
    @csrf

    <div class="Fila">
        <label><strong>Motivo:</strong></label><br>
        <label><input type="radio" name="motivo" value="Cliente no recibió">
            Cliente no recibió
        </label><br>

        <label><input type="radio" name="motivo" value="Chofer no alcanzo a entregar">
            Chofer no alcanzó a entregar
        </label><br>

        <label><input type="radio" name="motivo" value="Estatus marcado por error">
            Estatus marcado por error
        </label>
    </div>

        <div class="Fila">
            <label> Observaciones(opcional):</label><br>
            <textarea name="observaciones" rows="3" cols="56" maxlength="200"></textarea>
            <br>
            <small>(Máximo 200 caracteres)</small>
        </div>

        <div class="Fila"><input type="submit" value="Confirmar"/>
</form>