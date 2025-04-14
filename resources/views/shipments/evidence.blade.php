@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Evidencia de material terminado')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('shipments.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="type" value="evidence">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Subir evidencia de material terminado</h4>
                            <p class="card-category">Pedido {{ $order->invoice }}</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <label class="col-sm-2 col-form-label">Archivo</label>
                                <div class="col-sm-7">
                                    <div class="">
                                        <label for="picture">Adjuntar archivo</label>
                                        <input type="file" name="file" class="form-control-file" id="picture" accept="image/*,.pdf" required>
                                    </div>
                                </div>
                            </div>
                            {{-- @if ( isset($order->shipments) )
                                {{ $order->shipments }}
                                <input type="hidden" name="oldShipment" value="true" class="form-control">
                                <input type="hidden" name="shipmentId" value="{{ $order->sipments->id }}" class="form-control">
                            @else
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Razón</label>
                                    <div class="col-sm-7">
                                        <select name="reason_id" id="reason_id" class="form-control" required>
                                            <option value="1" selected><b>Selecciona una razón...</b></option>
                                            @foreach ($reasons as $reason)
                                                <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif --}}

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
        </div>
    </div>
@endsection

@push('js')

@endpush
