<form id="FSetAccion" method="POST" action="{{ url('pedidos2/devolucion_parcial_actualizar/'.$dev->id) }}" enctype="multipart/form-data">
    @csrf
    <aside class="AccionForm">

        <div class="Fila">
            <label>Folio</label>
            <input type="text" value="{{ $dev->folio }}" class="form-control" readonly />
        </div>

        <div class="Fila">
            <label>Motivo</label>
            <select name="motivo" class="form-control" required>
                <option value="Error del Cliente" {{ $dev->motivo == 'Error del Cliente' ? 'selected' : '' }}>Error del Cliente</option>
                <option value="Error Interno" {{ $dev->motivo == 'Error Interno' ? 'selected' : '' }}>Error Interno</option>
            </select>
        </div>

        <div class="Fila">
            <label>Descripcion</label>
            <textarea name="descripcion" class="form-control" maxlength="300" rows="3">{{ $dev->descripcion }}</textarea>
        </div>

        <div class="Fila">
            <label>Tipo de devolución</label>
            <select name="tipo" class="form-control" required>
                <option value="">-- No especificado --</option>
                <option value="total" {{ $dev->tipo == 'total' ? 'selected' : '' }}>Devolución Total</option>
                <option value="parcial" {{ $dev->tipo == 'parcial' ? 'selected' : '' }}>Devolución Parcial</option>
            </select>
        </div>

        <div class="Fila">
            <label>Evidencias actuales</label>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @foreach($dev->evidencias as $evidencia)
                    <div style="position: relative; display:inline-block;">
                        <img src="{{ asset('storage/'.$evidencia->file) }}" 
                             alt="Evidencia" 
                             style="width:100px; height:100px; object-fit:cover; border:1px solid #ccc; border-radius:5px;">

                             <button type="button"
                                class="btn btn-danger btn-sm"
                                style="position:absolute; top:0; right:0; border-radius:50%; padding:2px 6px;"
                                onclick="eliminarEvidencia({{ $evidencia->id }})">
                            X
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="Fila">
            <label>Agregar nuevas evidencias</label>
            <input type="file" name="archivos[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple />
            <small>Puedes subir varias (máx 10 en total incluyendo las existentes)</small>
        </div>

        <div class="Fila">
            <input type="submit" name="sb" class="form-control btn btn-primary" value="Guardar" />
        </div>
    </aside>
</form>

<script>
function eliminarEvidencia(id) {
    if (!confirm("¿Seguro que deseas eliminar esta evidencia?")) return;

    fetch("{{ url('pedidos2/devolucion_parcial_evidencia_eliminar') }}/" + id, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(resp => resp.json())
    .then(data => {
        if (data.status === 1) {
            location.reload();
        } else {
            alert("No se pudo eliminar la evidencia");
        }
    });
}
</script>