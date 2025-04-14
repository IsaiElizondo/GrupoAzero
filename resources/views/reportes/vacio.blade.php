@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')

<link rel="stylesheet" href="{{ url('/') }}/css/reportes.css?x=<?= rand(0,999) ?>">

<div class="content">
    <div class="container-fluid">
      <div class="row">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                            <h4 class="card-title ">Reporte vacío</h4>
                            <p class="card-category"> </p>
                        </div>
                        <!-- <div class="col-md-8 col-sm-12 col-xs-12 "></div> -->
                    </div>
                </div>
            </div>

            <main>
                <p><a class="button" href="{{ !empty($regresara) ? $regresara :  url('reportes') }}">&laquo; Regresar</a></p>
                <p>No se encontraron resultados con los parametros especificados. Por favor intenta con valores menos específicos. </p>
            </main>

        </div>
    </div>
</div>



@endsection



@push('js')



<script type='text/javascript'>


 </script>
@endpush
