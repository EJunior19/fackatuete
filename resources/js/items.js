let index = 1;

function addItem() {
    let container = document.getElementById("items");

    let row = document.createElement("div");
    row.className = "grid grid-cols-4 gap-2 mb-2";

    row.innerHTML = `
        <input class="border rounded px-2 py-1" name="items[${index}][descripcion]" placeholder="Descripción">
        <input class="border rounded px-2 py-1" name="items[${index}][cantidad]" type="number" placeholder="Cant.">
        <input class="border rounded px-2 py-1" name="items[${index}][precio]" type="number" placeholder="Precio">
        <input class="border rounded px-2 py-1 bg-gray-100" readonly placeholder="Subtotal">
    `;
    container.appendChild(row);

    index++;
}
