<?php
$estatuses = ["4"=>"Generado", "5" => "En Puerta", "6"=>"Entregado", "7"=>"Cancelado"];
?>

<aside class="Parcial">
<?php //var_dump($estatuses);   ?>
    <span rel='inv'><strong>Parcial # {{ $parcial->invoice }}</strong></span>

    <div rel='st'>
        @if (isset($estatuses[$parcial->status_id]))
        <div class='MiniEstatus E{{ $parcial->status_id }}'>{{ $estatuses[$parcial->status_id] }}</div>
        @else
        <span>?</span>
        @endif
    </div>
    
    <div rel='ed' style="text-align: right;">

    @if ($user->role_id == 1 || in_array($user->department_id,[4,6,8]) || ($user->department_id == 6 && $parcial->status_id < 6)  )    
    <a class="btn editarparcial" href="{{ url('pedidos2/parcial_edit/'.$parcial->id) }}">Editar</a>
    @endif 

    </div>

    <div rel='fi'>
        <div class="alGridset">


        
        @if ($parcial->status_5==1)
        <div class="alGridItem">
        <div class="MiniEstatus E5">{{ $estatuses[5] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$parcial->id}}_5' event='5'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&partial_id='.$parcial->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$parcial->id.'_5&catalog=pictures&mode=view&partial_id='.$parcial->id.'&event=5') }}">
            </section> 
        </div>
        @endif

        @if ($parcial->status_6==1)
        <div class="alGridItem">
        <div class="MiniEstatus E6">{{ $estatuses[6] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$parcial->id}}_6' event='6'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&partial_id='.$parcial->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$parcial->id.'_6&catalog=pictures&mode=view&partial_id='.$parcial->id.'&event=6') }}">
            </section> 
        </div>
        @endif

        @if ($parcial->status_7==1)
        <div class="alGridItem">
        <div class="MiniEstatus E7">{{ $estatuses[7] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$parcial->id}}_7' event='7'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&partial_id='.$parcial->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$parcial->id.'_7&catalog=pictures&mode=view&partial_id='.$parcial->id.'&event=7') }}">
            </section> 
        </div>
        @endif

        </div>
    </div>
    

</aside>
