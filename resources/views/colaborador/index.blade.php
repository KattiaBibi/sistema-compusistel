@extends('adminlte::page')
@section('content_header')
    <h1>Colaboradores</h1>
    @section('title', 'Colaboradores')
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" rel="stylesheet"/>

@endsection

@section('content')

<div class="card">

  <div class="card-header">

  <div class="row">
        <div class="col-lg-10">
                <h2>Listar</h2>
        </div>
        <div class="col-lg-2">

        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalagregar">AGREGAR</button>

        </div>
    </div>
    </div>

  <div class="card-body">

  @if ($message = Session::get('success'))
        <div class="alert alert-success" id="mensaje">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table id="colaboradores" class="table table-striped table-bordered" style="">
        <thead>
            <tr>
              <th colspan="2" style="text-align: center;">ACCIÓN</th>
              <th>ID</th>
              <th>N° DOCUMENTO</th>
              <th>NOMBRES</th>
              <th>APELLIDOS</th>
              <th>FECHA DE NACIMIENTO</th>
              <th>DIRECCIÓN</th>
              <th>TELÉFONO</th>
              <th>EMPRESA</th>
              <th>ÁREA</th> 


            </tr>
        </thead>
       <tbody>


        </tbody>

    </table>

  </div>
</div>


<div class="modal fade" id="modalagregar" tabindex="-1" role="dialog" aria-labelledby="modalagregar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Ups!</strong> Hubo algunos problemas con tus inputs.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <form action="{{ route('colaborador.store') }}" id="frmguardar" >

      <div class="form-group">
        <label for="">N° DE DOCUMENTO:</label>
        <input type="text" class="form-control" id="txtNombre" placeholder="Ingrese el nombre" name="nombre">
    </div>
    
        <div class="form-group">
            <label for="">Nombres:</label>
            <input type="text" class="form-control" id="txtNombre" placeholder="Ingrese el nombre" name="nombres">
        </div>
        
        <div class="form-group">
          <label for="">Apellidos:</label>
          <input type="text" class="form-control" id="txtApellido" placeholder="Ingrese el nombre" name="apellidos">
      </div>

      <div class="form-group">
        <label for="">Fecha de nacimiento:</label>
        <input type="date" class="form-control" id="txtFechanac" placeholder="" name="fechanacimiento">
    </div>

        <div class="form-group">
            <label for="">Dirección:</label>
            <input type="text" class="form-control" id="txtDireccion" placeholder="Ingrese la dirección" name="direccion">
        </div>
        <div class="form-group">
            <label for="">Teléfono:</label>
            <input type="text" class="form-control" id="txtTelefono" placeholder="Ingrese la dirección" name="telefono">
        </div>

        <div class="form-group">
            <label for="">Empresa y área:</label>

            <select class="form-control">
              <option selected>Elegir</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
        <button  id="btnguardar" class="btn btn-primary">GUARDAR</button>
      </div>
    </form>

    </div>
  </div>
</div>



<div class="modal fade" id="modaleditar" tabindex="-1" role="dialog" aria-labelledby="modaleditar" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Actualiza registro</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        @if ($errors->any())
          <div class="alert alert-danger">
              <strong>¡Ups!</strong> Hubo algunos problemas con tus inputs.<br><br>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      <form  id="frmeditar">

     <input type="hidden" class="form-control" id="idregistro"  name="id">

          
          <div class="form-group">
            <label for="">N° DOCUMENTO:</label>
          <input type="text" class="form-control" id="editarNrodoc" placeholder="Ingrese su DNI" name="nrodocumento">
        </div>

          <div class="form-group">
            <label for="">Nombres:</label>
            <input type="text" class="form-control" id="editarNombre" placeholder="Ingrese su(s) nombre(s)" name="nombres">
        </div>

          <div class="form-group">
              <label for="">Apellidos:</label>
              <input type="text" class="form-control" id="editarApellido" placeholder="Ingrese sus apellidos" name="apellidos">
          </div>

          <div class="form-group">
            <label for="">Fecha de nacimiento:</label>
            <input type="date" class="form-control" id="editarFechanac" placeholder="" name="fechanacimiento">
        </div>

          <div class="form-group">
              <label for="">Dirección:</label>
              <input type="text" class="form-control" id="editarDireccion" placeholder="Ingrese su dirección" name="direccion">
          </div>
          <div class="form-group">
              <label for="">Teléfono:</label>
              <input type="text" class="form-control" id="editarTelefono" placeholder="Ingrese su teléfono" name="telefono">
          </div>

          <div class="form-group">
            <label for="">Empresa y área:</label>

            <select class="form-control" aria-label="Default select example">
              <option selected>Elegir</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>

        </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
          <button type="submit" id="btnactualizar" class="btn btn-primary">EDITAR</button>
        </div>
      </form>

      </div>
    </div>
  </div>

@endsection




@section('js')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script> console.log('¡HOLA!');

</script>
<script src="{{asset('js/colaborador.js')}}"></script>



<script>

listar()
</script>
@endsection