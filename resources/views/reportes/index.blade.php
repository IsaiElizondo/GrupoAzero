@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')

<?php 

$desde = new DateTime();
$hoyf= $desde->format("d/m/Y");
$desde->modify("-7 month");
$desdef = $desde->format("d/m/Y");

?>
<link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Reportes</h4>
                        <p class="card-category"> Reportes adicionales de la plataforma</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 ">
                        
                        <!--  
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                add_circle_outline
                            </span>
                            Nuevo Reporte
                        </a>
                        -->
                    </div>
                </div>
            </div>
            
            
            </div>
            
            <div class="card-body">
                
                <main>

                <aside class="ListaReportes">            

<a href="{{  url('reportes/subprocesos') }}">Subprocesos</a>

<a href="{{  url('reportes/participaciones') }}">Participaciones</a>

</aside>

                </main>


            </div>
            
    	</div>
	</div>
</div>

<link rel="stylesheet" href="{{ url('/') }}/css/reportes.css?x=<?= rand(0,999) ?>">


@endsection



@push('js')

<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>

<script type='text/javascript'>



 $(document).ready(function(){

 	$("[name='tipo']").change(function(){
	var val = $(this).val();
	if(val==""){return;}
	$("#botConsultar").prop("disabled",false);

 	});

 	
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
