<?php
use App\Libraries\Tools;

$origins = ["F" => "Factura", "C" => "Cotización", "R" => "Requisición"];
?>

<aside class="ShipsLista">
    @foreach ($lista as $ship)
    <div class="Pedido" rel="{{ $ship->id }}" del="{{ url('pedidos2/set_status/'.$ship->id) }}">

        <div class="estatus E{{$ship->status_id}}">
            {{ $statuses[$ship->status_id] ?? 'Sin estatus' }}
        </div>

        <div>&nbsp;</div>

        <div class="FilaMorder morder">

            <div class="num">
                <label>Número Orden #</label>
                <div>{{ $ship->number ?? '—' }}</div>
            </div>

            <div class="order">
                @if (empty($ship->invoice_number))
                    <label>Cotización #</label>
                    <div>{{ $ship->invoice ?? '—' }}</div>
                @else
                    <label>Factura #</label>
                    <div>{{ $ship->invoice_number ?? '—' }}</div>
                @endif
            </div>

            <div class="createdat">
                <label>Creación</label>
                <div>
                    {{ property_exists($ship, 'created_at') ? Tools::fechaMedioLargo($ship->created_at) : '—' }} 
                </div>
            </div>

            {{-- VER ETIQUETAS YA ASIGNADAS --}}
            @if (isset($etiquetas_por_pedido[$ship->id]))
                <div class="etiquetas-actuales" style="margin-top: 6px;">
                    @foreach ($etiquetas_por_pedido[$ship->id] as $etiqueta)
                        <span class="etiqueta-visual" style="
                            background-color: {{ $etiqueta->color ?? '#CCC' }};
                            color: white;
                            padding: 2px 6px;
                            margin-right: 4px;
                            border-radius: 4px;
                            font-size: 12px;
                        ">
                            {{ strtoupper($etiqueta->nombre) }}
                        </span>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    @endforeach
</aside>
