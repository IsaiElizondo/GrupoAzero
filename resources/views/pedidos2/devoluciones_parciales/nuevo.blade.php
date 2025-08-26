<form id="FSetAccion" method="POST" action="{{ url('pedidos2/devolucion_parcial_guardar/'.$order->id) }}" enctype="multipart/form-data">
    @csrf
    <aside class="AccionForm">

        <div class="Fila">
            <label for="folio">Folio de nota de devolución *</label>
            <input type="text" name="folio" class="form-control" maxlength="100" required />
        </div>

        <div class="Fila">
            <label for="motivo">Motivo *</label>
            <select name="motivo" class="form-control" required>
                <option value="">-- Seleccionar un Motivo --</option>
                <option value="Error del Cliente">Error del Cliente</option>
                <option value="Error Interno">Error interno</option>
            </select>
        </div>

        <div class="Fila">
            <label for="descripcion">Descripción (opcional)</label>
            <textarea name="descripcion" class="form-control" maxlength="300" rows="3"></textarea>
        </div>

        <div class="Fila">
            <label for="tipo">Tipo de devolución *</label>
            <select name="tipo" class="form-control" required>
                <option value="">-- No especificado --</option>
                <option value="total">Devolución Total</option>
                <option value="parcial">Devolución Parcial</option>
            </select>
        </div>

        <div class="Fila">
            <label for="archivos">Adjuntar evidencias *</label>
            <input type="file" name="archivos[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple required />
            <small>Puedes subir entre 1 y 10 archivos, máximo 15 MB cada uno.</small>
        </div>

        <div class="Fila">
            <input type="submit" name="sb" class="form-control" value="Continuar" />
        </div>
    </aside>
</form>
