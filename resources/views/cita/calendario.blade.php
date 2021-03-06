@extends('adminlte::page')

<!-- @section('title', 'Calendario') -->

@section('content_header')
<h1>Reuniones</h1>
@stop


@section('css')

<link rel="stylesheet" href="{{ asset('fullcalendar/main.css') }}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<!-- Alertify css cdn -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />

<!-- Alertify bootstrap theme css cdn -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice {

    color: rgb(172, 30, 30) !important;

  }

  .fc-day-past {
    background-color: #e7e7e7;
  }

  .fc-day-today {
    background-color: #cbf8f4 !important;
  }

  /* .fc-day-future{
                                                                        background-color: #ccfafd;
                                                                    }
                                                                     */

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }

  input[type="number"] {
    width: 100px;
  }

  input+span {
    padding-right: 30px;
  }

  input:invalid+span:after {
    position: absolute;
    content: '✖';
    padding-left: 5px;
  }

  input:valid+span:after {
    position: absolute;
    content: '✓';
    padding-left: 5px;
  }
</style>

@stop


@section('content')

<div class="row">
  <div class="col-lg-auto">
    <div class="form-group">
      <label for="">Estado</label>
      <select id="inputFiltroEstado" class="form-control form-control-sm">
        <option value="todos" selected>TODOS</option>
        <option value="pendiente">PENDIENTE</option>
        <option value="concluida">CONLUIDA</option>
        <option value="cancelada">CANCELADA</option>
      </select>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h1 class="card-title">Calendario</h1>
  </div>
  <div class="card-body">
    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}


    <div class="container">

      {{-- modal   modal-lg --}}

      <div class="modal" id="citamodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">AGENDAR REUNIÓN</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form id="frmRegistrarReunion" name="formulario">

                <input type="hidden" name="id" id="inputId">

                <div class="form-group">
                  <label for="inputTitulo">Título</label>
                  <input type="text" class="form-control" id="inputTitulo" name="titulo" placeholder="Escriba el título de su reunión" data-label-validation="titulo">
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-group">
                  <label for="inputDescripcion">Descripción (Opcional)</label>
                  <textarea class="form-control" name="descripcion" id="inputDescripcion" rows="3" data-label-validation="descripcion"></textarea>
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-row">
                  <div class="form-group col-lg-4">
                    <label for="inputFecha">Fecha</label>
                    <input type="date" name="fecha" id="inputFecha" class="form-control" readonly data-label-validation="fecha">
                    <div class="show-validation-message"></div>
                  </div>

                  <div class="form-group col-lg-4">
                    <label for="inputHoraInicio">Hora Inicio</label>
                    <input type="text" id="inputHoraInicio" class="form-control timepicker" data-label-validation="hora_inicio" autocomplete="off">
                    <div class="show-validation-message"></div>
                  </div>

                  <div class="form-group col-lg-4">
                    <label for="inputHoraFin">Hora Fin</label>
                    <input type="text" id="inputHoraFin" class="form-control timepicker" data-label-validation="hora_fin" autocomplete="off">
                    <div class="show-validation-message"></div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputTipoReunion">Tipo reunión</label>
                  <select id="inputTipoReunion" class="form-control" name="tipocita" data-label-validation="tipocita">
                    <option value="presencial" selected>PRESENCIAL</option>
                    <option value="virtual">VIRTUAL</option>
                  </select>
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-group" id="formGroupLinkZoom" style="display: none;">
                  <label for="inputLinkZoom">Link</label>
                  <input type="text" class="form-control" id="inputLinkZoom" placeholder="Inserte el link de la reunión" name="link_reu" data-label-validation="link_reu">
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-group" id="formGroupOficina">
                  <label for="inputOficina">Oficina</label>
                  <select id="inputOficina" class="form-control" name="empresa_id" data-label-validation="empresa_id">
                    <option value="" selected>Elegir...</option>
                    @foreach ($empresas as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre }}
                      ({{ $e->direccion }})
                    </option>
                    @endforeach
                  </select>
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-group" id="formGroupOtraOficina" style="display: none;">
                  <label for="inputOtraOficina">Otra Oficina:</label>
                  <input type="text" name="lugarreu" id="inputOtraOficina" class="form-control" data-label-validation="lugarreu">
                  <div class="show-validation-message"></div>
                </div>

                <div class="form-group">
                  <label for="inputAsistentes">Asistentes:</label>
                  <div>
                    <select id="inputFiltroRolColaboradores" class="form-control form-control-sm w-auto">
                      <option value="" selected>Todos</option>
                      <!-- <option value="1">SEO</option> -->
                      <option value="2">Gerentes</option>
                      <option value="3">Trabajadores</option>
                    </select>
                  </div>
                  <select style="width:100%" id="inputAsistentes" name="asistentes[]" multiple="multiple" lang="es" data-label-validation="asistentes" class="form-control">
                  </select>
                  <div class="show-validation-message"></div>
                </div>

                <div class="accordion" id="confirmacionesAcordion" style="display: none;">
                  <div class="card">
                    <div class="card-header p-0" id="headingOne">
                      <h2 class="mb-0">
                        <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#confirmacionesCollapse" aria-expanded="true" aria-controls="confirmacionesCollapse" style="text-decoration: none; color: #000; font-size: 13px;">
                          VER CONFIRMACIONES
                        </button>
                        <button id="btnReloadConfirmaciones" class="btn btn-sm"><i class="fa-solid fa-rotate"></i></button>
                      </h2>
                    </div>

                    <div id="confirmacionesCollapse" class="collapse show" aria-labelledby="headingOne" data-parent="#confirmacionesAcordion">
                      <div class="card-body p-0 pt-3" id="showConfirmacionAsistentes" style="height: 100px; overflow-y: scroll;">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group" id="formGroupInputEstado" style="display: none;" data-label-validation="estado">
                  <label for="inputEstado">Estado</label>
                  <select name="estado" id="inputEstado" class="form-control" disabled>
                    <option value="pendiente">PENDIENTE</option>
                    <option value="concluida">CONCLUIDA</option>
                    <option value="cancelada">CANCELADA</option>
                  </select>
                  <div class="show-validation-message"></div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-sm" style="display: none; margin-right: auto;" id="btnEliminar">Eliminar</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">CERRAR</button>
                  <button type="submit" class="btn btn-primary btn-sm" id="btnGuardar">
                    GUARDAR
                    <img src="https://c.tenor.com/I6kN-6X7nhAAAAAj/loading-buffering.gif" class="loader btnGuardar" style="width: 18px; display: none;">
                  </button>
                </div>

              </form>

            </div>

          </div>
        </div>
      </div>

      {{-- modal cerrar --}}

      <div class="response">

        <div id='calendar'></div>


      </div>

    </div>


  </div>
</div>


@stop

@section('js')
<script>
  const ID_USUARIO_LOGUEADO = "<?= auth()->user()->id ?>";
</script>
<script src="{{ asset('fullcalendar/main.js') }}"></script>
<script src="{{ asset('fullcalendar/locales/es.js') }}"></script>
<script src="{{ asset('js/Utils.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<!-- Alertify js cdn -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="{{ asset('js/cita.js') }}"></script>
@stop