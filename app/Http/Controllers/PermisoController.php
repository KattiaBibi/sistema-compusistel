<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\PermisoRequest;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.permiso.listado')->only('index');
        $this->middleware('can:admin.permiso.crear')->only('store');
        $this->middleware('can:admin.permiso.editar')->only('update');
    }


    public function permiso()
    {

      return datatables()->of(Permission::all())->toJson();

    }

    public function index()
    {
        //

        return view('permiso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisoRequest $request)
    {
        //

        $permiso=  Permission::create($request->all());

        return $permiso?1:0;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */

    public function update(PermisoRequest $request, $id)
    {
        //

        $permiso=Permission::findOrfail($id);
        $permiso->update($request->all());

        return $permiso?1:0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $permiso = Permission::findOrfail($id);

        if ($permiso->estado == 1) {
            $permiso->estado = 0;
        } else {
            $permiso->estado = 1;
        }

        $permiso->update();

        return $permiso ? 1 : 0;
    }
}
