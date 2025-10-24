@extends('layouts.app', ['activePage' => 'rutas', 'titlePage' => __('Asignar pedidos a rutas')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                @if(session('success'))
                    <div style="color:green; font-weight:bold;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-6 text-left">
                                <h4 class="card-title">Asignar pedidos a rutas</h4>
                                <p class="card-category">Selecciona pedidos y agrégalos a una ruta existente</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('rutas.index') }}" class="btn btn-sm btn-primary">
                                    <span class="material-icons">arrow_back</span> Regresar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="buscador" placeholder="Buscar pedido (folio, cliente, etc.)">
                            </div>
                            <div class="col-md-6 text-right">
                                <button id="btnBuscar" class="btn btn-primary btn-sm">Buscar</button>
                            </div>
                        </div>

                        <div id="listaPedidos">
                            @include('rutas.multie.lista', ['shipments' => $shipments])
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label>Selecciona una ruta existente:</label>
                                <select id="rutaSelect" class="form-control">
                                    <option value="">-- Seleccionar ruta --</option>
                                    @foreach($rutas as $r)
                                        <option value="{{ $r->id }}">
                                            {{ $r->numero_ruta }} - {{ $r->cliente->nombre ?? 'Sin cliente' }} ({{ $r->chofer->name ?? 'Sin chofer' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 text-right mt-4">
                                <button id="btnAsignar" class="btn btn-success">Asignar pedidos a la ruta</button>
                            </div>
                        </div>

                        <div id="resultado" class="mt-3" style="font-weight:bold;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){

            // Buscar pedidos
            $('#btnBuscar').click(function(){
                var term = $('#buscador').val();
                $.post("{{ route('rutas.multie_lista') }}", { term: term, _token: "{{ csrf_token() }}" }, function(data){
                    $('#listaPedidos').html(data);
                }).fail(function(){
                    alert('Error al buscar pedidos.');
                });
            });

            // Asignar pedidos seleccionados
            $('#btnAsignar').click(function(){
                var ruta_id = $('#rutaSelect').val();
                var lista = [];
                $('input[name="pedido_id[]"]:checked').each(function(){
                    lista.push($(this).val());
                });

                if(ruta_id === '' || lista.length === 0){
                    alert('Selecciona al menos una ruta y un pedido.');
                    return;
                }

                $.post("{{ route('rutas.set_multiruta') }}",{
                    ruta_id: ruta_id,
                    lista: lista,
                    _token: "{{ csrf_token() }}"
                }, function(resp){
                    if(resp.status == 1){
                        $('#resultado').css('color','green').text(resp.message);
                    }else{
                        $('#resultado').css('color','red').text(resp.error);
                    }
                }).fail(function(){
                    $('#resultado').css('color','red').text('Error al enviar los datos.');
                });
            });
        });
    </script>
@endpush
