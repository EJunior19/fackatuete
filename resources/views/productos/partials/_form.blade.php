<div class="grid grid-cols-2 gap-4">

    <!-- Código -->
    <div>
        <label class="block text-gray-700 mb-1">Código</label>
        <input type="text"
               name="codigo"
               value="{{ old('codigo', $producto->codigo ?? '') }}"
               class="w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <!-- Categoría -->
    <div>
        <label class="block text-gray-700 mb-1">Categoría</label>
        <input type="text"
               name="categoria"
               value="{{ old('categoria', $producto->categoria ?? '') }}"
               class="w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Descripción -->
    <div class="col-span-2">
        <label class="block text-gray-700 mb-1">Descripción</label>
        <input type="text"
               name="descripcion"
               value="{{ old('descripcion', $producto->descripcion ?? '') }}"
               class="item-descripcion w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Unidad -->
    <div>
        <label class="block text-gray-700 mb-1">Unidad</label>
        <input type="text"
               name="unidad_medida"
               value="{{ old('unidad_medida', $producto->unidad_medida ?? 'UNI') }}"
               class="w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- IVA -->
    <div>
        <label class="block text-gray-700 mb-1">IVA</label>
        <select name="iva"
                class="w-full border border-gray-300 bg-white rounded px-3 py-2 
                       focus:ring-2 focus:ring-blue-500">
            <option value="0"  {{ old('iva', $producto->iva ?? '') == 0  ? 'selected' : '' }}>0%</option>
            <option value="5"  {{ old('iva', $producto->iva ?? '') == 5  ? 'selected' : '' }}>5%</option>
            <option value="10" {{ old('iva', $producto->iva ?? '') == 10 ? 'selected' : '' }}>10%</option>
        </select>
    </div>

    <!-- Precio 1 -->
    <div>
        <label class="block text-gray-700 mb-1">Precio 1</label>
        <input type="text"
               name="precio_1"
               class="precio-mask w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-green-500 focus:border-green-500 item-precio"
               value="{{ number_format(old('precio_1', $producto->precio_1 ?? 0), 0, ',', '.') }}">
    </div>

    <!-- Precio 2 -->
    <div>
        <label class="block text-gray-700 mb-1">Precio 2</label>
        <input type="text"
               name="precio_2"
               class="precio-mask w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-green-500 focus:border-green-500"
               value="{{ number_format(old('precio_2', $producto->precio_2 ?? 0), 0, ',', '.') }}">
    </div>

    <!-- Precio 3 -->
    <div>
        <label class="block text-gray-700 mb-1">Precio 3</label>
        <input type="text"
               name="precio_3"
               class="precio-mask w-full border border-gray-300 bg-white rounded px-3 py-2 
                      focus:ring-green-500 focus:border-green-500"
               value="{{ number_format(old('precio_3', $producto->precio_3 ?? 0), 0, ',', '.') }}">
    </div>

</div>

<!-- Autocomplete estilo claro -->
<style>
    .autocomplete-list {
        background: white;
        border: 1px solid #d1d5db; /* gray-300 */
        color: #111827;            /* gray-900 */
        border-radius: 6px;
        max-height: 260px;
        overflow-y: auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        z-index: 50;
        position: absolute;
    }
    .autocomplete-list div {
        padding: 8px 12px;
        cursor: pointer;
    }
    .autocomplete-list div:hover {
        background: #f3f4f6; /* gray-100 */
    }
</style>

<!-- Script: máscara de precios + autocomplete -->
<script>
document.querySelectorAll('.precio-mask').forEach(input => {
    input.addEventListener('input', function () {
        let raw = this.value.replace(/\./g, '').replace(/\D/g, '');
        this.value = raw ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "";
    });
});

document.querySelector('form').addEventListener('submit', function () {
    document.querySelectorAll('.precio-mask').forEach(input => {
        input.value = input.value.replace(/\./g, '');
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const inputDescripcion = document.querySelector('.item-descripcion');
    if (!inputDescripcion) return;

    const lista = document.createElement('div');
    lista.classList.add('autocomplete-list');
    lista.style.display = 'none';
    lista.style.width = inputDescripcion.offsetWidth + 'px';

    inputDescripcion.parentNode.appendChild(lista);

    let timeout = null;

    inputDescripcion.addEventListener('input', function () {
        clearTimeout(timeout);
        const q = this.value.trim();

        if (q.length < 2) {
            lista.style.display = 'none';
            return;
        }

        timeout = setTimeout(() => {

            fetch(`/api/productos/buscar?q=${q}`)
                .then(res => res.json())
                .then(items => {

                    lista.innerHTML = '';
                    if (items.length === 0) {
                        lista.style.display = 'none';
                        return;
                    }

                    lista.style.display = 'block';

                    items.forEach(item => {
                        const option = document.createElement('div');

                        option.innerHTML = `
                            <strong>${item.descripcion}</strong><br>
                            <span class="text-gray-600 text-xs">
                                Código: ${item.codigo} — Gs. ${item.precio_1.toLocaleString()}
                            </span>
                        `;

                        option.addEventListener('click', function () {
                            inputDescripcion.value = item.descripcion;
                            document.querySelector('.item-precio').value = item.precio_1.toLocaleString();
                            lista.style.display = 'none';
                        });

                        lista.appendChild(option);
                    });
                });

        }, 300);
    });
});
</script>
