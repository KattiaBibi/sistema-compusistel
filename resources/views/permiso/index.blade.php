@extends('adminlte::page')

@section('content_header')
    <h1>Permisos</h1>
    @section('title', 'Permisos')
@endsection

@section('css')


@endsection

@section('content')

<div class="card">

  <div class="card-header">

  <div class="row">
        <div class="col-lg-10">
                <h2>Listar</h2>
        </div>
        <div class="col-lg-2">

            @can('admin.servicio.crear')

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalagregar">AGREGAR</button>
            @endcan



        </div>
    </div>
    </div>

  <div class="card-body">

  @if ($message = Session::get('success'))
        <div class="alert alert-success" id="mensaje">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table id="permisos" class="table table-striped table-bordered" style="">
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>DESCRIPCIÓN</th>
               <th width="" colspan="2" class="text-center">ACCIONES</th>

            </tr>
        </thead>
       <tbody>


        </tbody>
        <tfoot>
            <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>DESCRIPCIÓN</th>
           <th width="" colspan="2" class="text-center">ACCIONES</th>

            </tr>
        </tfoot>
    </table>

  </div>
</div>


<div class="modal fade" id="modalagregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <form action="{{ route('permiso.store') }}" id="frmguardar" >

        <div class="form-group">
            <label for="">Nombre:</label>
            <input type="text" class="sololetras form-control sinespacio" maxlength="50" id="txtNombre" onkeyup="javascript:this.value=this.value.toLowerCase();" placeholder="Ingrese el nombre separado con puntos." name="name">
        </div>

        <div class="form-group">
            <label for="">Descripción:</label>
            <input type="text" class="sololetras form-control" id="txtDescripcion" maxlength="50" placeholder="Ingrese la descripción" name="description">
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
          <h5 class="modal-title" id="exampleModalLabel">Actualiza registro </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

      <form  id="frmeditar">

        <div class="form-group">
          <label for="">Nombre:</label>
          <input type="hidden" class="form-control " id="idregistro"  name="id">

          <input type="text" class="sololetras form-control sinespacio" maxlength="50" id="editarNombre" onkeyup="javascript:this.value=this.value.toLowerCase();" placeholder="Ingrese el nombre separado con puntos." name="name">
      </div>

          <div class="form-group">
              <label for="">Descripción:</label>

              <input type="text" class="sololetras form-control" id="editarDescripcion" maxlength="50" placeholder="Ingrese el nombre" name="description">
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

<script> console.log('¡HOLA!'); </script>

<script src="{{asset('js/permiso.js')}}"></script>



<script>

listar()
</script>
@endsection
