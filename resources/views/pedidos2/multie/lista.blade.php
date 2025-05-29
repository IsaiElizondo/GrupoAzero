<?php
use App\Libraries\Tools;

$origins = ["F" => "Factura", "C" => "Cotización", "R" => "Requisición"];
?>

<aside class="ShipsLista">
    @foreach ($shipments as $ship)
    <div class="Pedido" rel="{{ $ship->id }}" del="{{ url('pedidos2/set_status/'.$ship->id) }}">

        {{-- Estatus visual --}}
        <div class="estatus E{{$ship->status_id}}">
            {{ $statuses[$ship->status_id] ?? 'Sin estatus' }}
        </div>

        <div class="Cont">

            
            <div class="factura">
                <label>Factura #</label>
                {{ $ship->invoice_number ?? '—' }}
            </div>

            <div class="cot">
                <label>Cotización #</label>
                {{ $ship->invoice ?? '—' }}
            </div>

            <div class="office">
                <label>Sede</label>
                {{ property_exists($ship, 'office') ? $ship->office : '—' }} 
            </div>

            <div class="origin">
                <label>Origin</label>
                {{ property_exists($ship, 'origin') ? $ship->origin : '—' }} 
            </div>

            <div class="client">
                <label>Cliente</label>
                {{ property_exists($ship, 'client') ? $ship->client : '—' }} 
            </div>

            <div class="createdat">
                <label>Creación</label>
                {{ property_exists($ship, 'created_at') ? Tools::fechaMedioLargo($ship->created_at) : '—' }} 
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
