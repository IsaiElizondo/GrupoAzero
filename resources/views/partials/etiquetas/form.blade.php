{{-- ETIQUETAS ACTIVAS (SOLO LECTURA) --}}
@if(count($EtiquetasAsignadas) > 0)
    <div class="card etiquetas-card">
        <div class="headersub">Etiquetas activas (SOLO LECTURA)</div>
        <div class="Eleccion">
            @foreach($EtiquetasVisibles as $etiqueta)
                @if(in_array($etiqueta->id, $EtiquetasAsignadas))
                    <div class="etiqueta-item">
                        <input type="checkbox" disabled checked id="etiqueta_view_{{ $etiqueta->id }}" class="etiqueta-checkbox">
                        <label for="etiqueta_view_{{ $etiqueta->id }}" class="Candidato etiqueta-label checked" style="background-color: {{ $etiqueta->color ?? '#CCCCCC' }}; color:white;">
                            {{ strtoupper($etiqueta->nombre) }}
                        </label>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif


{{-- ETIQUETAS EDITABLES --}}
@if($EtiquetasEditables->count() > 0)
    <form method="POST" action="{{ route('pedido.etiquetas.guardar', ['id' => $id]) }}">
        @csrf
        <div class="card etiquetas-card">
            <div class="headersub">Etiquetas disponibles</div>
            <div class="Eleccion">
                @foreach($EtiquetasEditables as $etiqueta)
                    <div class="etiqueta-item">
                        <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" id="etiqueta_{{ $etiqueta->id }}" class="etiqueta-checkbox" {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}>
                        <label for="etiqueta_{{ $etiqueta->id }}" class="Candidato etiqueta-label {{ in_array($etiqueta->id, $EtiquetasAsignadas) ? 'checked' : '' }}" style="background-color: {{ $etiqueta->color ?? '#CCCCCC' }}; color:white;">
                            {{ strtoupper($etiqueta->nombre) }}
                        </label>
                    </div>
                @endforeach
            </div>
            <br>
            <button class="btn btn-dark" type="submit">Guardar</button>
        </div>
    </form>
@endif