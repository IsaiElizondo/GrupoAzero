<?php
use App\Pedidos2;
use App\Libraries\Tools;

$estatuses =[2=>"Recibido por embarques",3=>"En fabricación",4=>"Fabricado"];



    if($user->role_id != 1 && $user->department_id == 4){
        $estatuses = [2=>"Recibido por embarques"];
    }

    if($user->role_id != 1 && $user->department_id == 5){
        $estatuses = [3=>"En fabricación",4=>"Fabricado"];
    }

    if($user->role_id == 1 || $user->department_id == 9){
        if($user->department_id == 9){$estatuses=[];} //VACIAR SI ES AU DITORIA
        $estatuses[10]="Recibido por Auditoría";
    }

 

?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Pedidos y ordenes de fabricación')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/multie.css?x='.rand(0,999)) }}" />

<main class="content">





    <div class="card Fila">

        <center> <a class="regresar" href="{{ url('pedidos2') }}">&laquo; Regresar</a> </center>
    </div>


    <div class="card">

        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Cambio de estatus masivo</h4>
            </div>
        </div>

        <p>&nbsp;</p>
        
        <form id="festatus" action="{{url('pedidos2/multie')}}" method="get">
        <p class="Fila center">

        <select name="estatus" class="form-control">
            <option value=""> -Elija uno- </option>
            @foreach ($estatuses as $k => $v)
            <option value="{{$k}}" {{ ($k==$estatus) ? "selected" : "" }}>{{$v}}</option>
            @endforeach

        </select>
        
        
        </p>
        </form>

        <div class="Cuerpo">
        <p>Escriba un shipment en el campo de texto. Aparecerá uno o más pedidos en la lista. </p>
        <p>Puede usar la tecla de <b>Flecha Abajo</b> o la tecla de <b>Flecha Arriba</b> para recorrer varios resultados.</p>
        <p>Use la tecla <b>ENTER</b> para elegir el pedido para cambiar al estatus elegido. </p>

        <section class="SearchZone" >
            <div class="SearchDiv">
                <div class="Filita">
                <input type="text" class="" name="shipment" size="14" maxlength="16" href="{{ url('pedidos2/multie_lista') }}" />
                <span class="buscar"></span>
                </div>

                <div id="ShipsListaDiv"></div>

            </div>

            <div>
                <center><h4><b>Elegidos</b></h4></center>
            <div class="ResultsDiv">

            </div>
                <div>
                    <input type="button" href="<?= url("pedidos2/set_multistatus") ?>"
                    class="form-control" id="confirmButton" value="Confirmar" />
                    @csrf
                </div>
            </div>


        </section>

    </div>


    </div>    

</main>


@endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 

<script type="text/javascript">
$(document).ready(function(){

    $("[name='estatus']").change(function(){
        $("#festatus").submit();
    });


    $("[name='shipment']").change(function(){
    let href = $(this).attr("href");
    let val = $(this).val();
    let ide = $("[name='estatus']").val();

        $.ajax({
            url:href,
            data:{term:val, estatus:ide},
            error:function(err){alert(err.statusText);},
            success:function(h){
                $("#ShipsListaDiv").html(h);
                ActivaShipments();
            }
        });

    });


    $("body").on("click", ".SearchDiv .Pedido", function(){
        Enfoca(this);
        setTimeout(AgregarALista, 100);        
    });



    let valEstatus = $("[name='estatus']").val();
    if(valEstatus == ""){
        $(".Cuerpo").hide();
    }

    $("body").on("click",".PedidoPar a.del",function(){
        $(this).closest(".PedidoPar").remove();
    });

    $("[name='shipgment']").on("click",function(){
        $(".Pedido").removeClass("focus"); 
    });
  
    
    $("#confirmButton").click(function(){
        Enviar();
    });
    $("#confirmButton").hide();

});



function Enfoca(ob){
    $(".Pedido").removeClass("focus");
    $(ob).focus();

    $(ob).addClass("focus");
   // $(ob).find(":radio").prop("checked",true);
}


function ActivaShipments(){
    let cuantos = $("#ShipsListaDiv .Pedido").length;
    if(cuantos == 1){
        $("#ShipsListaDiv .Pedido").eq(0).click();
    }
}

function AbrirConfirmacion(){
    let focused = $(".ShipsLista .Pedido.focus");
    let href = $(focused).attr("del");
    //let cont = $(focused).find(".Cont").html();
    let strEstatus = $("[name='estatus']").find(":selected").text();
    let idEstatus = $("[name='estatus']").val();
    //let conth = "<div class='Pedido'><div class='Cont'>" +cont + "</div></div>" + "<p>Confirma que este pedido cambiará al estatus '"+strEstatus+"'</p>";
    let txt =  "Confirma que este pedido cambiará al estatus '"+strEstatus+"'";
    //MiModal.content(conth);
    //MiModal.show();
    if(confirm(txt)){
        $.ajax({
            url:href,
            datatype:"json",
            data:{ids:idEstatus},
            success:function(json){
                FocusBuscar();
                $("[name='shipment']").change();
            }
        });
        
    }
}



function FocusBuscar(){
    $(".SearchDiv [name='shipment']").focus();
}


function AgregarALista(){
    let focused = $(".ShipsLista .Pedido.focus");
    let href = $(focused).attr("del");
    let rel = $(focused).attr("rel");
    $(focused).removeClass("focus")

    //let cont = $(focused).find(".Cont").html();
    let strEstatus = $("[name='estatus']").find(":selected").text();
    let idEstatus = $("[name='estatus']").val();

    var item = "<div class='PedidoPar'>";
    item += "<div class='pc' rel='"+rel+"'></div>";
    item += "<div><a class='del'>X</a></div>";
    item += "</div>";


    $(".ResultsDiv").append(item);

    $(".ResultsDiv .pc[rel='"+rel+"']").html(focused);

    $("#confirmButton").show();

    FocusBuscar();

}


function Enviar(){
   var dd = new FormData();
   let href = $("#confirmButton").attr("href");
    let est = $("[name='estatus']").val();

    $(".ResultsDiv .Pedido").each(function(){
        dd.append("lista[]",$(this).attr("rel"));
    });
    
    dd.append("catalogo", (est==2 || est==10) ? "order" :"morder");
    dd.append("status_id", est);
    dd.append("_token",$(":hidden[name='_token']").val());

    $.ajax({
        url: href,
        data: dd,
        processData: false,
        contentType:false,
        type: 'POST',
        dataType:"json",
        success: function ( json ) {
            if(json.status==1){
                alert(json.value + " registros cambiados");
                $(".ResultsDiv").html("");
            }else{
                alert(json.errros);
            }            
        }
    });
}


</script>



@endpush
