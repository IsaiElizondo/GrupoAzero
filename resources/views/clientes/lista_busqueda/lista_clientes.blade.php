<div class="table-responsive mt-2">
    <table class="table table-hover table-bordered">
        <thead class="text-primary">
            <tr>
                <th width="120px"> Seleccionar </th>
                <th> ID </th>
                <th> Nombre </th>
                <th> Código </th>
            </tr>
        </thead>

        <tbody>
            @forelse($clientes as $c)
                <tr>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary seleccionar-cliente" data-id="{{ $c->id }}" data-nombre="{{ $c->nombre }}" data-codigo="{{ $c->codigo_cliente}}">
                            Elegir
                        </button>
                    </td>

                    <td>{{ $c->id }}</td>
                    <td>{{ $c->nombre }}</td>
                    <td>{{ $c->codigo_cliente }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No se encontraron clientes
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>