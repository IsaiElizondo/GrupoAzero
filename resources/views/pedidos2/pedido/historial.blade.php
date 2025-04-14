<?php
use App\Libraries\Tools;
?>
<b>Historial de pedido {{ isset($order->invoice_number) ? $order->invoice_number : $order->invoice }}</b>
<div class="ScrollHistorial">
    <table class="TablaHistorial" cellspacing='0' cellpadding='4' border='1'>
    @foreach ($lista as $li)
    <?php $fechaOb = new DateTime($li->created_at); ?>
        <tr>

            <td>
                <div><b>{{ $li->status }}</b></div>
                <div>{{ $li->action }}</div>
            </td>
            <td>
                <div>{{ Tools::fechaMedioLargo($li->created_at)  }} <b>{{ $fechaOb->format("H:i") }}</b></div>
                <div style="line-height: .9;">{{ $li->userName }}</div>
                <div style="line-height: .9; color:#999"><small>{{ $li->department }} <em>{{ $li->thisUser()->office }}</em></small></div>
            </td>
        </tr>
    @endforeach    
    </table>

</div>
