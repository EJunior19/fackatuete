<form method="POST" action="{{ $action }}" class="space-y-6 text-gray-800">
    @csrf
    @if(isset($method) && $method === 'PUT') @method('PUT') @endif

    {{-- Tipo y fecha --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <label class="block text-sm font-semibold mb-1">Establecimiento</label>
            <input
                type="number"
                name="establecimiento"
                placeholder="Ej: 1"
                min="1"
                max="999"
                value="{{ old('establecimiento', $documento->establecimiento ?? 1) }}"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Punto Expedición</label>
            <input
                type="number"
                name="punto_expedicion"
                placeholder="Ej: 1"
                min="1"
                max="999"
                value="{{ old('punto_expedicion', $documento->punto_expedicion ?? 1) }}"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Tipo de documento</label>
            <select name="tipo_documento"
                    class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                           focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <option value="FE" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='FE')>
                    Factura electrónica
                </option>
                <option value="ND" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='ND')>
                    Nota de débito
                </option>
                <option value="NC" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='NC')>
                    Nota de crédito
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Fecha de emisión</label>
            <input
                type="date"
                name="fecha_emision"
                value="{{ old('fecha_emision', $documento->fecha_emision ?? now()->format('Y-m-d')) }}"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    {{-- Cliente --}}
    <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1 mt-4">
        Datos del cliente
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative mt-2">
        <input
            type="text"
            name="cliente_ruc"
            placeholder="RUC"
            autocomplete="off"
            value="{{ old('cliente_ruc',$documento->cliente_ruc ?? '') }}"
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                   focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

        <input
            type="text"
            name="cliente_nombre"
            placeholder="Nombre o razón social"
            autocomplete="off"
            value="{{ old('cliente_nombre',$documento->cliente_nombre ?? '') }}"
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800
                   focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

        <div id="cliente-autocomplete"
             class="absolute left-0 top-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg w-full hidden z-50"></div>
    </div>

    {{-- Ítems --}}
    <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-200 pb-1 mt-4">
        Ítems
    </h3>

    <div id="items" class="space-y-3 mt-2">
        <div class="grid grid-cols-6 gap-2 item-row relative">

            <input
                name="items[0][descripcion]"
                placeholder="Descripción"
                autocomplete="off"
                class="col-span-2 rounded-md border border-gray-300 bg-white px-2 py-1 text-sm text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            <input
                name="items[0][cantidad]"
                type="number"
                placeholder="Cant."
                class="rounded-md border border-gray-300 bg-white px-2 py-1 text-sm text-right text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            <input
                name="items[0][precio]"
                type="number"
                placeholder="Precio"
                class="rounded-md border border-gray-300 bg-white px-2 py-1 text-sm text-right text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            <select
                name="items[0][iva]"
                class="rounded-md border border-gray-300 bg-white px-2 py-1 text-sm text-gray-800
                       focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <option value="10">IVA 10%</option>
                <option value="5">IVA 5%</option>
                <option value="0">Exenta</option>
            </select>

            <input
                readonly
                placeholder="Subtotal"
                class="rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-sm text-right text-gray-800">

            <input
                readonly
                placeholder="Total"
                class="rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-sm text-right text-gray-800">

            <div class="autocomplete-productos absolute left-0 top-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden w-full z-50"></div>
        </div>
    </div>

    <button
        type="button"
        onclick="addItem()"
        class="inline-flex items-center px-4 py-2 mt-2 text-xs font-medium rounded-md border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200">
        + Agregar ítem
    </button>

    {{-- Total --}}
    <div class="mt-6 text-right text-2xl font-semibold text-gray-800">
        Total:
        <span id="total" class="text-indigo-600">0</span> Gs.
    </div>

    <button
        class="mt-4 inline-flex items-center px-6 py-3 text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
        {{ $buttonText }}
    </button>
</form>

<style>
    .autocomplete-item {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 0.875rem;
        color: #111827;
    }
    .autocomplete-item:hover {
        background: #f3f4f6; /* gris clarito */
    }
</style>

