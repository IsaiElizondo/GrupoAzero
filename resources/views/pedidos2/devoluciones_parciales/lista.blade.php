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

        @if($dev->evidencias->count() > 0)
            <div class="subfila">
                <b>Evidencias:</b>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px;">
                    @foreach($dev->evidencias as $evidencia)
                        @php
                            $extension = pathinfo($evidencia->file, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($extension), ['jpg','jpeg','png']))
                            {{-- Mostrar imagen en miniatura --}}
                            <a href="{{ asset('storage/'.$evidencia->file) }}" target="_blank">
                                <img src="{{ asset('storage/'.$evidencia->file) }}" 
                                    alt="Evidencia" 
                                    style="width:100px; height:100px; object-fit:cover; border:1px solid #ccc; border-radius:5px;">
                            </a>
                        @else
                            {{-- Mostrar PDF como link --}}
                            <a class="pdf" targset="_blank" href="{{ asset('storage/'.$evidencia->file) }}">
                                Ver archivo ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    @endforeach
                </div>
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