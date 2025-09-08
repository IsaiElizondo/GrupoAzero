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
            <input type="file" name="archivos[]" id="inputArchivos" accept=".pdf,.jpg,.jpeg,.png" multiple />
            <div id="preview" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px"></div>
            <small>Puedes subir entre 1 y 10 archivos, máximo 15MB cada uno.</small>
        </div>

        <div class="Fila">
            <input type="submit" name="sb" class="form-control" value="Continuar" />
        </div>
    </aside>
</form>

@push('js')
    <script>
        let archivosTemp = [];

        document.addEventListener('change', function(e) {

            if (e.target && e.target.id === 'inputArchivos') {
                let nuevos = Array.from(e.target.files);
                archivosTemp = archivosTemp.concat(nuevos);
                mostrarPreview();
                e.target.value = ""; 
            }

        });

        function mostrarPreview(){

            let preview = document.getElementById('preview');
            if (!preview) return;

            preview.innerHTML = "";
            archivosTemp.forEach((file, index) => {
                let div = document.createElement('div');
                div.style.position = "relative";

                if (file.type.startsWith("image/")){
                    let img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.width = 120;
                    img.height = 120;
                    img.style.objectFit = "cover";
                    img.style.border = "1px solid #ccc";
                    img.style.borderRadius = "8px";
                    div.appendChild(img);
                }else{
                    div.innerText = file.name;
                }

                let btn = document.createElement('button');
                btn.type = "button";
                btn.innerText = "X";
                btn.style.position = "absolute";
                btn.style.top = "0";
                btn.style.right = "0";
                btn.style.background = "red";
                btn.style.color = "white";
                btn.style.border = "none";
                btn.style.cursor = "pointer";

                btn.onclick = function(){
                    archivosTemp.splice(index, 1);
                    mostrarPreview();
                };

                div.appendChild(btn);
                preview.appendChild(div);
            });

        }

    
        document.getElementById('FSetAccion').addEventListener('submit', function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            archivosTemp.forEach((file)=>{
                formData.append("archivos[]", file);
            });

            fetch(this.action, {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status == 1){
                    
                    location.reload();
                }else{
                    alert("Error: " + (data.error || "No se pudo guardar"));
                }
            })
            .catch(err =>{
                console.error(err);
                alert("Error en la conexión");
            });
            
        });

    </script>
@endpush