{{-- ================================ --}}
{{-- CLIENTES + PRODUCTOS + ITEMS JS --}}
{{-- ================================ --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ================================
       AUTOCOMPLETE CLIENTES
    =================================*/
    const inputRuc = document.querySelector("input[name='cliente_ruc']");
    const inputNombre = document.querySelector("input[name='cliente_nombre']");
    const boxCliente = document.querySelector("#cliente-autocomplete");

    function mostrarClientes(lista) {
        boxCliente.innerHTML = "";
        if (lista.length === 0) return boxCliente.classList.add("hidden");

        lista.forEach(c => {
            const div = document.createElement("div");
            div.classList.add("autocomplete-item");
            div.innerHTML = `<b>${c.ruc}-${c.dv}</b> — ${c.razon_social}`;
            div.onclick = () => {
                inputRuc.value = `${c.ruc}-${c.dv}`;
                inputNombre.value = c.razon_social;
                boxCliente.classList.add("hidden");
            };
            boxCliente.appendChild(div);
        });

        boxCliente.classList.remove("hidden");
    }

    function buscarCliente(q) {
        fetch(`/api/clientes/buscar?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(mostrarClientes);
    }

    let timeoutCliente = null;

    if (inputRuc) {
        inputRuc.addEventListener("input", e => {
            clearTimeout(timeoutCliente);
            timeoutCliente = setTimeout(() => buscarCliente(e.target.value), 250);
        });
    }

    if (inputNombre) {
        inputNombre.addEventListener("input", e => {
            clearTimeout(timeoutCliente);
            timeoutCliente = setTimeout(() => buscarCliente(e.target.value), 250);
        });
    }

    document.addEventListener("click", e => {
        if (!boxCliente.contains(e.target) && e.target !== inputRuc && e.target !== inputNombre) {
            boxCliente.classList.add("hidden");
        }
    });

    /* ================================
       AUTOCOMPLETE PRODUCTOS
    =================================*/
    function activarAutoProducto(row) {
        const desc   = row.children[0];
        const cant   = row.children[1];
        const precio = row.children[2];
        const ivaSel = row.children[3];
        const sub    = row.children[4];
        const total  = row.children[5];
        const box    = row.querySelector(".autocomplete-productos");

        let timeout = null;

        desc.addEventListener("input", () => {
            let q = desc.value.trim();
            if (q.length < 2) return box.classList.add("hidden");

            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetch(`/api/productos/buscar?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(lista => {
                        box.innerHTML = "";
                        lista.forEach(p => {
                            const item = document.createElement("div");
                            item.classList.add("autocomplete-item");
                            item.innerHTML =
                                `<b>${p.codigo}</b> — ${p.descripcion}<br><small>Gs. ${p.precio_1.toLocaleString()}</small>`;

                            item.onclick = () => {
                                desc.value   = p.descripcion;
                                precio.value = p.precicio_1 ?? p.precio_1; // por si acaso
                                cant.value   = 1;
                                ivaSel.value = p.iva ?? 10;

                                calcularFila(row);
                                calcularTotalGeneral();
                                box.classList.add("hidden");
                            };
                            box.appendChild(item);
                        });
                        box.classList.remove("hidden");
                    });
            }, 250);
        });

        document.addEventListener("click", e => {
            if (!box.contains(e.target) && e.target !== desc) {
                box.classList.add("hidden");
            }
        });
    }

    function calcularFila(row) {
        const cant   = Number(row.children[1].value || 0);
        const precio = Number(row.children[2].value || 0);

        const sub = cant * precio;

        // Subtotal = precio * cantidad (IVA incluido)
        row.children[4].value = sub.toFixed(0);

        // Total de la fila = subtotal (NO se suma IVA)
        row.children[5].value = sub.toFixed(0);
    }


    function calcularTotalGeneral() {
        let total = 0;
        document.querySelectorAll("#items .item-row").forEach(row => {
            calcularFila(row);
            total += Number(row.children[5].value || 0);
        });

        const totalSpan = document.querySelector("#total");
        if (totalSpan) {
            totalSpan.innerText = total.toLocaleString();
        }
    }

    document.addEventListener("input", calcularTotalGeneral);

    // activar autocomplete en filas iniciales
    document.querySelectorAll("#items .item-row").forEach(row => activarAutoProducto(row));
});
</script>

<script src="/js/items.js"></script>
