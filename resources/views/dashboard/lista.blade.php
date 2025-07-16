<?php
use App\Libraries\Paginacion;

Session::put('qs', request()->query()); // Guardar los filtros actuales

Paginacion::total($total);
Paginacion::actual($pag);
Paginacion::rpp($rpp);
Paginacion::items("resultados");
Paginacion::calc();
?>

<div class="paginacion_wrapper">
    <?php echo Paginacion::render_largo(url("pedidos2/dashboard/lista")); ?>
</div>


@forelse($lista as $pedido)
    <div class="col-12 col-md-6 mb-4">
        <div class="pedido-item">
            @include('dashboard.pedido_item', ['item' => $pedido])
        </div>
    </div>
@empty
    <div class="col-12">
        <p>No hay pedidos para mostrar.</p>
    </div>
@endforelse


<div class="paginacion_wrapper">
    <?php echo Paginacion::render_largo(url("pedidos2/dashboard/lista")); ?>
</div>
