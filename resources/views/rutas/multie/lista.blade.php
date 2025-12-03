<div class="table-responsive">
    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th width="40px"><input type="checkbox" id="selectAll"></th>
                <th> ID </th>
                <th> Folio </th>
                <th> Cliente </th>
                <th> Origen </th>
                <th> Oficina </th>
                <th> Creado en </th>
                <th> Estatus </th>
            </tr>
        </thead>
        <tbody>
            @forelse($shipments as $pedido)
                <tr>
                    <td>
                        <input type="checkbox"
                            name="pedido_id[]"
                            value="{{ $pedido->order_id }}"
                            data-folio="{{ $pedido->folio }}">
                    </td>
                    <td>{{ $pedido->order_id }}</td>
                    <td>{{ $pedido->folio }}</td>
                    <td>{{ $pedido->client ?? '-' }}</td>
                    <td>{{ $pedido->origin ?? '-' }}</td>
                    <td>{{ $pedido->office ?? '-' }}</td>
                    <td>{{ $pedido->created_at }}</td>
                    <td>{{ $pedido->status_id }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No se encontraron pedidos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    $('#selectAll').click(function(){
        $('input[name="pedido_id[]"]').prop('checked', this.checked);
    });
});
</script>
