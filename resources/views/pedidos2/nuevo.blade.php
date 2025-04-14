@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Crear Pedido')])

@section('content')


<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<input type="hidden" name="url_previous" value="{{ url()->previous()  }}"/>


<main class="content" >
    <section class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido Nuevo</h4>
         </div>
    </div>



    <form action="{{ url('pedidos2/crear') }}" id="FNuevo" class="Cuerpo" method="post" enctype="multipart/form-data">
        @csrf
    <fieldset>
            
    <h3 class="center">Iniciar con </h3>

            <div class="Eleccion">
            
                @if ($user->role_id ==1 || in_array($user->department_id,[3,4,8]) )
                <button class="Tipo" rel="F">Factura</button>
                @endif


                @if ($user->role_id ==1 || in_array($user->department_id,[3,4]) )
                <button class="Tipo" rel="C" title="Cotización">Pedido</button>
                @endif



                @if ($user->role_id ==1 || in_array($user->department_id,[7]) )
                <button class="Tipo" rel="R">Requisición Stock</button>
                @endif
            
            </div>

    </fieldset>

        <input type="hidden" name="origin" value=""/>
        <div>&nbsp;</div>

        <fieldset class="Tiposet or">
            <dl>
            <dt><label rel="code" F="Folio Factura *" C="Num Cotizacion *" R="Num Requisici[on *">Folio/Codigo</label></dt> 
            <dd><input type="text" name="code" class="form-control"  maxlength="24" autocomplete="off" placeholder="Min 4 caracteres" /></dd>
            
            <dt rel='client'><label>Clave de Cliente *</label></dt> 
            <dd rel='client'> <input type="text" name="client" class="form-control"   maxlength="45"/></dd>

            @if ($user->role_id == 1 || in_array($user->department_id,[4,7]) )
            
            <dt rel='archivo'><label rel="archivo" F="Archivo Factura" C="Archivo Cotizacion" R="Archivo Requisición">Archivo</label> </dt> 
            <dd rel='archivo'><input type="file" name="archivo"  class="form-control" /></dd>
            
            @endif

            <dt><label>Note</label></dt> 
            <dd> <textarea  name="nota" class="form-control" maxlength="520" ></textarea></dd>

            <dt></dt> 
            <dd><input type="submit" name="sb" value="Guardar"><span id="preGuardar">Indique todos los datos obligatorios * para guardar</span></dd>
        </dl>
        </fieldset>

        <blockquote class="Monitor"></blockquote>

    
    </form>
</main>

    @endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js')  }}" ></script>
<script type="text/javascript" >

$(document).ready(function(){

$(".Eleccion button").click(function(e){
    e.preventDefault();

    $(".Eleccion button").removeClass("activo");
    $(this).addClass("activo");
    let r = $(this).attr("rel");

    ShowTiposet(r);
});





    $("#FNuevo").ajaxForm({
        dataType:"json",
        error:function(err){
            alert(err.statuText);
        },
        success:function(json){
            if(json.status==0){
                alert(json.errors);
            }else{
                window.location.href = json.goto;
            }
        }
    });


    const codeInput = document.querySelector(".Tiposet input[name='code']");
    codeInput.addEventListener("change",UnlockContinuar);

    const clientInput = document.querySelector(".Tiposet input[name='client']");
    clientInput.addEventListener("change",UnlockContinuar);

    const notaInput = document.querySelector(".Tiposet textarea[name='nota']");
    notaInput.addEventListener("change",UnlockContinuar);

/*
    $(".Tiposet input[name='code']").on("change",function(e){
    console.log("change");
    setTimeout(UnlockContinuar,100);
    });

    $(".Tiposet input[name='client']").on("change",function(e){
    setTimeout(UnlockContinuar,100);
    });
*/


    $(".Tiposet.or").hide();

});




function ShowTiposet(r){   

    $(".Tiposet.or").show();
    $("#FNuevo [name='origin']").val(r);
    $("#FNuevo [name='sb']").hide();
    $(".Tiposet label[rel='code']").text($(".Tiposet label[rel='code']").attr(r));
    $(".Tiposet label[rel='archivo']").text($(".Tiposet label[rel='archivo']").attr(r));

    if(r=="F"){
        $("dt[rel='archivo']").hide();
        $("dd[rel='archivo']").hide();
    }else{
        $("dt[rel='archivo']").show();
        $("dd[rel='archivo']").show();       
    }

    if(r=="R"){
        $("dt[rel='client']").hide();
        $("dd[rel='client']").hide();       
    }else{
        $("dt[rel='client']").show();
        $("dd[rel='client']").show();        
    }

}

function UnlockContinuar(){
    let cd = $(".Tiposet").find("[name='code']").val();
    let ar = $(".Tiposet").find("[name='archivo']").val();
    let cli = $(".Tiposet").find("[name='client']").val();
    let ori = $("[name='origin']").val();

    var mostrar = true;
    //var formato = true;
    var errMsg="";
    //Code Len
    if(cd.length < 4){ 
        mostrar=false;
        errMsg += "El folio/número debe contener mínimo 4 caracteres. ";
    }
    //Cliente
    if(ori!="R" && cli.length < 3){
    mostrar=false;
    errMsg += "El número de cliente debe contener mínimo 3 caracteres. ";
    }

    //Formato Factura
        if(cd.length>2){
            cd = cd.toLowerCase();
            let ini = cd.substr(0,2);            
            console.log(ini);
            if(ori == "F" && ini!="a0" && ini!="bb"){
                
                mostrar = false;
               // formato = false;
                errMsg += "El folio de la factura debe iniciar con A0 o BB. ";
            }
        }

    if(mostrar){
    $("#FNuevo [name='sb']").show();
    $("#preGuardar").hide();
    $(".Monitor").text("");
    }
    else{
        $("#FNuevo [name='sb']").hide();
        $("#preGuardar").show();
        $(".Monitor").text(errMsg);
    }
    
}


</script>

@endpush
