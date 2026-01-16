document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('cp-input')) return;

    const cpInput = e.target;
    const cp = cpInput.value.trim();

    const contenedor = cpInput.closest('.direccion-item') || cpInput.closest('.direccion-scope');

    if (!contenedor) return;

    const estadoInput = contenedor.querySelector('.estado-input');
    const ciudadInput = contenedor.querySelector('.ciudad-input');
    const coloniaSelect = contenedor.querySelector('.colonia-input');


    if (cp.length !== 5 || !/^\d{5}$/.test(cp)) {
        if (estadoInput) estadoInput.value = '';
        if (ciudadInput) ciudadInput.value = '';
        if (coloniaSelect) coloniaSelect.innerHTML = '';
        return;
    }

    fetch(`/api/sepomex/buscar-cp?codigo_postal=${cp}`)
        .then(res => res.json())
        .then(data => {
            if(!data.ok){
                if(estadoInput) estadoInput.value = '';
                if(ciudadInput) ciudadInput.value = '';
                if(coloniaSelect) coloniaSelect.innerHTML = '';
                
                return;
            }

            if(estadoInput) estadoInput.value = data.estado;
            if(ciudadInput) ciudadInput.value = data.ciudad;

            if(coloniaSelect){
                coloniaSelect.innerHTML = '<option value="">Seleccione colonia</option>';
               const seleccionada = coloniaSelect.dataset.selected || '';
                data.colonias.forEach(col => {
                    const opt = document.createElement('option');
                    opt.value = col;
                    opt.textContent = col;
                    const normalizar = (str) =>
                        str
                            .toLowerCase()
                            .normalize("NFD")
                            .replace(/[\u0300-\u036f]/g, '')
                            .trim();
                    if(seleccionada && normalizar(col) === normalizar(seleccionada)){
                        opt.selected = true;
                    }
                    coloniaSelect.appendChild(opt);
                });

            }
        })
        .catch(() => {
            if(estadoInput) estadoInput.value = '';
            if(ciudadInput) ciudadInput.value = '';
            if(coloniaSelect) coloniaSelect.innerHTML = '';
        });
});

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        document.querySelectorAll('.cp-input').forEach(cpInput => {
            if (cpInput.value && cpInput.value.length === 5) {
                cpInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }, 0);
});


