<?php
$estatuses = [4 => "Elaborada", 5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];

?>
<aside class="Subproceso">

    <span rel='inv'><strong>Salida de Materiales # {{ $ob->code }}</strong></span>

    <span rel='st'>

    @if ( isset($estatuses[$ob->status_id]) )
    <div class="MiniEstatus E{{ $ob->status_id }}">{{ $estatuses[$ob->status_id] }}</div>
    @endif
    </span>
    

    <div rel='ed'>
    @if ($user->role_id == 1 || in_array($user->department_id,[4,8]) || 
    ($user->department_id==6 && $ob->status_id < 6 )
     ) 
    <a class="btn editarsm" href="{{ url('pedidos2/smaterial_edit/'.$ob->id) }}">Editar</a>
    @endif
    </div>


    <div rel='fi'>

        <div class="alGridset">
        @if ($ob->status_4==1)
        <div class="alGridItem statusItem">
        <div class="MiniEstatus E4">{{ $estatuses[4] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$ob->id}}_4' event='4'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&smaterial_id='.$ob->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$ob->id.'_4&catalog=pictures&mode=view&smaterial_id='.$ob->id.'&event=4') }}">
            </section> 
        </div>
        @endif

        @if ($ob->status_5==1)
        <div class="alGridItem statusItem">
        <div class="MiniEstatus E5">{{ $estatuses[5] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$ob->id}}_5' event='5'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&smaterial_id='.$ob->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$ob->id.'_5&catalog=pictures&mode=view&smaterial_id='.$ob->id.'&event=5') }}">
            </section> 
        </div>
        @endif

        @if ($ob->status_6==1)
        <div class="alGridItem statusItem">
        <div class="MiniEstatus E6">{{ $estatuses[6] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$ob->id}}_6' event='6'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&smaterial_id='.$ob->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$ob->id.'_6&catalog=pictures&mode=view&smaterial_id='.$ob->id.'&event=6') }}">
            </section> 
        </div>
        @endif

        @if ($ob->status_7==1)
        <div class="alGridItem statusItem">
        <div class="MiniEstatus E7">{{ $estatuses[7] }} </div>
            <section mode="view" class='attachList form-control' rel='prt_{{$ob->id}}_7' event='7'
                uploadto="{{ url('pedidos2/attachlist?catalog=pictures&smaterial_id='.$ob->id) }}" 
                href="{{ url('pedidos2/attachlist?rel=prt_'.$ob->id.'_7&catalog=pictures&mode=view&smaterial_id='.$ob->id.'&event=7') }}">
            </section> 
        </div>
        @endif

        
        </div>

    </div>
    

</aside>
