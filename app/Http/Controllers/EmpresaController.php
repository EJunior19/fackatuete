<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EDITAR DATOS BÁSICOS
    |--------------------------------------------------------------------------
    */
    public function edit()
    {
        $empresa = Empresa::findOrFail(1);
        return view('config.empresa', compact('empresa'));
    }

    public function update(Request $request)
    {
        $empresa = Empresa::findOrFail(1);

        $empresa->update([
            'razon_social'   => $request->razon_social,
            'nombre_fantasia'=> $request->nombre_fantasia,
            'ruc'            => $request->ruc,
            'dv'             => $request->dv,
            'direccion'      => $request->direccion,
            'telefono'       => $request->telefono,
            'email'          => $request->email,
            'ambiente'       => $request->ambiente ?? 'test',
        ]);

        return redirect()->back()->with('success', 'Datos de empresa actualizados correctamente.');
    }


    /*
    |--------------------------------------------------------------------------
    | CERTIFICADOS DIGITALES (CRT, KEY, P12)
    |--------------------------------------------------------------------------
    */
    public function certificados()
    {
        $empresa = Empresa::findOrFail(1);
        return view('config.certificados', compact('empresa'));
    }

    public function guardarCertificados(Request $request)
    {
        $empresa = Empresa::findOrFail(1);

        // Carpeta destino ejemplo: storage/app/certs/empresa_1/
        $dir = "certs/empresa_{$empresa->id}";
        Storage::makeDirectory($dir);

        /*
        |--------------------------------------------------------------------------
        | CRT — Certificado público
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('cert_publico')) {
            $file = $request->file('cert_publico');
            $path = $file->storeAs($dir, 'cert.crt');
            $empresa->cert_publico = "storage/app/{$path}";
        }

        /*
        |--------------------------------------------------------------------------
        | KEY — Clave privada
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('cert_privado')) {
            $file = $request->file('cert_privado');
            $path = $file->storeAs($dir, 'cert.key');
            $empresa->cert_privado = "storage/app/{$path}";
        }

        /*
        |--------------------------------------------------------------------------
        | P12 — Certificado unificado
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('cert_p12')) {
            $file = $request->file('cert_p12');
            $path = $file->storeAs($dir, 'cert.p12');
            $empresa->cert_p12_path = "storage/app/{$path}";
        }

        /*
        |--------------------------------------------------------------------------
        | Contraseña del P12
        |--------------------------------------------------------------------------
        */
        if ($request->cert_password) {
            $empresa->cert_password = $request->cert_password;
        }

        $empresa->save();

        return redirect()->back()->with('success', 'Certificados actualizados correctamente.');
    }
}
