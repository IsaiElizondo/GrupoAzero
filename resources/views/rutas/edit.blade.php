@extends('layouts.app', ['activePage' => 'rutas', 'titlePage' => __('Editar Ruta')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if($errors->any())
                    <div style="color:red; margin-bottom:12px;">
                        <strong>Corrige los siguientes errores:</strong>
                        <ul style="margin:6px 0 0 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('rutas.update', $ruta->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-4 text-left">
                                    <h4 class="card-title">Editar Ruta #{{ $ruta->numero_ruta }}</h4>
                                    <p class="card-category">Actualiza los datos de la ruta</p>
                                </div>
                                <div class="col-md-8 text-right">
                                    <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons">arrow_back</span>
                                        Regresar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <label class="col-sm-2 col-form-label text-right">Unidad</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="unidad_id">
                                        <option value="">-- Seleccionar unidad --</option>
                                        @foreach($unidades as $u)
                                            <option value="{{ $u->id }}" {{ $ruta->unidad_id == $u->id ? 'selected' : '' }}>
                                                {{ $u->nombre_unidad }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-2 col-form-label text-right">Chofer</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="chofer_id" required>
                                        @foreach($choferes as $ch)
                                            <option value="{{ $ch->id }}" {{ $ruta->chofer_id == $ch->id ? 'selected' : '' }}>
                                                {{ $ch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <label class="col-sm-2 col-form-label text-right">Fecha Ruta</label>
                                <div class="col-sm-4">
                                    <input type="datetime-local" name="fecha_hora"
                                        class="form-control"
                                        value="{{ $ruta->fecha_hora ? \Carbon\Carbon::parse($ruta->fecha_hora)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>

                            <hr class="mt-4 mb-3">

                            <h5 style="font-weight:bold;">Pedidos asignados a esta ruta</h5>

                            <div class="row mt-3 mb-3">
                                <div class="col-md-6">
                                    <input type="text" id="buscadorPedidos" class="form-control" placeholder="Buscar pedido...">
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" id="btnBuscarPedidos" class="btn btn-primary btn-sm">
                                        Buscar nuevos pedidos
                                    </button>
                                </div>
                            </div>

                            <div id="listaPedidos" class="mt-3"></div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaSeleccionados">
                                    <thead>
                                        <tr>
                                            <th>Pedido</th>
                                            <th>Estatus Pago</th>
                                            <th>Monto por Cobrar</th>
                                            <th>Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ruta->orders as $order)
                                            <tr id="pedido_{{ $order->id }}">
                                                <td>
                                                    {{ $order->invoice_number ?? 'Sin folio' }}
                                                    <input type="hidden" name="pedidos[]" value="{{ $order->id }}">
                                                </td>
                                                <td>
                                                    <select name="estatus_pago[]" class="form-control form-control-sm">
                                                        <option value="pagado" {{ $order->pivot->estatus_pago == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                        <option value="por_cobrar" {{ $order->pivot->estatus_pago == 'por_cobrar' ? 'selected' : '' }}>Por cobrar</option>
                                                        <option value="credito" {{ $order->pivot->estatus_pago == 'credito' ? 'selected' : '' }}>Crédito</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="monto_por_cobrar[]" class="form-control form-control-sm"
                                                        value="{{ $order->pivot->monto_por_cobrar ?? 0 }}">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm quitarPedido" data-id="{{ $order->id }}">
                                                        <span class="material-icons" style="font-size:18px;">remove_circle</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function(){

    $(document).on('click', '.quitarPedido', function(){
        var id = $(this).data('id');
        $('#pedido_'+id).remove();
    });

    $('#btnBuscarPedidos').click(function(){
        var term = $('#buscadorPedidos').val();
        if(!term) return;
        $.post("{{ route('rutas.multie_lista') }}",{
            term: term,
            _token: "{{ csrf_token() }}"
        }, function(data){
            $('#listaPedidos').html(data);
        }).fail(function(){
            alert('Error al buscar pedidos.');
        });
    });

    $(document).on('change', 'input[name="pedido_id[]"]', function(){
        var pedidoId = $(this).val();
        var folio = $(this).data('folio');
        var origin = $(this).data('origin');

        if($(this).is(':checked')){
            var fila = `
                <tr id="pedido_${pedidoId}">
                    <td>
                        ${folio}
                        <input type="hidden" name="pedidos[]" value="${pedidoId}">
                        <input type="hidden" name="partial_folio[]" value="${origin == 'P' ? folio : '' }">
                        <input type="hidden" name="sm_folio[]" value="${origin == 'SM' ? folio : '' }">
                    </td>
                    <td>
                        <select name="estatus_pago[]" class="form-control form-control-sm">
                            <option value="pagado">Pagado</option>
                            <option value="por_cobrar">Por cobrar</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="monto_por_cobrar[]" class="form-control form-control-sm" value="0.00">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm quitarPedido" data-id="${pedidoId}">
                            <span class="material-icons" style="font-size:18px;">remove_circle</span>
                        </button>
                    </td>
                </tr>
            `;
            $('#tablaSeleccionados tbody').append(fila);
        }else{
            $('#pedido_'+pedidoId).remove();
        }
    });

});
</script>
@endpush
