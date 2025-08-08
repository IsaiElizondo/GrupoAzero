<form id="FSetAccion" method="POST" action="{{ url('pedidos2/devolucion_parcial_guardar/'.$order->id) }}" enctype="multipart/form-data">
    @csrf
        <aside class="AccionForm">

            <div class="Fila">
                <label for="folio"> Folio de nota de devolución *</label>
                <input type="text" name="folio" class="form-control" maxlength="100" required/>
            </div>

            <div class="Fila">
                <label for="motivo">Motivo *</label>
                <select name="motivo" class="form-control" required>
                    <option value="">-- Seleccionar un Motivo --</option>
                    <option value="Error del Cliente"> Error del Cliente</option>
                    <option value="Error Interno"> Error interno</option>
                </select>
            </div>

            <div class="Fila">
                <label for="descripcion"> Descripcion (opcional)</label>
                <textarea name="descripcion" class="form-control" maxlength="300" rows="3"></textarea>
            </div>

            <div class="Fila">
                <label for="tipo"> Tipo de devolución *</label>
                <select name="tipo" class="form-control" required>
                    <option value="">-- No especificado --</option>
                    <option value="total"> Devolucion Total</option>
                    <option value="parcial"> Devolución parcial </option>
                </select>
            </div>

            <div class="Fila">
                <label for="archivo"> Adjuntar evidencia *</label>
                <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
            </div>

            <div class="Fila">
                <input type="submit" name="sb" class="form-control" value="Continuar" />
            </div>
        </aside>
</form>

