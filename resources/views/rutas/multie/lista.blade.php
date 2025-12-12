<div class="table-responsive">
    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th width="40px"><input type="checkbox" id="selectAll"></th>
                <th> Folio </th>
                <th> Cliente </th>
                <th> Dirección </th>
                <th> URL (Mapa) </th>
                <th> Celular / Telefono </th>
                <th> Sucursal </th>
                <th> Creado en </th>
                <th> Estatus </th>
            </tr>
        </thead>
        <tbody>
            @forelse($shipments as $pedido)
                @php
                    $order = \App\Order::find($pedido->order_id);
                    
                    $telefonos = [];
                    if($order->telefono){
                        $telefonos[] = $order->telefono;
                    }
                    if($order->celular){
                        $telefonos[] = $order->celular;
                    }

                    $estatus = $order ? (\App\Status::find($order->status_id)->name ?? '-') : '-';
                @endphp
                <tr>
                    <td>
                        <input type="checkbox"
                            name="pedido_id[]"
                            value="{{ $pedido->order_id }}"
                            data-folio="{{ $pedido->folio }}"
                            data-tipo="{{ $pedido->tipo }}"
                            data-id-real="{{ $pedido->id_real }}">
                    </td>
                    <td>
                        <a href="{{ url('pedidos2/pedido/'.$order->id) }}">
                            @if($pedido->origin == 'P')
                                {{ $pedido->folio }}
                            @elseif($pedido->origin == 'SM')
                                {{ $pedido->folio }}
                            @else
                                {{ $order->invoice_number }}
                            @endif
                        </a>
                    </td>
                    <td>{{ $order->client ?? '-' }}</td>
                    <td>{{ $order->direccion ?? '-'}}</td>
                    <td>
                        @if($order && $order->url_mapa)
                            <a href="{{ $order->url_mapa }}" target="_blank"> Ver Mapa</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ count($telefonos) ? implode ('/', $telefonos) : '-'}}</td>
                    <td>{{ $order->office ?? '-' }}</td>
                    <td>{{ $pedido->created_at }}</td>
                    <td>{{ $estatus }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No se encontraron pedidos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    $('#selectAll').click(function(){
        $('input[name="pedido_id[]"]').prop('checked', this.checked);
    });
});
</script>
