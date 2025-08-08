<form id="FSetAccion" method="POST" action="{{ url('pedidos2/devolucion_parcial_actualizar/'.$dev->id) }}">
    @csrf
        <aside class="AccionForm">
            <div class="Fila">
                <label>Folio</label>
                <input type="text" value="{{ $dev->folio }}" class="form-control" readonly />
            </div>

            <div class="Fila">
                <label> Motivo </label>
                <select name="motivo" class="form-control" required>
                    <option value="Error del Cliente" {{ $dev->motivo == 'Error del Cliente' ? 'selected' : '' }}> Error del Cliente </option>
                    <option value="Error Interno" {{$dev->motivo == 'Error Interno' ? 'selected' : ''}}> Error Interno </option>
                </select>
            </div>

            <div class="Fila">
                <label>Descripcion</label>
                <textarea name="descripcion" class="form-control" maxlength="300" rows="3"> {{$dev->descripcion}}</textarea>
            </div>

            <div class="Fila">
                <label>Tipo de devolución</label>
                <select name="tipo" class="form-control" required>
                    <option value="">-- No especificado --</option>
                    <option value="total" {{ $dev->tipo == 'total' ? 'selected' : ''}}>Devolución Total</option>
                    <option value="parcial" {{ $dev->tipo == 'parcial' ? 'selected' : ''}}>Devolución Parcial</option>
                </select>
            </div>

            <div class="Fila">
                <input type="submit" name="sb" class="form-control" value="Guardar" />
            </div>
        </aside>
</form>