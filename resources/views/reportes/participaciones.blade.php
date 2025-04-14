@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')


<?php
$desde = new DateTime();
$hoyf= $desde->format("d/m/Y");
$desde->modify("-7 month");
$desdef = $desde->format("d/m/Y");
?>
<style type="text/css">
.Campos{
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 40px;
    row-gap: 30px;
    align-items: start;
}
.subp{
    display:flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    align-items: start;
    flex-direction: row;
    row-gap:20px;
    column-gap:30px;
    margin:0px;
    padding:0px;
}
.subp li{
    list-style: none;
    padding:0px;
    margin:0px;
}
.subp li label{
    cursor:pointer;
}
#usuarioSpan{
    display:inline-block;
    width:12rem;
    color:#222;
    text-shadow: #999 0px 0px 1px;
}
.autoCompletador{
    border:none;
    border-bottom:#aaa 1px solid;
}

@media screen and (max-width:560px) {
    .Campos{
    grid-template-columns: 1fr;
    row-gap: 20px;
    align-items: start;
}
}
</style>
<div class="content">
    <div class="container-fluid">
      <div class="row">


        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Participacion en pedidos</h4>
                        <p class="card-category"> </p>
                    </div>
                    <!-- <div class="col-md-8 col-sm-12 col-xs-12 "></div> -->
                </div>
            </div>
        </div>

        <main>
        <p class="tallrow">
        <a href="{{ url('reportes') }}" class="button">&laquo; Regresar</a>

        </p>
        <form action="{{ url('reportes/reporte_participaciones') }}" method='POST' >
                            @csrf



            <section class="Campos">

            @if ($user->role_id == 1)
                <div class="Campo">
                    <input type="hidden" name="feed_usuarios" value="{{ url('reportes/feed_usuarios')  }}" />
                    <input type="text" id="usuarioCampo" name="user_id" value="" maxlength="60" size="18" 
                    class="autoCompletador" placeholder="Nombre o apellido..."  />
                    <span id='usuarioSpan'> </span>
                    <br/>
                    <label>Usuario</label>
                </div>
            @endif

                <div class="Campo">
                    <input type="text" name="termino" value="" maxlength="18" size="12" class="form-control" placeholder="Folio o número"  />
                    <label># de cotizacion, # de factura, o # Req. Stock</label>
                </div>

                
                <div class="Campo">
                <input type="text" name="fechas" value="<?= ($desdef ." - ". $hoyf) ?>" maxlength="13" size="21" class="form-control" readonly />
                <label>Periodo de Creación</label>
                </div>

                <div class="Campo">
                    <select name="origen" class="form-control" >
                        <option value="">Cualquiera</option>
                        <option value="F">Facturas</option>
                        <option value="C">Cotizaciones</option>
                        <option value="R">Requerimientos Stock</option>
                    </select>
                    <label>Creados como</label>
                </div>

                <div class="Campo">
                <ul class="subp">                   
                    
                    <li><label><input type="checkbox" name="subprocesos[]" value="ordenf" /> Orden de fabricación</label></li>
                    <li><label><input type="checkbox" name="subprocesos[]" value="requisicion" /> Requisición<label></li>
                    <li><label><input type="checkbox" name="subprocesos[]" value="smaterial" /> Salida de material</label></li>
                    <li><label><input type="checkbox" name="subprocesos[]" value="partial" /> Salida parcial</label></li>
                    <li><label><input type="checkbox" name="subprocesos[]" value="debolution" /> Devolución</label></li>
                </ul>
                <label>Sólo con subproceso</label>
                </div>


            </section>           

            <br/>
            <div class="">
            <button class="bot" id="botConsultar" >Consultar</button>
            </div>                     
                     
        </form>
</main>

        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('jqueryui/jquery-ui.min.css') }}" />

<link rel="stylesheet" href="{{ url('/') }}/css/reportes.css?x=<?= rand(0,999) ?>">


@endsection



@push('js')

<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>

<script type='text/javascript'>



 $(document).ready(function(){



 	
 	 $('.datetimepicker').datetimepicker({
         format: 'YYYY-MM-DD'
     });




     $('input[name="fechas"]').daterangepicker({
    timePicker: false,
    minDate: new Date("2021-10-11"),
    maxDate: new Date(),
    maxSpans:{
        "years":3
    },
    linkedCalendars: false,
    showDropdowns:true,
    //autoApply:true,
    autoUpdateInput:true,
    locale: {
      //format: 'YYYY-MM-DD',
      format:'DD/MM/YYYY',
      "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
    }
    });


    $( "#usuarioCampo" ).autocomplete({
      source: $("[name='feed_usuarios']").val(),
      minLength: 3,
      select: function( event, ui ) {
        //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        $("#usuarioSpan").text(ui.item.label);
      }
    });



});

 </script>
@endpush
