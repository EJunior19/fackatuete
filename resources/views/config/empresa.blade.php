@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-3xl font-bold mb-2">⚙️ Configuración de Empresa</h1>
    <p class="text-slate-600 mb-6">
        Datos oficiales y certificados digitales necesarios para operar con SIFEN.
    </p>

    {{-- ========================================= --}}
    {{-- DATOS DE LA EMPRESA --}}
    {{-- ========================================= --}}
    <div class="bg-white shadow-md rounded-xl p-6 border border-slate-200 mb-10">

        <h2 class="text-xl font-semibold mb-4 text-slate-800">🏢 Datos de la Empresa</h2>

        <form action="{{ route('config.empresa.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label>Razón Social</label>
                    <input type="text" name="razon_social" value="{{ $empresa->razon_social }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Nombre Fantasía</label>
                    <input type="text" name="nombre_fantasia" value="{{ $empresa->nombre_fantasia }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>RUC</label>
                    <input type="text" name="ruc" value="{{ $empresa->ruc }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>DV</label>
                    <input type="text" name="dv" value="{{ $empresa->dv }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div class="md:col-span-2">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ $empresa->direccion }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ $empresa->telefono }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Email</label>
                    <input type="text" name="email" value="{{ $empresa->email }}"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Ambiente SIFEN</label>
                    <select name="ambiente" class="w-full p-2 border rounded-lg">
                        <option value="test" {{ $empresa->ambiente == 'test' ? 'selected' : '' }}>Test</option>
                        <option value="prod" {{ $empresa->ambiente == 'prod' ? 'selected' : '' }}>Producción</option>
                    </select>
                </div>

            </div>

            <button class="mt-6 bg-indigo-600 text-white px-5 py-2 rounded-lg">
                Guardar Datos
            </button>
        </form>

    </div>


    {{-- ========================================= --}}
    {{-- CERTIFICADOS DIGITALES --}}
    {{-- ========================================= --}}
    <div class="bg-white shadow-md rounded-xl p-6 border border-slate-200">

        <h2 class="text-xl font-semibold mb-4 text-slate-800">🔐 Certificados Digitales</h2>

        <form action="{{ route('config.certificados.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label>Certificado Público (.crt)</label>
                    <input type="file" name="cert_publico"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Certificado Privado (.key)</label>
                    <input type="file" name="cert_privado"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Certificado Unificado (.p12)</label>
                    <input type="file" name="cert_p12"
                           class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label>Contraseña del P12</label>
                    <input type="password" name="cert_password"
                           class="w-full p-2 border rounded-lg"
                           value="{{ $empresa->cert_password }}">
                </div>

            </div>

            <button class="mt-6 bg-emerald-600 text-white px-5 py-2 rounded-lg">
                Guardar Certificados
            </button>

        </form>
    </div>

</div>

@endsection
