<?php
$estatuses = [1=>"Elaborada"];
if(in_array($user->department_id, [4,7]) || $user->role_id == 1){
    $estatuses[5]="En Puerta";
}
if(in_array($user->department_id, [4,7]) || $user->role_id == 1){
    $estatuses[6]="Entregada";
}
if($user->role_id==1 || in_array($user->department_id,[7])){
    $estatuses[7]="Cancelada";
}
?>
<form action="{{ url('pedidos2/requisicion_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm ">
    <div class="ScrollModal">
    <div class="Fila "><label># Requisición</label> <br/>
    @if ($user->role_id == 1 )
    <span><input type="text" name="number" maxlength="18" class="form-control" value="{{ $ob->number }}" /></span> 
    @else
    <span>{{$ob->number}}</span> 
    @endif
    

    </div>




    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $selected = ($k == $ob->status_id) ? "selected" : "";
        echo "<option value='$k' $selected >$v</option>";
        }
        ?>
    </select>
    </div>

    
    <div class="Fila"  id="rowFolioSM">
        <label>Folio Salida de Materiales</label>
        <span><input type="text" name="code_smaterial" class="form-control" value="{{$ob->code_smaterial}}" /></span>
    </div>

    @if (isset($ob->document) && !empty($ob->document) )
    <div class="Fila">
        <label>Archivo Factura</label>
        <div>
       
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}        


        @if($user->role_id == 1)
        <div><input type="file" name="document" class="form-control" /></div>
        @endif 
    
    </div>
    </div>
    @endif

    <div class="Fila archivo" rel="1">
        <label>Archivo Requisición</label>
        <div>
            <div>
            @if (isset($ob->requisition))
                <div id='fileDiv'>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->requisition]) }}            
                </div>
            @endif
            </div>   
            @if($user->role_id == 1 || in_array($user->department_id, [4,5,7]) )
            <div><input type="file" name="requisition" class="form-control" /> </div>
            @endif
        </div>

    </div>



    <div class="Fila archivo" rel="5">
        <label>Archivo Requisición Puerta</label>
        <div>
            <div>
            @if (isset($ob->requisition))
                <div id='fileDiv'>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document_5]) }}            
                </div>
            @endif
            </div>   
            @if($user->role_id == 1 || in_array($user->department_id, [5,7]))
            <div><input type="file" name="document_5" class="form-control" /> </div>
            @endif
        </div>

    </div>



    <div class="Fila archivo" rel="6">
        <label>Archivo Requisición Entregada</label>
        <div>
            <div>
            @if (isset($ob->requisition))
                <div id='fileDiv'>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document_6]) }}            
                </div>
            @endif
            </div>   
            @if($user->role_id == 1 || in_array($user->department_id, [5,7]))
            <div><input type="file" name="document_6" class="form-control" /> </div>
            @endif
        </div>

    </div>


    <div class="Fila archivo" rel="7">
        <label>Archivo Cancelación de Requisición</label>
        <div>
            <div>
            @if (isset($ob->requisition))
                <div id='fileDiv'>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document_7]) }}            
                </div>
            @endif
            </div>   
            @if($user->role_id == 1 || in_array($user->department_id, [5,7]) )
            <div><input type="file" name="document_7" class="form-control" /> </div>
            @endif
        </div>

    </div>



    </div>
    
    <div class="Fila " id="SubmitButtonRow"><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>
