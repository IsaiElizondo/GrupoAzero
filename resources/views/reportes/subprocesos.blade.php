@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')


<?php
$desde = new DateTime();
$hoyf= $desde->format("d/m/Y");
$desde->modify("-7 month");
$desdef = $desde->format("d/m/Y");
?>

<div class="content">
    <div class="container-fluid">
      <div class="row">


        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Tiempos de subprocesos</h4>
                        <p class="card-category"> </p>
                    </div>
                    <!-- <div class="col-md-8 col-sm-12 col-xs-12 "></div> -->
                </div>
            </div>
        </div>

        <main>
        <p class="tallrow">
<a class="button" href="{{ url('reportes') }}">&laquo; Regresar</a>

        </p>
        <form action="{{ url('reportes/reporte_subprocesos') }}" method='POST' >
                            @csrf

            <section class="FormularioCampos">

                <div class="Campo">
                    <input type="text" name="fechas" value="<?= ($desdef ." - ". $hoyf) ?>" maxlength="13" size="21" class="form-control" readonly />
                    <label>Rango de fechas</label>
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

<link rel="stylesheet" href="{{ url('/') }}/css/reportes.css?x=<?= rand(0,999) ?>">


@endsection



@push('js')

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



});

 </script>
@endpush
