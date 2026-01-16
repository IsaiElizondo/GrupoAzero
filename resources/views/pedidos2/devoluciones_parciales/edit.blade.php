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
                            onclick="eliminarEvidencia({{ $evidencia->id }})"
                            style="position:absolute; top:0; right:0; background:red; color:white; border:none; cursor:pointer; border-radius:50%; padding:2px 6px;">
                            X
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="Fila">
            <label>Agregar nuevas evidencias</label>
            <input type="file" name="archivos[]" id="inputArchivos" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple />
            <div id="preview" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px"></div>
        </div>

        <div class="Fila">
            <input type="submit" name="sb" class="form-control btn btn-primary" value="Guardar" />
        </div>
    </aside>
</form>



    <script>
        let archivosTemp = [];

        function eliminarEvidencia(id){
            
            if(!confirm("¿Seguro que quiere eliminar esta evidencia?")) return;

            fetch("{{ url('pedidos2/devolucion_parcial_evidencia_eliminar') }}/" + id, {
                method: "POST",
                headers: {"X-CSRF-TOKEN": "{{csrf_token() }}"}
            })
            .then(resp => resp.json())
            .then(data => {
                if(data.status == 1){
                    location.reload();
                }else{
                    alert("No se pudo eliminar evidencia");
                }
            });

        }

        function mostrarPreview(){
            
            const preview = document.getElementById('preview');
            if(!preview) return;
            preview.innerHTML = "";

            archivosTemp.forEach((file,index) => {
                const wrapper = document.createElement('div');
                wrapper.style.position = "relative";
                wrapper.style.width = "120px";
                wrapper.style.height = "120px";

                if(file.type && file.type.startsWith("image/")){
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.width = 120;
                    img.height = 120;
                    img.style.objectFit = "cover";
                    img.style.border = "1px solid #ccc";
                    img.style.borderRadius = "8px";
                    wrapper.appendChild(img);
                }else{
                    const box = document.createElement('div');
                    box.style.width = "120px";
                    box.style.height = "120px";
                    box.style.border = "1px solid #ccc";
                    box.style.borderRadius = "8px";
                    box.style.display = "flex";
                    box.style.alignItems = "center";
                    box.style.padding ="6px";
                    box.style.textAlign = "center";
                    box.style.fontSize = "11px";
                    box.textContent = file.name || "archivo";
                    wrapper.appendChild(box);
                }

                const btn = document.createElement('button');
                btn.type = "button";
                btn.innerText = "X";
                btn.style.position = "absolute";
                btn.style.top = "0";
                btn.style.right = "0";
                btn.style.background = "red";
                btn.style.color ="white";
                btn.style.cursor = "pointer";
                btn.style.borderRadius = "4px";
                btn.onclick = function(){
                    archivosTemp.splice(index, 1);
                    mostrarPreview();
                };
                wrapper.appendChild(btn);
                preview.appendChild(wrapper);
            });

        }

        document.addEventListener('change', function(e){
            if (e.target && e.target.id == 'inputArchivos'){
                const input = e.target;
                const nuevos = Array.from(input.files);
                archivosTemp = archivosTemp.concat(nuevos);
                input.value = "";        
                mostrarPreview();
            }
        });

        document.addEventListener('submit', function(e){
            const form = e.target;
            if(!(form && form.id == 'FSetAccion')) return;

            e.preventDefault();
            e.stopPropagation();

            const fd = new FormData();
            const token = form.querySelector('input[name="_token"]')?.value || '';
            const motivo = form.querySelector('select[name="motivo"]')?.value || '';
            const descripcion = form.querySelector('textarea[name="descripcion"]')?.value || '';
            const tipo = form.querySelector('select[name="tipo"]')?.value || '';

            fd.append('_token', token);
            fd.append('motivo', motivo);
            fd.append('descripcion', descripcion);
            fd.append('tipo', tipo);

            if(archivosTemp.length > 0){
                archivosTemp.forEach(f => fd.append('archivos[]', f));
            }

            fetch(form.action, {method: "POST", body:fd })
            .then(r => r.json())
            .then(data => {
                if(data.status == 1){
                    location.reload();
                }else{
                    alert("Error al guardar: " + (data.error || "Unprocessable Content"));
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error en la conexión");
            });
        }, true); 

    </script>
