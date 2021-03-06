<?php

namespace App\Http\Requests;

use App\Area;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EmpresaAreaRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    Validator::extend('custom_rule', function ($attribute, $value) {
      $empresa_id = request()->input('empresa_id');

      $id_area = request()->input('id');

      if ($id_area) {
        $area = Area::find($id_area);
        if (strtolower($area->nombre) == strtolower($value)) {
          return true;
        }
      }

      $query = Area::join('empresa_areas', 'empresa_areas.area_id', '=', 'areas.id')
        ->where($attribute, $value)
        ->where('empresa_areas.empresa_id', $empresa_id);

      return !$query->count();
    });

    return [
      'nombre' => 'required|custom_rule',
      'empresa_id' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'nombre.required' => 'El nombre es un campo requerido',
      'nombre.custom_rule' => 'El area ya existe en esta empresa',
      'empresa_id.required' => 'El campo empresa es requerido'
    ];
  }
}
