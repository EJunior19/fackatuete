<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        // Limpiar puntos de miles antes de validar
        $this->merge([
            'precio_1' => $this->precio_1 ? str_replace('.', '', $this->precio_1) : null,
            'precio_2' => $this->precio_2 ? str_replace('.', '', $this->precio_2) : null,
            'precio_3' => $this->precio_3 ? str_replace('.', '', $this->precio_3) : null,
        ]);
    }

    public function rules()
    {
        return [
            'codigo'        => 'required|max:50',
            'descripcion'   => 'required|max:255',
            'categoria'     => 'nullable|max:100',
            'unidad_medida' => 'required|max:10',

            // Ahora sí va a validar correctamente
            'precio_1'      => 'required|numeric|min:0',
            'precio_2'      => 'nullable|numeric|min:0',
            'precio_3'      => 'nullable|numeric|min:0',

            'iva'           => 'required|in:0,5,10',
        ];
    }
}
