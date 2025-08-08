<?php
use App\Libraries\Tools;

?>

<link rel="stylesheet" href="{{ asset('css/devolucion_lista.css') }}">



@foreach($lista as $dev)
    <aside class="Subproceso {{ $dev->cancelado ? 'cancelado' : ''}} ">

        

        <div class="subfila">
            <b>Folio:</b>{{$dev->folio}}
            @if($dev->cancelado)
                <div class="AlertaGrande">(Cancelada)</div>
            @endif
        </div>

        
        <div class="subfila">
            <b>Motivo:</b>{{ $dev->motivo }}
        </div>

        <div class="subfila">
            <b>Descripcion</b>{{ $dev->descripcion }}
        </div>

        @if(!empty($dev->tipo))
            <div class="subfila">
                <b>Tipo:</b> {{ ucfirst($dev->tipo) }}
            </div>
        @endif 

        <div class="subfila">
            <b>Fecha:</b> {{ Tools::fechaMedioLargo($dev->created_at) }}
        </div>

        @if(!empty($dev->file))
            <div class="subfila">
                <b>Documento:</b>
                <a class="pdf" target="_blank" href="{{ asset('storage/'.str_replace('public/', '', $dev->file)) }}">Ver archivo</a>
            </div>
        @endif

        @if($user->role->name == 'Administrador' && !$dev->cancelado)
            <div class="subfila">
                <a class="btn editapg" href="{{url('pedidos2/devolucion_parcial_editar/'.$dev->id) }}">Editar</a>
                    <form method="POST" action="{{url('pedidos2/devolucion_parcial_cancelar/'.$dev->id) }}" class="formCancelarDevolucion" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm cancelarDevolucion" type="submit">Cancelar</button>
                    </form>
            </div>
        @endif
    </aside>
@endforeach