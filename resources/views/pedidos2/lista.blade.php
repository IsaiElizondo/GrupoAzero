<?php
use App\Libraries\Paginacion;

Paginacion::total($total);
Paginacion::actual($pag);
Paginacion::rpp($rpp);
Paginacion::items("resultados");
Paginacion::calc();
?>
<?php
echo Paginacion::render_largo(url("pedidos2/lista"));
?>


@foreach ($lista as $item)

{{ view("pedidos2.pedido_item",compact("item","estatuses","user")) }}

@endforeach


@if (count($lista)==0) 
    <p>No se encontraron resultados con esos filtros.</p>
@endif

<?php
echo Paginacion::render_largo(url("pedidos2/lista"));
?>

