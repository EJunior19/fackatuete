@extends('layouts.app')

@section('content')

<div class="px-6 py-4 max-w-6xl mx-auto">

    {{-- TÍTULO PRINCIPAL --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-2">⚙️ Configuración de Empresa</h1>
    <p class="text-gray-600 mb-6">
        Datos oficiales y certificados digitales necesarios para operar con SIFEN.
    </p>

    {{-- ========================================================= --}}
    {{-- DATOS DE LA EMPRESA --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200 mb-10">

        <h2 class="text-xl font-semibold mb-5 text-gray-800">🏢 Datos de la Empresa</h2>

        <form action="{{ route('config.empresa.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Razón Social --}}
                <div>
                    <label class="font-medium text-gray-700">Razón Social</label>
                    <input type="text" name="razon_social" value="{{ $empresa->razon_social }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- Nombre Fantasía --}}
                <div>
                    <label class="font-medium text-gray-700">Nombre Fantasía</label>
                    <input type="text" name="nombre_fantasia" value="{{ $empresa->nombre_fantasia }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- RUC --}}
                <div>
                    <label class="font-medium text-gray-700">RUC</label>
                    <input type="text" name="ruc" value="{{ $empresa->ruc }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- DV --}}
                <div>
                    <label class="font-medium text-gray-700">DV</label>
                    <input type="text" name="dv" value="{{ $empresa->dv }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label class="font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" value="{{ $empresa->direccion }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" value="{{ $empresa->telefono }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- Email --}}
                <div>
                    <label class="font-medium text-gray-700">Email</label>
                    <input type="text" name="email" value="{{ $empresa->email }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                </div>

                {{-- Ambiente --}}
                <div>
                    <label class="font-medium text-gray-700">Ambiente SIFEN</label>
                    <select name="ambiente"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200">
                        <option value="test" {{ $empresa->ambiente=='test' ? 'selected' : '' }}>Test</option>
                        <option value="prod" {{ $empresa->ambiente=='prod' ? 'selected' : '' }}>Producción</option>
                    </select>
                </div>

            </div>

            <button class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow">
                Guardar Datos
            </button>
        </form>

    </div>

    {{-- ========================================================= --}}
    {{-- CERTIFICADOS DIGITALES --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">

        <h2 class="text-xl font-semibold mb-5 text-gray-800">🔐 Certificados Digitales</h2>

        <form action="{{ route('config.certificados.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Certificado público --}}
                <div>
                    <label class="font-medium text-gray-700">Certificado Público (.crt)</label>
                    <input type="file" name="cert_publico"
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                {{-- Privado --}}
                <div>
                    <label class="font-medium text-gray-700">Certificado Privado (.key)</label>
                    <input type="file" name="cert_privado"
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                {{-- P12 --}}
                <div>
                    <label class="font-medium text-gray-700">Certificado Unificado (.p12)</label>
                    <input type="file" name="cert_p12"
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                {{-- Clave --}}
                <div>
                    <label class="font-medium text-gray-700">Contraseña del P12</label>
                    <input type="password" name="cert_password"
                           value="{{ $empresa->cert_password }}"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-emerald-200">
                </div>

            </div>

            <button class="mt-6 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg shadow">
                Guardar Certificados
            </button>

        </form>

    </div>

</div>

@endsection
