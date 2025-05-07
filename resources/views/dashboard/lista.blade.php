{{--{{ dd($lista) }}--}}

@forelse($lista as $pedido)
    
    <div class="col-12 col-md-6 mb-4">
        @include('dashboard.pedido_item', ['item' => $pedido])
    </div>

@empty
    <div class="col-12">
        <p> No hay pedidos para mostrar.</p>
    </div>
@endforelse