<form method="POST" action="{{ $action }}" class="space-y-6 text-gray-100">
    @csrf
    @if(isset($method) && $method === 'PUT') @method('PUT') @endif

    <!-- Tipo y fecha -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold mb-1">Tipo de Documento</label>
            <select name="tipo_documento"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="FE" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='FE')>
                    Factura Electrónica
                </option>
                <option value="ND" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='ND')>
                    Nota de Débito
                </option>
                <option value="NC" @selected(old('tipo_documento',$documento->tipo_documento ?? '')=='NC')>
                    Nota de Crédito
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Fecha de Emisión</label>
            <input type="date"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:ring-indigo-500"
                   name="fecha_emision"
                   value="{{ old('fecha_emision', $documento->fecha_emision ?? now()->format('Y-m-d')) }}">
        </div>
    </div>

    <!-- Cliente -->
    <h3 class="text-lg font-semibold border-b border-gray-700 pb-1 mt-4">Datos del Cliente</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
        <input type="text" name="cliente_ruc"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2"
               placeholder="RUC"
               autocomplete="off"
               value="{{ old('cliente_ruc',$documento->cliente_ruc ?? '') }}">

        <input type="text" name="cliente_nombre"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2"
               placeholder="Nombre o Razón Social"
               autocomplete="off"
               value="{{ old('cliente_nombre',$documento->cliente_nombre ?? '') }}">

        <div id="cliente-autocomplete"
             class="absolute left-0 top-full mt-1 bg-gray-900 border border-gray-700 rounded-lg shadow-lg w-full hidden z-50"></div>
    </div>

    <!-- Items -->
    <h3 class="text-lg font-semibold border-b border-gray-700 pb-1 mt-4">Items</h3>

    <div id="items" class="space-y-3">
        <div class="grid grid-cols-6 gap-2 item-row relative">

            <input class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1"
                   name="items[0][descripcion]"
                   placeholder="Descripción"
                   autocomplete="off">

            <input class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1"
                   name="items[0][cantidad]"
                   type="number"
                   placeholder="Cant.">

            <input class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1"
                   name="items[0][precio]"
                   type="number"
                   placeholder="Precio">

            <select class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1"
                    name="items[0][iva]">
                <option value="10">IVA 10%</option>
                <option value="5">IVA 5%</option>
                <option value="0">Exenta</option>
            </select>

            <input class="bg-gray-700 border border-gray-600 rounded-lg px-2 py-1 text-right"
                   readonly placeholder="Subtotal">

            <input class="bg-gray-700 border border-gray-600 rounded-lg px-2 py-1 text-right"
                   readonly placeholder="Total">

            <div class="autocomplete-productos absolute left-0 top-full mt-1 bg-gray-900 border border-gray-700 rounded-lg shadow-lg hidden w-full z-50"></div>
        </div>
    </div>

    <button type="button"
            onclick="addItem()"
            class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-sm mt-2">
        + Agregar Item
    </button>

    <!-- Total -->
    <div class="mt-6 text-right text-2xl font-bold">
        Total: <span id="total" class="text-indigo-400">0</span> Gs.
    </div>

    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold mt-4 shadow-md transition">
        {{ $buttonText }}
    </button>
</form>

<style>
    .autocomplete-item { padding: 8px 12px; cursor: pointer; }
    .autocomplete-item:hover { background: #1f2937; }
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
        fetch(`/api/clientes/buscar?q=${q}`)
            .then(res => res.json())
            .then(mostrarClientes);
    }

    let timeoutCliente = null;

    inputRuc.addEventListener("input", e => {
        clearTimeout(timeoutCliente);
        timeoutCliente = setTimeout(() => buscarCliente(e.target.value), 250);
    });

    inputNombre.addEventListener("input", e => {
        clearTimeout(timeoutCliente);
        timeoutCliente = setTimeout(() => buscarCliente(e.target.value), 250);
    });

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
                fetch(`/api/productos/buscar?q=${q}`)
                    .then(r => r.json())
                    .then(lista => {
                        box.innerHTML = "";
                        lista.forEach(p => {
                            const item = document.createElement("div");
                            item.classList.add("autocomplete-item");
                            item.innerHTML =
                                `<b>${p.codigo}</b> — ${p.descripcion}<br><small>Gs. ${p.precio_1.toLocaleString()}</small>`;

                            item.onclick = () => {
                                desc.value = p.descripcion;
                                precio.value = p.precio_1;
                                cant.value = 1;
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
        const iva    = Number(row.children[3].value || 0);

        const sub = cant * precio;
        row.children[4].value = sub;

        let total = sub;
        if (iva === 10) total = sub * 1.10;
        if (iva === 5)  total = sub * 1.05;

        row.children[5].value = total;
    }

    function calcularTotalGeneral() {
        let total = 0;
        document.querySelectorAll("#items .item-row").forEach(row => {
            calcularFila(row);
            total += Number(row.children[5].value || 0);
        });

        document.querySelector("#total").innerText = total.toLocaleString();
    }

    document.addEventListener("input", calcularTotalGeneral);

    /* Aplicar autocomplete a filas iniciales */
    document.querySelectorAll("#items .item-row").forEach(row => activarAutoProducto(row));

});
</script>

<script src="/js/items.js"></script>
