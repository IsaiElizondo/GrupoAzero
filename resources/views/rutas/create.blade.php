@extends('layouts.app', ['activePage' => 'rutas', 'titlePage' => __('Crear Ruta')])

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
                <form method="post" action="{{ route('rutas.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-4 text-left">
                                    <h4 class="card-title"> Crear Ruta </h4>
                                    <p class="card-category"> Completa los datos de la nueva ruta </p>
                                </div>
                                <div class="col-md-8 text-right">
                                    <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons"> arrow_back </span>
                                            Regresar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <label class="col-sm-2 col-form-label text-right"> Unidad </label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="unidad_id">
                                        <option value="">-- Seleccionar unidad --</option>
                                        @foreach($unidades as $u)
                                            <option value="{{ $u->id }}">{{ $u->nombre_unidad }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-2 col-form-label text-right"> Chofer </label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="chofer_id" required>
                                        <option value="">-- Seleccionar chofer --</option>
                                        @foreach($choferes as $ch)
                                            <option value="{{ $ch->id }}">{{ $ch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                
                            </div>

                            <hr class="mt-4 mb-3">

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 style="font-weight: bold;"> Agregar pedidos </h5>
                                    <p style="font-size: 13px;"> Busca los pedidos y define su estatus de pago y monto individual. </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" id="buscadorPedidos" class="form-control" placeholder="Buscar pedido...">
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" id="btnBuscarPedidos" class="btn btn-primary btn-sm">Buscar</button>
                                </div>
                            </div>

                            <div id="listaPedidos" class="mb-4">
                                
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaSeleccionados">
                                    <thead>
                                        <tr>
                                            <th> Pedido </th>
                                            <th> Estatus Pago </th>
                                            <th> Monto por Cobrar </th>
                                            <th> Quitar </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Guardar Ruta</button>
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

            $('#btnBuscarPedidos').click(function(){
                var term = $('#buscadorPedidos').val();
                $.post("{{ route('rutas.multie_lista') }}",{
                    term: term,
                    _token: "{{ csrf_token() }}"
                },function(data){
                    $('#listaPedidos').html(data);
                }).fail(function(){
                    alert('Error al buscar pedidos.');
                });
            });

            $(document).on('change', 'input[name="pedido_id[]"]', function(){
                var pedidoId = $(this).val();
                var folio = $(this).data('folio');

                if($(this).is(':checked')){
                    var fila = `
                        <tr id="pedido_${pedidoId}">
                            <td>
                                ${folio}
                                <input type="hidden" name="pedidos[]" value="${pedidoId}">
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

            $(document).on('click', '.quitarPedido', function(){
                var id = $(this).data('id');
                $('#pedido_'+id).remove();
                $('input[name="pedido_id[]"][value="'+id+'"]').prop('checked', false);
            });

        });
    </script>
@endpush
