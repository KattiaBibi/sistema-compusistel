<?php

namespace App\Http\Controllers;

use App\Colaborador;
use GuzzleHttp\Promise\Utils;
use App\Requerimiento;
use App\DetalleRequerimiento;
use App\RequerimientoEncargados;
use App\Servicio;
use App\HistorialFechaHora;
use Illuminate\Http\Request;
use App\Http\Requests\RequerimientoRequest;
use App\Mail\RequerimientoEmail;
use App\RequerimientoRespuesta;
use App\subirarchivo;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use stdClass;

class RequerimientoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function __construct()
  {
    $this->middleware('auth');
  }


  public function requerimiento()
  {
    $logueado = auth()->user()->id;

    $role_id = intval(DB::table('model_has_roles')
      ->select('roles.id AS role_id')
      ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
      ->where('model_id', '=', $logueado)
      ->get()->first()->role_id);

    $empresa = request()->all()['filters']['nombre_empresa'] ?? 'todos';
    $estado = request()->all()['filters']['estado'] ?? 'todos';
    $nombrado = request()->all()['filters']['nombrado'] ?? 'todos';

    $query = DB::table('requerimientos')
      ->select(
        DB::raw("requerimientos.id AS id"),
        "requerimientos.titulo AS titulo_requerimiento",
        "requerimientos.descripcion AS descripcion_requerimiento",
        DB::raw("CONCAT(solicitante.nombres, ' ', solicitante.apellidos) AS nom_ape_solicitante"),
        DB::raw("empresas.nombre AS nombre_empresa"),
        DB::raw("empresas.id AS id_empresa"),
        DB::raw("servicios.nombre AS nombre_servicio"),
        "requerimientos.usuarioregist_id AS usuario_que_registro",
        "requerimientos.avance AS avance_requerimiento",
        "requerimientos.estado AS estado_requerimiento",
        "requerimientos.prioridad AS prioridad_requerimiento",
        "requerimientos.created_at AS fecha_creacion",
        "requerimientos.imagen AS imagen",
        "requerimientos.archivo AS archivo"
      )
      ->join('users AS usuario_solicitante', 'usuario_solicitante.id', '=', 'requerimientos.usuarioregist_id')
      ->join('colaboradores AS solicitante', 'solicitante.id', '=', 'usuario_solicitante.colaborador_id')
      ->join('empresa_servicios', 'empresa_servicios.id', '=', 'requerimientos.empresa_servicio_id')
      ->join('servicios', 'servicios.id', '=', 'empresa_servicios.servicio_id')
      ->join('empresas', 'empresas.id', '=', 'empresa_servicios.empresa_id')
      ->groupBy('requerimientos.id', 'requerimientos.titulo', 'requerimientos.descripcion', 'solicitante.nombres', 'solicitante.apellidos', 'empresas.nombre', 'empresas.id', 'servicios.nombre', 'requerimientos.usuarioregist_id', 'requerimientos.avance', 'requerimientos.estado', 'requerimientos.prioridad', 'requerimientos.created_at', 'requerimientos.imagen', 'requerimientos.archivo');

    if ($role_id === 2) {

      if ($nombrado == 'solicitante') {
        $query->where('requerimientos.usuarioregist_id', '=', $logueado);
      } else if ($nombrado == 'encargado') {
        $query->join('requerimiento_encargados', 'requerimiento_encargados.requerimiento_id', '=', 'requerimientos.id', 'left')
          ->where('requerimiento_encargados.usuarioencarg_id', '=', $logueado);
      } else if ($nombrado == 'asignado') {
        $query->join('detalle_requerimientos', 'detalle_requerimientos.requerimiento_id', '=', 'requerimientos.id', 'left')
          ->where('detalle_requerimientos.usuario_colab_id', '=', $logueado);
      } else {
        $query->join('requerimiento_encargados', 'requerimiento_encargados.requerimiento_id', '=', 'requerimientos.id', 'left')
          ->where(function ($query) use ($logueado) {
            $query->where('requerimiento_encargados.usuarioencarg_id', '=', $logueado)
              ->orWhere('requerimientos.usuarioregist_id', '=', $logueado);
          });
      }
    }

    if ($role_id === 3) {
      $query->join('detalle_requerimientos', 'detalle_requerimientos.requerimiento_id', '=', 'requerimientos.id', 'left')
        ->where('detalle_requerimientos.usuario_colab_id', '=', auth()->user()->id);
    }

    if ($estado != 'todos') {
      $query->where('requerimientos.estado', '=', $estado);
    }

    if ($empresa != 'todos') {
      $query->where('empresas.nombre', '=', $empresa);
    }

    if ($role_id === 1) {
      if ($nombrado == 'solicitante') {
        $query->where('requerimientos.usuarioregist_id', '=', auth()->user()->id);
      } else if ($nombrado == 'encargado') {
        $query->join('requerimiento_encargados', 'requerimiento_encargados.requerimiento_id', '=', 'requerimientos.id', 'left')
          ->where('requerimiento_encargados.usuarioencarg_id', '=', auth()->user()->id);
      } else if ($nombrado == 'asignado') {
        $query->join('detalle_requerimientos', 'detalle_requerimientos.requerimiento_id', '=', 'requerimientos.id', 'left')
          ->where('detalle_requerimientos.usuario_colab_id', '=', auth()->user()->id);
      }
    }

    $rpta = $query->orderBy('requerimientos.created_at', 'desc')->get();

    $requerimientos = $rpta->all();

    foreach ($requerimientos as &$req) {

      $req->log = $logueado;


      $req->asignados = DB::table('detalle_requerimientos')
        ->select(
          DB::raw("CONCAT(colaboradores.nombres, ' ', colaboradores.apellidos) AS nom_ape"),
          'users.id as id_user',
          'detalle_requerimientos.id as detalle_id',
          DB::raw("(CASE users.id WHEN $logueado THEN 1 ELSE 2 END) AS logeado")

        )
        ->join("users", 'users.id', '=', 'detalle_requerimientos.usuario_colab_id', 'inner')
        ->join("colaboradores", 'colaboradores.id', '=', 'users.colaborador_id', 'inner')
        // ->join('historial_requerimientos as h_req','h_req.id', '=','detalle_requerimientos.historial_requerimiento_id')
        ->where('detalle_requerimientos.requerimiento_id', '=', $req->id)
        ->get()->all();


      $req->encargados = DB::table('requerimiento_encargados')

        ->select(
          "users.id AS id_usuario",
          DB::raw("CONCAT(colaboradores.nombres, ' ', colaboradores.apellidos) AS nom_ape"),
          DB::raw("(CASE WHEN users.id = $logueado THEN 1 ELSE 2 END) AS logeado")
        )
        ->join("users", 'users.id', '=', 'requerimiento_encargados.usuarioencarg_id', 'inner')
        ->join("colaboradores", 'colaboradores.id', '=', 'users.colaborador_id', 'inner')
        ->where('requerimiento_encargados.requerimiento_id', '=', $req->id)
        ->get()->all();


      $ho = $req->historial = DB::table('historial_requerimientos as his_req')
        ->select(
          'his_req.id',
          'his_req.fechahoraprogramada as fechahoraprogramada',
          'his_req.motivo as motivo',
          'his_req.created_at as created_at',
          DB::raw("CONCAT(c.nombres,' ' ,c.apellidos) AS nom_ape")
        )
        ->join('detalle_requerimientos as det_req', 'det_req.id', '=', 'his_req.detalle_requerimiento_id')
        ->join('users as u', 'det_req.usuario_colab_id', '=', 'u.id')
        ->join('colaboradores as c', 'u.colaborador_id', '=', 'c.id')
        ->where('det_req.requerimiento_id', '=', $req->id)
        ->orderBy('created_at', 'DESC')
        ->get()->all();


      // SACAR EL ÚLTIMO REGISTRO DEL HISTORIAL DEL REQUERIMIENTO
      $req->ultimafecha = DB::table('historial_requerimientos as his_req')
        ->select('*')
        ->join('detalle_requerimientos as det_req', 'det_req.id', '=', 'his_req.detalle_requerimiento_id')
        ->where('det_req.requerimiento_id', '=', $req->id)
        ->orderBy('created_at', 'desc')
        ->take(1)
        ->get();

      // SACAR EL ID DE LOS DETALLES DE REQUERIMIENTO CON EL USUARIO QUE ESTÉ LOGUEADO
      $req->usuariodetalle = DB::table('detalle_requerimientos as deta_req')
        ->select('deta_req.id as detalle_id')
        ->join('users as u', 'deta_req.usuario_colab_id', '=', 'u.id')
        ->where('deta_req.requerimiento_id', '=', $req->id)
        ->where('deta_req.usuario_colab_id', '=', $logueado)
        ->get();

      $encarg = $req->encargados;
      $asig = $req->asignados;

      $usuarioqueregistro = $req->usuario_que_registro;
      $estado = $req->estado_requerimiento;



      if ($estado == "cancelado" || $role_id !== 1 && $usuarioqueregistro != $logueado) {

        $req->valor[] = "disabled";
      } else if ($estado != "cancelado" || $role_id === 1 && $usuarioqueregistro == $logueado) {
        $req->valor[] = "nodisabled";
      }

      foreach ($asig as $a) {

        if ($a->logeado == 1) {
          $req->asignadolog[] = "log";
        } else {
          $req->asignadolog[] = "nolog";
        }
      }

      $req->reg = $usuarioqueregistro;

      foreach ($encarg as $e) {

        if ($e->logeado == 1 && $usuarioqueregistro == $logueado) {

          $req->elemento[] = "dos";
        }

        // SI EL USUARIO LOGUEADO ES EL ENCARGADO

        else if ($e->logeado == 1) {

          $req->elemento[] = "silog";
        }

        // SI EL USUARIO QUE REGISTRÓ ESTÁ LOGUEADO

        else if ($usuarioqueregistro == $logueado) {

          $req->elemento[] = "sireg";
        } else {

          $req->elemento[] = "mostrar";
        }
      }
    }

    foreach ($requerimientos as &$req) {
      $req->respuestas = RequerimientoRespuesta::where('requerimiento_id', $req->id)->get()->all();
    }

    return datatables()->of($requerimientos)->toJson();
  }

  public function getdetalle($id)
  {

    $query = DB::table('detalle_requerimientos')
      ->select("usuario_colab_id as id")
      ->where("requerimiento_id", "=", $id)->get();

    return response()->json($query);
  }

  public function listarservicios($id)
  {

    $empresa_servicios = DB::table('empresa_servicios as es')
      ->join('empresas as e', 'es.empresa_id', '=', 'e.id')
      ->join('servicios as s', 'es.servicio_id', '=', 's.id')
      ->select('es.id as esid', 'e.id as eid', 's.id as sid', 's.nombre as snombre', 'e.nombre as enombre', 's.nombre as snombre')->where('empresa_id', $id)->where("s.estado", "=", 1)->get();

    return $empresa_servicios;
  }

  public function listargerentes($id)
  {
    $gerentes = DB::table('users as u')
      ->join('colaboradores as c', 'u.colaborador_id', '=', 'c.id')
      ->join('colaborador_empresa_area', 'colaborador_empresa_area.colaborador_id', '=', 'c.id')
      ->join('empresa_areas as ea', 'colaborador_empresa_area.empresa_area_id', '=', 'ea.id')
      ->join('model_has_roles as mr', 'u.id', '=', 'mr.model_id')
      ->select('u.id', 'u.name', 'u.colaborador_id', 'c.nombres', 'c.apellidos', 'mr.role_id AS id_rol')
      ->where(function ($query) {
        $query->where('mr.role_id', 1)->orWhere('mr.role_id', 2);
      })->where('ea.empresa_id', $id)->where("c.estado", "=", 1)->get()->all();

    if (!array_filter($gerentes, fn ($gerente) => $gerente->id_rol === 1)) {
      foreach ($this->getUsersAdmin() as $userAdmin) {
        $gerentes[] = $userAdmin;
      }
    }

    // dd($gerentes);

    return $gerentes;
  }

  private function getUsersAdmin()
  {
    return DB::table('users as u')
      ->join('colaboradores as c', 'u.colaborador_id', '=', 'c.id')
      ->join('model_has_roles as mr', 'u.id', '=', 'mr.model_id')
      ->select('u.id', 'u.name', 'u.colaborador_id', 'c.nombres', 'c.apellidos', 'mr.role_id AS id_rol')
      ->where('mr.role_id', '=', 1)->get()->all();
  }

  public function listarcolaboradores($id)
  {
    $colaboradores = DB::table('users as u')
      ->join('colaboradores as c', 'u.colaborador_id', '=', 'c.id')
      ->join('colaborador_empresa_area', 'colaborador_empresa_area.colaborador_id', '=', 'c.id')
      ->join('empresa_areas as ea', 'colaborador_empresa_area.empresa_area_id', '=', 'ea.id')
      ->select('u.id', 'u.name', 'u.colaborador_id', 'c.nombres', 'c.apellidos')->where('ea.empresa_id', $id)->where("c.estado", "=", 1)->get();

    return $colaboradores;
  }

  public function index()
  {
    $role_id = intval(DB::table('model_has_roles')
      ->select('roles.id AS role_id')
      ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
      ->where('model_id', '=', auth()->user()->id)
      ->get()->first()->role_id);

    $servicios = Servicio::all();
    $empresas = DB::table('empresas')->where('estado', '=', '1')->get();
    $usuarios = DB::table('users as u')
      ->join('colaboradores as c', 'u.colaborador_id', '=', 'c.id')
      ->select('u.id as usuario_id', 'c.id as colaborador_id', 'c.nombres', 'c.apellidos')->get();

    return view('requerimiento.index', compact('servicios', 'empresas', 'usuarios', 'role_id'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(RequerimientoRequest $request)
  {
    $ruta2 = "archivo/";
    $file2 = $request->file('archivopost');
    $nombre2 = "archivo";
    $subir2 = subirarchivo::archivo($file2, $nombre2, $ruta2);
    $ruta = "requerimiento/";
    $file = $request->imagenpost;

    $nombre = "requerimiento";
    $subir = subirarchivo::imagen($file, $nombre, $ruta);
    $subir2 = subirarchivo::archivo($file2, $nombre2, $ruta2);

    $request->request->add(['imagen' => $subir]);
    $request->request->add(['archivo' => $subir2]);
    $request->request->add(['avance' => 0]);
    $request->request->add(['estado' => 'pendiente']);

    $requerimiento =  Requerimiento::create($request->all());

    $encarg = $request->usuarioencarg_id;
    foreach ($encarg as $key => $value) {
      $encargado_requerimiento = RequerimientoEncargados::create([
        "requerimiento_id" => $requerimiento->id,
        "usuarioencarg_id" => $value
      ]);
    }

    $this->enviarMensageWsp($encarg, $requerimiento->id);
    $this->sendEmail($requerimiento->id);

    return $requerimiento ? 1 : 0;
  }

  public function getDownload($archivo)
  {
    //PDF file is stored under project/public/download/info.pdf
    $file = public_path() . "/storage/archivo/" . $archivo;
    return response()->download($file);
  }

  public function show(Request $request, $id)
  {
    $fechaActual = date('Y-m-d H:i:s');
    $requerimiento = Requerimiento::findOrfail($id);
    $avance = $request->avance;

    if ($avance == "100") {
      $requerimiento->update(
        [
          'avance' => $request->avance,
          'estado' => "culminado"
        ]
      );

      HistorialFechaHora::create([
        "fechahoraprogramada" => $fechaActual,
        "motivo" => "Finalización del requerimiento",
        'detalle_requerimiento_id' => $request->detalle_requerimiento_id
      ]);
    } else if ($avance > "0") {

      $requerimiento->update(
        [
          'avance' => $request->avance,
          'estado' => "en proceso"
        ]
      );
    } else {
      $requerimiento->update(
        [
          'avance' => $request->avance,
          'estado' => "pendiente"

        ]
      );
    }

    return $requerimiento ? 1 : 0;
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */

  public function update(RequerimientoRequest $request, $id)
  {
    $userColabActual = DB::table('detalle_requerimientos')->where('requerimiento_id', $id)->get()->all();
    $userColabReq = $request->usuario_colab_id;
    $userColabDelete = [];

    foreach ($userColabActual as $i) {
      if (array_search($i->usuario_colab_id, $userColabReq) === false) {
        $userColabDelete[] = $i;
      } else {
        unset($userColabReq[array_search($i->usuario_colab_id, $userColabReq)]);
      }
    }

    if ($userColabDelete) {
      foreach ($userColabDelete as $e) {
        DB::table('historial_requerimientos')->where('detalle_requerimiento_id', $e->id)->delete();
        DB::table('detalle_requerimientos')->where('id', $e->id)->delete();
      }
    }

    $requerimiento = Requerimiento::findOrfail($id);

    //  RUTA DE LA IMAGEN / ARCHIVO
    $ruta = "requerimiento/";

    $ruta2 = "archivo/";

    // IMAGEN Y ARCHIVO NUEVO
    $file = $request->imagennue;
    $filearch = $request->archivonue;


    // IMAGEN ANTERIOR

    $file2 = $requerimiento->imagen;
    $filearch2 = $requerimiento->archivo;

    // NOMBRE PARA CONCATENAR A LA NUEVA IMAGEN Y ARCHIVO
    $nombre = "requerimiento";
    $nombre2 = "archivo";
    // return response()->json($file2);

    // dd($filearch2);


    if ($file && $filearch) {

      // SI EXISTE LA IMAGEN NUEVA Y TMB EL ARCHIVO NUEVO

      $subir = subirarchivo::imagen($file, $nombre, $ruta, $file2);
      $subir2 = subirarchivo::archivo($filearch, $nombre2, $ruta2, $filearch2);


      // DESPUÉS GUARDA EN LA BASE DE DATOS

      $requerimiento->update(
        [
          'titulo' => $request->titulo,
          'descripcion' => $request->descripcion,
          'prioridad' => $request->prioridad,
          'estado' => $request->estado,
          'imagen' => $subir,
          'archivo' => $subir2
        ]
      );
    } else if ($file) {

      $subir = subirarchivo::imagen($file, $nombre, $ruta, $file2);

      $requerimiento->update(
        [
          'titulo' => $request->titulo,
          'descripcion' => $request->descripcion,
          'prioridad' => $request->prioridad,
          'estado' => $request->estado,
          'imagen' => $subir
        ]
      );
    } else if ($filearch) {

      $subir2 = subirarchivo::archivo($filearch, $nombre2, $ruta2, $filearch2);

      $requerimiento->update(
        [
          'titulo' => $request->titulo,
          'descripcion' => $request->descripcion,
          'prioridad' => $request->prioridad,
          'estado' => $request->estado,
          'archivo' => $subir2
        ]
      );
    } else {

      // SI NO MANDA IMAGEN NUEVA

      $requerimiento->update(
        [
          'titulo' => $request->titulo,
          'descripcion' => $request->descripcion,
          'prioridad' => $request->prioridad,
          'estado' => $request->estado,
        ]
      );
    }

    $colab = $userColabReq;

    foreach ($colab as $key => $value) {
      $deta_requerimiento = DetalleRequerimiento::create([
        "usuario_colab_id" => $value,
        "requerimiento_id" => $requerimiento->id
      ]);
    }

    $this->enviarMensageWsp($colab, $requerimiento->id);
    $this->sendEmail($requerimiento->id);

    return $requerimiento ? 1 : 0;
  }

  public function sendEmail($id)
  {
    $req = Requerimiento::getById($id);

    // dd($req);

    $merged_keyed = array_column(array_merge($req->encargados, $req->asignados), NULL, 'id');
    ksort($merged_keyed);
    $colaboradores = array_values($merged_keyed);

    // dd($colaboradores);

    foreach ($colaboradores as $colaborador) {
      Mail::to($colaborador->email)->send(new RequerimientoEmail($req, $colaborador));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */

  public function destroy(Request $request, $id)
  {
    //delete
    $requerimiento = Requerimiento::findOrfail($id);

    $requerimiento->estado = "cancelado";


    $requerimiento->update();

    return $requerimiento ? 1 : 0;
  }

  public function delete($id)
  {
    $detalleRequ = DB::table('detalle_requerimientos')
      ->where('requerimiento_id', $id)->get()->all();

    // dd($detalleRequ);

    foreach ($detalleRequ as $value) {
      DB::table('historial_requerimientos')
        ->where('detalle_requerimiento_id', $value->id)->delete();
    }

    DB::table('detalle_requerimientos')->where('requerimiento_id', $id)->delete();
    DB::table('requerimiento_encargados')->where('requerimiento_id', $id)->delete();
    DB::table('requerimientos')->where('id', $id)->delete();

    return 1;
  }


  /**
   * Send Whatsapp Messages
   *
   * @param  array $recipients Example: [prefijo => '51', message => 'Test', phoneNumber => '123456789']
   * @return array $responses
   */

  private function sendWhatsappMessages(array $recipients)
  {
    // $apiURL = 'http://localhost:3000/api/v1/sendMessage';
    // $apiURL = 'https://my-whatsapp-client.herokuapp.com/api/v1/sendMessage';
    $apiURL = 'https://whatsapp-client-production.up.railway.app/api/v1/sendMessage';

    $promises = [];

    $client = new Client();

    foreach ($recipients as $recipient) {
      $promises[] = $client->postAsync($apiURL, [
        'json' => $recipient
      ]);
    }

    $responses = Utils::unwrap($promises);

    return $responses;
  }

  private function enviarMensageWsp(array $idsUsuarios, string $idRequerimiento)
  {
    $requerimiento = Requerimiento::getById($idRequerimiento);

    $encargados = "";
    foreach ($requerimiento->encargados as $encargado) {
      $encargados .= $encargado->nom_ape_encargado . ', ';
    }

    $asignados = "";
    foreach ($requerimiento->asignados as $asignado) {
      $asignados .= $asignado->nom_ape_asignado . ', ';
    }

    $recipients = array_map(function ($recipient) use ($requerimiento, $encargados, $asignados) {

      $fechaRegistro = date('d/m/Y h:i A', strtotime($requerimiento->fecha_creacion));

      $message = "👉 HOLA, *$recipient->nom_ape*, TIENES UN REQUERIMIENTO: \n ✅ *SOLICITANTE:* $requerimiento->nom_ape_solicitante \n ✅ *TITULO:* $requerimiento->titulo \n ✅ *EMPRESA RESPONSABLE:* $requerimiento->nombre_empresa \n ✅ *SERVICIO:* $requerimiento->nombre_servicio \n ✅ *PRIORIDAD:* $requerimiento->prioridad \n 📅 *FECHA REGISTRO:* $fechaRegistro \n ✅ *ENCARGADOS:* $encargados \n ✅ *ASIGNADOS:* $asignados";

      return [
        "prefijo" => $recipient->prefijo,
        "message" => $message,
        "phoneNumber" => $recipient->telefono
      ];
    }, Colaborador::getContactInfoByUserIds($idsUsuarios));

    $responses = $this->sendWhatsappMessages($recipients);
  }

  // public function sendWspMessage()
  // {
  //   $requerimiento = Requerimiento::getById('1');

  //   dd($requerimiento);

  //   $encargados = "";
  //   foreach ($requerimiento->encargados as $encargado) {
  //     $encargados .= $encargado->nom_ape_encargado . ', ';
  //   }

  //   $asignados = "";
  //   foreach ($requerimiento->asignados as $asignado) {
  //     $asignados .= $asignado->nom_ape_asignado . ', ';
  //   }

  //   $recipients = array_map(function ($recipient) use ($requerimiento, $encargados, $asignados) {

  //     $fechaRegistro = date('d/m/Y h:i A', strtotime($requerimiento->fecha_creacion));

  //     $message = "👉 HOLA, *$recipient->nom_ape*, SE TE ASIGNO A UN REQUERIMIENTO: \n ✅ *SOLICITANTE:* $requerimiento->nom_ape_solicitante \n ✅ *TITULO:* $requerimiento->titulo \n ✅ *EMPRESA RESPONSABLE:* $requerimiento->nombre_empresa \n ✅ *SERVICIO:* $requerimiento->nombre_servicio \n ✅ *PRIORIDAD:* $requerimiento->prioridad \n 📅 *FECHA REGISTRO:* $fechaRegistro \n ✅ *ENCARGADOS:* $encargados \n ✅ *ASIGNADOS:* $asignados";

  //     return [
  //       "message" => $message,
  //       "phoneNumber" => $recipient->telefono
  //     ];
  //   }, Colaborador::getContactInfoByUserIds([66]));

  //   dd($recipients);

  //   $responses = $this->sendWhatsappMessages($recipients);

  //   dd($responses);
  // }
}
