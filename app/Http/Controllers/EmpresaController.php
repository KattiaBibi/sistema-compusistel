<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Requests\EmpresaRequest;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
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

  public function empresa()
  {
    return datatables()->of(Empresa::all())->toJson();
  }



  public function index()
  {
    //get

    return view('empresa.index');
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //get

    return view('empresa.crear');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EmpresaRequest $request)
  {
    $empresa = Empresa::create($request->all());

    return $empresa ? 1 : 0;
  }


  /**
   * Display the specified resource.
   *
   * @param  \App\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */
  public function show(Empresa $empresa)
  {
    //get
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */
  public function edit(Empresa $empresa)
  {
    //get


  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */
  public function update(EmpresaRequest $request, $id)
  {

    $empresa = Empresa::findOrfail($id);
    $empresa->update($request->all());


    return $empresa ? 1 : 0;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */



  public function destroy(Request $request, $id)
  {
    //delete
    $empresa = Empresa::findOrfail($id);


    /*  dd($request->nombre); */
    if ($empresa->estado == 1) {
      $empresa->estado = 0;
    } else {
      $empresa->estado = 1;
    }

    $empresa->update();

    return $empresa ? 1 : 0;
  }

  public function search(Request $request)
  {
    $search = $request->get('search');
    $page = $request->get('page');
    $filters = $request->get('filters') ?? [];

    $empresaModel = new Empresa();
    $data = $empresaModel->search($search, $page, $filters);
    return response()->json($data);
  }
}
