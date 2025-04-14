


@if ($paso == 1)
    <form action="{{ url('pedidos2/set_accion/'.$id.'?a=enpuerta') }}" id="FSetAccion" method="post">
    @csrf 
    <input type="hidden" name="paso" value="1"/>
    <input type="hidden" name="type" value="0"/>

    <p><b><center>Es OBLIGATORIO subir evidencia en el siguiente paso.</center></b></p>

    <nav class="SubAccion">
        <button rel="type" class="setto" val="1">Entrega chofer interno</button>
        <button rel="type" class="setto" val="2">Cliente recoge</button>
    </nav>



    </form>

@elseif ($paso == 2)
    <div class="AccionForm">
        
    <label>Agregar evidencias</label>


    <section mode="edit" class='attachList form-control' rel='enp' 
    uploadto="{{ url('pedidos2/attachlist?catalog=shipments&shipment_id='.$shipment->id) }}" 
    href="{{ url('pedidos2/attachlist?rel=enp&catalog=shipments&stage=1&shipment_id='.$shipment->id) }}"></section> 


    <div class="Fila" id="filaEnpuertaN" style="display:none"><input type="button" name="sb" class="form-control" onclick="MiModal.exit()" value="Terminar" /> </div>

    </div>
@endif



