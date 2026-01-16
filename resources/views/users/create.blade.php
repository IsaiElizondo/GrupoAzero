@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Usuarios')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('users.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Creación de usuario</h4>
                                        <p class="card-category">Por favor introduce la información que se pide</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                arrow_back
                                            </span>

                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Nombre completo" value="" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="email" id="input-email" type="email" placeholder="Email" value="" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Rol</label>
                                    <div class="col-sm-7">
                                        <select name="role" id="inputRole" class="form-control" onchange="actualizar(this)"">
                                            <option value="3" selected><b>Selecciona un rol...</b></option>
                                            @if(auth()->user()->department->name == "Administrador" && auth()->user()->role->name == "Administrador")
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            @endif

                                            @if(auth()->user()->department->name == "Embarques" && auth()->user()->role->name == "Empleado")
                                                @foreach ($roles as $role)
                                                    @if($role->name =="Empleado")
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Departamento</label>
                                    <div class="col-sm-7">
                                        <select name="department" id="inputDepartment" class="form-control">
                                            <option value="1" selected><b>Selecciona un departamento...</b></option>
                                            @if(auth()->user()->department->name == "Administrador" && auth()->user()->role->name == "Administrador")
                                                @foreach ($departments as $department)                                                    
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>                                                    
                                                @endforeach
                                            @endif

                                            @if(auth()->user()->department->name == "Embarques" && auth()->user()->role->name == "Empleado")
                                                @foreach ($departments as $department)
                                                    @if($department->name == "Flotilla")
                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Sucursal</label>
                                    <div class="col-sm-7">
                                    <select name="office" id="inputOffice" class="form-control">                        
                                        <option value="San Pablo">San Pablo</option>                            
                                        <option  value="La Noria">La Noria</option>  
                                    </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label" for="input-password">Password</label>
                                    <div class="col-sm-7">
                                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="input-password" type="password" placeholder="{{ __('Password') }}" value="" required />
                                        @if ($errors->has('password'))
                                            <span id="password-error" class="error text-danger" for="input-password">{{ $errors->first('password') }}</span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label" for="input-password-confirmation">Confirmar password</label>
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                        <input class="form-control" name="password_confirmation" id="input-password-confirmation" type="password" placeholder="{{ __('Confirmar password') }}" value="" required />
                                        </div>
                                    </div>
                                </div>

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

<script>
    function actualizar(opcion){
        console.log("Opción de rol",opcion.value);
        if(opcion.value == 3){
            let value = document.getElementById("inputDepartment").value= 2;
            let index = document.getElementById("inputDepartment").selectedIndex = 2;
            // let disable = document.getElementById("inputDepartment").disabled = true;
            console.log( document.getElementById("inputDepartment").value );
            console.log(value, index);
        }else{
            // document.getElementById("inputDepartment").disabled = false;
        }
    }
</script>

@endpush

